var baseUrl;
import Vue from 'vue';
import Router from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import VueCroppie from 'vue-croppie';
import { eventBus } from './eventBus.js';
import { User, Media, Tag } from './models';
import VueApexCharts from 'vue-apexcharts';
var app;
var theVue;
var searchDelay;
require("./models");
var siteManager = /** @class */ (function () {
    function siteManager(base) {
        var _this = this;
        this.initing = true;
        baseUrl = base + "/";
        this.currentPage = "overview";
        this.catchedTagMedias = [];
        this.usedSearchTerms = [];
        this.loggedUserId = Number($("#loggedUserId").attr("content"));
        this.receiveUsers(true);
        var that = this;
        eventBus.$on('refreshMedias', function (title) {
            theVue.canloadmore = true;
            that.catchedTagMedias = [];
            _this.usedSearchTerms = [];
            that.receiveMedias("/api/media", true);
            // deprecated, only example for eventbus
        });
        eventBus.$on('loadAllMedias', function (title) {
            that.receiveMedias("/api/medias/all", true);
            theVue.canloadmore = false;
            // deprecated, only example for eventbus
        });
        eventBus.$on('videoDeleted', function (title) {
            that.deleteMediaByName(title);
        });
        eventBus.$on('videoCreated', function (json) {
            that.receiveTagsForMedia(json);
        });
        eventBus.$on('checkTag', function (tagName) {
            //if(theVue.$router.currentRoute.path!="/search"){
            if (tagName == '') {
                if ($("#specialAllTag").is(":checked")) {
                    theVue.medias = that.medias;
                }
                else {
                    theVue.medias = [];
                    theVue.medias = that.medias;
                }
            }
            else {
                if (that.catchedTagMedias.includes(tagName) == false) {
                    that.catchedTagMedias.push(tagName);
                    that.receiveMedias("/api/tags/" + tagName);
                }
            }
            //}
        });
        eventBus.$on('loadMore', function (title) {
            that.receiveMedias(that.nextLink);
        });
        eventBus.$on('refreshSearch', function (title) {
            theVue.searching();
        });
        eventBus.$on('showAlert', function (data) {
            theVue.dismisscountdown = theVue.dismisssecs;
        });
    }
    siteManager.prototype.initVue = function () {
        Vue.use(Router);
        Vue.use(BootstrapVue);
        Vue.use(VueCroppie);
        Vue.use(VueApexCharts);
        Vue.component('apexchart', VueApexCharts);
        var overview = Vue.component('overview', require("./components/OverviewComponent.vue"));
        var player = Vue.component('player', require("./components/MediaComponent.vue"));
        var profileComp = Vue.component('profile', require("./components/ProfileComponent.vue"));
        var tagComp = Vue.component('tags', require("./components/TagComponent.vue"));
        var loginComp = Vue.component('login', require("./components/LoginComponent.vue"));
        var uploadComp = Vue.component('upload', require("./components/UploadComponent.vue"));
        var alertComp = Vue.component('alert', require("./components/AlertComponent.vue"));
        var searchComp = Vue.component('search', require("./components/SearchComponent.vue"));
        var chartsComp = Vue.component('search', require("./components/ChartsComponent.vue"));
        var editVideoComp = Vue.component('search', require("./components/EditVideo.vue"));
        var aboutComp = Vue.component('search', require("./components/About.vue"));
        var that = this;
        var routes = [
            { path: '/', component: overview },
            { path: '/media/:currentTitle', component: player },
            { path: '/profile/:profileId', component: profileComp },
            { path: '/tags', component: tagComp },
            { path: '/tags/:tagName', component: tagComp },
            { path: '/login', component: loginComp },
            { path: '/upload', component: uploadComp },
            { path: '/search', component: searchComp },
            { path: '/charts', component: chartsComp },
            { path: '/about', component: aboutComp },
            { path: '/mediaedit/:editTitle', component: editVideoComp }
        ];
        //  sm.receiveUsers(true);
        theVue = new Vue({
            data: {
                title: "Overview",
                dismisssecs: 10,
                dismisscountdown: 0,
                showdismissiblealert: false,
                search: '',
                users: this.users,
                loggeduserid: this.loggedUserId,
                tags: this.tags,
                canloadmore: true,
                medias: this.medias,
                currentTitle: '',
                user: new User(0, "None", "img/404/avatar.png", "img/404/background.png", "None-user", {}),
                baseUrl: baseUrl
            },
            router: new Router({ routes: routes }),
            components: {
                'alert': alertComp
            },
            methods: {
                emitRefreshMedias: function () {
                    eventBus.$emit('refreshMedias', "");
                },
                emitLoadAllMedias: function () {
                    eventBus.$emit('loadAllMedias', "");
                },
                searching: function () {
                    if (theVue.$router.currentRoute.path != "/search") {
                        theVue.$router.push('/search');
                    }
                    var s = $("#theLiveSearch").val();
                    var m = [];
                    if (that.usedSearchTerms.includes(s.toString()) == false && s.toString() != "") {
                        that.usedSearchTerms.push(s);
                        if (searchDelay != undefined) {
                            clearTimeout(searchDelay);
                        }
                        searchDelay = setTimeout(function () {
                            that.receiveMedias("/api/media/search/" + s);
                        }, 300);
                    }
                    var so = new Search(s.toString(), that.medias, that.tags, that.users);
                    theVue.search = so;
                    theVue.medias = so.mediaResult;
                    console.log(so.mediaResult);
                    theVue.users = so.userResult;
                }
            },
            mounted: function () {
                //  if(sm.params.currentTitle!=undefined){
                // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
                //  if(sm.findMediaByName(sm.params.currentTitle)==undefined){
                //  sm.receiveMediaByName(sm.params.currentTitle);
                //}
                //}
            },
            watch: {
                $route: function (to, from) {
                    //this.show = false;
                    if (to.params.currentTitle != undefined) {
                        // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
                        if (sm.findMediaByName(to.params.currentTitle) == undefined) {
                            sm.receiveMediaByName(to.params.currentTitle);
                        }
                    }
                    if (to.params.editTitle != undefined) {
                        // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
                        if (sm.findMediaByName(to.params.editTitle) == undefined) {
                            sm.receiveMediaByName(to.params.editTitle);
                        }
                    }
                    if (to.params.profileId != undefined) {
                        this.user = sm.getUserById(to.params.profileId);
                        this.medias = sm.getMediasByUser(to.params.profileId);
                    }
                    else {
                        this.medias = sm.medias;
                    }
                    if (to.path == "/search") {
                        //this.searching();
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
    siteManager.prototype.receiveTagsForMedia = function (json, forceUpdate) {
        if (forceUpdate === void 0) { forceUpdate = true; }
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
            json = json.data;
            console.log(that.getTagsByIdArray(json.tagsIds));
            that.medias.unshift(new Media(json.title, json.description, json.source, json.poster_source, json.simpleType, json.type, that.getUserById(json.user_id), json.user_id, json.created_at, json.created_at_readable, json.comments, that.getTagsByIdArray(json.tagsIds)));
            theVue.medias = that.medias;
            theVue.$router.push('/');
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
                data = data.data;
                var m = new Media(data.title, data.description, data.source, data.poster_source, data.simpleType, data.type, that.getUserById(data.user_id), data.user_id, data.created_at, data.created_at_readable, data.comments, that.getTagsByIdArray(data.tagsIds));
                that.medias.push(m);
                theVue.medias = that.medias;
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
        return returnMedia;
    };
    siteManager.prototype.deleteMediaByName = function (mediaName) {
        console.log("deletemethod reach");
        var that = this;
        var i = 0;
        $.each(that.medias, function (key, value) {
            if (value != undefined) {
                if (value.title == mediaName) {
                    console.log("delete media " + mediaName);
                    that.medias.splice(i, 1);
                }
            }
            i++;
        });
        theVue.medias = that.medias;
        theVue.$router.push('/');
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
            theVue.users = that.users;
            theVue.medias = that.medias;
            if (theVue.$route.params.profileId != undefined) {
                theVue.user = sm.getUserById(theVue.$route.params.profileId);
                theVue.medias = sm.getMediasByUser(theVue.$route.params.profileId);
            }
            if (theVue.$route.params.currentTitle != undefined) {
                if (that.findMediaByName(theVue.$route.params.currentTitle) == undefined) {
                    that.receiveMediaByName(theVue.$route.params.currentTitle);
                }
            }
            if (theVue.$route.params.editTitle != undefined) {
                if (that.findMediaByName(theVue.$route.params.editTitle) == undefined) {
                    that.receiveMediaByName(theVue.$route.params.editTitle);
                }
            }
            if ((theVue.$router.currentRoute.path == "/search")) {
                theVue.searching();
                /*  if($("#theLiveSearch").val()==''){
                    theVue.medias = [];
                  }*/
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
var Search = /** @class */ (function () {
    function Search(search, medias, tags, users) {
        this.search = search;
        this.tagResult = [];
        this.userResult = [];
        this.mediaResult = [];
        if (search != "") {
            var mediaTitle = $("#theLiveSearchMediaTitle").is(':checked');
            var mediaDescription = $("#theLiveSearchMediaDescription").is(':checked');
            var tagsEnabled = $("#theLiveSearchTags").is(':checked');
            var that_1 = this;
            if ($("#theLiveSearchUsers").is(':checked')) {
                $.each(users, function (key, value) {
                    if (value.name.toLowerCase().indexOf(that_1.search.toLowerCase()) > -1) {
                        if (that_1.userResult.includes(value) == false) {
                            that_1.userResult.push(value);
                        }
                    }
                    if (value.bio.toLowerCase().indexOf(that_1.search.toLowerCase()) > -1) {
                        if (that_1.userResult.includes(value) == false) {
                            that_1.userResult.push(value);
                        }
                    }
                });
            }
            if (mediaTitle || mediaDescription) {
                console.log("PAssed media-if");
                console.log(medias);
                $.each(medias, function (key, value) {
                    console.log(that_1.search);
                    console.log(value.title);
                    if (mediaTitle) {
                        if (value.title.toLowerCase().indexOf(that_1.search.toLowerCase()) > -1) {
                            if (that_1.mediaResult.includes(value) == false) {
                                that_1.mediaResult.push(value);
                            }
                        }
                    }
                    if (mediaDescription) {
                        if (value.description.toLowerCase().indexOf(search.toLowerCase()) > -1) {
                            if (that_1.mediaResult.includes(value) == false) {
                                that_1.mediaResult.push(value);
                            }
                        }
                    }
                    if (tagsEnabled) {
                        if (that_1.mediaResult.includes(value) == false) {
                            that_1.mediaResult.push(value);
                        }
                    }
                });
            }
            if ($("#theLiveSearchMedias").is(':checked')) {
                this.userResult = medias;
            }
        }
    }
    return Search;
}());
