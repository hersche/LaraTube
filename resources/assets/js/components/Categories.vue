<template>
    <div class="row text-center text-lg-left" id="profilevideos">
  <vs-collapse>
  <vs-collapse-item v-for="cat in categories" :key="cat.id" class="">
      <div slot="header">
        {{ cat.title }}
      </div>

    <p>{{ cat.description }}</p>
    <div class="row text-center">
    <div v-for="media in cat.medias"  class="col-lg-3 col-md-3 col-xs-6">
          <singleField v-bind:item="media" v-bind:loggeduserid="loggeduserid"></singleField>
    </div>
  </div>
  </vs-collapse-item>
  </vs-collapse>
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
