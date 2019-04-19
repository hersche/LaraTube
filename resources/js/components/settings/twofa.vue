<template>
  <div>
    <div class="text-center" v-if="twofactor!=undefined">
    <h1>{{ $t("2factor") }} {{ $t("settings") }} {{ twofactorstatenr }}</h1>
    <p>Always take care about the QR-Code and it's secret. Don't let stranger take a photo.</p>
    <!-- <v-expansion-panel v-if="twofactorstatenr>0">
      <v-expansion-panel-content>
    <template v-slot:header>
      <div>Show QR-Code and secret</div>
    </template>-->
    <v-card>
      <v-card-text class="text-center"><img  :src="twofactor.url" />
      <v-text-field
        label="2 Factor secret (same as in QR-Code)"
        :value="twofactor.key"
        :append-icon="show2fakey ? 'visibility_off' : 'visibility'"
        readonly
        @click:append="show2fakey=!show2fakey"
        :type="show2fakey ? 'text' : 'password'"
        ></v-text-field>
        </v-card-text>
    </v-card>
  <!-- </v-expansion-panel-content>
</v-expansion-panel> -->
    

    <p v-if="twofactorstatenr==0" >2 factor auth is currently disabled</p>
    <p v-if="twofactorstatenr==1" >
      <p v-if="twofactorstatenr==1">2 factor auth is enabled, but not active. Enter the code for activate it.</p>
      <v-text-field
      v-if="twofactorstatenr==1"
        label="2 Factor test"
        hint="Scan the code with Google Authenticator and enter the result"
         v-model="checkTwofactorCode"
        required
        ></v-text-field>
    <v-btn v-if="twofactorstatenr==1" @click="testTwofactor()" >Check and activate</v-btn>
    </p>
    <p v-if="twofactorstatenr==2">2 factor auth is enabled and tested!</p>

    <v-btn v-if="(twofactor.enabled=='1'||twofactor.url!='')" @click="disableTwofactor()">Disable</v-btn>
    <v-btn @click="refreshTwofactor()">{{ enableRefreshLabel }}</v-btn>

</div>
<v-text-field
  label="Userpassword for change"
  v-model="userpassword"
  :append-icon="showUserpassword ? 'visibility_off' : 'visibility'"
  @click:append="showUserpassword=!showUserpassword"
  :type="showUserpassword ? 'text' : 'password'"
  ></v-text-field>
    <v-btn @click="getTwofactor()" v-if="twofactor==undefined">Get</v-btn>
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
      rmBr(str) {
        return str.replace(/<br\s*\/?>/mg,"");
      },
      
      getTwofactor(){
        axios.post("/internal-api/settings/get/twofactor",{"userpass":this.userpassword})  
        .then(function (response) {
          store.commit("setTwofactor",JSON.parse(response.request.response).data)
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
// CALBACK USAGE
resultAvatar(output) {
  
},
resultBackground(output) {

},
updateAvatar(val) {

},
updateBackground(val) {

},
rotateAvatar(rotationAngle,event) {
    // Rotates the image
},
rotateBackground(rotationAngle,event) {
}
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
        show2fakey:false,
        backgroundCropped: null,
      }
    }
  }
</script>
