var baseUrl;
import Vue from 'vue';
import Router from 'vue-router';
import { eventBus } from './eventBus.js';
var app;
var theVue;
require("./models");
var siteManager = /** @class */ (function () {
    function siteManager(base) {
        baseUrl = base + "/";
        this.currentPage = "overview";
        this.receiveUsers(true);
        var that = this;
        eventBus.$on('refreshMedias', function (title) {
            that.receiveMedias(true);
            // deprecated, only example for eventbus
        });
    }
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
                that.receiveMedias();
            }
        });
    };
    siteManager.prototype.receiveMediaByName = function (mediaName, forceUpdate) {
        if (forceUpdate === void 0) { forceUpdate = false; }
        var that = this;
        $.getJSON("/api/media/" + mediaName, function name(data) {
            that.medias[that.findMediaByName(mediaName)].comments = data.comments;
            theVue.medias = that.medias;
        });
    };
    siteManager.prototype.findMediaByName = function (mediaName) {
        $.each(this.medias, function (key, value) {
            if (value.title == mediaName) {
                return key;
            }
        });
    };
    siteManager.prototype.receiveMedias = function (forceUpdate) {
        if (forceUpdate === void 0) { forceUpdate = false; }
        var that = this;
        $.getJSON("/api/media", function name(data) {
            if ((that.medias == undefined) || (forceUpdate)) {
                that.medias = [];
                $.each(data.data, function (key, value) {
                    var med = new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, that.getUserById(value.user_id), value.user_id, value.created_at, value.created_at_readable, value.comments, value.tags);
                    $.each(med.comments, function (key1, value1) {
                        med.comments[key1].user = that.getUserById(value1.user_id);
                    });
                    that.medias.push(med);
                });
                that.nextLink = data.links.next;
                that.lastLink = data.links.prev;
                theVue.medias = that.medias;
                if (theVue.$route.params.profileId != undefined) {
                    theVue.user = sm.getUserById(theVue.$route.params.profileId);
                    theVue.medias = sm.getMediasByUser(theVue.$route.params.profileId);
                }
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
    var overview = Vue.component('overview', require("./components/OverviewComponent.vue"));
    var player = Vue.component('player', require("./components/MediaComponent.vue"));
    var profileComp = Vue.component('player', require("./components/ProfileComponent.vue"));
    Vue.use(Router);
    var routes = [
        { path: '/', component: overview },
        { path: '/media/:currentTitle', component: player },
        { path: '/profile/:profileId', component: profileComp }
    ];
    //  sm.receiveUsers(true);
    theVue = new Vue({
        data: { title: "Overview",
            currentComponent: 'overview', medias: sm.medias, currentTitle: '', user: new User(0, "None", "img/404/avatar.png", "img/404/background.png", "None-user", {}), baseUrl: baseUrl },
        router: new Router({ routes: routes }),
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
    eventBus.$on('overviewPlayClick', function (title) {
        theVue.currentTitle = title;
        theVue.title = title;
        // deprecated, only example for eventbus
    });
}
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
    function Media(title, description, source, poster_source, simpleType, type, user, user_id, created_at, created_at_readable, comments, tags) {
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
        this.created_at = created_at;
        this.created_at_readable = created_at_readable;
    }
    Media.prototype.toJson = function () {
        return "{title:'" + this.title + "',description:'" + this.description + "',source:'" + this.source;
    };
    return Media;
}());
