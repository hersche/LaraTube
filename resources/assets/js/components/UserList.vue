<template>
        <div class="row">

          <div v-for="(item,index) in users" style="max-height: 200px; overflow-y: auto"  class="col-lg-4 col-md-4 col-xs-6">
            <b-card overlay :title="item.name"
              :img-src="item.background"
              img-alt="Image"
              img-top
              tag="article"
              :style="'max-width: 20rem; max-height: 300px; overflow-y: auto; background-url: url('+item.background+');'"
              class="mb-2">

              <p class="card-text text-center">
                <router-link :to="'/profile/'+item.id"><img :src="item.avatar" height="70px" /></router-link>
                <p>{{ item.bio }}</p>
                <router-link class="btn btn-primary" :to="'/profile/'+item.id">Go to profile</router-link>
              </p>

            </b-card>
          </div>

        </div>
</template>
<script>
  import { eventBus, store } from '../eventBus.js';
  export default {
    props: ['users'],
    computed: {
      currentuser(){
        return store.getters.getUserById(store.state.loginId)
      },
      loggeduserid(){
        return store.state.loginId
      },
    },
  }
</script>
