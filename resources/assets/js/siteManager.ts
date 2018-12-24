var baseUrl:string;
import Vue from 'vue'
import Router from 'vue-router';
import BootstrapVue from 'bootstrap-vue'
import VueCroppie from 'vue-croppie';
import { eventBus } from './eventBus.js';
var app;
var theVue;

var searchDelay;

require("./models")

class siteManager {
  medias:Array<Media>;
  currentPage:string;
  users:Array<User>;
  usedSearchTerms:any;
  tags:Array<Tag>;
  nextLink:string;
  loggedUserId:number;
  lastLink:string;
  catchedTagMedias:any;
  constructor(base:string){
    baseUrl = base+"/";
    this.currentPage = "overview";
    this.catchedTagMedias=[];
    this.usedSearchTerms=[];
    this.loggedUserId = Number($("#loggedUserId").attr("content"));
    this.receiveUsers(true);
    let that = this;
    eventBus.$on('refreshMedias', title => {
      theVue.canloadmore = true;
      that.catchedTagMedias=[];
      this.usedSearchTerms=[];
      that.receiveMedias("/api/media",true)
      // deprecated, only example for eventbus
    });
    eventBus.$on('checkTag', tagName => {
      //if(theVue.$router.currentRoute.path!="/search"){
      if(tagName==''){
        if($("#specialAllTag").is(":checked")){
          theVue.medias = that.medias;
        } else {
          theVue.medias = [];
          theVue.medias = that.medias;
        }
      } else {
      if(that.catchedTagMedias.includes(tagName)==false){
        that.catchedTagMedias.push(tagName);
        that.receiveMedias("/api/tags/"+tagName);
      }
    }
    //}
    });
    eventBus.$on('loadMore', title => {
      that.receiveMedias(that.nextLink)
    });


    eventBus.$on('refreshSearch', title => {
      theVue.searching();
    });
    eventBus.$on('showAlert', data => {
      theVue.dismisscountdown = theVue.dismisssecs
    });

  }

  initVue(){
    var overview = Vue.component('overview', require("./components/OverviewComponent.vue"));
    var player = Vue.component('player', require("./components/MediaComponent.vue"));
    var profileComp = Vue.component('profile', require("./components/ProfileComponent.vue"));
    var tagComp = Vue.component('tags', require("./components/TagComponent.vue"));
    var loginComp = Vue.component('login', require("./components/LoginComponent.vue"));
    var uploadComp = Vue.component('upload', require("./components/UploadComponent.vue"));
    var alertComp = Vue.component('alert', require("./components/AlertComponent.vue"));
    var searchComp = Vue.component('search', require("./components/SearchComponent.vue"));
    Vue.use(Router)
    Vue.use(BootstrapVue);
    Vue.use(VueCroppie);
    let that = this;
    const routes = [
      { path: '/', component: overview },
      { path: '/media/:currentTitle', component: player },
      { path: '/profile/:profileId', component: profileComp },
      { path: '/tags', component: tagComp },
      { path: '/tags/:tagName', component: tagComp },
      { path: '/login', component: loginComp },
      { path: '/upload', component: uploadComp },
      { path: '/search', component: searchComp }
    ]
  //  sm.receiveUsers(true);
   theVue = new Vue({
    data : {
      title : "Overview",
      dismisssecs: 10,
      dismisscountdown: 0,
      showdismissiblealert: false,
      search:'',
      users:this.users,
      loggeduserid:this.loggedUserId,
      tags:this.tags,
      canloadmore:true,
      medias:this.medias,
      currentTitle:'',
      user:new User(0,"None","img/404/avatar.png","img/404/background.png", "None-user", {}),
      baseUrl:baseUrl
    },
    router:new Router({ routes }),
    components : {
      'alert': alertComp
    },
    methods:{
      searching: function() {
        //this.search = $("#theLiveSearch").val();

        /*
        if(s!=''){
          $.each( that.medias, function( key, value ) {
            if(value.title.toLowerCase().indexOf(s.toLowerCase()) > -1){
              m.push(value);
            }
          });
        }
        */
        console.log("search!");
        console.log(s);
        console.log(that.medias)

        if(theVue.$router.currentRoute.path!="/search"){
          theVue.$router.push('/search');
        }
        var s =  $("#theLiveSearch").val();
        var m = [];
        if(that.usedSearchTerms.includes(s.toString())==false&&s.toString()!=""){
          that.usedSearchTerms.push(s);
          if(searchDelay!=undefined){
            clearTimeout(searchDelay);
          }
          searchDelay = setTimeout(function(){
            that.receiveMedias("/api/media/search/"+s);
          }, 300);

        }
        var so = new Search(s.toString(),that.medias,that.tags,that.users);
        theVue.search = so;
        theVue.medias = so.mediaResult;
        console.log(so.mediaResult)
        theVue.users = so.userResult;

      }
    },
    mounted(){
    //  if(sm.params.currentTitle!=undefined){
        // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
      //  if(sm.findMediaByName(sm.params.currentTitle)==undefined){
        //  sm.receiveMediaByName(sm.params.currentTitle);
        //}
      //}
    },
    watch:{
      $route (to, from){
          //this.show = false;
          if(to.params.currentTitle!=undefined){
            // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
            if(sm.findMediaByName(to.params.currentTitle)==undefined){
              sm.receiveMediaByName(to.params.currentTitle);
            }
          }
          if(to.params.profileId!=undefined){
            this.user = sm.getUserById(to.params.profileId)
            this.medias = sm.getMediasByUser(to.params.profileId)
          } else {
            this.medias = sm.medias;
          }
          if(to.path=="/search"){
            //this.searching();
          }
      }
  }
  }).$mount('#app2');
  }

  getCurrentSite(){
    return this.currentPage;
  }
  receiveUsers(forceUpdate=false):void{
    let that = this;
    $.getJSON("/api/user", function name(data) {
      if((that.users==undefined)||(forceUpdate)){
      that.users = [];
        $.each( data.data, function( key, value ) {
          that.users.push(new User(value.id, value.name, value.avatar, value.background, value.bio, value.mediaIds));
        });

        that.receiveTags();
      }
    });
  }



  receiveTags(forceUpdate=false):void{
    let that = this;
    $.getJSON("/api/tags", function name(data) {
      if((that.tags==undefined)||(forceUpdate)){
      that.tags = [];
        $.each( data.data, function( key, value ) {
          that.tags.push(new Tag(value.id, value.name, value.slug, value.count));
        });
      }
      this.tags = that.tags;
      if(theVue!=undefined){
        theVue.tags = this.tags;
      }
      that.receiveMedias();
    });
  }


  receiveMediaByName(mediaName:string,forceUpdate=false):void{
    let that = this;
    var existsAlready = false;
    $.getJSON("/api/media/"+mediaName, function name(data) {
      $.each(this.medias, function(key,value){
        if(value.title==mediaName){
          existsAlready=true;
        }
      });
      if(existsAlready==false){
        data = data.data;
        var m = new Media(data.title, data.description, data.source, data.poster_source, data.simpleType, data.type, that.getUserById(data.user_id),data.user_id,data.created_at,data.created_at_readable,data.comments,that.getTagsByIdArray(data.tagsIds));
        that.medias.push(m)
        theVue.medias = that.medias;
      } else {
        console.warn("If the media already existed, why this method was used?");
      }
      });
  }

  getTagsByIdArray(arr:Array<number>){
    var tmpTags = [];
    let that = this;
    $.each(arr, function(key,value){
      tmpTags.push(that.findTagById(value));
    });
    return tmpTags;
  }
  findTagById(id:number){
    var returner:Tag=undefined;
    $.each(this.tags, function(key,value){
      if(value.id==id){
        returner=value;
      }
    });
    return returner;
  }
  findMediaByName(mediaName:string):Media{
    var returnMedia = undefined;
    let that = this;
    $.each(that.medias, function(key,value){
      if(value.title==mediaName){
        returnMedia=value;
      }
    });
    return returnMedia;
  }
  receiveMedias(url="/api/media",forceUpdate=false):void{
    let that = this;
    $.getJSON(url, function name(data) {
      if((forceUpdate)||(that.medias==undefined)){
        that.medias = [];
      }
        $.each( data.data, function( key, value ) {
         if(that.findMediaByName(value.title)==undefined){
            var med = new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, that.getUserById(value.user_id),value.user_id,value.created_at,value.created_at_readable,value.comments,that.getTagsByIdArray(value.tagsIds))
            $.each( med.comments, function( key1, value1 ) {
              med.comments[key1].user = that.getUserById(value1.user_id)
            });
            that.medias.push(med);
          }
        });
        if(data.links!=undefined){
          that.nextLink = data.links.next;
          that.lastLink = data.links.prev;
        }
        if(theVue==undefined){
          that.initVue();
        }
        if(that.nextLink==null){
          theVue.canloadmore=false;
        }
        theVue.users = that.users;
        theVue.medias = that.medias;
        if(theVue.$route.params.profileId != undefined){
          theVue.user = sm.getUserById(theVue.$route.params.profileId)
          theVue.medias = sm.getMediasByUser(theVue.$route.params.profileId)
        }

        if(theVue.$route.params.currentTitle!=undefined){
          // PLACEHOLDER FOR LOAD THE EXTENDED VIDEO (include comments n'stuff)
          if(that.findMediaByName(theVue.$route.params.currentTitle)==undefined){
            that.receiveMediaByName(theVue.$route.params.currentTitle);
          }
        }
        if((theVue.$router.currentRoute.path=="/search")) {
          theVue.searching();
        /*  if($("#theLiveSearch").val()==''){
            theVue.medias = [];
          }*/
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

class Search{
  mediaResult:any;
  tagResult:Array<Tag>;
  userResult:any;
  search:string;
  constructor(search:string,medias,tags,users){
    this.search = search;
    this.tagResult=[]
    this.userResult=[]
    this.mediaResult=[]
    if(search!=""){
    var mediaTitle = $("#theLiveSearchMediaTitle").is(':checked')
    var mediaDescription = $("#theLiveSearchMediaDescription").is(':checked')
    console.log("WTF??")
    console.log(mediaTitle)
    let that = this;
    if($("#theLiveSearchUsers").is(':checked')){
      $.each( users, function( key, value ) {
        if(value.name.toLowerCase().indexOf(that.search.toLowerCase()) > -1){
          if(that.userResult.includes(value)==false){
            that.userResult.push(value);
          }
        }
        if(value.bio.toLowerCase().indexOf(that.search.toLowerCase()) > -1){
          if(that.userResult.includes(value)==false){
            that.userResult.push(value);
          }
        }
      });
    }
    if(mediaTitle||mediaDescription) {
      console.log("PAssed media-if")
      console.log(medias)
      $.each( medias, function( key, value ) {
        console.log(that.search)
        console.log(value.title)
        if(mediaTitle){
        if(value.title.toLowerCase().indexOf(that.search.toLowerCase()) > -1){
          if(that.mediaResult.includes(value)==false){
            that.mediaResult.push(value);
          }
        }
      }
      if(mediaDescription){
        if(value.description.toLowerCase().indexOf(search.toLowerCase()) > -1){
          if(that.mediaResult.includes(value)==false){
            that.mediaResult.push(value);
          }
        }
      }
      });
    }
    if($("#theLiveSearchMedias").is(':checked')){
      this.userResult=medias
    }
  }
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
