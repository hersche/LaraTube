<template>
<div class="">
    <div class="card">
        <div class="card-header">{{ $t('Login') }}</div>

        <div class="card-body">
            <form method="POST" action="/login" id="loginForm" aria-label="The Login">
                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                    <div class="col-md-6">
                      <input type="hidden" name="_token" :value="csrf">
                      <input id="email" type="email" class="form-control" name="email" value="" required autofocus>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" required>

                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                </div>
              </form>
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                      <button @click="submitLogin()" class="btn btn-primary">
                          {{ $t('Login') }}
                      </button>

                        <a class="btn btn-link" href="">
                            Forgot Your Password?
                        </a>
                    </div>
                </div>


        </div>
    </div>
</div>
</template>


<script>
  import { eventBus } from '../../eventBus.js';
  export default {
    props: ['medias','baseUrl','user','tags','csrf'],
    data(){
      return {
      }
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
