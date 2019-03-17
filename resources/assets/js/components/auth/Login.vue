<template>
<div class="">
  <h1 class="text-center">{{ $t('Login') }}</h1>
          <v-form
            ref="form"
            id="loginForm"
            v-model="valid"
            lazy-validation
            >
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="ajaxLogin" value="1">
            <v-text-field
              v-model="email"
              label="E-mail"
              name="email"
              v-on:keyup.enter="submitLogin()"
              required
              ></v-text-field>
              
              <v-text-field
                v-model="password"
                v-on:keyup.enter="submitLogin()"
                :append-icon="show1 ? 'visibility_off' : 'visibility'"
                :rules="[rules.required, rules.min]"
                :type="show1 ? 'text' : 'password'"
                name="password"
                :label="$t('Password')"
                hint="At least 8 characters"
                counter
                @click:append="show1 = !show1"
              ></v-text-field>
              <v-checkbox
                v-model="checkbox"
                label="Remember me"
                name="remember"
                required
              ></v-checkbox>

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
            url: '/internal-api/login',
            type: 'POST',
            data: new FormData($("#loginForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                eventBus.$emit('login',res.responseJSON.data);
              } else if(res.status==422){
                eventBus.$emit('loginFailed',"");
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
