<template>
  <v-card class="mb-2"><div class="subheading text-center">{{ item.title }}</div>
    <v-layout>
      <v-flex xs5 style="height:160px;">
        <v-img
          :src="item.poster_source"
          contain
        ></v-img>
        <div class="pl-1" v-if="item.duration!='0'">{{ item.duration }}</div>
        <div class="pl-1">{{ item.created_at_readable }}</div>
        <div class="pl-1">{{ item.type }}</div>
      </v-flex>
      <v-flex xs6 sm7 md7 lg7>
        <v-card-title primary-title style="height: 160px; overflow-x:auto; padding-top: 0px;">
          <div>
            
            <VueMarkdown :source="item.description"></VueMarkdown>

          </div>
        </v-card-title>
      </v-flex>
    </v-layout>
    <v-card-text>
      <div>      <span v-for="tag in item.tags" >
              <router-link class="" :to="'/tags/'+tag.name" >
                <v-chip class="small" small>
                  <v-avatar class="teal">
                    <v-icon>tag</v-icon>
                  </v-avatar>
                  {{ tag.name }}
                </v-chip>
              </router-link>
            </span>
          </div>  
    </v-card-text>
    <v-card-actions class="">

      <v-btn :to="'/media/'+item.urlTitle">
      <v-icon class="mr-1">play_circle_filled</v-icon>
      <span class="hidden-sm-and-down" >{{ $t('Play') }}</span>
    </v-btn>
      <v-spacer></v-spacer>
      <v-badge class="ml-3 small" left color="blue" overlap>
        <span slot="badge" class="small">{{ item.comments.length }}</span>
      <v-icon>comment</v-icon>
    </v-badge>
    
    <v-badge class="ml-3 small" left color="green" overlap>
      <span slot="badge" class="small">{{ item.likes }}</span>
    <v-icon>thumb_up</v-icon>
  </v-badge>
  <v-badge class="ml-3 small" left color="red" overlap>
    <span slot="badge" class="small">{{ item.dislikes }}</span>
  <v-icon >thumb_down</v-icon>
  </v-badge>
    </v-card-actions>
  </v-card>
        <!-- <div :id="item.id+'scard'" class="card hide-on-hover mb-1">
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

            <!-- </div>
          </div>
          </router-link>
          <b-tooltip :target="item.id+'scard'" placement="top">
            <h5>{{ item.title}}</h5> <p><VueMarkdown :source="item.description"></VueMarkdown></p>
          </b-tooltip>
        </div> -->
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
