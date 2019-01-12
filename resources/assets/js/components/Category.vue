<template>
<div v-if="theCat!=undefined" >
<h2>{{ theCat.title }}</h2>
<p>{{ theCat.description }}</p>
<p v-if="theCat.children.length>0">Subs</p>
<cat v-bind:catlevel="Number(catlevel)+1" v-bind:categories="theCat.children" v-bind:currentuser="currentuser" v-if="theCat.children.length>0"></cat>
<div class="row text-center">
  <div v-for="media in theCat.medias"  class="col-lg-3 col-md-3 col-xs-6">
    <singleField v-bind:item="media" v-bind:loggeduserid="loggeduserid"></singleField>
  </div>
</div>
</div>
</template>

<script>
import { eventBus } from '../eventBus.js';
import SingleGalleryField from './SingleGalleryField'
import Categories from './Categories'
export default {
  props: ['medias','baseUrl','canloadmore','loggeduserid','categories','currentuser'],
  name: 'cat',
  mounted(){
    this.theCat = this.getCurrentCategory();
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
          }
        }
        if(val.urlTitle==encodeURIComponent(that.$route.params.currentCat)){
          theC = val;
        }
      });
      return theC;
    }
  },
  components : {
      'singleField': SingleGalleryField,
      'cat' : Categories
  }
}
</script>
