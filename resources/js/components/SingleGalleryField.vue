<template>
  <v-card class="mb-2"><div class="subheading text-center">{{ item.title }}</div>
    <v-layout>
      <v-flex xs5 style="height:160px; overflow-x:auto;">
        <router-link :to="'/media/'+item.urlTitle">
          <v-img
            :src="item.poster_source"
            contain
            ></v-img>
        </router-link>
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
      <router-link :to="'/profile/'+item.user.id" class="ml-1">
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

    </v-card-actions>
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
