<template>
      <div class="">
          <div class="card">
              <div class="card-header">{{ $t('Register') }}</div>

              <div class="card-body">
                  <form method="POST" id="theForm" action="/register" aria-label="Register">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ $t('Username') }}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="" required autofocus>
                        </div>
                    </div>
                      <input type="hidden" name="_token" :value="csrf">
                      <div class="form-group row">
                          <label class="col-md-4 col-form-label text-md-right">Avatar</label>
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
                            <div class="col-md-8 col-form-label text-md-right">
                            <button @click="rotateAvatar(-90,$event)">Rotate Left</button>
                            <button @click="rotateAvatar(90,$event)">Rotate Right</button>
                            <input id="avatarUpload" @change="avatarChange()" name="avatarf" type="file">
                          </div>
                          <div id="avatar"></div>
                      </div>


                      <div class="form-group row">
                          <label class="col-md-4 col-form-label text-md-right">Background</label>
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
                            <div class="col-md-8 col-form-label text-md-right">
                            <button @click="rotateBackground(-90,$event)">Rotate Left</button>
                            <button @click="rotateBackground(90,$event)">Rotate Right</button>
                          <input id="backgroundUpload" @change="backgroundChange()" name="backgroundf" type="file">
                        </div>
                          <div id="background"></div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-4 col-form-label text-md-right">Biographie:</label>
                          <div>
                            <MarkdownCreator theText="" theId="bio" theTitle="Bio" ></MarkdownCreator>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="public" class="col-md-4 col-form-label text-md-right">Public</label>

                          <div class="col-md-6">
                              <vs-switch v-model="public"/>
                              <input type="hidden" name="public" :value="Number(public)" />
                          </div>
                      </div>

                      <div class="form-group row">
                          <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                          <div class="col-md-6">
                              <input id="email" type="email" class="" name="email" value="" required>

                          </div>
                      </div>

                      <div class="form-group row">
                          <label for="tags" class="col-md-4 col-form-label text-md-right">Tags</label>

                          <div class="col-md-6">
                              <input id="tags" type="text" class="form-control" name="tags" value="" >
                          </div>
                      </div>

                      <div class="form-group row">
                          <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                          <div class="col-md-6">
                              <input id="password" type="password" class="form-control" name="password" required>

                          </div>
                      </div>

                      <div class="form-group row">
                          <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                          <div class="col-md-6">
                              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                          </div>
                      </div>
                      </form>
                      <div class="form-group row mb-0">
                          <div class="col-md-6 offset-md-4">
                              <button @click="submitAction()" class="btn btn-primary">{{ $t('Register') }}</button>
                          </div>
                      </div>

              </div>
          </div>
      </div>
</template>


<script>
  import { eventBus } from '../../eventBus.js';
    import MarkdownCreator from '../MarkdownCreator'
  export default {
    components: {
      MarkdownCreator
    },
    props: ['baseUrl','csrf'],
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
        if(this.$refs.croppieRef!=undefined&this.editpicloaded==false){
          this.editpicloaded=true;
          /*console.log("redo picture")
          this.$refs.croppieAvatarRef.bind({
            url: this.currentuser.avatar_source,
          })
          this.$refs.croppieBackgroundRef.bind({
            url: this.currentuser.background_source,
          })
          */
        }
      })
    },
    data(){
      return {
        avatarCropped: null,
        backgroundCropped: null,
        public: false,
        tmpBio:''
      }
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
        console.log("the form")
        console.log($("#theForm")[0])
        $.ajax({
            url: '/internal-api/register',
            type: 'POST',
            data: new FormData($("#theForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
              }
              eventBus.$emit('login',res.responseJSON.data);
            //  eventBus.$emit('videoEdited',[that.currentmedia.title,res.responseJSON])
            //  eventBus.$emit('videoDeleted',that.currentmedia.title);
            //  eventBus.$emit('videoCreated',res.responseJSON);
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
  }
</script>
