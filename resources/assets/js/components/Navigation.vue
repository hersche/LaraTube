<template lang="html">
<div data-app>
  <v-toolbar fixed dark color="primary" style="z-index:99999">
    <v-toolbar-side-icon @click="active=true"></v-toolbar-side-icon>
    <router-link class="" to="/"><v-toolbar-title class="white--text" to="/">LaraTube</v-toolbar-title></router-link>

    <v-spacer></v-spacer>
    <v-flex xs5 sm4 md3 lg3 align-right>
      <v-text-field
      hide-details
      append-icon="search"
      single-line
      id="theLiveSearch"
      :placeholder="$t('Search')+'...'"
      @keyup="searching()" @focus="searching()"
      ></v-text-field>
    </v-flex>
  </v-toolbar>
    
    
    <v-navigation-drawer
  v-model="active"
  fixed
  style="z-index:99999; height: 100%;overflow-x:auto; max-width:90%;"
  dark
  temporary
>
  <v-list-tile>
    <v-list-tile-action>
      <v-btn @click="active=false" small fab color="orange" style="cursor:pointer;"><v-icon>close</v-icon></v-btn>
    </v-list-tile-action>  
  </v-list-tile>
  <v-list-tile>
    <v-list-tile-action>
      <v-icon>call_merge</v-icon>
    </v-list-tile-action>
    <v-select
  v-model="dataTypes"
  :items="[{value:'audio',text:$t('Audio')},{value:'video',text:$t('Video')}]"
  deletable-chips
  attach
  :label="$t('Mediatypes')"
  multiple
></v-select>
  <!--  <treeselect class="" instanceId="dataTypeTree" v-if="treeTypes!=undefined" :multiple="true" :append-to-body="false" :always-open="false" v-model="dataTypes"  :options="treeTypes" /> -->
  </v-list-tile>
<v-list-tile>
<v-list-tile-action>
  <v-icon>language</v-icon>
</v-list-tile-action>
    <v-select
  v-model="lang"
  attach
  chips
  :items="[{value:'en',text:'English'},{value:'de',text:'Deutsch'}]"
  :label="$t('Language')"
></v-select>
<!--    <select id="langSelect" class="float-right custom-select custom-select-sm ml-1" v-model="lang" >
      <option value="en">EN</option>
      <option value="de">DE</option>
    </select>-->
</v-list-tile>
  <v-list class="pa-1">

    <v-list-tile  avatar tag="div" v-if="currentuser.id!=0" :style="'background-image:url('+currentuser.background+');'">
      <v-badge left color="orange" overlap>
        <router-link class="small" slot="badge" to="/notifications">{{ n }}</router-link>
        <router-link :to="'/profile/'+currentuser.id">
        <v-list-tile-avatar >
          <img :src="currentuser.avatar">
        </v-list-tile-avatar>
      </router-link>
      </v-badge>

      <v-list-tile-content>
        <v-list-tile-title>{{ currentuser.name }}</v-list-tile-title>
      </v-list-tile-content>

    </v-list-tile>
  </v-list>

  <v-list class="pt-0" dense>

    <v-list-tile to="/">
      <v-list-tile-action>
        <v-icon>home</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Home') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>
    
    <v-list-tile to="/charts">
      <v-list-tile-action>
        <v-icon>multiline_chart</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Charts') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile to="/categories">
      <v-list-tile-action>
        <v-icon>category</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Categories') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>
    
    <v-list-tile to="/tags">
      <v-list-tile-action>
        <v-icon>tag</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Tags') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>
  

    
    
    <v-list-group
      prepend-icon="warning"
      no-action
      v-if="currentuser.admin"
    >
      <v-list-tile slot="activator">
        <v-list-tile-title>Admin</v-list-tile-title>
      </v-list-tile>
      <v-list-tile to="/admin/users">
        <v-list-tile-action>
          <v-icon>account_box</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>{{ $t('Users') }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-list-group>
    <v-list-group
      prepend-icon="build"
      no-action
    >
      <v-list-tile slot="activator">
        <v-list-tile-title>Debug</v-list-tile-title>
      </v-list-tile>
      <v-list-tile>
        <v-list-tile-action>
          <v-icon>multiline_chart</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>{{ $t('Medias') }}: {{ medias.length }} / {{ tm }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile>
        <v-list-tile-action>
          <v-icon>account_box</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>{{ $t('Users') }}: {{ users.length }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      
      <v-list-tile>
        <v-list-tile-action>
          <v-icon>tag</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>{{ $t('Tags') }}: {{ tags.length }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      
      <v-list-tile @click="emitLoadAllMedias()">
        <v-list-tile-action>
          <v-icon>get_app</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>{{ $t('Load') }} {{ $t('all') }} {{ $t('medias') }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      
      <v-list-tile @click="emitRefreshMedias()">
        <v-list-tile-action>
          <v-icon>clear_all</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>{{ $t('Reset') }} {{ $t('data') }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-list-group>
    
    
    <v-list-tile v-if="currentuser.id==0" to="/login">
      <v-list-tile-action>
        <v-icon>exit_to_app</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Login') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile v-if="currentuser.id==0" to="/register">
      <v-list-tile-action>
        <v-icon>person_add</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Register') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile v-if="currentuser.id!=0" to="/editprofile">
      <v-list-tile-action>
        <v-icon>settings</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Settings') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>
      
    <v-list-tile v-if="currentuser.id!=0" @click="emitLogout()" >
      <v-list-tile-action>
        <v-icon>power_settings_new</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Logout') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>
    
    <v-list-tile to="/about">
      <v-list-tile-action>
        <v-icon>help</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('About') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>
    
  </v-list>
</v-navigation-drawer>
<v-fab-transition>
  <v-btn
    fab
    v-if="currentuser.id!=0"
    dark
    small
    fixed
    bottom
    right
    to="/upload"
  >
    <v-icon>add</v-icon>
  </v-btn>
</v-fab-transition>
</v-toolbar>

<v-snackbar
v-model="alertshown"
:bottom="true"
:color="alertcolor"
:multi-line="true"
:timeout="9000"
>
{{ alerttext }}
<v-btn

flat
@click="alertshown = false"
>
{{ $t('Close') }}
</v-btn>
</v-snackbar>
    </div>

</template>

<script>
  import { eventBus, store } from '../eventBus.js';
export default {
  mounted(){
    let that = this;
    if(localStorage.getItem("mediaTypes")!=''&&localStorage.getItem("mediaTypes")!=null){
      this.dataTypes = localStorage.getItem("mediaTypes").split(",")
    } else {
      this.dataTypes = ["audio","video"]
    }

    if(localStorage.getItem("language")!=''&&localStorage.getItem("language")!=undefined){
      this.lang = localStorage.getItem("language");
    }

},
  methods:{
    searching(){
      eventBus.$emit('refreshSearch',"");
    },
    emitGetNewMedias() {
      eventBus.$emit('getNewMedias',"");
    },
    emitRefreshMedias: function() {
      eventBus.$emit('refreshMedias',"");
    },
    emitLogout: function() {
      $.ajax({
          url: '/logout',
          type: 'POST',
          data: new FormData($("#logoutForm")[0]),
          cache: false,
          contentType: false,
          processData: false,
          complete : function(res) {
            if(res.status==200){
              eventBus.$emit('logout',"");
              //eventBus.$emit('login',res.responseJSON.data);
            }
          }

      });

    },
    emitLoadAllMedias: function() {
      eventBus.$emit('loadAllMedias',"");
    },
  },
  props:['alertshown','alerttext','alertcolor'],
  computed:{
    totalmedias: function(){
      return store.state.totalMedias
    },
    csrf: function(){
      return store.getters.getCSRF()
    },
    notifications: function(){
      return store.state.notifications
    },
    currentuser(){
      return store.getters.getUserById(store.state.loginId)
    },
    tags: function(){ 
      return store.state.tags
    },
    users: function(){ 
      return store.state.users
    },
    tm: function(){ 
      return store.state.totalMedias
    },
    medias: function(){
      return store.state.medias
    }
  },
  watch:{
    lang:function(val){
      localStorage.setItem("language",val);
      eventBus.$emit('languageChange',val);
    },
    dataTypes:function(val){
      localStorage.setItem("mediaTypes",val.join())
      eventBus.$emit('filterTypes',val);
    },
    notifications:function(val){
      var tmpArray = []
      this.notifications.forEach(function(val,key){
        if(val.read_at==null||val.read_at==0||val.read_at==undefined){
          tmpArray.push(val)
        }
      });
      this.n = tmpArray.length;
    }
  },
  data:()=>({
    active:false,
    activeItem:0,
    lang:'en',
    dataTypes: ["audio","video"],
    n:0,
    mini:false,
    
    treeTypes: [{id:'audio',label:'Audio'},{id:'video',label:'Video'}]
    

  })
}
</script>

<style lang="stylus">
.vue-treeselect__multi-value
  display inline-flex
  overflow hidden
vs-navbar >>> .vue-treeselect__menu
  width 100px
  padding-left 15px
  padding-bottom 15px
  z-index 999999
.header-sidebar
  display flex
  align-items center
  justify-content center
  flex-direction column
  width 100%
  h4
    display flex
    align-items center
    justify-content center
    width 100%
    > button
      margin-left 10px
.footer-sidebar
  display flex
  align-items center
  justify-content space-between
  width 100%
  > button
      border 0px solid rgba(0,0,0,0) !important
      border-left 1px solid rgba(0,0,0,.07) !important
      border-radius 0px !important
</style>
