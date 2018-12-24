<template>

<div>
      <div><input type="text" v-model="filterTags" placeholder="Filter tags"><button class="btn btn-danger" v-if="canloadmore" @click="emitLoadMore()">Load more</button></div>
      <div class="btn btn-success">
        <input type="checkbox" checked id="specialAllTag" />
        All
      </div>
      <div v-for="(item,index) in tags" v-if="item.name.toLowerCase().indexOf(filterTags.toLowerCase())>-1" class="btn btn-primary">
        <input type="checkbox" @click="checkTag(item.name)" v-model="selectedTags" :value="item" />
        {{ item.name }} ({{item.count}}x)
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
    props: ['medias','baseUrl','user','tags','canloadmore'],
    data(){
      return {
        selectedTags:[],
        filterTags:''
      }
    },
    methods: {
      emitLoadMore() {
        eventBus.$emit('loadMore','');
      },
      checkTag(tagName) {
        eventBus.$emit('checkTag',tagName);
      },
      filterMedia(media, sTags) {
        var returnVal = false;
        if($("#specialAllTag").is(":checked")){
          returnVal=true;
        } else {
        sTags.forEach( function(item, index) {
          media.tags.forEach( function(mediaTag, index2) {
          if(mediaTag.id==item.id){
            returnVal = true;
          }
          });
        });

      }
      return returnVal;
      }
    },
    components : {
        'singleField': SingleGalleryField
    }

  }
</script>
