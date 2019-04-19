<template>
<div class="">
  <h1 class="text-center">{{ $t('2FA-Login') }}</h1>
          <v-form
            ref="form"
            id="oneTimePasswordForm"
            v-model="valid"
            lazy-validation
            >
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="ajaxLogin" value="1">
            <v-text-field
              label="2fa-Token"
              name="one_time_password"
              v-on:keyup.enter="submitLogin()"
              required
              ></v-text-field>
              

              </v-form>
                      <v-btn @click="submitLogin()">
                          {{ $t('Login') }}
                      </v-btn>

                        <a class="btn btn-link" href="">
                            Forgot Your Password?
                        </a>
</div>
</template>


<script>
 const $ = require("jquery");
  import { eventBus,store } from '../../eventBus.js';
  export default {
    props: ['baseUrl'],
    data(){
      return {
        valid:true,
        email:'',
        password:'',
        show1:false,
        checkbox:false,
        rules: {
          required: value => !!value || 'Required.',
          min: v => v.length >= 8 || 'Min 8 characters',
          emailMatch: () => ('The email and password you entered don\'t match')
        }
      }
    },
    computed: {
      csrf: function(){
        return store.getters.getCSRF()
      },
    },
    methods:{
      submitLogin() {
        let that = this;
        $.ajax({
            url: '/2faVerify',
            type: 'POST',
            data: new FormData($("#oneTimePasswordForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                eventBus.$emit('login',res.responseJSON.data);
              } else if(res.status==401){
                eventBus.$emit('alert',"2-factor auth failed");
              }
              console.log("received login")
            //  eventBus.$emit('refreshMedia',that.currentmedia.id);
            //  eventBus.$emit('videoEdited',[that.currentmedia.title,res.responseJSON])
            }

        });
        return false;
      },
    }

  }
</script>
