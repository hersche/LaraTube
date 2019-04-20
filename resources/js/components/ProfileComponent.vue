<template>

<div v-if="currentuser!=undefined">
  <div id="profile" >
    <div id="profileheader" class="text-center" :style="'background-image:url('+currentuser.background+')'">
      <img class='pl-2 pt-1 pb-1' :src='currentuser.avatar' />
      <h3 class='ml-2' style=''>{{ currentuser.name }}</h3>
    </div>
    <VueMarkdown :source="currentuser.bio"></VueMarkdown>
    <div v-if="ownuser.id!=currentuser.id">
    <div v-if="friendstatus==0">No friend-thing yet 
      <v-btn @click="changeFriend('/internal-api/friends/friendRequest', currentuser.id)" >Send friendrequest</v-btn>
      <v-btn @click="changeFriend('/internal-api/friends/block', currentuser.id)" >Block</v-btn>
    </div>
    <div v-if="friendstatus==1">Accepted friend
    <v-btn @click="changeFriend('/internal-api/friends/block', currentuser.id)" >Block</v-btn>
    </div>
    <div v-if="friendstatus==2">Your request is pending
    <v-btn @click="changeFriend('/internal-api/friends/block', currentuser.id)" >Block</v-btn>
    </div>
    <div v-if="friendstatus==3">Denied friend
    <v-btn @click="changeFriend('/internal-api/friends/acceptRequest', currentuser.id)" >Accept request</v-btn>
    </div>
    <div v-if="friendstatus==4">Blocked friend
    <v-btn @click="changeFriend('/internal-api/friends/unblock', currentuser.id)" >Unblock</v-btn></div>
    <div v-if="friendstatus==5">Pending request friend
      <v-btn @click="changeFriend('/internal-api/friends/acceptRequest', currentuser.id)" >Accept request</v-btn>
    </div>
  </div>
  </div>
  <h3>{{ $t("User") }}'s {{ $t("medias") }}</h3>
  <p>
    <sortSelect></sortSelect>
  </p>
  <overview v-bind:currentuser="currentuser" v-bind:medias="usermedias" v-if="usermedias.length>0"></overview>
  <h5 v-else>{{ $t("User") }} got no {{ $t('medias') }}</h5>
</div>
</template>

<script>
  const axios = require('axios');
  import { eventBus, store } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'

  import SortSelect from './SortSelect'
  import VueMarkdown from 'vue-markdown'
  export default {
    props: ['baseUrl'],
    methods:{
      changeFriend(url, uid){
        console.log("change friend fired??")
        axios.post(url,{fid:uid,csrf:this.csrf})  
        .then(function (response) {
          store.commit("setUsers",JSON.parse(response.request.response).data)
      //   console.log(JSON.parse(response.request.response).data);
      //   return response.data
       })
       .catch(function (error) {
         console.log(error);
       })
      },
    },
  components : {
      'overview': GalleryComponent,
      'sortSelect': SortSelect,
      VueMarkdown,
      
  },
  mounted(){
    eventBus.$emit("loadUserVideos",this.currentuser.id)
  },
  computed: {
    currentuser: function(){
      var u = store.getters.getUserById(Number(this.$route.params.profileId))
      if(u!=undefined){
        
      }
      return u
    },
    ownuser: function(){
      var u = store.getters.getUserById(Number(store.state.loginId))
      return u
    },
    
    friendstatus: function(){
      var u = store.getters.getUserById(Number(store.state.loginId))
      if(u.friends!=undefined){
        console.log("pending",u.friends.pending);
      if(u.friends.accepted.indexOf(Number(this.$route.params.profileId))>-1){
        return 1;
      } else if(u.friends.pending.indexOf(Number(this.$route.params.profileId))>-1){
        return 2;
      } else if(u.friends.denied.indexOf(Number(this.$route.params.profileId))>-1){
        return 3;
      } else if(u.friends.blocked.indexOf(Number(this.$route.params.profileId))>-1){
        return 4;
      } else if(u.friends.pendingRequests.indexOf(Number(this.$route.params.profileId))>-1){
        return 5;
      }
    }
      return 0;
    },
    // a computed getter
    usermedias: function () {
      
      var d = store.getters.getMediasByUserId(Number(this.$route.params.profileId))
      console.log("usermedias? ",d)
      return d;
    },
  }
  }
</script>
