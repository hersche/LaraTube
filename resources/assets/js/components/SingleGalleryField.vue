<template>
        <div style="min-width: 300px;" class="card hide-on-hover mb-1">
          <router-link :to="'/media/'+item.title" class="d-block h-100 ">
            <img class="card-img-top" :src="item.poster_source" alt="">
            <div class="card-img-overlay  content text-light" style="opacity: 0.875; color: black;">
              <div class=" card-header " style="padding-bottom:0px;">
                <h4 class="card-title text-center " ><span class="bg-secondary sgfText">{{ item.title }}</span></h4>
              </div>
            <div class=" card-body " style="padding-top:0px;">

              <div ><span class="bg-secondary sgfText">{{ shorteneddescription }}</span></div>
              <div><span class="bg-secondary sgfText">{{ item.duration }} - {{ item.comments.length }} comments</span><span class="bg-secondary sgfText float-right">{{ item.type }}</span></div>
              <footer><span class="float-left bg-dark sgfText text-info">Tags: {{ shortenedtags }}</span>
              <span v-if="loggeduserid==item.user.id" class="float-right bg-secondary sgfText">This is your media</span></footer>

            </div>
          </div>

          </router-link>
        </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['item','loggeduserid'],
    methods: {
      emitRefreshMedias: function() {
        eventBus.$emit('refreshMedias',"");
      },
    },
    computed: {
      // a computed getter
      shorteneddescription: function () {
        if(this.item.description.length>93){
          return this.item.description.substring(0,90)+"..."
        }
        return this.item.description;
      },
      shortenedtags: function () {
        if(this.item.tagString.length>80){
          return this.item.tagString.substring(0,77)+"..."
        }
        return this.item.tagString;
      }
    },
  }
</script>
