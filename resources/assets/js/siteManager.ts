var baseUrl:string;
import Vue from 'vue'
import Router from 'vue-router';
import { eventBus } from './eventBus.js';
var theComp = Vue.component(
    'exco', require("./components/ExampleComponent.vue")

);
var app;
var theVue;
class siteManager {

  medias:Array<Media>;
  currentPage:string;
  sites:Array<site>;
  currentSite:site;
  constructor(base:string){
    baseUrl = base+"/";

    this.currentPage = "overview";
    this.sites = [];
    this.currentSite = new overviewSite();
  }

  getCurrentSite(){
    return this.currentPage;
  }

  changeSite(site:string,theValue:string){
    //if(this.sites[site]==undefined){
    console.log("changeSite: "+site);
    if(site=="player"){
      this.currentSite = new playerSite(theValue);
    } else {
      this.currentSite = new overviewSite();
    }
    //}
  }

  buildSite(){
    //if(this.currentPage=="overview"){
      //this.sites['overview']
    //}
  }

};

class site {
  title:string;
  htmlSkeleton:string;
  constructor(title:string){
    this.title = title;
  }

  build(){

  }

  destroy(){

  }

};


class overviewSite extends site {

  private medias:Array<Media>;

  constructor(){
    super("Overview");
    this.receiveMedias();

  }

  receiveMedias(forceUpdate=false):void{
    var that = this;

    $.getJSON("/api/media", function name(data) {
      if((sm.medias==undefined)||(forceUpdate)){
      sm.medias = [];
        $.each( data.data, function( key, value ) {
          sm.medias.push(new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, value.user,value.created_at,value.created_at_readable,value.comments,value.tags));
        });
        theVue.medias = sm.medias;
      }
    });

  }

};
class playerSite extends site {

  private medias:Array<Media>;
  constructor(title:string){
    super(title);
    this.receiveMedias();


  }

  receiveMedias(forceUpdate=false):void{
    var that = this;
    if((sm.medias==undefined)||(forceUpdate)){
    $.getJSON("/api/media/"+that.title, function name(data) {
        sm.medias = [];
        $.each( data, function( key, value ) {
          sm.medias.push(new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, value.user,value.created_at,value.created_at_readable,value.comments,value.tags));
        });
        //createContentCallback(sm.medias);
        theVue.medias = sm.medias;
    });
  }
  }

  createContent(data:Array<Media>):void{
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


  }

};
class Media {
  title:string;
  description:string;
  source:string;
  poster_source:string;
  type:string;
  simpleType:string;
  user_id:number;
  user:any;
  comments:any;
  tags:any;
  created_at:string;
  created_at_readable:string;


  constructor(title:string,description:string,source:string,poster_source:string,simpleType:string,type:string,user:any,created_at:string,created_at_readable:string,comments:any,tags:any){
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


  getMediaTag(){
    var tmpHtml = "";
    if(this.type=="localVideo"||this.type=="directVideo"||this.type=="torrentVideo"){
      tmpHtml += '<video class="col-12" id="player" poster="{{ url($media->poster()) }}" playsinline controls>';
      if(this.type=="localVideo"||this.type=="directVideo"){
        tmpHtml += '<source src="'+baseUrl+this.source+'" >';
      }
      return tmpHtml+"</video>";
    }

    if(this.type=="localAudio"||this.type=="directAudio"||this.type=="torrentAudio"){
      tmpHtml += '<audio class="col-12" id="player" poster="{{ url($media->poster()) }}" playsinline controls>';
      if(this.type=="localAudio"||this.type=="directAudio"){
        tmpHtml += '<source src="'+baseUrl+this.source+'" >';
      }
      return tmpHtml+"</audio>";
    }
  }

  createEditModal(containerId:string){
    var tmpHtml = "";
  }
};
if(sm==undefined){
  var sm;
}
export function init(baseUrl) {


    sm = new siteManager(baseUrl);
  var overview = Vue.component('overview', require("./components/OverviewComponent.vue"));
  var player = Vue.component('player', require("./components/MediaComponent.vue"));
  const Foo = { template: '<div>hahha {{ $route.params.currentTitle }}</div>' }
  const Bar = { template: '<div>bar</div>' }
  Vue.use(Router)
  const routes = [
    { path: '/', component: overview },
    { path: '/media/:currentTitle', component: player },
    { path: '/bar', component: Bar }
  ]
 theVue = new Vue({
  data : {title : "Overview",
  currentComponent: 'overview', medias:sm.medias,currentTitle:'',baseUrl:baseUrl},
  router:new Router({ routes }),
  methods:{
    swapComponent: function(component) {
      this.currentComponent = component;
    }
  }
}).$mount('#app2');

  eventBus.$on('playerBackClick', title => {
    console.log("chaNGE BACK")
  theVue.swapComponent("overview");
  });
  eventBus.$on('overviewPlayClick', title => {
    theVue.currentTitle = title;
    theVue.title = title;
    theVue.swapComponent("player");
  });

}
