<template>
  <div>
    <!-- this component is unused yeT! -->
    <h3>Medias (sort by {{ selectVal }})</h3>
    <p>Sort by <select @change="sortBy()" id="sortBy" value="created_at" v-model="selectVal"><option value="created_at">Created at</option> <option value="created_at_reverse">Created at (reverse)</option><option value="updated_at">Updated at</option> <option value="updated_at_reverse">Updated at (reverse)</option><option value="title">By title</option><option value="title_reverse">By title (reverse)</option>
      <option value="type">By type</option><option value="type_reverse">By type (reverse)</option><option value="simpleType">By simpletype</option><option value="simpleType_reverse">By simpletype (reverse)</option><option value="comments">By comments</option><option value="comments_reverse">By comments (reverse)</option>
      <option value="likes">By likes</option><option value="likes_reverse">By likes (reverse)</option>
      <option value="dislikes">By dislikes</option><option value="dislikes_reverse">By dislikes (reverse)</option>
    </select></p>
<div id="flick" v-if="medias!=undefined">
        <div v-for="item in medias" class="carousel-cell">
          <h5>{{ item.title }}</h5>
          <p>{{ item.description }}</p>


        </div>
    </div>
      <button @click="previous()">Custom Previous Button</button>
      <button @click="next()">Custom Next Button</button>
      </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import Flickity from 'flickity';
  import 'flickity/dist/flickity.css' //flickity styles
  import FlickityVue from 'vue-flickity';
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore'],
    watch:{
      medias:function(val){
        this.rebuilded=true
        console.log("load flick")
        //let flickityInstance = this.$refs.flickity
      //  flickityInstance.reloadCells()
      if(this.flkty!=undefined){
        this.flkty.destroy();
      }
 this.flktyElement = document.querySelector('#flick');
 this.flkty = new Flickity( this.flktyElement,this.flickityOptions);
      //  this.$refs.flickity.rerender()
        //flickityInstance.destroy();
        //flickityInstance = new Flickity()

      }
    },
    methods: {
      next() {
  this.$refs.flickity.next();
},

previous() {
  this.$refs.flickity.previous();
},
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
      'flickity' : FlickityVue
  },
  mounted(){
    this.$nextTick(function() {
      this.rebuilded=true
      //  this.$refs.flickity.rerender()
    })
    /*this.$nextTick(function () {
      var cs = localStorage.getItem("choosenSort")
      this.selectVal = cs;
      eventBus.$emit('sortBy',cs);
    })*/

  },
  data(){
    return {
      selectVal:"created_at",
      flickityOptions: {
        initialIndex: 0,
        prevNextButtons: true,
        pageDots: false,
        wrapAround: true,
        groupCells: true,
        rebuilded: false,
        flkty: undefined,
        flktyElement: undefined,
        // any options from Flickity can be used
      }
    }
  }
  }
</script>


<style lang="stylus">

.carousel-cell {
  width: 28%;
  height: 200px;
  margin-right: 10px;
  background: #8C8;
  border-radius: 5px;
  counter-increment: carousel-cell;
}

.carousel-cell.is-selected {
  background: #ED2;
}

/* cell number */
.carousel-cell:before {
  display: block;
  text-align: center;
  line-height: 200px;
  font-size: 80px;
  color: white;
}
</style>
