<template>
  <div>
    <h3>Newest videos</h3>
    <p><button @click="emitRefreshMedias()">Refresh</button></p>
    <div id="demo" class="carousel slide" data-ride="carousel">
      <ul class="carousel-indicators" id="carouselIndicatorsBody">
        <li v-for="(item,index) in medias" v-if="index<3" data-target="#demo" :data-slide-to="index" class="active"></li>
      </ul>
      <div class="carousel-inner" id="carouselInnerBody">
        <div v-for="(item,index) in medias" :class="{ 'active': index === 0 }" class="carousel-item bg-dark">
          <img :src="item.poster_source" :alt="item.title">
          <div class="carousel-caption" style="color: black; background: lightgrey; opacity:0.9;">
            <h3>{{ item.title }} ({{ item.created_at_readable }})</h3>
            <p>{{ item.description }}<span class="float-right"><router-link class="btn btn-primary mr-2" :to="'/media/'+item.title">Play</router-link></span></p>
            <div class="card-footer">Tags: <span v-for="tag in item.tags" ><router-link class="btn btn-xs btn-info mr-1" :to="'/tags/'+tag.name" >{{ tag.name }} ({{ tag.count }}x)</router-link></span></div>
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
    <gallery v-bind:medias="medias"></gallery>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'
  export default {
    props: ['medias','baseUrl'],
    methods: {
      emitRefreshMedias: function() {
        eventBus.$emit('refreshMedias',"");
      }
    },
  components : {
      'gallery': GalleryComponent
  }
  }
</script>
