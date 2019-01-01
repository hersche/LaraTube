<template>
    <div class="col-xs-12 col-sm-12 col-md-12">
    <h4>Edit profile</h4>
    <form id="theForm">
      <div class="form-group">
          <label>Username</label>
          <input type="hidden" value="" name="image" id="addMediaImage" />
               <input placeholder="Username" class="form-control" :value="currentuser.name" name="name" type="text">
      </div>

    <div class="form-group">
        <label>Avatar</label>
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
        <input id="avatarUpload" @change="avatarChange()" name="avatarf" type="file">
        <div id="avatar"></div>
    </div>


    <div class="form-group">
        <label>Background</label>
        <!-- the result -->
        <vue-croppie
          ref="croppieBackgroundRef"
          :enableOrientation="true"
          :enableResize="false"
          @result="resultBackground"
          :viewport="{ width: 700, height: 394, type: 'square' }"
          :boundary="{ width: 700, height: 394 }"
          @update="updateBackground">
          </vue-croppie>

          <input type="hidden" id="backgroundBase" name="background" :value="backgroundCropped" />

          <!-- Rotate angle is Number -->
          <button @click="rotateBackground(-90,$event)">Rotate Left</button>
          <button @click="rotateBackground(90,$event)">Rotate Right</button>
        <input id="backgroundUpload" @change="backgroundChange()" name="backgroundf" type="file">
        <div id="background"></div>
    </div>

      <div class="form-group">
          <label>Biographie:</label>
          <textarea placeholder="Media-description" id="addMediaDescription" class="form-control" :value="currentuser.bio" name="description" cols="50" rows="10"></textarea>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
              <strong>Tags (separate with spaces):</strong>
              <input id="tags" type="text" class="form-control" name="tags" :value="currentuser.tagString" >
          </div>
      </div>


    </form>

    <button @click="submitAction();" class="btn btn-success" >Save</button> <button @click="deleteAction();" class="btn btn-danger" >Delete</button>
    </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import { Media }  from '../models';
  export default {
    props: ['medias','baseUrl','currentuser'],
    mounted: function () {
      this.$refs.croppieAvatarRef.bind({
        url: '/img/404/avatar.png',
      })
      this.$refs.croppieBackgroundRef.bind({
        url: '/img/404/background.png',
      })
    },
    updated: function () {
      this.$nextTick(function () {
        if(this.$refs.croppieAvatarRef!=undefined&this.editpicloaded==false){
          this.editpicloaded=true;
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
    },

    methods: {
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
                //eventBus.$emit('showAlert',['success','Video uploaded']);
              }
              eventBus.$emit('userEdited','')
            //  eventBus.$emit('videoDeleted',that.currentmedia.title);
            //  eventBus.$emit('videoCreated',res.responseJSON);
            }

        });
        return false;
      },
      deleteAction() {
        let that = this;
        $.ajax({
            url: '/internal-api/media/'+this.currentmedia.title,
            type: 'DELETE',
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                //eventBus.$emit('showAlert',['success','Video uploaded']);

              }
              eventBus.$emit('videoDeleted',that.currentmedia.title);
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
        dismisssecs: 20,
        dismisscountdown: 0,
        alertType: 'warning',
        alertMsg: '',
        editpicloaded:false,
        showdismissiblealert: false,
        avatarCropped: null,
        backgroundCropped: null,
      }
    }
  }
</script>
