<template>
<div class="col-md-8">
    <div class="card">
        <div class="card-header">Login</div>

        <div class="card-body">
            <form method="POST" action="/login" aria-label="The Login">
                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>

                    <div class="col-md-6">
                      <input type="hidden" name="_token" :value="csrf">
                        <input id="email" type="email" class="form-control" name="email" value="" required autofocus>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right"></label>

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

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

                        <a class="btn btn-link" href="">
                            Forgot Your Password?
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</template>


<script>
  import { eventBus } from '../../eventBus.js';
  export default {
    props: ['medias','baseUrl','user','tags'],
    data(){
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }

  }
</script>
