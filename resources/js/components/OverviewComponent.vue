<template>
  <div>
    <h1 class="text-center">{{ $t('Medias') }} ({{ filter }})</h1>
    <div>
      <v-container fluid grid-list-xl>
        <v-layout wrap align-center>
          <v-flex xs12 sm6 d-flex>
      
      <sortSelect></sortSelect>
      
    </v-flex>
    <v-flex xs12 sm6 d-flex>

<v-switch v-model="carouselView" :label="$t('Carousel')+' '+$t('view')"></v-switch>
<v-switch v-model="swiperView" :label="$t('Swiper')+' '+$t('view')"></v-switch>
<v-switch v-model="galleryView" :label="$t('Gallery')+' '+$t('view')"></v-switch>
</v-flex>
    </v-layout>
    </v-container>
    
    </div>
    <div v-if="carouselView">
    <h2 class="text-center">{{ $t('Carousel') }}</h2>
    <carousel v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></carousel>
    <v-btn block color="blue" @click="emitLoadMore()" v-if="canloadmore">{{ $t('Load') }} {{ $t('more') }} ({{ medias.length}}/{{ totalMedias }} {{ $t('loaded') }} {{ $t('medias') }})</v-btn>
  </div>
  <div v-if="swiperView">
    <p class="btn-block btn-sm btn btn-danger btn-disabled" v-if="canloadmore==false">All medias loaded</p>
    <h2 class="text-center">{{ $t('Swiper') }}</h2>
    <SwiperView v-bind:medias="medias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></SwiperView>
    <v-btn block color="blue" @click="emitLoadMore()" v-if="canloadmore">{{ $t('Load') }} {{ $t('more') }} ({{ medias.length}}/{{ totalMedias }} {{ $t('loaded') }} {{ $t('medias') }})</v-btn>
  </div>
  <div v-if="galleryView">
    <h2 class="text-center">{{ $t('Gallery') }}</h2><v-switch v-model="gallerySimpleMode" :label="$t('Simple')+' '+$t('view')"></v-switch>
    <gallery v-bind:simple="gallerySimpleMode" v-bind:medias="medias" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></gallery>
  </div>
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
      loggeduserid:function(){
        return store.state.loginId
      },
      currentuser:function(){
        return store.getters.getUserById(store.state.loginId)
      },
      medias:function(){
        return store.getters.getMediasByTypes()
      },
      filter:function(){
        return store.state.filterTypes.join()
      },  
      totalMedias:function(){
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
    if(localStorage.getItem("galleryView")!=undefined&&localStorage.getItem("galleryView")!='true'){
      this.galleryView = false
    }
    if(localStorage.getItem("carouselView")!=undefined&&localStorage.getItem("carouselView")!='true'){
      this.carouselView = false
    }
    if(localStorage.getItem("swiperView")!=undefined&&localStorage.getItem("swiperView")!='true'){
      this.swiperView = false
    }

  },
  methods: {
    emitLoadMore() {
      eventBus.$emit('loadMore','');
    }
  },
  watch:{
    galleryView:function(val){
      localStorage.setItem("galleryView",val)
    },
    carouselView:function(val){
      localStorage.setItem("carouselView",val)
    },
    swiperView:function(val){
      localStorage.setItem("swiperView",val)
      
    },    
  },
  data(){
    return {
      gallerySimpleMode:true,
      galleryView:true,
      carouselView:true,
      swiperView:true
    }
  }
  }
</script>
