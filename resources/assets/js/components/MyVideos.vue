<template>
  <div>
    <h3>My Videos</h3>
    <div class="row text-center text-lg-left" id="profilevideos">
    <div v-for="item in medias" v-if="item.user_id==loggeduserid"  class="col-lg-4 col-md-4 col-xs-6">
        <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
    </div>

    </div>
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
    mounted(){
      eventBus.$emit('loadUserVideos',this.loggeduserid);
    },
    components : {
        'singleField': SingleGalleryField
    }
  }
</script>
