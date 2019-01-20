<template>
  <div>
    <h3>{{ $t('Medias') }}</h3>
    <p>{{ $t('Sort by') }} <sortSelect></sortSelect></p>

    <carousel v-bind:medias="xmedias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></carousel>
    <flick v-bind:medias="xmedias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></flick>
    <gallery v-bind:medias="xmedias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></gallery>
  </div>
</template>
<script>
  import { eventBus,store } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'
  import Carousel from './Carousel'
  import Flick from './SwiperView'
  import SortSelect from './SortSelect'
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore','currentuser'],
    methods: {
    },
    computed:{
      xmedias(){
        return store.getters.getMediasByTypes()
      }
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
