<template>

<div>
      <div v-for="(item,index) in tags" class="btn btn-primary">
        <input type="checkbox" v-model="selectedTags" :value="item" />
        {{ item.name }}
      </div>
      <div class="row text-center text-lg-left" id="profilevideos">
        <div v-for="(item1,index) in medias" v-if="filterMedia(item1,selectedTags)==true" class="col-lg-4 col-md-4 col-xs-6">
          <singleField v-bind:item="item1"></singleField>
        </div>
      </div>
</div>

</template>

<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    name: 'tags',
    props: ['medias','baseUrl','user','tags'],
    data(){
      return {
        selectedTags:[]
      }
    },
    methods: {
      filterMedia(media, sTags) {
        console.log("Start if")
        var returnVal = false;
        sTags.forEach( function(item, index) {
          media.tags.forEach( function(mediaTag, index2) {
          if(mediaTag.id==item.id){
            console.log(mediaTag.id);
            returnVal = true;
          }
          });
        });
        return returnVal;
      }
    },
    components : {
        'singleField': SingleGalleryField
    }

  }
</script>
