<template>
      <div class="">
        <h1 class="text-center">{{ $t('Register') }}</h1>
        <v-form
          ref="form"
          id="theForm"
          v-model="valid"
          lazy-validation
          >
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
                      <v-text-field
                        v-model="name"
                        :label="$t('Name')"
                        name="name"
                        required
                        ></v-text-field>
                        
                        <v-text-field
                          v-model="email"
                          :label="$t('E-mail')"
                          name="email"
                          required
                          ></v-text-field>
                      <MarkdownCreator theText="" theId="bio" :theTitle="$t('Biographie')" :theHint="$t('Some words about you')+'...'" ></MarkdownCreator>

                              <v-switch v-model="public" :label="$t('Public')+' '+$t('account')"></v-switch>
                              <input type="hidden" name="public" :value="Number(public)" />



                      <v-text-field
                        v-model="tags"
                        label="Tags"
                        name="tags"
                        ></v-text-field>
                      <v-text-field
                        v-model="password"
                        :append-icon="show1 ? 'visibility_off' : 'visibility'"
                        :rules="[rules.required, rules.min]"
                        :type="show1 ? 'text' : 'password'"
                        name="password"
                        :label="$t('Password')"
                        hint="At least 8 characters"
                        counter
                        @click:append="show1 = !show1"
                      ></v-text-field>
                      <v-text-field
                        v-model="password2"
                        :append-icon="show2 ? 'visibility_off' : 'visibility'"
                        :rules="[rules.required, rules.min]"
                        :type="show2 ? 'text' : 'password'"
                        :label="$t('Confirm Password')"
                        hint="At least 8 characters"
                        counter
                        name="password_confirmation"
                        @click:append="show2 = !show2"
                      ></v-text-field>
                    </v-form>
                              <v-btn @click="submitAction()">{{ $t('Register') }}</v-btn>


              </div>
          </div>
      </div>
</template>


<script>
  import { eventBus,store } from '../../eventBus.js';
  import MarkdownCreator from '../MarkdownCreator'
  export default {
    components: {
      MarkdownCreator
    },
    props: ['baseUrl'],
    mounted: function () {
      this.$refs.croppieAvatarRef.bind({
        url: '/img/404/avatar.png',
      })
      this.$refs.croppieBackgroundRef.bind({
        url: '/img/404/background.png',
      })
    },
    computed: {
      csrf: function(){
        return store.getters.getCSRF()
      },
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
        tmpBio:'',
        name:'',
        valid:true,
        email:'',
        password:'',
        password2:'',
        show1:false,
        show2:false,
        checkbox:false,
        tags:'',
        rules: {
          required: value => !!value || 'Required.',
          min: v => v.length >= 8 || 'Min 8 characters',
          emailMatch: () => ('The email and password you entered don\'t match')
        }
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
