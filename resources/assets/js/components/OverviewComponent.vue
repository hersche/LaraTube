<template>
  <div>
    <h3>Medias (sort by {{ selectVal }})</h3>
    <p>Sort by <select @change="sortBy()" id="sortBy" value="created_at" v-model="selectVal"><option value="created_at">Created at</option> <option value="created_at_reverse">Created at (reverse)</option><option value="updated_at">Updated at</option> <option value="updated_at_reverse">Updated at (reverse)</option><option value="title">By title</option><option value="title_reverse">By title (reverse)</option>
      <option value="type">By type</option><option value="type_reverse">By type (reverse)</option><option value="simpleType">By simpletype</option><option value="simpleType_reverse">By simpletype (reverse)</option><option value="comments">By comments</option><option value="comments_reverse">By comments (reverse)</option>
      <option value="likes">By likes</option><option value="likes_reverse">By likes (reverse)</option>
      <option value="dislikes">By dislikes</option><option value="dislikes_reverse">By dislikes (reverse)</option>
    </select></p>
    <carousel v-bind:medias="medias" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></carousel>
    <gallery v-bind:medias="medias" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></gallery>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'
  import Carousel from './Carousel'
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore'],
    methods: {
      emitRefreshMedias: function() {
        eventBus.$emit('refreshMedias',"");
      },
      emitLoadAllMedias: function() {
        eventBus.$emit('loadAllMedias',"");
      },
      sortBy: function() {
        localStorage.setItem("choosenSort",this.selectVal)
        eventBus.$emit('sortBy',this.selectVal);
      }
    },
  components : {
      'gallery': GalleryComponent,
      'carousel': Carousel
  },
  mounted(){

    this.$nextTick(function () {
      var cs = localStorage.getItem("choosenSort")
      this.selectVal = cs;
      eventBus.$emit('sortBy',cs);
    })

  },
  data(){
    return {
      selectVal:"created_at"
    }
  }
  }
</script>
