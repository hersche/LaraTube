<template>

<div v-if="currentuser!=undefined">
  <div id="profile" >
    <div id="profileheader" class="text-center" :style="'background-image:url('+currentuser.background+')'">
      <img class='pl-2 pt-1 pb-1' :src='"/"+currentuser.avatar' />
      <h3 class='ml-2' style=''>{{ currentuser.name }}</h3>
    </div>
    <VueMarkdown :source="currentuser.bio"></VueMarkdown>
  </div>
  <h3>{{ $t("User") }}'s {{ $t("medias") }}</h3>
  <p>
    <sortSelect></sortSelect>
  </p>
  <overview v-bind:currentuser="currentuser" v-bind:medias="usermedias" v-if="usermedias.length>0"></overview>
  <h5 v-else>{{ $t("User") }} got no {{ $t('medias') }}</h5>
</div>
</template>

<script>
  import { eventBus, store } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'

  import SortSelect from './SortSelect'
  import VueMarkdown from 'vue-markdown'
  export default {
    props: ['baseUrl'],
  components : {
      'overview': GalleryComponent,
      'sortSelect': SortSelect,
      VueMarkdown,
      
  },
  mounted(){
    eventBus.$emit("loadUserVideos",this.currentuser.id)
  },
  computed: {
    currentuser: function(){
      var u = store.getters.getUserById(Number(this.$route.params.profileId))
      if(u!=undefined){
        
      }
      return u
    },
    // a computed getter
    usermedias: function () {
      return store.getters.getMediasByUserId(store.state.loginId)
    },
  }
  }
</script>
