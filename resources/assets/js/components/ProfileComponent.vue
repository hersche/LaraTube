<template>

<div><div id="profile" >
  <div id="profileheader" class="text-center" :style="'background-image:url('+user.background+')'">
    <img class='pl-2 pt-1 pb-1' :src='"/"+user.avatar' /><h3 class='ml-2' style=''>{{ user.name }}</h3>
  </div>
  <div v-html="user.bio"></div>
</div>
<h3>{{ $t("User") }}'s {{ $t("medias") }}</h3>
<p><sortSelect></sortSelect></p>
<overview v-bind:currentuser="currentuser" v-bind:medias="usermedias" v-if="usermedias.length>0"></overview>
<h5 v-else>{{ $t("User") }} got no medias</h5>
</div>
</template>

<script>
  import { eventBus } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'
  import SortSelect from './SortSelect'
  export default {
    props: ['medias','baseUrl','user','currentuser'],
  components : {
      'overview': GalleryComponent,
      'sortSelect': SortSelect
  },
  mounted(){
    // loadUserVideos
    // console.log("user-id for get videos"+this.user.id)
    eventBus.$emit('loadUserVideos',this.user.id);
  },
  computed: {

    // a computed getter
    usermedias: function () {
      var filteredMedias = [];
      let that = this;
      $.each( this.medias, function( key, value ) {
        if(value.user.id==that.user.id){
          filteredMedias.push(value);
        }

      });
      return filteredMedias;
    },
  }
  }
</script>
