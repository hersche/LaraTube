<template lang="html">

  <div id="parentx" class=" fixed-top bg-light">
    <vs-navbar class="nabarx" style="">
      <vs-button @click="active=!active" type="flat" icon="menu"></vs-button>
      <router-link class="" to="/"><vs-navbar-title>LaraTube</vs-navbar-title></router-link>
      <vs-spacer></vs-spacer>
      <vs-select
        placeholder="Types"
        multiple
        class="selectExample"
        v-model="dataTypes"
        >
        <vs-select-item value="audio" text="Audio" />
        <vs-select-item value="video" text="Video" />
      </vs-select>
      <input icon="search" :placeholder="$t('Search')+'...'" id="theLiveSearch" class="" @keyup="searching()" @focus="searching()" />
    </vs-navbar>
    <vs-sidebar parent="body" default-index="0"  color="primary" class="sidebarx" spacer v-model="active">
      <div class="row col-12">
      <label class="custom-control-label col-7" for="langSelect">Language</label>
      <select id="langSelect" class="col-5 custom-select custom-select-sm" v-model="lang" >
        <option value="en">EN</option>
        <option value="de">DE</option>
      </select>
    </div>
      <div v-if="currentuser.id!=0" class="header-sidebar" slot="header" :style="'background-image:url('+currentuser.background+');'">
        <router-link class="" to="/notifications">
          <vs-avatar :badge="n" to="/notifications" size="70px" :src="currentuser.avatar"/>
        </router-link>
        <h4><router-link class="btn btn-sm btn-success" :to="'/profile/'+currentuser.id">{{ currentuser.name }}</router-link></h4>
        <span>
          <router-link class="btn btn-sm btn-success" to="/upload">{{ $t("Upload") }}</router-link>
          <router-link class="btn btn-sm btn-success" to="/myvideos">{{ $t("My") }} {{ $t("medias") }}</router-link>
        </span>
      </div>
      <!-- The existing vs-select doesn't work here. This does, but isn't elegant (yet) -->
      <!-- <select
        placeholder="Types"
        multiple
        class="selectExample"
        v-model="dataTypes"
        >
        <option value="audio" text="Audio" >Audio</option>
        <option value="video" text="Video" >Video</option>
      </select> -->
      <vs-navbar-item index="1">
        <router-link class="" to="/"><vs-icon icon="home"></vs-icon>Home</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="9">
        <router-link class="" to="/categories"><vs-icon icon="view_list"></vs-icon>{{ $t('Categories') }}</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="3">
        <router-link class="" to="/tags"><vs-icon icon="tag"></vs-icon>{{ $t('Tags') }}</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="2">
        <router-link class="" to="/charts"><vs-icon icon="multiline_chart"></vs-icon>Charts</router-link>
      </vs-navbar-item>

      <vs-navbar-item index="4">
        <router-link class="" to="/about">{{ $t('About') }}</router-link>
      </vs-navbar-item>
      <vs-navbar-item index="5">
        <a @click="emitGetNewMedias()" index="5.1" style="cursor: pointer;" class="">Check 4 new medias</a>
      </vs-navbar-item>

      <vs-sidebar-group v-if="currentuser.admin" title="Admin">
        <vs-navbar-item index="7.1">
          <router-link class="" to="/admin/users">{{ $t('Users') }}</router-link>
        </vs-navbar-item>
     </vs-sidebar-group>
       <vs-sidebar-group title="Dev options">
         <vs-navbar-item index="6.1">
           <a @click="emitLoadAllMedias()"  style="cursor: pointer;" class="">Load all {{ $t('medias') }}</a>
          </vs-navbar-item>
         <vs-navbar-item index="6.2">
           <a @click="emitRefreshMedias()" style="cursor: pointer;" class="">Reset data</a>
         </vs-navbar-item>
         <p>{{ $t('Medias') }} {{ $t('loaded') }}: {{ medias.length }}</p>
         <p>{{ $t('Users') }} {{ $t('loaded') }}: {{ users.length }}</p>
         <p>{{ $t('Tags') }} {{ $t('loaded') }}: {{ tags.length }}</p>


      </vs-sidebar-group>



      <div class="footer-sidebar" slot="footer">
        <vs-button v-if="currentuser.id==0" to="/login" icon="exit_to_app" color="success" type="flat">{{ $t("Login") }}</vs-button>
        <vs-button v-if="currentuser.id==0" to="/register" icon="person_add" color="success" type="flat">{{ $t("Register") }}</vs-button>
        <vs-button v-if="currentuser.id!=0" @click="emitLogout()" icon="power_settings_new" color="danger" type="flat">{{ $t("Logout") }}</vs-button>
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
    changeIndex(i){
      console.log("this change index? "+i)
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
  props:['notifications','currentuser','medias','users','tags','csrf','totalmedias'],
  computed:{
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
        console.log("go for notifications")
        console.log(val.read_at)
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
