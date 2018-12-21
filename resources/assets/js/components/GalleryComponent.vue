<template>
  <div>
    <div class="row text-center text-lg-left" id="profilevideos">
    <div v-for="item in medias"  class="col-lg-4 col-md-4 col-xs-6">
        <div style="min-width: 300px;" class="card">
          <singleField v-bind:item="item"></singleField>
        </div>
    </div>

    </div>
<button class="btn btn-danger" v-if="true" @click="emitLoadMore('')">Load more {{ canLoadMore }}</button>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['medias','currentTitle','swapComponent','baseUrl','canLoadMore'],
    methods: {
      emitLoadMore(title) {
        eventBus.$emit('loadMore',title);
      }
    },
    components : {
        'singleField': SingleGalleryField
    }
  }
</script>
