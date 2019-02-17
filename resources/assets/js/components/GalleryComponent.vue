<template>
  <div>
    <div class="row text-center text-lg-left" id="profilevideos">

      <v-layout row wrap>
      <v-flex xs12 sm12 md6 lg6 v-for="item in medias" :key="item.id" >
        <singleField class="ml-2"  v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
  </v-flex>
</v-layout>
    </div>
<p class="btn-block btn-sm btn btn-info" @click="emitLoadMore()" v-if="canloadmore">Scroll down or click to load more</p>
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
