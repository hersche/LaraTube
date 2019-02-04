<template>
        <div :id="item.id+'scard'" class="card hide-on-hover mb-1">
          <router-link  :to="'/media/'+item.urlTitle" class="d-block h-100">

            <img class="card-img-top" :src="item.poster_source" alt="">

            <div class="card-img-overlay text-light" style="padding:0;opacity: 0.875; color: black;">
              <div class="pr-sm-0 pl-sm-0 ml-sm-0 mr-sm-0 card-header " style="padding-bottom:0px;/* overflow-x: hidden; */">
                <p class="card-title text-center text-xs-left pl-sm-0 pr-sm-0 pl-md-1 pr-md-1 " ><span style="/*white-space: nowrap;*/" class="pl-sm-0 pr-sm-0 pl-md-1 pr-md-1 mr-sm-0 ml-sm-0 bg-secondary sgfText">{{ item.title }}</span></p>
              </div>
            <div class=" card-body pl-md-1 pr-md-1 pl-sm-0 pr-sm-0" style="padding-top:0px;">
              <div class="d-none d-lg-block"><span class="bg-secondary sgfText"><span v-if="item.duration!=0">{{ item.duration }} -</span> {{ item.comments.length }} {{ $t('comments') }}</span><span class="bg-secondary sgfText float-right">{{ item.type }}</span></div>
              <!--<footer class="d-none d-xl-block"><span class="float-left bg-dark sgfText text-info">{{ shortenedtags }}</span>
               <span v-if="loggeduserid==item.user.id" class="d-none d-xl-block float-right bg-secondary sgfText">This is your media</span></footer> -->

            </div>
          </div>
          </router-link>
          <b-tooltip :target="item.id+'scard'" placement="top">
            <h5>{{ item.title}}</h5> <p><VueMarkdown :source="item.description"></VueMarkdown></p>
          </b-tooltip>
        </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
    import VueMarkdown from 'vue-markdown'
  export default {
    props: ['item','loggeduserid'],
    methods: {
    },
    components : {
        VueMarkdown
    },
    computed: {
      // a computed getter
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
