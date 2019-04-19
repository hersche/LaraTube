<template>
  <div>
      <v-layout row wrap v-if="(simple==undefined|simple==false)">
      <v-flex xs12 sm12 md6 lg6 v-for="item in medias" :key="item.id" >
        <singleField  v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
        </v-flex>
        </v-layout>
        <v-layout row wrap v-if="simple">
        <v-flex xs6 sm4 md3 lg2 v-for="item in medias" :key="item.id" >
        <simpleField  ml-1 v-bind:item="item" v-bind:loggeduserid="loggeduserid"></simpleField>
      </v-flex>
      </v-layout>

<v-btn block @click="emitLoadMore()" v-if="canloadmore">{{ $t('Scroll down or click to load more') }}</v-btn>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  import SimpleGalleryField from './SimpleGalleryField'
  export default {
    props: ['medias','baseUrl','canloadmore','loggeduserid','simple'],
    methods: {
      emitLoadMore() {
        eventBus.$emit('loadMore','');
      }
    },
    components : {
        'singleField': SingleGalleryField,
        'simpleField': SimpleGalleryField
    }
  }
</script>
