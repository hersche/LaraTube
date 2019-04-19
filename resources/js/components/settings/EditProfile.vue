<template>
    <div v-if="currentuser.id!=0">
    <h1 class="text-center">{{ $t("Edit") }} {{ $t("profile") }}</h1>
    <form id="theForm">
      <input type="hidden" name="_token" :value="csrf">
      <v-text-field
        :label="$t('Name')"
        name="name"
         :value="currentuser.name"
        required
        ></v-text-field>
    <div class="form-group">
        <label>{{ $t("Avatar") }}</label>
    <Cropper v-bind:theurl="currentuser.avatar" v-bind:width="180" v-bind:height="180" type="circle" name="avatar" ></Cropper>
  </div>
    <div class="form-group">
        <label>{{ $t("Background") }}</label>
            <Cropper v-bind:theurl="currentuser.background" v-bind:width="800" v-bind:height="394" type="square" name="background" ></Cropper>
    </div>
            <v-switch v-model="public" :label="$t('Public')+' '+$t('account')"></v-switch>
            <input type="hidden" name="public" :value="Number(public)" />
          <MarkdownCreator :theText="currentuser.bio" theId="bio" :theTitle="$t('Biographie')" ></MarkdownCreator>
          <v-text-field
            label="Tags"
            name="tags"
            :value="currentuser.tagString"
            ></v-text-field>


    </form>

    <v-btn @click="submitAction();"color="green" ><v-icon>save</v-icon>{{ $t('Save') }}</v-btn> <!-- <button @click="deleteAction();" class="btn btn-danger" >Delete</button> -->
    </div>
</template>
<script>
  import { eventBus,store } from '../../eventBus.js';
  import MarkdownCreator from '../MarkdownCreator'
  import Cropper from '../cropp'
  const $ = require("jquery");
  export default {
    props: ['baseUrl'],
    components: {
      MarkdownCreator,
      Cropper
    },
    mounted: function () {
    //  this.$refs.croppieAvatarRef.bind({
      //  url: '/img/404/avatar.png',
      //})
    //  this.$refs.croppieBackgroundRef.bind({
      //  url: '/img/404/background.png',
      //})
    },
    updated: function () {
      this.$nextTick(function () {
        if(this.$refs.croppieAvatarRef!=undefined&this.editpicloaded==false){
          this.editpicloaded=true;
          this.public = this.currentuser.publicState;
          console.log("redo picture")
          this.$refs.croppieAvatarRef.bind({
            url: this.currentuser.avatar,
          })
          this.$refs.croppieBackgroundRef.bind({
            url: this.currentuser.background,
          })
        }
      })
    },
    computed: {
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

      submitAction() {
        let that = this;
        var d = new FormData($("#theForm")[0])
        d.delete("avatarFile")
        d.delete("backgroundFile")
        $.ajax({
            url: '/internal-api/profiles/edit/'+this.currentuser.id,
            type: 'POST',
            data: d,
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                store.commit("setUsers",res.responseJSON.data)
              }
              
          //    eventBus.$emit('userEdited',that.currentuser.id)
            }

        });
        return false;
      },
    },
    data(){
      return {
        mediaType: '',
        public: false,
        editpicloaded:false,
        showdismissiblealert: false,
        avatarCropped: null,
        tmpBio:'',
        backgroundCropped: null,
      }
    }
  }
</script>
