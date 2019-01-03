<template>
  <div>
    <div class="row text-center text-lg-left" id="profilevideos">
    <div v-for="item in medias"  class="col-lg-4 col-md-4 col-xs-6">
        <div class="card">
          <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
        </div>
    </div>

    </div>
<p class="text-center" v-if="canloadmore">Scroll to bottom to load more</p>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['medias','baseUrl','canloadmore','loggeduserid'],
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
