<template>
  <div>
    <h3>Medias</h3>
    <p>Sort by <sortSelect></sortSelect></p>
    <carousel v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></carousel>
    <gallery v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></gallery>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'
  import Carousel from './Carousel'
  import SortSelect from './SortSelect'
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore','currentuser'],
    methods: {
    },
  components : {
      'gallery': GalleryComponent,
      'carousel': Carousel,
      'sortSelect': SortSelect
  },
  mounted(){



  },
  data(){
    return {
    }
  }
  }
</script>
