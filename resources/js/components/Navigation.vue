<template lang="html">
<div>
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
      prepend-icon="settings"
      no-action
      v-if="currentuser.admin"
    >
    <v-list-tile slot="activator">

      <v-list-tile-title>Admin</v-list-tile-title>

    </v-list-tile>
    <v-list-tile to="/admin/users">
      <v-list-tile-action>
        <v-icon>supervised_user_circle</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>Users</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile to="/admin/roles">
      <v-list-tile-action>
        <v-icon>reorder</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>Roles</v-list-tile-title>
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
      <v-list-group
        prepend-icon="warning"
        no-action
        sub-group
        v-if="currentuser.id!=0"
      >
        <v-list-tile slot="activator">
          <v-list-tile-title>Oauth</v-list-tile-title>
        </v-list-tile>
        <v-list-tile to="/passport/clients">
          <v-list-tile-action>
            <v-icon>account_box</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ $t('Clients') }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile to="/passport/personalaccess">
          <v-list-tile-action>
            <v-icon>account_box</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ $t('Personal access tokens') }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list-group>
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


    <v-list-tile v-if="((currentuser.id==0)&&(auth=='oauth'))" href="/login">
      <v-list-tile-action>
        <v-icon>exit_to_app</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Oauthlogin') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile v-if="((currentuser.id==0)&&(auth=='local'))" to="/login">
      <v-list-tile-action>
        <v-icon>exit_to_app</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Login') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile v-if="((currentuser.id==0)&&(auth=='local'))" to="/register">
      <v-list-tile-action>
        <v-icon>person_add</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Register') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-group
      prepend-icon="settings"
      no-action
      v-if="currentuser.id!=0"
    >
    <v-list-tile slot="activator">

      <v-list-tile-title>{{ $t('Settings') }}</v-list-tile-title>

    </v-list-tile>


    <v-list-tile to="/settings/profile">
      <v-list-tile-action>
        <v-icon>account_circle</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Profile') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile to="/settings/friends">
      <v-list-tile-action>
        <v-icon>accessibility</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Friends') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile to="/settings/apps">
      <v-list-tile-action>
        <v-icon>apps</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Manage apps') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>


    <v-list-tile to="/settings/2fa">
      <v-list-tile-action>
        <v-icon>security</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>2factor-Auth</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile to="/settings/password">
      <v-list-tile-action>
        <v-icon>lock</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>{{ $t('Password') }}</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>
  </v-list-group>

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

<v-speed-dial
  v-model="speedDeal"
  bottom
  v-if="currentuser.id!=0"
  right
  fixed
>
  <v-btn
    slot="activator"
    v-model="speedDeal"
    color="blue darken-2"
    dark
    fab
  >
    <v-icon>account_circle</v-icon>
    <v-icon>close</v-icon>
  </v-btn>
  <v-btn
    fab
    dark
    small
    color="green"
  >
    <v-icon>edit</v-icon>
  </v-btn>
  <v-btn
    fab
    dark
    small
    color="indigo"
    to="/upload"
  >
    <v-icon>add</v-icon>
  </v-btn>
  <v-btn
    fab
    dark
    small
    color="red"
  >
    <v-icon>delete</v-icon>
  </v-btn>
</v-speed-dial>


</v-toolbar>

<v-snackbar
v-model="alarmEnabledInternal"
:bottom="true"
:color="alertcolor"
:multi-line="true"
:timeout="9000"
>
{{ alerttext }}
<v-btn

flat
@click="closeAlarm()"
>
{{ $t('Close') }}
</v-btn>
</v-snackbar>

<form class="d-none" id="logoutForm">
  <input type="hidden" name="_token" :value="csrf">
</form>
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
    closeAlarm(){
      eventBus.$emit('closeAlarm',"");
    },
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
    auth:function(){
      return store.state.env.MIX_APP_AUTH
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
    alarmEnabledInternal: function(val){
      if(val==false){
        eventBus.$emit('closeAlarm',"");
      }
    },
    alertshown: function(val){
      console.log("react to alertshown",val)
      this.alarmEnabledInternal = val
    },
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
    alarmEnabledInternal:false,
    speedDeal:false,
    treeTypes: [{id:'audio',label:'Audio'},{id:'video',label:'Video'}]


  })
}
</script>

<style>
.vue-treeselect__multi-value {
    display: inline-flex;
    overflow: hidden; }

vs-navbar > > > .vue-treeselect__menu {
    width: 100px;
    padding-left: 15px;
    padding-bottom: 15px;
    z-index: 999999; }

.header-sidebar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    width: 100%; }
.header-sidebar h4 {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%; }
.header-sidebar h4 > button {
    margin-left: 10px; }

.footer-sidebar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%; }
.footer-sidebar > button {
    border: 0px solid transparent !important;
    border-left: 1px solid rgba(0, 0, 0, 0.07) !important;
    border-radius: 0px !important; }

</style>
