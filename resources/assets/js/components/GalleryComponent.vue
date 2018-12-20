<template>
  <div>
    <div class="row text-center text-lg-left" id="profilevideos">
    <div v-for="item in medias"  class="col-lg-4 col-md-4 col-xs-6">
        <div style="min-width: 300px;" class="card">
          <router-link :to="'/media/'+item.title" class="d-block h-100">
            <img class="card-img-top" :src="item.poster_source" alt="">
            <div class="card-img-overlay">
              <h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">{{ item.title }}</h4>
            </div>
          </router-link>
        </div>
    </div>

    </div>
<button class="btn btn-danger" @click="emitLoadMore('')">Load more</button>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['medias','currentTitle','swapComponent','baseUrl'],
    methods: {
      emitLoadMore(title) {
        eventBus.$emit('loadMore',title);
      }
    }
  }
</script>
