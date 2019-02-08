<template>
  <div>
    <h3>{{ $t('Medias') }} ({{ filter }})</h3>
    <p>{{ $t('Sort by') }} <sortSelect></sortSelect></p>

    <carousel v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></carousel>
    <p class="btn-block btn-sm btn btn-info" @click="emitLoadMore()" v-if="canloadmore">{{ $t('Load') }} {{ $t('more') }} ({{ medias.length}}/{{ totalMedias }} {{ $t('loaded') }} {{ $t('medias') }})</p>
    <p class="btn-block btn-sm btn btn-danger btn-disabled" v-if="canloadmore==false">All medias loaded</p>
    <SwiperView v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></SwiperView>
    <p class="btn-block btn-sm btn btn-info" @click="emitLoadMore()" v-if="canloadmore">{{ $t('Load') }} {{ $t('more') }} ({{ medias.length}}/{{ totalMedias }} {{ $t('loaded') }} {{ $t('medias') }})</p>
    <gallery v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></gallery>
  </div>
</template>
<script>
  import { eventBus,store } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'
  import Carousel from './Carousel'
  import SwiperView from './SwiperView'
  import SortSelect from './SortSelect'
  export default {
    props: ['baseUrl','canloadmore'],
    methods: {
    },
    computed:{
      loggeduserid(){
        return store.state.loginId
      },
      currentuser(){
        return store.getters.getUserById(store.state.loginId)
      },
      medias(){
        return store.getters.getMediasByTypes()
      },
      filter(){
        return store.state.filterTypes.join()
      },  
      totalMedias(){
        return store.state.totalMedias
      },     
    },
  components : {
      'gallery': GalleryComponent,
      'carousel': Carousel,
      'SwiperView': SwiperView,
      'sortSelect': SortSelect
  },
  mounted(){



  },
  methods: {
    emitLoadMore() {
      eventBus.$emit('loadMore','');
    }
  },
  data(){
    return {
    }
  }
  }
</script>
