<template>
  <div>
    <div class="row text-center text-lg-left" id="profilevideos">
    <div v-for="item in medias"  class="col-lg-4 col-md-4 col-xs-6">
        <div style="min-width: 300px;" class="card">
          <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
        </div>
    </div>

    </div>
<button class="btn btn-danger" v-if="canloadmore" @click="emitLoadMore()">Load more</button>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['medias','currentTitle','baseUrl','canloadmore','loggeduserid'],
    methods: {
      emitLoadMore() {
        eventBus.$emit('loadMore','');
      }
    },
    components : {
        'singleField': SingleGalleryField
    }
  }
</script>
