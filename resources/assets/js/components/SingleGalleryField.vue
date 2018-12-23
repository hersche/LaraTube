<template>
        <div style="min-width: 300px;" class="card hide-on-hover">
          <router-link :to="'/media/'+item.title" class="d-block h-100 ">
            <img class="card-img-top" :src="item.poster_source" alt="">
            <div class="card-img-overlay bg-secondary content"  style="opacity: 0.9; color: black;">
              <h4 class="card-title text-center" >{{ item.title }}</h4>
              <p>{{ loggeduserid }} {{ item.description }}</p>

              <span v-if="loggeduserid==item.user.id" class="float-right">Owner!!</span>
            </div>
          </router-link>
        </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['item','loggeduserid']
  }
</script>
