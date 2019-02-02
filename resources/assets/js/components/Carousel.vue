<template>

<div id="demo" class="carousel slide" data-ride="carousel">
  <ul class="carousel-indicators" id="carouselIndicatorsBody">
    <li v-for="(item,index) in medias" data-target="#demo" :data-slide-to="index" class="active"></li>
  </ul>
  <div class="carousel-inner" id="carouselInnerBody">
    <div v-for="(item,index) in medias" :class="{ 'active': index === 0 }" class="carousel-item bg-dark">
      <div class="text-center"><img style="width:100%;" :src="item.poster_source" class="" :alt="item.title"></div>
      <div class="carousel-caption" style="color:black; opacity:0.9;">

        <vs-row vs-justify="center">
          <vs-col type="flex" vs-justify="center" vs-align="center" vs-w="10">
            <vs-card>
              <div slot="header">
                <h3>{{ item.title }}</h3>
                <h5>({{ item.created_at_readable }})</h5>
              </div>
              <div>
                <div><VueMarkdown>{{ item.description }}</VueMarkdown></div>
                <div>
                  <span v-for="tag in item.tags" >
                    <router-link class="" :to="'/tags/'+tag.name" >
                      <vs-chip color="primary">
                        <vs-avatar icon="tag" />
                        {{ tag.name }}
                      </vs-chip>
                    </router-link>
                  </span>
                </div>
              </div>
              <div slot="footer">
                <vs-row vs-justify="flex-end" style="flex-flow: row!important;">
                  <vs-button class="mr-1" v-if="loggeduserid==item.user.id|currentuser.admin" icon="settings" :title="$t('Edit')+' '+$t('media')" :to="'/mediaedit/'+item.title"></vs-button>
                  <vs-button icon="play_circle_filled" :title="$t('Play')+' '+$t('media')" :to="'/media/'+item.urlTitle"></vs-button>
                </vs-row>
              </div>
            </vs-card>
          </vs-col>
        </vs-row>


    </div>
  </div>
    <a class="carousel-control-prev bg-dark" href="#demo" data-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </a>
    <a class="carousel-control-next bg-dark" href="#demo" data-slide="next">
      <span class="carousel-control-next-icon"></span>
    </a>
  </div>
</div>

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
