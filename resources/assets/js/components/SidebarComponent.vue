<template lang="html">

  <div id="parentx" class=" fixed-top bg-light">
    <vs-navbar class="nabarx" style="">
      <vs-button @click="active=!active" type="flat" icon="menu"></vs-button>
      <router-link class="" to="/"><vs-navbar-title>LaraTube</vs-navbar-title></router-link>
      <vs-spacer></vs-spacer>
      <input icon="search" placeholder="Search" id="theLiveSearch" class="" @keyup="searching()" @focus="searching()" />
    </vs-navbar>

    <vs-sidebar parent="body" default-index="0"  color="primary" class="sidebarx" spacer v-model="active">
      <div v-if="currentuser.id!=0" class="header-sidebar" slot="header" :style="'background-image:url('+currentuser.background+');'">
        <vs-avatar  size="70px" :src="currentuser.avatar"/>
        <h4>
          <router-link class="btn btn-sm btn-success" :to="'/profile/'+currentuser.id">{{ currentuser.name }}</router-link>
        </h4>
        <span>
          <router-link class="btn btn-sm btn-success" to="/upload">Upload</router-link>
          <router-link class="btn btn-sm btn-success" to="/myvideos">My videos</router-link>
        </span>
      </div>
      <vs-navbar-item index="1">
        <router-link class="" to="/">Home</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="9">
        <router-link class="" to="/categories">Categories</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="3">
        <router-link class="" to="/tags">Tags</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="2">
        <router-link class="" to="/charts">Charts</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="4">
        <router-link class="" to="/about">About</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="5">
        <a @click="emitGetNewMedias()" style="cursor: pointer;" class="">Check 4 new medias</a>
      </vs-navbar-item>

      <vs-sidebar-group v-if="currentuser.admin" title="Admin">
        <vs-navbar-item index="7.1">
          <router-link class="" to="/admin/users">Users</router-link>
        </vs-navbar-item>
     </vs-sidebar-group>
       <vs-sidebar-group title="Dev options">
         <p>Medias loaded: {{ medias.length }}</p>
         <p>Users loaded: {{ users.length }}</p>
         <p>Tags loaded: {{ tags.length }}</p>
         <vs-navbar-item index="6.1">
           <a @click="emitRefreshMedias()" style="cursor: pointer;" class="">Reset data</a>
         </vs-navbar-item>
         <vs-navbar-item index="6.1">
           <a @click="emitLoadAllMedias()" style="cursor: pointer;" class="">Load all medias</a>
          </vs-navbar-item>
      </vs-sidebar-group>



      <div class="footer-sidebar" slot="footer">
        <vs-button v-if="currentuser.id==0" to="/login" icon="exit_to_app" color="success" type="flat">Login</vs-button>
        <vs-button v-if="currentuser.id==0" to="/register" icon="person_add" color="success" type="flat">Register</vs-button>
        <vs-button v-if="currentuser.id!=0" @click="emitLogout()" icon="power_settings_new" color="danger" type="flat">Logout</vs-button>
        <form v-if="currentuser.id!=0" id="logoutForm" action="/logout" method="POST" style="display: none;">
            <input type="hidden" name="_token" :value="csrf">
        </form>
        <vs-button v-if="currentuser.id!=0" icon="settings" to="/editprofile" color="primary" type="border"></vs-button>
      </div>

    </vs-sidebar>
  </div>

</template>

<script>
  import { eventBus } from '../eventBus.js';
export default {
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
  props:['currentuser','medias','users','tags','csrf'],
  computed:{
    csrf1: function(){
      document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
  },
  data:()=>({
    active:false,
    activeItem:0,

  })
}
</script>

<style lang="stylus">
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
