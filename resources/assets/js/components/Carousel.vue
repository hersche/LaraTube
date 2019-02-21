<template>

  <v-carousel style="height: 100vh; " class="xs12 sm12 md12 lg12"
  interval="12000"
  >
    <v-carousel-item
      v-for="(item,i) in medias"
      :key="i"
      :src="item.poster_source"
    >
    <v-layout row justify-center>
    <v-flex xs10 sm10 md9 lg8
    align-center
    >
    <v-card
  class="mt-4 "
  cycle="false"
  dark
  style="opacity: 0.9; z-index:999999; max-height: 60vh; overflow-x: auto;"
>

  <v-card-title>
    <v-icon
      large
      left
    >
      perm_media
    </v-icon>
    <span class="title text-center font-weight-bold headline">{{ item.title }}</span>
  </v-card-title>

  <v-card-text class="">
    <VueMarkdown :source="item.description"></VueMarkdown>
    <div style="overflow-y:auto;">
      <span v-for="tag in item.tags" >
        <router-link class="" :to="'/tags/'+tag.name" >
          <v-chip small>
            <v-avatar class="teal">
              <v-icon>tag</v-icon>
            </v-avatar>
            {{ tag.name }}
          </v-chip>
        </router-link>
      </span>
    </div>
  </v-card-text>

  <v-card-actions>
    <v-layout
      row wrap hidden-sm-and-down
    >
    <v-btn :to="'/media/'+item.urlTitle">
    <v-icon class="mr-1">play_circle_filled</v-icon>
    <span class="subheading hidden-sm-and-down" >{{ $t('Play') }}</span>
  </v-btn>
      <v-btn hidden-sm-and-down :to="'/mediaedit/'+item.title" v-if="loggeduserid==item.user.id|currentuser.admin">
      <v-icon class="mr-1">settings</v-icon>
      <span class="subheading mr-2 hidden-sm-and-down" >{{ $t('Edit') }}</span>
    </v-btn>
    

    </v-layout>
    <v-spacer></v-spacer>
    <v-layout 
    align-end
    justify-end
    wrap
    xs12 sm4
     >
    <v-list-tile class="">
      <router-link :to="'/profile/'+item.user.id">
      <v-list-tile-avatar color="grey darken-3" >
        <v-img
          class="elevation-6"
          :src="item.user.avatar"
        ></v-img>
      </v-list-tile-avatar>
    </router-link>
    <v-badge class="ml-1 small" left color="blue" overlap>
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


    </v-list-tile>
  </v-layout>

  </v-card-actions>
</v-card>
</v-flex>
</v-layout>
    </v-carousel-item>
  </v-carousel>

</template>

<script>
  import { eventBus } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'
  import VueMarkdown from 'vue-markdown'
  export default {
    props: ['medias','loggeduserid','currentuser'],
    methods: {

    },
  components : {
      'gallery': GalleryComponent,
      VueMarkdown
  },
  mounted(){

  },
  data(){
    return {
    }
  }
  }
</script>

<style>
.v-carousel__prev, .v-carousel__next{
  background-color: black;
  opacity: 0.5;
  border-radius: 20px;
}
</style>
