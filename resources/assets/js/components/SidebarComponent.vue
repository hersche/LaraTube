<template lang="html">

  <div id="parentx" class=" fixed-top bg-light">
    <vs-navbar class="nabarx" style="">
      <vs-button @click="active=true" type="flat" icon="menu"></vs-button>
      <router-link class="" to="/"><vs-navbar-title>LaraTube</vs-navbar-title></router-link>
      
      
<vs-spacer></vs-spacer>
      <input icon="search" :placeholder="$t('Search')+'...'" id="theLiveSearch" class="col-3 col-md-3" @keyup="searching()" @focus="searching()" />
    </vs-navbar>
    <vs-sidebar parent="body" default-index="1" :reduce="false" :reduce-not-hover-expand="false"  color="primary" class="sidebarx" spacer v-model="active">
      <div slot="header" class="header-sidebar">
        <div class="row">
      <treeselect class="col-10" instanceId="dataTypeTree" v-if="treeTypes!=undefined" :multiple="true" :append-to-body="false" :always-open="false" v-model="dataTypes"  :options="treeTypes" />
      <vs-button @click="active=false" class="" size="small" radius color="danger" type="gradient" icon="close"></vs-button>
    </div>
      <div class="col-12 pl-0 pr-0">
      <label class="custom-control-label col-6" for="langSelect">Language</label>
      <select id="langSelect" class="col-6 float-right custom-select custom-select-sm" v-model="lang" >
        <option value="en">EN</option>
        <option value="de">DE</option>
      </select>

    </div>
      <div v-if="currentuser.id!=0" class="col-12 text-center"  :style="'background-image:url('+currentuser.background+');'">
        <router-link class="" to="/notifications">
          <vs-avatar :badge="n" to="/notifications" size="70px" :src="currentuser.avatar"/>
        </router-link>
        <h4><router-link class="btn btn-sm btn-success" :to="'/profile/'+currentuser.id">{{ currentuser.name }}</router-link></h4>
        <span>
          <router-link class="btn btn-sm btn-success" to="/upload">{{ $t("Upload") }}</router-link>
          <router-link class="btn btn-sm btn-success" to="/myvideos">{{ $t("My") }} {{ $t("medias") }}</router-link>
        </span>
      </div>
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
      <vs-sidebar-item index="1" icon="home" to="/">
        Home
      </vs-sidebar-item>
      <vs-sidebar-item index="9" to="/categories" icon="category">
        {{ $t('Categories') }}
      </vs-sidebar-item>
      <vs-sidebar-item index="3" icon="tag" to="/tags">
        {{ $t('Tags') }}
      </vs-sidebar-item>
      <vs-sidebar-item index="2" icon="multiline_chart" to="/charts" >
        Charts
      </vs-sidebar-item>

      <vs-sidebar-item index="4" to="/about" icon="multiline_chart">
        {{ $t('About') }}
      </vs-sidebar-item>
      <vs-sidebar-item index="5" @click="emitGetNewMedias()">
        Check 4 new medias
      </vs-sidebar-item>

      <vs-sidebar-group v-if="currentuser.admin" title="Admin">
        <vs-sidebar-item index="7.1" to="/admin/users">
          {{ $t('Users') }}
        </vs-sidebar-item>
     </vs-sidebar-group>
       <vs-sidebar-group title="Dev options">
         <vs-sidebar-item index="6.1" @click="emitLoadAllMedias()">
           Load all {{ $t('medias') }}
          </vs-sidebar-item>
         <vs-sidebar-item index="6.2" @click="emitRefreshMedias()">
           Reset data
         </vs-sidebar-item>
         <vs-sidebar-item>{{ $t('Medias') }} {{ $t('loaded') }}: {{ medias.length }} / {{ tm }}</vs-sidebar-item>
         <vs-sidebar-item>{{ $t('Users') }} {{ $t('loaded') }}: {{ users.length }}</vs-sidebar-item>
         <vs-sidebar-item>{{ $t('Tags') }} {{ $t('loaded') }}: {{ tags.length }}</vs-sidebar-item>


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
  props:['notifications','currentuser','users','tags','csrf','totalmedias'],
  computed:{
    tm: function(){ 
      return store.state.totalMedias
     } 
  },
  watch:{
    lang:function(val){
      localStorage.setItem("language",val);
      eventBus.$emit('languageChange',val);
    },
    dataTypes:function(val){
      console.log(val)
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
    medias:store.state.medias,
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
