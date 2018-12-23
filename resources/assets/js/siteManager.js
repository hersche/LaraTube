var baseUrl;
import Vue from 'vue';
import Router from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import VueCroppie from 'vue-croppie';
import { eventBus } from './eventBus.js';
var app;
var theVue;
require("./models");
var siteManager = /** @class */ (function () {
    function siteManager(base) {
        baseUrl = base + "/";
        this.currentPage = "overview";
        this.catchedTagMedias = [];
        this.loggedUserId = Number($("#loggedUserId").attr("content"));
        console.log("iiiint");
        console.log($("#loggedUserId").attr("content"));
        this.receiveUsers(true);
        var that = this;
        eventBus.$on('refreshMedias', function (title) {
            theVue.canloadmore = true;
            that.catchedTagMedias = [];
            that.receiveMedias("/api/media", true);
            // deprecated, only example for eventbus
        });
        eventBus.$on('checkTag', function (tagName) {
            if (that.catchedTagMedias.includes(tagName) == false) {
                that.catchedTagMedias.push(tagName);
                that.receiveMedias("/api/tags/" + tagName);
            }
        });
        eventBus.$on('loadMore', function (title) {
            console.log("received load more");
            that.receiveMedias(that.nextLink);
        });
        eventBus.$on('showAlert', function (data) {
            console.log("got showAlert");
            theVue.dismissCountDown = theVue.dismissSecs;
        });
    }
    siteManager.prototype.initVue = function () {
        var overview = Vue.component('overview', require("./components/OverviewComponent.vue"));
        var player = Vue.component('player', require("./components/MediaComponent.vue"));
        var profileComp = Vue.component('profile', require("./components/ProfileComponent.vue"));
        var tagComp = Vue.component('tags', require("./components/TagComponent.vue"));
        var loginComp = Vue.component('login', require("./components/LoginComponent.vue"));
        var uploadComp = Vue.component('upload', require("./components/UploadComponent.vue"));
        var alertComp = Vue.component('alert', require("./components/AlertComponent.vue"));
        Vue.use(Router);
        Vue.use(BootstrapVue);
        Vue.use(VueCroppie);
        var routes = [
            { path: '/', component: overview },
            { path: '/media/:currentTitle', component: player },
            { path: '/profile/:profileId', component: profileComp },
            { path: '/tags', component: tagComp },
            { path: '/tags/:tagName', component: tagComp },
            { path: '/login', component: loginComp },
            { path: '/upload', component: uploadComp }
        ];
        //  sm.receiveUsers(true);
        theVue = new Vue({
            data: { title: "Overview",
                dismissSecs: 10,
                dismissCountDown: 0,
                showDismissibleAlert: false,
                currentComponent: 'overview', loggeduserid: this.loggedUserId, tags: this.tags, canloadmore: true, medias: this.medias, currentTitle: '', user: new User(0, "None", "img/404/avatar.png", "img/404/background.png", "None-user", {}), baseUrl: baseUrl },
            router: new Router({ routes: routes }),
            components: {
                'alert': alertComp
            },
            methods: {
                swapComponent: function (component) {
                    this.currentComponent = component;
                }
            },
            watch: {
                $route: function (to, from) {
                    //this.show = false;
                    if (to.params.currentTitle != undefined) {
                        // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
                    }
                    if (to.params.profileId != undefined) {
                        this.user = sm.getUserById(to.params.profileId);
                        this.medias = sm.getMediasByUser(to.params.profileId);
                    }
                    else {
                        this.medias = sm.medias;
                    }
                }
            }
        }).$mount('#app2');
    };
    siteManager.prototype.getCurrentSite = function () {
        return this.currentPage;
    };
    siteManager.prototype.receiveUsers = function (forceUpdate) {
        if (forceUpdate === void 0) { forceUpdate = false; }
        var that = this;
        $.getJSON("/api/user", function name(data) {
            if ((that.users == undefined) || (forceUpdate)) {
                that.users = [];
                $.each(data.data, function (key, value) {
                    that.users.push(new User(value.id, value.name, value.avatar, value.background, value.bio, value.mediaIds));
                });
                //theVue.us = sm.medias;
                that.receiveTags();
            }
        });
    };
    siteManager.prototype.receiveTags = function (forceUpdate) {
        if (forceUpdate === void 0) { forceUpdate = false; }
        var that = this;
        $.getJSON("/api/tags", function name(data) {
            if ((that.tags == undefined) || (forceUpdate)) {
                that.tags = [];
                $.each(data.data, function (key, value) {
                    that.tags.push(new Tag(value.id, value.name, value.slug, value.count));
                });
            }
            this.tags = that.tags;
            if (theVue != undefined) {
                theVue.tags = this.tags;
            }
            that.receiveMedias();
        });
    };
    siteManager.prototype.receiveMediaByName = function (mediaName, forceUpdate) {
        if (forceUpdate === void 0) { forceUpdate = false; }
        var that = this;
        var existsAlready = false;
        $.getJSON("/api/media/" + mediaName, function name(data) {
            $.each(this.medias, function (key, value) {
                if (value.title == mediaName) {
                    existsAlready = true;
                }
            });
            if (existsAlready == false) {
                var m = new Media(data.title, data.description, data.source, data.poster_source, data.simpleType, data.type, that.getUserById(data.user_id), data.user_id, data.created_at, data.created_at_readable, data.comments, that.getTagsByIdArray(data.tagsIds));
                theVue.medias = this.medias;
                this.medias.push(m);
            }
            else {
                console.warn("If the media already existed, why this method was used?");
            }
        });
    };
    siteManager.prototype.getTagsByIdArray = function (arr) {
        var tmpTags = [];
        var that = this;
        $.each(arr, function (key, value) {
            tmpTags.push(that.findTagById(value));
        });
        return tmpTags;
    };
    siteManager.prototype.findTagById = function (id) {
        var returner = undefined;
        $.each(this.tags, function (key, value) {
            if (value.id == id) {
                returner = value;
            }
        });
        return returner;
    };
    siteManager.prototype.findMediaByName = function (mediaName) {
        var returnMedia = undefined;
        var that = this;
        $.each(that.medias, function (key, value) {
            if (value.title == mediaName) {
                returnMedia = value;
            }
        });
        if (returnMedia == undefined) {
            console.log("Media didn't exist, download it.");
            //that.receiveMediaByName(mediaName);
        }
        return returnMedia;
    };
    siteManager.prototype.receiveMedias = function (url, forceUpdate) {
        if (url === void 0) { url = "/api/media"; }
        if (forceUpdate === void 0) { forceUpdate = false; }
        var that = this;
        $.getJSON(url, function name(data) {
            if ((forceUpdate) || (that.medias == undefined)) {
                that.medias = [];
            }
            $.each(data.data, function (key, value) {
                if (that.findMediaByName(value.title) == undefined) {
                    var med = new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, that.getUserById(value.user_id), value.user_id, value.created_at, value.created_at_readable, value.comments, that.getTagsByIdArray(value.tagsIds));
                    $.each(med.comments, function (key1, value1) {
                        med.comments[key1].user = that.getUserById(value1.user_id);
                    });
                    that.medias.push(med);
                }
            });
            if (data.links != undefined) {
                that.nextLink = data.links.next;
                that.lastLink = data.links.prev;
            }
            if (theVue == undefined) {
                that.initVue();
            }
            if (that.nextLink == null) {
                theVue.canloadmore = false;
            }
            theVue.medias = that.medias;
            if (theVue.$route.params.profileId != undefined) {
                theVue.user = sm.getUserById(theVue.$route.params.profileId);
                theVue.medias = sm.getMediasByUser(theVue.$route.params.profileId);
            }
        });
    };
    siteManager.prototype.getUserById = function (id) {
        var search = new User(0, "None", "/img/404/avatar.png", "/img/404/background.png", "None-profile", {});
        $.each(this.users, function (key, value) {
            if (value.id == id) {
                search = value;
            }
        });
        return search;
    };
    siteManager.prototype.getMediasByUser = function (id) {
        var userMedias = [];
        $.each(this.medias, function (key, value) {
            if (value.user_id == id) {
                userMedias.push(value);
            }
        });
        return userMedias;
    };
    return siteManager;
}());
;
if (sm == undefined) {
    var sm;
}
export function init(baseUrl) {
    sm = new siteManager(baseUrl);
    eventBus.$on('overviewPlayClick', function (title) {
        theVue.currentTitle = title;
        theVue.title = title;
        // deprecated, only example for eventbus
    });
}
var Tag = /** @class */ (function () {
    function Tag(id, name, slug, count) {
        this.id = id;
        this.name = name;
        this.slug = slug;
        this.count = count;
    }
    return Tag;
}());
var User = /** @class */ (function () {
    function User(id, name, avatar, background, bio, mediaIds) {
        this.id = id;
        this.name = name;
        this.avatar = avatar;
        this.background = background;
        this.bio = bio;
        this.mediaIds = mediaIds;
    }
    User.prototype.toJson = function () {
        return "{id:" + this.id + ",name:'" + this.name + "',avatar:'" + this.avatar + "',background:'" + this.background + "'}";
    };
    return User;
}());
var Media = /** @class */ (function () {
    function Media(title, description, source, poster_source, simpleType, type, user, user_id, created_at, created_at_readable, comments, tags, tagIds) {
        if (tagIds === void 0) { tagIds = undefined; }
        this.title = title;
        this.description = description;
        this.source = source;
        this.poster_source = poster_source;
        this.type = type;
        this.simpleType = simpleType;
        this.user = user;
        this.user_id = user_id;
        this.comments = comments;
        this.tags = tags;
        this.tagIds = tagIds;
        this.created_at = created_at;
        this.created_at_readable = created_at_readable;
    }
    Media.prototype.toJson = function () {
        return "{title:'" + this.title + "',description:'" + this.description + "',source:'" + this.source;
    };
    return Media;
}());
