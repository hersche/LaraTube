<template>
  <div>
    <h3>Newest videos</h3>
    <div id="demo" class="carousel slide" data-ride="carousel">
      <ul class="carousel-indicators" id="carouselIndicatorsBody">
        <li v-for="(item,index) in medias" data-target="#demo" :data-slide-to="index" class="active"></li>
      </ul>
      <div class="carousel-inner" id="carouselInnerBody">
        <div v-for="(item,index) in medias"  v-if="index==0" class="carousel-item bg-dark active">
          <img :src="item.poster_source" :alt="item.title">
          <div class="carousel-caption" style="color: black; background: lightgrey; opacity:0.9;">
            <h3>{{ item.title }} ({{ item.created_at_readable }})</h3>
            <p>{{ item.description }}<span class="float-right"><a @click="emitPlayClicked(item.title)" class="btn btn-primary mr-2" >Play</a></span></p>
          </div>
        </div>
        <div v-for="(item,index) in medias"  v-else class="carousel-item bg-dark">
          <img :src="item.poster_source" :alt="item.title">
          <div class="carousel-caption" style="color: black; background: lightgrey; opacity:0.9;">
            <h3>{{ item.title }} ({{ item.created_at_readable }})</h3>
            <p>{{ item.description }}<span class="float-right"><a @click="emitPlayClicked(item.title)" class="btn btn-primary mr-2" >Play</a></span></p>
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
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['medias','swapComponent','baseUrl'],
    methods: {
      emitPlayClicked(title) {
        // Send the event on a channel (i-got-clicked) with a payload (the click count.)
        eventBus.$emit('overviewPlayClick', title);
      }
    }
  }
</script>
