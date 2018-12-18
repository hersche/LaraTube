<template>

<div><div id="profile" >
  <div id="profileheader" :style="'background-image:url('+user.background+')'">
    <img class='pl-2 pt-1 pb-1' :src='"/"+user.avatar' /><span><h3 class='ml-2' style='display: inline;'>{{ user.name }}</h3></span>
  </div></div>

<overview v-bind:medias="medias"></overview></div>
</template>

<script>
  import { eventBus } from '../eventBus.js';
  import OverviewComponent from './OverviewComponent'
  export default {
    props: ['medias','baseUrl','user'],
  components : {
      'overview': OverviewComponent
  }
  }
</script>
