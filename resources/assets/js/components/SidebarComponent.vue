<template lang="html">

  <div id="parentx" class=" fixed-top bg-light">
    <vs-navbar  v-model="activeItem" class="nabarx" style="">
<vs-button @click="active=!active" type="flat" icon="menu"></vs-button>

        <router-link class="" to="/"><vs-navbar-title>LaraTube</vs-navbar-title></router-link>


      <vs-spacer></vs-spacer>


      <input icon="search" placeholder="Search" id="theLiveSearch" class="" @keyup="searching()" @focus="searching()" />
    </vs-navbar>

    <vs-sidebar parent="body" default-index="1"  color="primary" class="sidebarx" spacer v-model="active">
      <div v-if="currentuser.id!=0" class="header-sidebar" slot="header" :style="'background-image:url('+currentuser.background+');'">
        <vs-avatar  size="70px" :src="currentuser.avatar"/>
        <h4>
          <router-link class="" :to="'/profile/'+currentuser.id">{{ currentuser.name }}</router-link>
          <vs-button color="primary" icon="more_horiz" type="flat"></vs-button>
        </h4>
        <vs-navbar-item index="3">
          <router-link class="" to="/upload">Upload</router-link>
        </vs-navbar-item>
      </div>
      <div v-else>
        <h4>Hello guest</h4>
        <p>Sign up or in for more interaction</p>
      </div>
      <vs-navbar-item index="1">
        <router-link class="" to="/">Home</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="0">
        <router-link class="" to="/charts">Charts</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="2">
        <router-link class="" to="/tags">Tags</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="3">
        <a @click="emitGetNewMedias()" style="cursor: pointer;" class="">Check 4 new</a>
      </vs-navbar-item>
      <vs-navbar-item index="4">
      <a v-b-modal.moremodal class="" style="cursor: pointer;" >More</a>
      </vs-navbar-item>
      <vs-sidebar-group title="Aplication">
        <vs-sidebar-item index="1" icon="question_answer">
          Dashboard
        </vs-sidebar-item>
        <vs-sidebar-group title="Store">
          <vs-sidebar-item index="2.1" icon="store">
            Store
          </vs-sidebar-item>
          <vs-sidebar-item index="2.2" icon="nature_people">
            Nature
          </vs-sidebar-item>
          <vs-sidebar-item index="2.3" icon="style">
            Style
          </vs-sidebar-item>
        </vs-sidebar-group>
        <vs-sidebar-item index="2" icon="gavel">
          History
        </vs-sidebar-item>
        <vs-sidebar-item index="3" icon="https">
          security
        </vs-sidebar-item>
        <vs-sidebar-item index="4" icon="help">
          Help
        </vs-sidebar-item>
      </vs-sidebar-group>


      <vs-divider icon="person" position="left">
        User
      </vs-divider>

      <vs-sidebar-item index="5" icon="verified_user">
        Configurations
      </vs-sidebar-item>
      <vs-sidebar-item index="6" icon="account_box">
        Perfile
      </vs-sidebar-item>

      <div class="footer-sidebar" slot="footer">
        <vs-button v-if="currentuser.id==0" to="/login" icon="exit_to_app" color="success" type="flat">Login</vs-button>
        <vs-button v-if="currentuser.id==0" to="/register" icon="person_add" color="success" type="flat">Register</vs-button>
        <vs-button v-if="currentuser.id!=0" onclick="document.getElementById('logout-form').submit();" icon="power_settings_new" color="danger" type="flat">Logout</vs-button>
        <form v-if="currentuser.id!=0" id="logout-form" action="/logout" method="POST" style="display: none;">
            <input type="hidden" name="_token" :value="csrf">
        </form>
        <vs-button v-if="currentuser.id!=0" icon="settings" to="/editprofile" color="primary" type="border"></vs-button>
      </div>

    </vs-sidebar>
    <b-modal id="moremodal" style="" title="More options">
      <p>Medias loaded: {{ medias.length }}</p>
      <p>Users loaded: {{ users.length }}</p>
      <p>Tags loaded: {{ tags.length }}</p>
      <p ><button @click="emitRefreshMedias()" class="btn btn-warning mr-1">Reset data</button></p>
      <p><button @click="emitLoadAllMedias()" class="btn btn-danger mr-1">Load all medias</button></p>
    </b-modal>
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
    emitLoadAllMedias: function() {
      eventBus.$emit('loadAllMedias',"");
    },
  },
  props:['currentuser','medias','users','tags'],
  data:()=>({
    active:false,
    activeItem:0,
    csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
