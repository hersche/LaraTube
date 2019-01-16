var baseUrl;
import Vue from 'vue';
import Router from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import VueCroppie from 'vue-croppie';
import { eventBus } from './eventBus';
import translation from './translation';
import dateTranslation from './dateTranslation';
import { MediaSorter, Search } from './tools';
import { User, Media, Tag, Category, Notification } from './models';
import VueApexCharts from 'vue-apexcharts';
import Vuesax from 'vuesax';
import 'material-icons/iconfont/material-icons.css';
import 'vuesax/dist/vuesax.css'; //Vuesax styles
import VuePlyr from 'vue-plyr';
import VueI18n from 'vue-i18n';
import Treeselect from '@riophae/vue-treeselect';
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css';
//import Echo from "laravel-echo"
var app;
var theVue;
var searchDelay;
var theMediaSorter = new MediaSorter();
var i18n;
var siteManager = /** @class */ (function () {
    function siteManager(base) {
        this.currentMediaId = 0;
        this.initing = true;
        this.blockScrollExecution = false;
        baseUrl = base + "/";
        if (localStorage.getItem("mediaTypes") != '' && localStorage.getItem("mediaTypes") != null) {
            this.types = localStorage.getItem("mediaTypes").split(",");
        }
        else {
            this.types = ["audio", "video"];
        }
        this.catchedTagMedias = [];
        this.usedSearchTerms = [];
        this.usedCatRequests = [];
        this.nextMedias = [];
        this.loggedUserId = Number($("#loggedUserId").attr("content"));
        this.updateCSRF();
        this.receiveUsers(function () {
        });
        this.csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        /*  if(this.loggedUserId){
          Echo.private('App.User.' + this.loggedUserId)
              .notification((notification) => {
                  console.log("wow, that worked!")
                  console.log(notification.type);
              });
            } */
        setInterval(this.updateCSRF, 1800000);
        //this.loadMorePages()
    }
    siteManager.prototype.initVue = function () {
        var _this = this;
        Vue.use(Router);
        Vue.use(BootstrapVue);
        Vue.use(VueCroppie);
        Vue.use(VueApexCharts);
        Vue.use(Vuesax);
        Vue.use(VuePlyr);
        Vue.use(VueI18n);
        Vue.component('treeselect', Treeselect);
        //Vue.component('plyr', VuePlyr)
        Vue.component('apexchart', VueApexCharts);
        var overview = Vue.component('overview', require("./components/OverviewComponent.vue"));
        var player = Vue.component('player', require("./components/MediaComponent.vue"));
        var profileComp = Vue.component('profile', require("./components/ProfileComponent.vue"));
        var editProfileComp = Vue.component('editprofile', require("./components/EditProfile.vue"));
        var tagComp = Vue.component('tags', require("./components/TagComponent.vue"));
        var loginComp = Vue.component('login', require("./components/auth/Login.vue"));
        var registerComp = Vue.component('register', require("./components/auth/Register.vue"));
        // how place this? may better let this routine static?
        // var resetComp = Vue.component('reset', require("./components/auth/Reset.vue"));
        var uploadComp = Vue.component('upload', require("./components/UploadComponent.vue"));
        var searchComp = Vue.component('search', require("./components/SearchComponent.vue"));
        var chartsComp = Vue.component('search', require("./components/ChartsComponent.vue"));
        var editVideoComp = Vue.component('search', require("./components/EditVideo.vue"));
        var aboutComp = Vue.component('search', require("./components/About.vue"));
        var sidebarComp = Vue.component('thesidebar', require("./components/SidebarComponent.vue"));
        var catComp = Vue.component('thesidebar', require("./components/Categories.vue"));
        var uaComp = Vue.component('thesidebar', require("./components/UserAdmin.vue"));
        var myVideosComp = Vue.component('thesidebar', require("./components/MyVideos.vue"));
        var notiComp = Vue.component('thesidebar', require("./components/Notifications.vue"));
        var ccComp = Vue.component('thesidebar', require("./components/CreateCategory.vue"));
        var ceComp = Vue.component('thesidebar', require("./components/EditCategory.vue"));
        var singleCatComp = Vue.component('thesidebar', require("./components/Category.vue"));
        var that = this;
        var routes = [
            { path: '/', component: overview },
            { path: '/media/:currentTitle', component: player },
            { path: '/profile/:profileId', component: profileComp },
            { path: '/tags', component: tagComp },
            { path: '/tags/:tagName', component: tagComp },
            { path: '/login', component: loginComp },
            { path: '/editprofile', component: editProfileComp },
            { path: '/register', component: registerComp },
            { path: '/upload', component: uploadComp },
            { path: '/search', component: searchComp },
            { path: '/charts', component: chartsComp },
            { path: '/categories', component: catComp },
            { path: '/category/:currentCat', component: singleCatComp },
            { path: '/editcat/:catId', component: ceComp },
            { path: '/newcat', component: ccComp },
            { path: '/about', component: aboutComp },
            { path: '/notifications', component: notiComp },
            { path: '/myvideos', component: myVideosComp },
            { path: '/admin/users', component: uaComp },
            { path: '/mediaedit/:editTitle', component: editVideoComp }
        ];
        eventBus.$on('getNotifications', function (url) {
            theVue.alert("Look for new notifications");
            that.receiveNotifications(url);
        });
        eventBus.$on('getNewMedias', function (title) {
            theVue.alert("Look for new medias..");
            that.receiveMedias();
        });
        eventBus.$on('languageChange', function (lang) {
            console.log("change language to " + lang);
            i18n.locale = lang;
        });
        eventBus.$on('userEdited', function (id) {
            theVue.alert("Look for new users..");
            that.receiveUsers();
            that.updateCSRF();
            if (id != '' && id != undefined) {
                theVue.$router.push("/profile/" + id);
            }
        });
        eventBus.$on('refreshMedias', function (title) {
            theVue.canloadmore = true;
            that.catchedTagMedias = [];
            _this.usedSearchTerms = [];
            _this.usedCatRequests = [];
            that.receiveMedias("/internal-api/media" + _this.getIgnoreParam(), true);
            that.updateCSRF();
        });
        eventBus.$on('loadAllMedias', function (title) {
            that.receiveMedias("/internal-api/medias/all" + _this.getIgnoreParam());
            theVue.canloadmore = false;
            that.updateCSRF();
        });
        eventBus.$on('autoplayNextVideo', function (id) {
            console.log("received autoplay");
            var tmpv = that.nextVideosList(id);
            //console.log(theVue.nextvideos)
            if (tmpv.length > 0) {
                console.log("got values!" + tmpv[0].title);
                theVue.currentmedia = tmpv[0];
                theVue.$router.push('/media/' + tmpv[0].urlTitle);
                that.nextMedias = that.nextVideosList(tmpv[0].id);
                theVue.nextvideos = that.nextMedias;
                if (theVue.nextvideos == null || theVue.nextvideos) {
                    console.log("do load more cause nextvideos is empty");
                    that.loadMorePages(function () {
                        that.nextMedias = that.nextVideosList(id);
                        theVue.nextvideos = that.nextMedias;
                        that.loadMorePages();
                        console.log("received by callback from nextvideo-empty");
                        //theVue.$router.push('/media/'+encodeURIComponent(theVue.nextvideos[0].title));
                    });
                }
            }
            else {
                //  console.log("do alternative next medias")
                that.loadMorePages(function () {
                    that.nextMedias = that.nextVideosList(id);
                    theVue.nextvideos = that.nextMedias;
                    //    console.log("received by callback")
                    theVue.$router.push('/media/' + theVue.nextvideos[0].urlTitle);
                    theVue.currentmedia = theVue.nextvideos[0];
                    theVue.nextvideos = that.nextVideosList(theVue.nextvideos[0].id);
                    if (theVue.nextvideos == null || theVue.nextvideos) {
                        //  console.log("do load more")
                        that.loadMorePages(function () {
                            that.nextMedias = that.nextVideosList(id);
                            theVue.nextvideos = that.nextMedias;
                            that.loadMorePages();
                            theVue.medias = that.getFilteredMedias();
                            theVue.fullmedias = that.medias;
                            //  console.log("received by callback")
                            //theVue.$router.push('/media/'+encodeURIComponent(theVue.nextvideos[0].title));
                        });
                    }
                });
            }
        });
        eventBus.$on('login', function (settings) {
            _this.initing = false;
            _this.loggedUserId = settings.user_id;
            theVue.loggeduserid = _this.loggedUserId;
            that.receiveUsers(function () {
                that.currentUser = that.getUserById(that.loggedUserId);
                theVue.currentuser = that.currentUser;
            });
            theVue.alert("Welcome back, " + that.getUserById(that.loggedUserId).name, "success", "exit_to_app");
            theVue.$router.push('/');
            that.updateCSRF();
        });
        eventBus.$on('logout', function (settings) {
            _this.loggedUserId = 0;
            theVue.loggeduserid = _this.loggedUserId;
            that.currentUser = that.getUserById(that.loggedUserId);
            theVue.currentuser = that.currentUser;
            theVue.alert("Logged out", "danger", "power_settings_new");
            theVue.$router.push('/');
            that.updateCSRF();
        });
        eventBus.$on('loginFailed', function (settings) {
            theVue.alert("Login failed", "danger", "error");
        });
        eventBus.$on('loadUserVideos', function (userid) {
            that.receiveMedias("/internal-api/medias/by/" + userid + _this.getIgnoreParam());
        });
        eventBus.$on('sortBy', function (sortBy) {
            theMediaSorter.sortBy = sortBy;
            if (theVue.$router.currentRoute.path == "/search") {
                theVue.search.mediaResult = theMediaSorter.sort(theVue.search.mediaResult);
            }
            that.medias = theMediaSorter.sort(that.medias);
            if (that.currentMediaId != 0) {
                that.nextMedias = that.nextVideosList(that.currentMediaId);
                theVue.nextvideos = that.nextMedias;
            }
            theVue.fullmedias = that.medias;
            theVue.medias = that.getFilteredMedias();
        });
        eventBus.$on('commentCreated', function (json) {
            // Workaround by receive the media again.
            that.receiveMediaById(json.data.media_id);
            that.updateCSRF();
            /*var m = that.findMediaById(Number(json.data.media_id))
            m.comments = JSON.parse(JSON.stringify(m.comments)).push(json.data)
            console.log(m.comments)*/
            //m.comments = m.comments.sort(MediaSorter.byCreatedAtComments)
            //console.log(JSON.parse(that.findMediaById(Number(json.data.media_id)).comments))
            //that.findMediaById(Number(json.data.media_id)).comments = JSON.parse(that.findMediaById(Number(json.data.media_id)).comments).unshift(json.data)
            theVue.alert("Comment created", "success");
        });
        eventBus.$on('refreshMedia', function (id) {
            // Workaround by receive the media again.
            that.receiveMediaById(id);
            that.updateCSRF();
            /*var m = that.findMediaById(Number(json.data.media_id))
            m.comments = JSON.parse(JSON.stringify(m.comments)).push(json.data)
            console.log(m.comments)*/
            //m.comments = m.comments.sort(MediaSorter.byCreatedAtComments)
            //console.log(JSON.parse(that.findMediaById(Number(json.data.media_id)).comments))
            //that.findMediaById(Number(json.data.media_id)).comments = JSON.parse(that.findMediaById(Number(json.data.media_id)).comments).unshift(json.data)
            theVue.alert("Media refreshed", "success");
        });
        eventBus.$on('loadMediaById', function (id) {
            // Workaround by receive the media again.
            that.receiveMediaById(id);
            that.updateCSRF();
            if (theVue != undefined) {
                theVue.alert("Media load by id", "success");
            }
        });
        eventBus.$on('loadMediaByCommentId', function (id) {
            // Workaround by receive the media again.
            if (theVue != undefined) {
                that.receiveMediaByCommentId(id);
                that.updateCSRF();
            }
            theVue.alert("Media load by comment", "success");
        });
        eventBus.$on('loadMedia', function (title) {
            // Workaround by receive the media again.d
            that.receiveMediaByName(encodeURIComponent(title), function (id) {
                that.updateCSRF();
                if (theVue != undefined) {
                    theVue.fullmedias = that.medias;
                    theVue.medias = that.getFilteredMedias(that.medias);
                    if (theVue.$route.params.currentTitle != undefined) {
                        that.currentMediaId = id;
                        that.nextMedias = that.nextVideosList(id);
                        theVue.nextvideos = that.nextMedias;
                    }
                    //  theVue.alert("Media load media by title","success")
                }
            });
            /*var m = that.findMediaById(Number(json.data.media_id))
            m.comments = JSON.parse(JSON.stringify(m.comments)).push(json.data)
            console.log(m.comments)*/
            //m.comments = m.comments.sort(MediaSorter.byCreatedAtComments)
            //console.log(JSON.parse(that.findMediaById(Number(json.data.media_id)).comments))
            //that.findMediaById(Number(json.data.media_id)).comments = JSON.parse(that.findMediaById(Number(json.data.media_id)).comments).unshift(json.data)
        });
        eventBus.$on('videoDeleted', function (title) {
            theVue.alert("Video " + title + " deleted", "success");
            // TODO that.fillMediasToCat(); in callback!
            that.deleteMediaByName(title);
            that.updateCSRF();
        });
        eventBus.$on('videoCreated', function (json) {
            that.receiveTagsForMedia(json);
            theVue.alert("Video " + json.data.title + " created", "success");
            that.updateCSRF();
        });
        eventBus.$on('categoriesRefreshed', function (json) {
            that.receiveCategories(function () {
                theVue.alert("Categories refreshed", "success");
                that.updateCSRF();
                theVue.$router.push("/categories");
            });
        });
        eventBus.$on('videoEdited', function (json) {
            that.deleteMediaByName(json[0]);
            that.receiveTagsForMedia(json[1]);
            theVue.alert("Video " + json[1].data.title + " edited", "success");
            that.updateCSRF();
        });
        eventBus.$on('checkTag', function (tagName) {
            //if(theVue.$router.currentRoute.path!="/search"){
            if (tagName == '') {
                if ($("#specialAllTag").is(":checked")) {
                    theVue.medias = that.getFilteredMedias();
                }
                else {
                    theVue.medias = [];
                    theVue.medias = that.getFilteredMedias();
                }
            }
            else {
                if (that.catchedTagMedias.includes(tagName) == false) {
                    that.catchedTagMedias.push(tagName);
                    that.receiveMedias("/api/tags/" + tagName, false, function () {
                        theVue.fullmedias = that.medias;
                    });
                }
            }
            //}
        });
        eventBus.$on('loadMore', function (title) {
            that.loadMorePages();
        });
        $(window).scroll(function () {
            // Here, we load more medias if we reach the end of page
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 50) {
                if (theVue.canloadmore && that.blockScrollExecution == false) {
                    if (theVue.$router.currentRoute.path == "/" || theVue.$router.currentRoute.path == "/tags") {
                        console.log("near bottom, do a request and block!");
                        that.blockScrollExecution = true;
                        that.loadMorePages(function () {
                            console.log("done, allow next request");
                            that.blockScrollExecution = false;
                        });
                    }
                }
            }
        });
        eventBus.$on('refreshSearch', function (title) {
            theVue.searching();
        });
        eventBus.$on('getMediasByCatId', function (id) {
            if (that.usedCatRequests.includes(id) == false) {
                that.usedCatRequests.push(id);
                that.receiveMedias("/internal-api/medias/byCatId/" + id + that.getIgnoreParam(), false, function () {
                    eventBus.$emit('mediasByCatIdReceived', id);
                });
            }
        });
        eventBus.$on('filterTypes', function (types) {
            that.types = types;
            theVue.fullmedias = that.medias;
            theVue.medias = that.getFilteredMedias();
            if (_this.currentMediaId != 0) {
                that.nextMedias = that.nextVideosList(_this.currentMediaId);
            }
            theVue.nextvideos = that.getFilteredMedias(that.nextMedias);
            if (theVue.$router.currentRoute.path == "/search") {
                theVue.searching();
            }
        });
        eventBus.$on('setCurrentMedia', function (id) {
            console.log("set current id");
            that.currentMediaId = id;
            that.nextMedias = that.nextVideosList(id);
            if (theVue != undefined) {
                theVue.nextvideos = that.nextMedias;
            }
        });
        var lang = "en";
        if (localStorage.getItem("language") != '' && localStorage.getItem("language") != undefined) {
            lang = localStorage.getItem("language");
        }
        i18n = new VueI18n({
            locale: lang,
            fallbackLocale: 'en',
            messages: translation,
            dateTimeFormats: dateTranslation
        });
        //  sm.receiveUsers(true);
        // new User(0,"None","img/404/avatar.png","img/404/background.png", "None-user", {})
        theVue = new Vue({
            i18n: i18n,
            data: {
                title: "Overview",
                search: '',
                nextvideos: [],
                notifications: [],
                treecatptions: undefined,
                fullmedias: that.medias,
                csrf: that.csrf,
                totalmedias: that.totalMedias,
                currentuser: that.currentUser,
                users: this.users,
                loggeduserid: this.loggedUserId,
                tags: this.tags,
                canloadmore: true,
                medias: this.getFilteredMedias(),
                user: that.currentUser,
                categories: that.categories,
                baseUrl: baseUrl
            },
            components: {
                'thesidebar': sidebarComp
            },
            router: new Router({ routes: routes,
                scrollBehavior: function (to, from, savedPosition) {
                    return { x: 0, y: 0 };
                }
            }),
            methods: {
                alert: function (msg, type, icon) {
                    if (type === void 0) { type = "dark"; }
                    if (icon === void 0) { icon = ''; }
                    this.$vs.notify({ title: msg, text: '', icon: icon, color: type, position: 'bottom-center' });
                },
                openLoading: function () {
                    var _this = this;
                    this.$vs.loading();
                    setTimeout(function () {
                        _this.$vs.loading.close();
                    }, 2000);
                },
                searching: function () {
                    var s = $("#theLiveSearch").val();
                    if (theVue.$router.currentRoute.path != "/search" && s != '') {
                        theVue.$router.push('/search');
                    }
                    if (theVue.$router.currentRoute.path == "/search" && s == '') {
                        theVue.$router.go(-1);
                    }
                    if (s != '') {
                        var m = [];
                        if (that.usedSearchTerms.includes(s.toString()) == false && s.toString() != "") {
                            if (searchDelay != undefined) {
                                clearTimeout(searchDelay);
                            }
                            searchDelay = setTimeout(function () {
                                that.usedSearchTerms.push(s);
                                that.receiveMedias("/internal-api/medias/search/" + s + that.getIgnoreParam());
                            }, 300);
                        }
                        var so = new Search(s.toString(), that.getFilteredMedias(), that.tags, that.users);
                        theVue.search = so;
                        theVue.fullmedias = that.medias;
                        theVue.medias = that.getFilteredMedias(so.mediaResult);
                        theVue.users = so.userResult;
                    }
                }
            },
            mounted: function () {
                //  $("#moremodal").show();
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
                    /*if(to.params.currentTitle!=undefined){
                      // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
                      if(that.findMediaByName(to.params.currentTitle)==undefined){
                        that.receiveMediaByName(to.params.currentTitle);
                      }else {
                        this.nextvideos = that.nextVideosList(that.findMediaByName(this.$route.params.currentTitle).id)
                      //  console.log("after site-change")
                      //  console.log(this.nextvideos)
                      //  console.log(that.findMediaByName(this.$route.params.currentTitle).id)
                      }
                    }*/
                    /*  if(to.params.editTitle!=undefined){
                        // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
                        if(that.findMediaByName(to.params.editTitle)==undefined){
                          that.receiveMediaByName(to.params.editTitle);
                        }
                      }*/
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
                    if (to.path == "/login" || to.path == "/register") {
                        if (that.loggedUserId != 0) {
                            // Redirect if already logged in
                            theVue.$router.push('/');
                        }
                    }
                }
            }
        }).$mount('#app');
        theVue.$router.beforeResolve(function (to, from, next) {
            //  if (to.name) {
            //console.log("start loadng")
            //theVue.$vs.loading()
            //}
            next();
        });
        theVue.$router.afterEach(function (to, from) {
            //  console.log("stop loadng")
            //  theVue.$vs.loading.close()
        });
        if (localStorage.getItem('cookiePolicy') != "read") {
            theVue.$vs.notify({
                title: 'We use cookies and the offline-storage',
                text: 'Some of your informations are saved in your browser or on the server (mostly in case of login).<br /> With a Ok you acceppt this. <br /> <a class="btn btn-success" onclick="localStorage.setItem(\'cookiePolicy\',\'read\');">Ok</a>',
                color: 'primary',
                fixed: true,
                click: function () {
                },
            });
        }
    };
    siteManager.prototype.loadMorePages = function (callback) {
        if (callback === void 0) { callback = undefined; }
        console.log("load more pages");
        console.log(this.totalMedias);
        console.log("vs");
        console.log(this.medias.length);
        if (this.totalMedias > this.medias.length) {
            this.receiveMedias('/internal-api/media?' + this.getIgnoreParam(false), false, callback);
            theVue.canloadmore = true;
        }
        else {
            console.log("end reached");
            theVue.canloadmore = false;
        }
    };
    siteManager.prototype.getFilteredMedias = function (myList) {
        if (myList === void 0) { myList = undefined; }
        var theMedias = [];
        var origMedias;
        if (myList == undefined) {
            origMedias = this.medias;
        }
        else {
            origMedias = myList;
        }
        var that = this;
        $.each(origMedias, function (key, value) {
            $.each(that.types, function (key1, type) {
                if (type == value.simpleType) {
                    if (theMedias.indexOf(value) == -1) {
                        theMedias.push(value);
                    }
                }
            });
            if (value.id == that.currentMediaId) {
                if (theMedias.indexOf(value) == -1) {
                    theMedias.push(value);
                }
            }
        });
        return theMedias;
    };
    siteManager.prototype.fillUser = function (comment) {
        var that = this;
        $.each(comment.childs, function (key, value) {
            if (value.childs.length > 0) {
                comment.childs[key] = that.fillUser(value);
            }
            comment.childs[key].user = that.getUserById(value.user_id);
        });
        comment.childs = comment.childs.sort(MediaSorter.byCreatedAtComments);
        return comment;
    };
    /*
    * Old: Update the CSRF-Token from server for all forms
    * New: Refresh CSRF AND totalMedias, which is needed to get medias.
    * Within this, we can react if there are new videos since initial.
    */
    siteManager.prototype.updateCSRF = function () {
        var that = this;
        $.getJSON('/internal-api/refresh-csrf').done(function (data) {
            that.csrf = data.csrf;
            that.totalMedias = data.totalMedias;
            if (theVue != undefined) {
                console.log("update the vue total medias" + data.totalMedias);
                theVue.csrf = data.csrf;
                theVue.totalmedias = data.totalMedias;
                if (that.totalMedias > that.medias.length) {
                    theVue.canloadmore = true;
                }
            }
            $('meta[name="csrf-token"]').attr('content', data.csrf);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': data.csrf
                }
            });
            //  csrfToken = data; // the new token
        });
    };
    siteManager.prototype.receiveUsers = function (callback) {
        if (callback === void 0) { callback = undefined; }
        var that = this;
        $.getJSON("/internal-api/users", function name(data) {
            that.users = [];
            if (that.loggedUserId == 0) {
                that.currentUser = new User(0, "Guest", "/img/404/avatar.png", "/img/404/background.png", "", "", "", false);
                if (theVue != undefined) {
                    theVue.currentuser = that.currentUser;
                }
            }
            $.each(data.data, function (key, value) {
                var u = new User(value.id, value.name, value.avatar, value.background, value.bio, value.mediaIds, value.tagString, value.public, value.admin, value.email, value.created_at.date, value.updated_at.date);
                if (u.id == that.loggedUserId) {
                    that.currentUser = u;
                    if (theVue != undefined) {
                        // workaround since url doesn't change
                        // theVue.currentuser = new User(0, "Guest", "/img/404/avatar.png", "/img/404/background.png", "","");
                        theVue.currentuser = u;
                    }
                }
                that.users.push(u);
            });
            if (that.initing) {
                that.receiveTags();
                that.receiveCategories();
            }
            if (callback != undefined) {
                callback();
            }
        });
    };
    siteManager.prototype.nextVideosList = function (id) {
        var nextVideos = [];
        var startAdd = false;
        $.each(this.getFilteredMedias(), function (key, value) {
            if (startAdd) {
                nextVideos.push(value);
            }
            if (value.id == id) {
                startAdd = true;
            }
        });
        return nextVideos;
    };
    // This method is for telling the server, which medias we don't need to redownload.
    siteManager.prototype.getIgnoreParam = function (first) {
        if (first === void 0) { first = true; }
        // id 0 is fake, but by it, we can alway attach ,:id
        var content = "&i=0";
        if (first) {
            content = "?i=0";
        }
        $.each(this.medias, function (key, value) {
            content += "," + value.id;
        });
        return content + "&types=" + this.types.join() + "&sortBy=" + theMediaSorter.sortBy;
    };
    siteManager.prototype.mkTreeCat = function (data, l) {
        if (l === void 0) { l = 0; }
        var result = [];
        if (l == 0) {
            result = [{ id: 0, label: 'None' }];
        }
        var that = this;
        $.each(data, function (key, value) {
            if (value.children.length > 0) {
                result.push({ id: value.id, label: value.title, children: that.mkTreeCat(value.children, 1) });
            }
            else {
                result.push({ id: value.id, label: value.title });
            }
        });
        return result;
    };
    siteManager.prototype.resolveMediaCategorys = function () {
    };
    siteManager.prototype.receiveCategories = function (callback) {
        if (callback === void 0) { callback = undefined; }
        var that = this;
        $.getJSON("/internal-api/categories", function name(data) {
            that.categories = [];
            $.each(data.data, function (key, value) {
                that.categories.push(new Category(value.id, value.title, value.description, value.avatar_source, value.background_source, value.parent_id, value.children));
            });
            that.fillMediasToCat();
            // here, we handle categorys and bring it in a format for the
            // special tree-select-component
            that.treecatptions = that.mkTreeCat(data.data);
            if (theVue != undefined) {
                theVue.categories = that.categories;
                theVue.treecatptions = that.treecatptions;
                console.log(theVue.treecatptions);
            }
            if (callback != undefined) {
                callback();
            }
        });
    };
    siteManager.prototype.receiveNotifications = function (url, callback) {
        if (url === void 0) { url = '/internal-api/notifications'; }
        if (callback === void 0) { callback = undefined; }
        var that = this;
        if (this.loggedUserId != 0) {
            $.getJSON(url, function name(data) {
                that.notifications = [];
                $.each(data, function (key, value) {
                    if (value.data.media_id != null && value.data.media_id != 0) {
                        that.findMediaById(value.data.media_id, function () {
                            console.log("push media-like-notification " + value.id);
                            that.notifications.push(new Notification(value.id, value.type, value.data, value.read_at, value.created_at));
                            theVue.notifications = that.notifications;
                        });
                    }
                    if (value.data.comment_id != null && value.data.comment_id != 0) {
                        console.log("load a comment");
                        that.getCommentById2(value.data.comment_id, function () {
                            console.log("push comment-like-notification " + value.id);
                            that.notifications.push(new Notification(value.id, value.type, value.data, value.read_at, value.created_at));
                            theVue.notifications = that.notifications;
                        });
                    }
                });
                this.notifications = that.notifications;
                if (theVue != undefined) {
                    console.log("set notifications to vue");
                    theVue.notifications = this.notifications;
                }
            });
        }
    };
    siteManager.prototype.getCategoryMedias = function (category_id) {
        var ma = [];
        $.each(this.medias, function (key, value) {
            if (value.category_id == category_id) {
                ma.push(value);
            }
        });
        return ma;
    };
    siteManager.prototype.getCategoryKey = function (category_id, data) {
        if (data === void 0) { data = undefined; }
        var res;
        var that = this;
        var idata = this.categories;
        if (data != undefined) {
            idata = data;
        }
        $.each(idata, function (key, value) {
            if (value.children.length > 0) {
                var t = that.getCategoryKey(category_id, value.children);
                if (t != undefined) {
                    res = t;
                }
            }
            if (value.id == category_id) {
                res = key;
            }
        });
        return res;
    };
    siteManager.prototype.getCategoryById = function (category_id, data) {
        if (data === void 0) { data = undefined; }
        var res;
        var that = this;
        var idata = this.categories;
        if (data != undefined) {
            idata = data;
        }
        $.each(idata, function (key, value) {
            if (value.children.length > 0) {
                var t = that.getCategoryById(category_id, value.children);
                if (t != undefined) {
                    res = t;
                }
            }
            if (value.id == category_id) {
                res = value;
            }
        });
        return res;
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
            that.medias.unshift(new Media(json.id, json.title, json.description, json.source, json.poster_source, json.duration, json.simpleType, json.techType, json.type, that.getUserById(json.user_id), json.user_id, json.created_at, json.updated_at, json.created_at_readable, json.comments, that.getTagsByIdArray(json.tagsIds), json.myLike, json.likes, json.dislikes, json.tracks, json.category_id));
            theVue.fullmedias = that.medias;
            theVue.medias = that.getFilteredMedias();
            theVue.$router.push('/');
        });
    };
    siteManager.prototype.getCommentById2 = function (id, callback) {
        if (callback === void 0) { callback = undefined; }
        if (id == null || id == 0) {
            return;
        }
        var theMedia = undefined;
        var that = this;
        this.medias.forEach(function (val, key) {
            val.comments.forEach(function (val2, key2) {
                if (val2.id == id) {
                    theMedia = val2;
                }
            });
        });
        if (theMedia == undefined) {
            //eventBus.$emit('loadMediaByCommentId',id);
            that.receiveMediaByCommentId(id, callback);
        }
        else {
            if (callback != undefined) {
                callback();
            }
        }
        return theMedia;
    };
    siteManager.prototype.receiveMediaByCommentId = function (mediaName, callback) {
        if (callback === void 0) { callback = undefined; }
        var that = this;
        var theKey;
        var existsAlready = false;
        $.getJSON("/internal-api/medias/byCommentId/" + mediaName, function name(data) {
            data = data.data;
            $.each(that.medias, function (key, value) {
                $.each(value.comments, function (key2, comment) {
                    if (comment.id == mediaName) {
                        existsAlready = true;
                        theKey = key;
                    }
                });
            });
            if (existsAlready == false) {
                var m = new Media(data.id, data.title, data.description, data.source, data.poster_source, data.duration, data.simpleType, data.techType, data.type, that.getUserById(data.user_id), data.user_id, data.created_at, data.updated_at, data.created_at_readable, data.comments, that.getTagsByIdArray(data.tagsIds), data.myLike, data.likes, data.dislikes, data.tracks, data.category_id);
                $.each(m.comments, function (key1, value1) {
                    m.comments[key1] = that.fillUser(value1);
                    m.comments[key1].user = that.getUserById(value1.user_id);
                });
                that.medias.push(m);
                that.medias = theMediaSorter.sort(that.medias);
                theVue.fullmedias = that.medias;
                theVue.medias = that.getFilteredMedias();
            }
            else {
                var m = new Media(data.id, data.title, data.description, data.source, data.poster_source, data.duration, data.simpleType, data.techType, data.type, that.getUserById(data.user_id), data.user_id, data.created_at, data.updated_at, data.created_at_readable, data.comments, that.getTagsByIdArray(data.tagsIds), data.myLike, data.likes, data.dislikes, data.tracks, data.category_id);
                $.each(m.comments, function (key1, value1) {
                    m.comments[key1] = that.fillUser(value1);
                    m.comments[key1].user = that.getUserById(value1.user_id);
                });
                if (m != that.medias[theKey]) {
                    //console.log(JSON.parse(JSON.stringify(m.comments)))
                    //m.comments = m.comments.sort(MediaSorter.byCreatedAtComments);
                    that.medias[theKey].likes = m.likes;
                    that.medias[theKey].dislikes = m.dislikes;
                    that.medias[theKey].tracks = m.tracks;
                    that.medias[theKey].updated_at = m.updated_at;
                    that.medias[theKey].comments = m.comments.sort(MediaSorter.byCreatedAtComments);
                    theVue.fullmedias = that.medias;
                    theVue.medias = that.getFilteredMedias();
                }
                //console.warn("If the media already existed, why this method was used?");
            }
            if (callback != undefined) {
                callback();
            }
        });
    };
    siteManager.prototype.receiveMediaById = function (mediaName, callback) {
        if (callback === void 0) { callback = undefined; }
        var that = this;
        var theKey;
        var existsAlready = false;
        $.getJSON("/internal-api/medias/byId/" + mediaName, function name(data) {
            $.each(that.medias, function (key, value) {
                if (value.id == mediaName) {
                    existsAlready = true;
                    theKey = key;
                }
            });
            data = data.data;
            if (that.findMediaById(mediaName, undefined, false) == undefined) {
                var m = new Media(data.id, data.title, data.description, data.source, data.poster_source, data.duration, data.simpleType, data.techType, data.type, that.getUserById(data.user_id), data.user_id, data.created_at, data.updated_at, data.created_at_readable, data.comments, that.getTagsByIdArray(data.tagsIds), data.myLike, data.likes, data.dislikes, data.tracks, data.category_id);
                $.each(m.comments, function (key1, value1) {
                    m.comments[key1] = that.fillUser(value1);
                    m.comments[key1].user = that.getUserById(value1.user_id);
                });
                that.medias.push(m);
                that.medias = theMediaSorter.sort(that.medias);
                theVue.fullmedias = that.medias;
                theVue.medias = that.getFilteredMedias();
            }
            else {
                var m = new Media(data.id, data.title, data.description, data.source, data.poster_source, data.duration, data.simpleType, data.techType, data.type, that.getUserById(data.user_id), data.user_id, data.created_at, data.updated_at, data.created_at_readable, data.comments, that.getTagsByIdArray(data.tagsIds), data.myLike, data.likes, data.dislikes, data.tracks, data.category_id);
                $.each(m.comments, function (key1, value1) {
                    m.comments[key1] = that.fillUser(value1);
                    m.comments[key1].user = that.getUserById(value1.user_id);
                });
                if (m != that.medias[theKey]) {
                    //console.log(JSON.parse(JSON.stringify(m.comments)))
                    //m.comments = m.comments.sort(MediaSorter.byCreatedAtComments);
                    that.medias[theKey].likes = m.likes;
                    that.medias[theKey].dislikes = m.dislikes;
                    that.medias[theKey].tracks = m.tracks;
                    that.medias[theKey].updated_at = m.updated_at;
                    that.medias[theKey].comments = m.comments.sort(MediaSorter.byCreatedAtComments);
                    theVue.fullmedias = that.medias;
                    theVue.medias = that.getFilteredMedias();
                }
                //console.warn("If the media already existed, why this method was used?");
            }
            if (callback != undefined) {
                callback();
            }
        });
    };
    siteManager.prototype.receiveMediaByName = function (mediaName, callback) {
        if (callback === void 0) { callback = undefined; }
        var that = this;
        var theKey;
        var existsAlready = false;
        var m;
        $.each(that.medias, function (key, value) {
            if (value.urlTitle == mediaName) {
                existsAlready = true;
                theKey = key;
            }
        });
        $.getJSON("/internal-api/media/" + mediaName, function name(data) {
            data = data.data;
            if (that.findMediaByName(mediaName) == undefined) {
                console.log("receiveMediaByName; seems undefined");
                m = new Media(data.id, data.title, data.description, data.source, data.poster_source, data.duration, data.simpleType, data.techType, data.type, that.getUserById(data.user_id), data.user_id, data.created_at, data.updated_at, data.created_at_readable, data.comments, that.getTagsByIdArray(data.tagsIds), data.myLike, data.likes, data.dislikes, data.tracks, data.category_id);
                $.each(m.comments, function (key1, comment) {
                    m.comments[key1] = that.fillUser(comment);
                    m.comments[key1].user = that.getUserById(comment.user_id);
                });
                if (that.medias.indexOf(m) == -1) {
                    that.medias.push(m);
                }
                that.medias = theMediaSorter.sort(that.medias);
            }
            else if (theKey != undefined) {
                console.log("receiveMediaByName; seems already exist");
                m = new Media(data.id, data.title, data.description, data.source, data.poster_source, data.duration, data.simpleType, data.techType, data.type, that.getUserById(data.user_id), data.user_id, data.created_at, data.updated_at, data.created_at_readable, data.comments, that.getTagsByIdArray(data.tagsIds), data.myLike, data.likes, data.dislikes, data.tracks, data.category_id);
                $.each(m.comments, function (key1, comment) {
                    m.comments[key1] = that.fillUser(comment);
                    m.comments[key1].user = that.getUserById(comment.user_id);
                });
                if (m != that.medias[theKey]) {
                    //console.log(JSON.parse(JSON.stringify(m.comments)))
                    m.comments = m.comments.sort(MediaSorter.byCreatedAtComments);
                    that.medias[theKey].likes = m.likes;
                    that.medias[theKey].dislikes = m.dislikes;
                    that.medias[theKey].tracks = m.tracks;
                    that.medias[theKey].updated_at = m.updated_at;
                }
                that.medias[theKey].comments = m.comments.sort(MediaSorter.byCreatedAtComments);
                //console.warn("If the media already existed, why this method was used?");
            }
            theVue.fullmedias = that.medias;
            theVue.medias = that.getFilteredMedias();
            if (callback != undefined) {
                callback(data.id);
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
            if (value.urlTitle == mediaName) {
                returnMedia = value;
            }
        });
        return returnMedia;
    };
    siteManager.prototype.findMediaById = function (id, callback, getIfUndefined) {
        if (callback === void 0) { callback = undefined; }
        if (getIfUndefined === void 0) { getIfUndefined = true; }
        var returnMedia = undefined;
        var that = this;
        $.each(that.medias, function (key, value) {
            //console.log("found the value:"+value.id+" vs "+id)
            if (value.id == id) {
                returnMedia = value;
            }
        });
        if (returnMedia == undefined) {
            if (getIfUndefined) {
                that.receiveMediaById(id, callback);
            }
        }
        else {
            if (callback != undefined) {
                callback();
            }
        }
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
        theVue.fullmedias = that.medias;
        theVue.medias = that.getFilteredMedias();
        theVue.$router.push('/');
    };
    siteManager.prototype.fillMediasToCat = function (c) {
        if (c === void 0) { c = undefined; }
        var that = this;
        if (c == undefined) {
            c = that.categories;
        }
        $.each(c, function (key1, value) {
            value.setMedias(that.medias);
            if (value.children.length > 0) {
                that.fillMediasToCat(value.children);
            }
        });
    };
    siteManager.prototype.receiveMedias = function (url, forceUpdate, callback) {
        if (url === void 0) { url = "/internal-api/media" + this.getIgnoreParam(); }
        if (forceUpdate === void 0) { forceUpdate = false; }
        if (callback === void 0) { callback = undefined; }
        var that = this;
        var loadCount = 0, replaceCount = 0;
        if ((forceUpdate) || (that.medias == undefined)) {
            that.medias = [];
        }
        if (this.totalMedias > this.medias.length) {
            $.getJSON(url, function name(data) {
                $.each(data.data, function (key, value) {
                    //console.log(that.findMediaById(value.id))
                    if (that.findMediaById(value.id, undefined, false) == undefined) {
                        var m = new Media(value.id, value.title, value.description, value.source, value.poster_source, value.duration, value.simpleType, value.techType, value.type, that.getUserById(value.user_id), value.user_id, value.created_at, value.updated_at, value.created_at_readable, value.comments, that.getTagsByIdArray(value.tagsIds), value.myLike, value.likes, value.dislikes, value.tracks, value.category_id);
                        $.each(m.comments, function (key1, value1) {
                            m.comments[key1] = that.fillUser(value1);
                            //console.log(that.fillUser(value1))
                            m.comments[key1].user = that.getUserById(value1.user_id);
                        });
                        //m.comments = m.comments.sort(MediaSorter.byCreatedAt)
                        loadCount++;
                        m.comments = m.comments.sort(MediaSorter.byCreatedAtComments);
                        that.medias.push(m);
                        //if(that.getCategoryKey(m.category_id)!=undefined){
                        //that.categories[that.getCategoryKey(m.category_id)].medias.push(m)
                        //}
                        that.fillMediasToCat();
                    }
                    else {
                        replaceCount++;
                        /*  var m = new Media(value.id,value.title, value.description, value.source, value.poster_source,value.duration, value.simpleType,value.techType, value.type, that.getUserById(value.user_id),value.user_id,value.created_at,value.updated_at,value.created_at_readable,value.comments,that.getTagsByIdArray(value.tagsIds),value.myLike,value.likes,value.dislikes)
                          $.each( m.comments, function( key1, value1 ) {
                            m.comments[key1] = that.fillUser(value1);
                            //console.log(that.fillUser(value1))
                            m.comments[key1].user = that.getUserById(value1.user_id)
              
                          });
                          m.comments = m.comments.sort(MediaSorter.byCreatedAtComments);
                          if(m!=value){
                            replaceCount++;
                            console.log("Media replaced "+value.title+" with "+m.title)
                            m.comments = JSON.parse(JSON.stringify(m.comments)).sort(MediaSorter.byCreatedAtComments);
                            that.medias[key] = m;
                          }*/
                    }
                });
                if (theVue == undefined) {
                    that.initVue();
                    that.receiveNotifications();
                    if (that.notificationTimer != undefined) {
                        clearInterval(that.notificationTimer);
                    }
                    that.notificationTimer = setInterval(function () {
                        console.log("check for new notifications");
                        that.receiveNotifications();
                    }, 120000);
                    that.updateCSRF();
                }
                theVue.users = that.users;
                if (that.treecatptions != undefined) {
                    theVue.treecatptions = that.treecatptions;
                }
                that.medias = theMediaSorter.sort(that.medias);
                theVue.fullmedias = that.medias;
                theVue.medias = that.getFilteredMedias();
                theVue.categories = that.categories;
                if (theVue.$route.params.profileId != undefined) {
                    theVue.user = that.getUserById(theVue.$route.params.profileId);
                    theVue.fullmedias = that.medias;
                    theVue.medias = that.getFilteredMedias(that.getMediasByUser(theVue.$route.params.profileId));
                }
                /*if(theVue.$route.params.currentTitle!=undefined){
                  if(that.findMediaByName(theVue.$route.params.currentTitle)==undefined){
                    that.receiveMediaByName(theVue.$route.params.currentTitle,function(id){
                      that.currentMediaId = id
                      that.nextMedias = that.nextVideosList(id)
                      theVue.nextvideos = that.nextMedias
                    });
                  }else {
                    that.nextMedias = that.nextVideosList(that.findMediaByName(theVue.$route.params.currentTitle).id)
                    theVue.nextvideos = that.nextMedias
                  }
                }*/
                /*if(theVue.$route.params.editTitle!=undefined){
                  if(that.findMediaByName(theVue.$route.params.editTitle)==undefined){
                    that.receiveMediaByName(encodeURIComponent(theVue.$route.params.editTitle),function(id){
                      that.currentMediaId = id
                    });
                  }
                }*/
                if ((theVue.$router.currentRoute.path == "/search")) {
                    theVue.searching();
                }
                if (loadCount == 0 && replaceCount == 0) {
                    if (that.totalMedias == that.medias.length) {
                        theVue.alert("All medias are loaded", "warning");
                    }
                }
                else {
                    theVue.alert("Load " + loadCount + " and " + replaceCount + " medias already existed.");
                }
                if (callback != undefined) {
                    callback();
                }
            });
        }
    };
    siteManager.prototype.getUserById = function (id) {
        var search = new User(0, "None", "/img/404/avatar.png", "/img/404/background.png", "None-profile", {}, "", false);
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
        theVue.title = title;
        // deprecated, only example for eventbus
    });
}
