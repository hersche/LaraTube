<template>
  <div>
    <h3>{{ $t('Medias') }}</h3>
    <p>{{ $t('Sort by') }} <sortSelect></sortSelect></p>
    <!-- <flick v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></flick> -->
    <carousel v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></carousel>
    <gallery v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></gallery>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'
  import Carousel from './Carousel'
  import Flick from './FlickityCarousel'
  import SortSelect from './SortSelect'
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore','currentuser'],
    methods: {
    },
  components : {
      'gallery': GalleryComponent,
      'carousel': Carousel,
      'flick': Flick,
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
