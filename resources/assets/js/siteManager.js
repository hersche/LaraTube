var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    }
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var baseUrl;
import Vue from 'vue';
import Router from 'vue-router';
import { eventBus } from './eventBus.js';
var theComp = Vue.component('exco', require("./components/ExampleComponent.vue"));
var app;
var theVue;
var siteManager = /** @class */ (function () {
    function siteManager(base) {
        baseUrl = base + "/";
        this.currentPage = "overview";
        this.sites = [];
        this.currentSite = new overviewSite();
    }
    siteManager.prototype.getCurrentSite = function () {
        return this.currentPage;
    };
    siteManager.prototype.changeSite = function (site, theValue) {
        //if(this.sites[site]==undefined){
        console.log("changeSite: " + site);
        if (site == "player") {
            this.currentSite = new playerSite(theValue);
        }
        else {
            this.currentSite = new overviewSite();
        }
        //}
    };
    siteManager.prototype.buildSite = function () {
        //if(this.currentPage=="overview"){
        //this.sites['overview']
        //}
    };
    return siteManager;
}());
;
var site = /** @class */ (function () {
    function site(title) {
        this.title = title;
    }
    site.prototype.build = function () {
    };
    site.prototype.destroy = function () {
    };
    return site;
}());
;
var overviewSite = /** @class */ (function (_super) {
    __extends(overviewSite, _super);
    function overviewSite() {
        var _this = _super.call(this, "Overview") || this;
        _this.receiveMedias();
        return _this;
    }
    overviewSite.prototype.receiveMedias = function (forceUpdate) {
        if (forceUpdate === void 0) { forceUpdate = false; }
        var that = this;
        $.getJSON("/api/media", function name(data) {
            if ((sm.medias == undefined) || (forceUpdate)) {
                sm.medias = [];
                $.each(data.data, function (key, value) {
                    sm.medias.push(new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, value.user, value.created_at, value.created_at_readable, value.comments, value.tags));
                });
                theVue.medias = sm.medias;
            }
        });
    };
    return overviewSite;
}(site));
;
var playerSite = /** @class */ (function (_super) {
    __extends(playerSite, _super);
    function playerSite(title) {
        var _this = _super.call(this, title) || this;
        _this.receiveMedias();
        return _this;
    }
    playerSite.prototype.receiveMedias = function (forceUpdate) {
        if (forceUpdate === void 0) { forceUpdate = false; }
        var that = this;
        if ((sm.medias == undefined) || (forceUpdate)) {
            $.getJSON("/api/media/" + that.title, function name(data) {
                sm.medias = [];
                $.each(data, function (key, value) {
                    sm.medias.push(new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, value.user, value.created_at, value.created_at_readable, value.comments, value.tags));
                });
                //createContentCallback(sm.medias);
                theVue.medias = sm.medias;
            });
        }
    };
    playerSite.prototype.createContent = function (data) {
        $("#mainContent").html("");
        var carouselHtml = '';
        var first = true;
        var finalCarouselHtml;
        /*$.each( data, function( key, val1 ) {
          if(first){
            var tmpSourceBase = baseUrl;
            if(val1.source.substr(0,4)=="http"){
              tmpSourceBase = "";
            }
            if(val1.simpleType=="audio"){
              carouselHtml += '<audio width="320" height="240" controls poster="'+baseUrl+val1.poster_source + '"><source src="'+tmpSourceBase+val1.source + '" type="audio/mp3"></audio>';
            } else if(val1.simpleType=="video"){
              carouselHtml += '<video width="320" height="240" controls poster="'+baseUrl+val1.poster_source + '"><source src="'+tmpSourceBase+val1.source + '" type="video/mp4"></video>';
            }
            finalCarouselHtml = '<h3>'+val1.title+'</h3><div>'+carouselHtml+'</div>';
            $("#mainContent").html(finalCarouselHtml);
            $("#mainMenu").html('<a class="btn btn-primary" id="returnBtn">Go back</a>');
            $("#returnBtn").on("click", function(){
              sm.changeSite("overview", "");
            });
          }
          if(first){
            first = false;
          }
        });*/
    };
    return playerSite;
}(site));
;
var Media = /** @class */ (function () {
    function Media(title, description, source, poster_source, simpleType, type, user, created_at, created_at_readable, comments, tags) {
        this.title = title;
        this.description = description;
        this.source = source;
        this.poster_source = poster_source;
        this.type = type;
        this.simpleType = simpleType;
        this.user = user;
        this.comments = comments;
        this.tags = tags;
        this.created_at = created_at;
        this.created_at_readable = created_at_readable;
    }
    /*
    
    @if ($media->type=="localVideo" || $media->type=="directVideo"|| $media->type=="torrentVideo")
      <video class="col-12" id="player" poster="{{ url($media->poster()) }}" playsinline controls>
        @if ($media->type=="localVideo" || $media->type=="directVideo")
          <source src="{{ url($media->source) }}" type="video/mp4">
        @endif
      </video>
    
    */
    Media.prototype.getMediaTag = function () {
        var tmpHtml = "";
        if (this.type == "localVideo" || this.type == "directVideo" || this.type == "torrentVideo") {
            tmpHtml += '<video class="col-12" id="player" poster="{{ url($media->poster()) }}" playsinline controls>';
            if (this.type == "localVideo" || this.type == "directVideo") {
                tmpHtml += '<source src="' + baseUrl + this.source + '" >';
            }
            return tmpHtml + "</video>";
        }
        if (this.type == "localAudio" || this.type == "directAudio" || this.type == "torrentAudio") {
            tmpHtml += '<audio class="col-12" id="player" poster="{{ url($media->poster()) }}" playsinline controls>';
            if (this.type == "localAudio" || this.type == "directAudio") {
                tmpHtml += '<source src="' + baseUrl + this.source + '" >';
            }
            return tmpHtml + "</audio>";
        }
    };
    Media.prototype.createEditModal = function (containerId) {
        var tmpHtml = "";
    };
    return Media;
}());
;
if (sm == undefined) {
    var sm;
}
export function init(baseUrl) {
    sm = new siteManager(baseUrl);
    var overview = Vue.component('overview', require("./components/OverviewComponent.vue"));
    var player = Vue.component('player', require("./components/MediaComponent.vue"));
    var Foo = { template: '<div>hahha {{ $route.params.currentTitle }}</div>' };
    var Bar = { template: '<div>bar</div>' };
    Vue.use(Router);
    var routes = [
        { path: '/', component: overview },
        { path: '/media/:currentTitle', component: player },
        { path: '/bar', component: Bar }
    ];
    theVue = new Vue({
        data: { title: "Overview",
            currentComponent: 'overview', medias: sm.medias, currentTitle: '', baseUrl: baseUrl },
        router: new Router({ routes: routes }),
        methods: {
            swapComponent: function (component) {
                this.currentComponent = component;
            }
        }
    }).$mount('#app2');
    eventBus.$on('playerBackClick', function (title) {
        console.log("chaNGE BACK");
        theVue.swapComponent("overview");
    });
    eventBus.$on('overviewPlayClick', function (title) {
        theVue.currentTitle = title;
        theVue.title = title;
        theVue.swapComponent("player");
    });
}
