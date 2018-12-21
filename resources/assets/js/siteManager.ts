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
  tags:Array<Tag>;
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
        that.receiveTags();
      }
    });
  }



  receiveTags(forceUpdate=false):void{
    var that = this;
    $.getJSON("/api/tags", function name(data) {
      if((that.tags==undefined)||(forceUpdate)){
      that.tags = [];
        $.each( data.data, function( key, value ) {
          that.tags.push(new Tag(value.id, value.name, value.slug, value.count));
        });
      }
      this.tags = that.tags;
      theVue.tags = this.tags;
      that.receiveMedias();
    });
  }


  receiveMediaByName(mediaName:string,forceUpdate=false):void{
    var that = this;
    var existsAlready = false;
    $.getJSON("/api/media/"+mediaName, function name(data) {
      $.each(this.medias, function(key,value){
        if(value.title==mediaName){
          existsAlready=true;
        }
      });
      if(existsAlready==false){
        var m = new Media(data.title, data.description, data.source, data.poster_source, data.simpleType, data.type, that.getUserById(data.user_id),data.user_id,data.created_at,data.created_at_readable,data.comments,that.getTagsByIdArray(data.tagsIds));
        theVue.medias = this.medias;
        this.medias.push(m)
      } else {
        console.warn("If the media already existed, why this method was used?");
      }
      });
  }

  getTagsByIdArray(arr:Array<number>){
    var tmpTags = [];
    var that = this;
    $.each(arr, function(key,value){
      tmpTags.push(that.findTagById(value));
    });
    return tmpTags;
  }
  findTagById(id:number){
    var returner;
    console.log("exe")
    $.each(this.tags, function(key,value){
      if(value.id==id){
        returner=value;
      }
    });
    return returner;
  }
  findMediaByName(mediaName:string):Media{
    var returnMedia = undefined;
    $.each(this.medias, function(key,value){
      if(value.title==mediaName){
        returnMedia=value;
      }
    });
    if(returnMedia==undefined){
      console.log("Media didn't exist, download it.")
      this.receiveMediaByName(mediaName);
    }
    return returnMedia;
  }
  receiveMedias(url="/api/media",forceUpdate=false):void{
    var that = this;
    $.getJSON(url, function name(data) {
      if((forceUpdate)||(that.medias==undefined)){
        that.medias = [];
      }
        $.each( data.data, function( key, value ) {
          var med = new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, that.getUserById(value.user_id),value.user_id,value.created_at,value.created_at_readable,value.comments,that.getTagsByIdArray(value.tagsIds))
          $.each( med.comments, function( key1, value1 ) {
            med.comments[key1].user = that.getUserById(value1.user_id)
          });
          that.medias.push(med);
        });
        that.nextLink = data.links.next;
        that.lastLink = data.links.prev;
        if(that.nextLink==null){
          theVue.canLoadMore=false;
        }
        theVue.medias = that.medias;
        if(theVue.$route.params.profileId != undefined){
          theVue.user = sm.getUserById(theVue.$route.params.profileId)
          theVue.medias = sm.getMediasByUser(theVue.$route.params.profileId)
        }
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
  var tagComp = Vue.component('player', require("./components/TagComponent.vue"));
  Vue.use(Router)
  const routes = [
    { path: '/', component: overview },
    { path: '/media/:currentTitle', component: player },
    { path: '/profile/:profileId', component: profileComp },
    { path: '/tags', component: tagComp }
  ]
//  sm.receiveUsers(true);
 theVue = new Vue({
  data : {title : "Overview",
  currentComponent: 'overview', tags:sm.tags, canLoadMore:true, medias:sm.medias,currentTitle:'',user:new User(0,"None","img/404/avatar.png","img/404/background.png", "None-user", {}),baseUrl:baseUrl},
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

class Tag {
  id:number;
  name:string;
  slug:string;
  count:number;
  constructor(id:number,name:string,slug:string,count:number){
    this.id=id;
    this.name=name;
    this.slug=slug;
    this.count=count;
  }
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
  tagIds:any;
  created_at:string;
  created_at_readable:string;


  constructor(title:string,description:string,source:string,poster_source:string,simpleType:string,type:string,user:any,user_id:any,created_at:string,created_at_readable:string,comments:any,tags:any,tagIds:any=undefined){
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

  toJson(){
    return "{title:'"+this.title+"',description:'"+this.description+"',source:'"+this.source
  }

}
