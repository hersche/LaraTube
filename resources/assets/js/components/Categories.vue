<template>
  <div>
    <div class="row text-center text-lg-left" id="profilevideos">
  <div v-for="cat in categories"  class="">
    <p>{{ cat.title }}</p>
    <div class="row text-center">
    <div v-for="media in cat.medias"  class="col-lg-4 col-md-4 col-xs-6">
          <singleField v-bind:item="media" v-bind:loggeduserid="loggeduserid"></singleField>
    </div>
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
    props: ['medias','baseUrl','canloadmore','loggeduserid','categories'],
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
