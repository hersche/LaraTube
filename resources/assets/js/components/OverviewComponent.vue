<template>
  <div>
    <h3>Medias (sort by {{ selectVal }})</h3>
    <p>Sort by <select @change="sortBy()" id="sortBy" value="created_at" v-model="selectVal"><option value="created_at">Created at</option> <option value="created_at_reverse">Created at (reverse)</option><option value="updated_at">Updated at</option> <option value="updated_at_reverse">Updated at (reverse)</option><option value="title">By title</option><option value="title_reverse">By title (reverse)</option>
      <option value="type">By type</option><option value="type_reverse">By type (reverse)</option><option value="simpleType">By simpletype</option><option value="simpleType_reverse">By simpletype (reverse)</option><option value="comments">By comments</option><option value="comments_reverse">By comments (reverse)</option>
      <option value="likes">By likes</option><option value="likes_reverse">By likes (reverse)</option>
      <option value="dislikes">By dislikes</option><option value="dislikes_reverse">By dislikes (reverse)</option>
    </select></p>
    <div id="demo" class="carousel slide" data-ride="carousel">
      <ul class="carousel-indicators" id="carouselIndicatorsBody">
        <li v-for="(item,index) in medias" v-if="index<3" data-target="#demo" :data-slide-to="index" class="active"></li>
      </ul>
      <div class="carousel-inner" id="carouselInnerBody">
        <div v-for="(item,index) in medias" :class="{ 'active': index === 0 }" class="carousel-item bg-dark">
          <div class="text-center"><img :src="item.poster_source" class="" :alt="item.title"></div>
          <div class="carousel-caption" style="color: black; background: lightgrey; opacity:0.9;">
            <h3>{{ item.title }} ({{ item.created_at_readable }})</h3>
            <p>{{ item.description }}<span class="float-right"><router-link class="btn btn-primary mr-2" :to="'/media/'+item.title">Play</router-link></span></p>
            <div class="card-footer"><span v-for="tag in item.tags" >
              <router-link class="" :to="'/tags/'+tag.name" >    <vs-chip color="primary">
      <vs-avatar icon="tag" />
      {{ tag.name }}
    </vs-chip></router-link>
            </span><span v-if="loggeduserid==item.user.id" class="float-right"><router-link class="btn btn-sm btn-info float-right" :to="'/mediaedit/'+item.title">Edit</router-link></span></div>
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
    <gallery v-bind:medias="medias" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></gallery>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import GalleryComponent from './GalleryComponent'
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore'],
    methods: {
      emitRefreshMedias: function() {
        eventBus.$emit('refreshMedias',"");
      },
      emitLoadAllMedias: function() {
        eventBus.$emit('loadAllMedias',"");
      },
      sortBy: function() {
        localStorage.setItem("choosenSort",this.selectVal)
        eventBus.$emit('sortBy',this.selectVal);
      }
    },
  components : {
      'gallery': GalleryComponent
  },
  mounted(){

    this.$nextTick(function () {
      var cs = localStorage.getItem("choosenSort")
      this.selectVal = cs;
      eventBus.$emit('sortBy',cs);
    })

  },
  data(){
    return {
      selectVal:"created_at"
    }
  }
  }
</script>
