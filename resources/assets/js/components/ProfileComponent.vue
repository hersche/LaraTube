<template>

<div><div id="profile" >
  <div id="profileheader" class="text-center" :style="'background-image:url('+user.background+')'">
    <img class='pl-2 pt-1 pb-1' :src='"/"+user.avatar' /><h3 class='ml-2' style=''>{{ user.name }}</h3>
  </div>
  <div>{{ user.bio }}</div>
</div>

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
