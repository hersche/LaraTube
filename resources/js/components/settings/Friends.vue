<template>
  <div v-if="currentuser.friends!=undefined">
    <h1>Open requests from others</h1>
    <div v-for="item in currentuser.friends.pendingRequests">
      <router-link :to="'/profile/'+toUser(item).id">
        <v-chip>
          <v-avatar>
            <img :src="toUser(item).avatar" alt="trevor">
          </v-avatar>
          {{ toUser(item).username }}
        </v-chip>
      </router-link>
    <v-btn @click="changeFriend('/internal-api/friends/acceptRequest', toUser(item).id)" >Accept request</v-btn>
      <v-btn @click="changeFriend('/internal-api/friends/denyRequest', toUser(item).id)" >Deny request</v-btn>
      <v-btn @click="changeFriend('/internal-api/friends/block', currentuser.id)" >Block</v-btn>
    </div>
    <h1>Open requests by myself</h1>
    <div v-for="item in currentuser.friends.pending">
      <router-link :to="'/profile/'+toUser(item).id">
        <v-chip>
          <v-avatar>
            <img :src="toUser(item).avatar" alt="trevor">
          </v-avatar>
          {{ toUser(item).username }}
        </v-chip>
      </router-link>
    </div>
    <h1>Blocked by myself</h1>
    <div v-for="item in currentuser.friends.blocked">
      <router-link :to="'/profile/'+toUser(item).id">
        <v-chip>
          <v-avatar>
            <img :src="toUser(item).avatar" alt="trevor">
          </v-avatar>
          {{ toUser(item).username }}
        </v-chip>
      </router-link>
      <v-btn @click="changeFriend('/internal-api/friends/unblock', currentuser.id)" >Unblock</v-btn>
    </div>
    <h1>Accepted by myself</h1>
    <div v-for="item in currentuser.friends.accepted">
      <router-link :to="'/profile/'+toUser(item).id">
        <v-chip>
          <v-avatar>
            <img :src="toUser(item).avatar" alt="trevor">
          </v-avatar>
          {{ toUser(item).username }}
        </v-chip>
      </router-link>
      <v-btn @click="changeFriend('/internal-api/friends/unfriend', currentuser.id)" >Unfriend</v-btn>
    </div>
    <h1>Denied by myself</h1>
    <div v-for="item in currentuser.friends.denied">
      <router-link :to="'/profile/'+toUser(item).id" >{{ toUser(item).username }}</router-link>
      <v-btn @click="changeFriend('/internal-api/friends/unblock', currentuser.id)" >Unblock</v-btn>
    </div>
</div>
</template>
<script>
  import { eventBus,store } from '../../eventBus.js';
  import MarkdownCreator from '../MarkdownCreator'
  const $ = require("jquery");
  const axios = require("axios");
  export default {
    props: ['baseUrl'],
    components: {
      MarkdownCreator
    },
    mounted: function () {

    //  this.$refs.croppieBackgroundRef.bind({
      //  url: '/img/404/background.png',
      //})
    },
    updated: function () {

    },
    computed: {
      enableRefreshLabel(){
        if(this.twofactor.enabled=='1'||this.twofactor.url!=''){
          return "Refresh"
        } else {
          return "Enable"
        }
      },
      twofactorstatenr(){
        console.log("on nr ",this.twofactor)
        if((this.twofactor.enabled=='1')&&((this.twofactor.url!='')&&(this.twofactor.url!=undefined))){
          return 2
        }
        else if((this.twofactor.enabled=='0')&&((this.twofactor.url!='')&&(this.twofactor.url!=undefined))){
          return 1
        } else {
          return 0
        }
      },
      twofactor(){
        return store.state.twofactor
      },
      loggeduserid(){
        return store.state.loginId
      },
      csrf: function(){
        return store.getters.getCSRF()
      },
      currentuser: function(){
        var u = store.getters.getUserById(store.state.loginId)
        if(u!=undefined){
          this.tmpBio = u.bio
        }
        return u
      },
    },

    methods: {
      toUser(str) {
        return store.getters.getUserById(str);
      },
      changeFriend(url, uid){
        axios.post(url,{fid:uid,csrf:this.csrf})  
        .then(function (response) {
          store.commit("setUsers",JSON.parse(response.request.response).data)
       })
       .catch(function (error) {
         console.log(error);
       })
      },
      changePassword(){
        axios.post("/internal-api/settings/password",{"oldpass":this.oldPass,"newpass":this.newPass,"newpass2":this.newPass2})  
        .then(function (response) {
          //store.commit("setTwofactor",JSON.parse(response.request.response).data)
          console.log("get twofactor",JSON.parse(response.request.response).data);
       })
       .catch(function (error) {
         console.log(error);
       })
      },
      testTwofactor(){
        let that = this;
        axios.post("/internal-api/settings/2faTest",{"one_time_test_password":this.checkTwofactorCode,"userpass":this.userpassword})  
        .then(function (response) {
          if(response.request.response!='{"twofactor":testinvalid}'){
            that.userpassword=''
            store.commit("setTwofactor",JSON.parse(response.request.response).data)
            console.log("refresh twofactor",JSON.parse(response.request.response).data);
            eventBus.$emit('alert',"2-factor auth passed");
          } 
       })
       .catch(function (error) {
         eventBus.$emit('alert',"2-factor test failed");
         console.log(error);
       })
      },
      refreshTwofactor(){
        axios.post("/internal-api/settings/refresh/twofactor",{"userpass":this.userpassword})  
        .then(function (response) {
          store.commit("setTwofactor",JSON.parse(response.request.response).data)
          console.log("refresh twofactor",JSON.parse(response.request.response).data);
          eventBus.$emit('alert',"2-factor refresh passed");
       })
       .catch(function (error) {
         console.log(error);
       })
      },
      disableTwofactor(){
        let that = this;
        axios.post("/internal-api/settings/disable/twofactor",{"userpass":this.userpassword})  
        .then(function (response) {
          that.userpassword=''
          store.commit("setTwofactor",JSON.parse(response.request.response).data)
          console.log("disable twofactor",JSON.parse(response.request.response).data);
       })
       .catch(function (error) {
         console.log(error);
       })
      },
      submitAction() {
        let that = this;
        $.ajax({
            url: '/internal-api/profiles/edit/'+this.currentuser.id,
            type: 'POST',
            data: new FormData($("#theForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
              }
              //eventBus.$emit('userEdited',that.currentuser.id)
            }

        });
        return false;
      },
    },
    data(){
      return {
        mediaType: '',
        checkTwofactorCode: '',
        public: false,
        editpicloaded:false,
        showdismissiblealert: false,
        avatarCropped: null,
        tmpBio:'',
        showUserpassword:false,
        userpassword:'',
        showOldPass:false,
        showNewPass:false,
        showNewPass2:false,
        backgroundCropped: null,
      }
    }
  }
</script>
