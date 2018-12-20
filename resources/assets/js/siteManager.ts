var baseUrl:string;
import Vue from 'vue'
import Router from 'vue-router';
import { eventBus } from './eventBus.js';
var app;
var theVue;

require("./models")

class siteManager {
  medias:Array<Media>;
  currentPage:string;
  users:Array<User>;
  nextLink:string;
  lastLink:string;
  constructor(base:string){
    baseUrl = base+"/";
    this.currentPage = "overview";
    this.receiveUsers(true);
    var that = this;
    eventBus.$on('refreshMedias', title => {
      that.receiveMedias("/api/media",true)
      // deprecated, only example for eventbus
    });
    eventBus.$on('loadMore', title => {
      that.receiveMedias(that.nextLink)
      // deprecated, only example for eventbus
    });
  }

  getCurrentSite(){
    return this.currentPage;
  }
  receiveUsers(forceUpdate=false):void{
    var that = this;
    $.getJSON("/api/user", function name(data) {
      if((that.users==undefined)||(forceUpdate)){
      that.users = [];
        $.each( data.data, function( key, value ) {
          that.users.push(new User(value.id, value.name, value.avatar, value.background, value.bio, value.mediaIds));
        });
        //theVue.us = sm.medias;
        that.receiveMedias();
      }
    });
  }
  receiveMediaByName(mediaName:string,forceUpdate=false):void{
    var that = this;
    $.getJSON("/api/media/"+mediaName, function name(data) {
      that.medias[that.findMediaByName(mediaName)].comments = data.comments
      theVue.medias = that.medias;
      });
  }
  findMediaByName(mediaName:string):any{
    $.each(this.medias, function(key,value){
      if(value.title==mediaName){
        return key;
      }
    });
  }
  receiveMedias(url="/api/media",forceUpdate=false):void{
    var that = this;
    $.getJSON(url, function name(data) {
      if((forceUpdate)||(that.medias==undefined)){
        that.medias = [];
      }
  //    if((forceUpdate)||(url!="/api/media")){
        $.each( data.data, function( key, value ) {
          var med = new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, that.getUserById(value.user_id),value.user_id,value.created_at,value.created_at_readable,value.comments,value.tags)
          $.each( med.comments, function( key1, value1 ) {
            med.comments[key1].user = that.getUserById(value1.user_id)
          });
          that.medias.push(med);
        });
        that.nextLink = data.links.next;
        that.lastLink = data.links.prev;
        theVue.medias = that.medias;
        if(theVue.$route.params.profileId != undefined){
          theVue.user = sm.getUserById(theVue.$route.params.profileId)
          theVue.medias = sm.getMediasByUser(theVue.$route.params.profileId)
        }
  //    }
    });

  }
  getUserById(id:number):User{
    var search:User = new User(0,"None","/img/404/avatar.png","/img/404/background.png","None-profile",{})
    $.each( this.users, function( key, value ) {
      if(value.id == id){
        search = value;
      }
    });
    return search;
  }
  getMediasByUser(id:number){
    var userMedias = []
    $.each( this.medias, function( key, value ) {
      if(value.user_id == id){
        userMedias.push(value)
      }
    });
    return userMedias;
  }

};

if(sm==undefined){
  var sm;
}
export function init(baseUrl) {
  sm = new siteManager(baseUrl);
  var overview = Vue.component('overview', require("./components/OverviewComponent.vue"));
  var player = Vue.component('player', require("./components/MediaComponent.vue"));
  var profileComp = Vue.component('player', require("./components/ProfileComponent.vue"));
  Vue.use(Router)
  const routes = [
    { path: '/', component: overview },
    { path: '/media/:currentTitle', component: player },
    { path: '/profile/:profileId', component: profileComp }
  ]
//  sm.receiveUsers(true);
 theVue = new Vue({
  data : {title : "Overview",
  currentComponent: 'overview', medias:sm.medias,currentTitle:'',user:new User(0,"None","img/404/avatar.png","img/404/background.png", "None-user", {}),baseUrl:baseUrl},
  router:new Router({ routes }),
  methods:{
    swapComponent: function(component) {
      this.currentComponent = component;
    }
  },
  watch:{
    $route (to, from){
        //this.show = false;
        if(to.params.currentTitle!=undefined){
          // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
        }
        if(to.params.profileId!=undefined){
          this.user = sm.getUserById(to.params.profileId)
          this.medias = sm.getMediasByUser(to.params.profileId)
        } else {
          this.medias = sm.medias;
        }
    }
}
}).$mount('#app2');

  eventBus.$on('overviewPlayClick', title => {
    theVue.currentTitle = title;
    theVue.title = title;
    // deprecated, only example for eventbus
  });

}


class User{
  id:number;
  name:string;
  avatar:string;
  background:string;
  bio:string;
  mediaIds:any;
  constructor(id:number,name:string,avatar:string,background:string,bio:string,mediaIds:any){
    this.id=id;
    this.name = name;
    this.avatar = avatar;
    this.background = background;
    this.bio = bio;
    this.mediaIds = mediaIds;
  }
  toJson(){
    return "{id:"+this.id+",name:'"+this.name+"',avatar:'"+this.avatar+"',background:'"+this.background+"'}"
  }
}
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


  constructor(title:string,description:string,source:string,poster_source:string,simpleType:string,type:string,user:any,user_id:any,created_at:string,created_at_readable:string,comments:any,tags:any){
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

  toJson(){
    return "{title:'"+this.title+"',description:'"+this.description+"',source:'"+this.source
  }

}
