<template>
<div v-if="theCat!=undefined" >
<h2>{{ theCat.title }}</h2>
<p><VueMarkdown :source="theCat.description"></VueMarkdown></p>
<p v-if="theCat.children.length>0">Subcategories</p>
<p v-for="subcat in theCat.children" v-if="theCat.children.length>0"><router-link :to="'/category/'+subcat.urlTitle">{{ subcat.title }}</router-link></p>
<h5>{{ $t("Medias") }}</h5>
<div class="row text-center">
  <div v-for="media in theCat.medias"  class="col-lg-3 col-md-3 col-xs-6">
    <singleField v-bind:item="media" v-bind:loggeduserid="loggeduserid"></singleField>
  </div>
</div>
</div>
</template>

<script>
import { eventBus,store } from '../eventBus.js';
import SingleGalleryField from './SingleGalleryField'
import Categories from './Categories'
import VueMarkdown from 'vue-markdown'
export default {
  props: ['baseUrl','canloadmore','loggeduserid','categories','currentuser'],
  name: 'cat',
  mounted(){
    this.theCat = this.getCurrentCategory();
  },
  computed:{
    medias:function(){
      return store.getters.getMediasByTypes()
    }
  },
  data(){
    return {
      theCat:undefined,
      catlevel:0
    }
  },
  methods: {
    getCurrentCategory(data=undefined) {
      // `this` points to the vm instance
      let that = this;
      var theC = undefined
      var idata = this.categories
      if(data!=undefined){
        idata = data
      }
      idata.forEach(function(val,key){
        if(val.children.length>0){
          var tmpResult = that.getCurrentCategory(val.children)
          if(tmpResult!=undefined){
            theC = tmpResult
            eventBus.$emit('getMediasByCatId',theC.id);
          }
        }
        if(val.urlTitle==encodeURIComponent(that.$route.params.currentCat)){
          theC = val;
          eventBus.$emit('getMediasByCatId',theC.id);
        }
      });
      return theC;
    }
  },
  components : {
      'singleField': SingleGalleryField,
      'cat' : Categories,
      VueMarkdown
  }
}
</script>
