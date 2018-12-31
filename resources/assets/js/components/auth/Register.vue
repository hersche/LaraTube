<template>
  <div class="row justify-content-center">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">Register</div>

              <div class="card-body">
                  <form method="POST" action="/register" aria-label="Register">
                      <input type="hidden" name="_token" :value="csrf">

                      <div class="form-group row">
                          <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                          <div class="col-md-6">
                              <input id="name" type="text" class="form-control" name="name" value="" required autofocus>
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

                      <div class="form-group row mb-0">
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">Register</button>
                          </div>
                      </div>
                  </form>
              </div>
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
