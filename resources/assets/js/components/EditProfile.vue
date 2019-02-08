<template>
    <div class="col-xs-12 col-sm-12 col-md-12">
    <h4>{{ $t("Edit") }} {{ $t("profile") }}</h4>
    <form id="theForm">
      <div class="form-group">
          <label>{{ $t("Username") }}</label>
          <input type="hidden" name="_token" :value="csrf">
          <input type="hidden" value="" name="image" id="addMediaImage" />
          <input placeholder="Username" class="form-control" :value="currentuser.name" name="name" type="text">
      </div>

    <div class="form-group">
        <label>{{ $t("Avatar") }}</label>
        <!-- the result -->
        <vue-croppie
          ref="croppieAvatarRef"
          :enableOrientation="true"
          :enableResize="false"
          @result="resultAvatar"
          :viewport="{ width: 180, height: 180, type: 'circle' }"
          :boundary="{ width: 200, height: 200 }"
          @update="updateAvatar">
          </vue-croppie>

          <input type="hidden" id="avatarBase" name="avatar" :value="avatarCropped" />

          <!-- Rotate angle is Number -->
          <button @click="rotateAvatar(-90,$event)">Rotate Left</button>
          <button @click="rotateAvatar(90,$event)">Rotate Right</button>
        <input id="avatarUpload" accept=".png,.jpg,.jpeg" @change="avatarChange()" name="avatarf" type="file">
        <div id="avatar"></div>
    </div>


    <div class="form-group">
        <label>{{ $t("Background") }}</label>
        <!-- the result -->
        <vue-croppie
          ref="croppieBackgroundRef"
          :enableOrientation="true"
          :enableResize="false"
          @result="resultBackground"
          :viewport="{ width: 1200, height: 394, type: 'square' }"
          :boundary="{ width: 800, height: 394 }"
          @update="updateBackground">
          </vue-croppie>

          <input type="hidden" id="backgroundBase" name="background" :value="backgroundCropped" />

          <!-- Rotate angle is Number -->
          <button @click="rotateBackground(-90,$event)">Rotate Left</button>
          <button @click="rotateBackground(90,$event)">Rotate Right</button>
        <input id="backgroundUpload" accept=".png,.jpg,.jpeg" @change="backgroundChange()" name="backgroundf" type="file">
        <div id="background"></div>
    </div>
    <div class="form-group">
        <label for="public" class="col-md-4 col-form-label text-md-right">Public</label>

        <div class="col-md-6">
            <vs-switch v-model="public"/>
            <input type="hidden" name="public" :value="Number(public)" />
        </div>
    </div>
      <div class="form-group">
          <label>{{ $t("Biographie") }}</label>
          <MarkdownCreator :theText="currentuser.bio" theId="bio" theTitle="Bio" ></MarkdownCreator>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
              <strong>Tags (separate with spaces):</strong>
              <input id="tags" type="text" class="form-control" name="tags" :value="currentuser.tagString" >
          </div>
      </div>


    </form>

    <button @click="submitAction();" class="btn btn-success" ><vs-icon icon="save"></vs-icon>{{ $t('Save') }}</button> <!-- <button @click="deleteAction();" class="btn btn-danger" >Delete</button> -->
    </div>
</template>
<script>
  import { eventBus, store } from '../eventBus.js';
  import { Media }  from '../models';
  import MarkdownCreator from './MarkdownCreator'
  export default {
    props: ['baseUrl','loggeduserid'],
    components: {
      MarkdownCreator
    },
    mounted: function () {
      this.$refs.croppieAvatarRef.bind({
        url: '/img/404/avatar.png',
      })
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
        var u = store.getters.getUserById(this.loggeduserid)
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
      avatarChange(){
        var reader = new FileReader();
        let that = this;
       reader.onload = function (e) {
         that.$refs.croppieAvatarRef.bind({
             url: e.target.result,
         });
        }
        reader.readAsDataURL($("#avatarUpload")[0].files[0]);

      },
      backgroundChange(){
        var reader = new FileReader();
        let that = this;
       reader.onload = function (e) {
         that.$refs.croppieBackgroundRef.bind({
             url: e.target.result,
         });
        }
        reader.readAsDataURL($("#backgroundUpload")[0].files[0]);

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
              eventBus.$emit('userEdited',that.currentuser.id)
            }

        });
        return false;
      },
// CALBACK USAGE
resultAvatar(output) {
    this.avatarCropped = output;
},
resultBackground(output) {
    this.backgroundCropped = output;
},
updateAvatar(val) {
  let options = {
      format: 'png'
  }
  this.$refs.croppieAvatarRef.result(options, (output) => {
      this.avatarCropped = output;
  });
},
updateBackground(val) {
  let options = {
      format: 'png'
  }
  this.$refs.croppieBackgroundRef.result(options, (output) => {
      this.backgroundCropped = output;
  });
},
rotateAvatar(rotationAngle,event) {
    // Rotates the image
    if (event) event.preventDefault()
    this.$refs.croppieAvatarRef.rotate(rotationAngle);
},
rotateBackground(rotationAngle,event) {
    // Rotates the image
    if (event) event.preventDefault()
    this.$refs.croppieBackgroundRef.rotate(rotationAngle);
}
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
