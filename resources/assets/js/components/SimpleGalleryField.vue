<template>
  <v-card class="mb-2">
        <router-link :to="'/media/'+item.urlTitle">
          <v-img
            :src="item.poster_source"
            contain
            ><div class="text-center bg-dark text-white" style="opacity:.9; overflow-x:hidden; height: 20%;">{{ item.title }}</div></v-img>
        </router-link>
  </v-card>
</template>
<script>
  import { eventBus,store } from '../eventBus.js';
    import VueMarkdown from 'vue-markdown'
  export default {
    props: ['item'],
    methods: {
    },
    components : {
        VueMarkdown
    },
    computed: {
      loggeduserid: function(){
        return store.state.loginId
      },
      shorteneddescription: function () {
        if(this.item.description.length>83){
          return this.item.description.substring(0,80)+"..."
        }
        return this.item.description;
      },
      shortenedtags: function () {
        if(this.item.tagString.length>60){
          return this.item.tagString.substring(0,57)+"..."
        }
        return this.item.tagString;
      }
    },
  }
</script>
