<template>

<div>
  <div v-if="tagenabled!=undefined&tagenabled!=false" >        
        <v-text-field
          :label="$t('Filter tags')"
          v-model="filterTags"
          ></v-text-field>
      <div v-for="(item,index) in tags"  @click="changeCheck(item.name)" v-if="item.name.toLowerCase().indexOf(filterTags.toLowerCase())>-1" class="btn btn-primary ml-1 mb-1">
        <input type="checkbox" class="d-none"  @click="checkTag(item.name)" :id="'tagId'+item.name" v-model="selectedTags" :value="item" />
        {{ item.name }} ({{item.count}}x)
      </div>
</div>
<sortSelect></sortSelect>
      <div class="row text-center text-lg-left" id="profilevideos">
        <div v-for="(item1,index) in medias" v-if="filterMedia(item1,selectedTags)==true" class="col-lg-4 col-md-4 col-xs-6">
          <singleField v-bind:item="item1"></singleField>
        </div>

      </div>
      <!--  <div><button class="btn btn-danger" v-if="canloadmore" @click="emitLoadMore()">Load more</button></div> -->
</div>

</template>

<script>
  import { eventBus, store } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  import SortSelect from './SortSelect'
  export default {
    name: 'tags',
    props: ['medias','baseUrl','canloadmore','tagenabled'],
    data(){
      return {
        selectedTags:[],
        filterTags:'',
        selectVal:"created_at",
      }
    },
    computed: {
      currentuser(){
        return store.getters.getUserById(store.state.loginId)
      },
      loggeduserid(){
        return store.state.loginId
      },
      tags:function(){
        return store.state.tags
      }
    },
    mounted(){
      if(this.$route.params.tagName!=''){
        this.changeCheck(this.$route.params.tagName);
      }
      this.$nextTick(function () {
        var cs = localStorage.getItem("choosenSort")
        this.selectVal = cs;
        eventBus.$emit('sortBy',cs);
      })
    },
    methods: {
      emitLoadMore() {
        eventBus.$emit('loadMore','');
      },
      checkTag(id) {
        eventBus.$emit('checkTag',id);
      },
      sortBy: function() {
        localStorage.setItem("choosenSort",this.selectVal)
        eventBus.$emit('sortBy',this.selectVal);
      },
      changeCheck(id) {
        $("#tagId"+id).trigger('click');
        if($("#tagId"+id).is(':checked')){
          //$("#tagId"+id).prop('checked', false);
          $("#tagId"+id).parent().removeClass("btn-primary")
          $("#tagId"+id).parent().addClass("btn-success")
        } else {
          //$("#tagId"+id).prop('checked', true);
          $("#tagId"+id).parent().addClass("btn-primary")
          $("#tagId"+id).parent().removeClass("btn-success")
        }
        //eventBus.$emit('checkTag',id);

      },
      filterMedia(media, sTags) {
        var returnVal = false;
        if(this.tagenabled==false){
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
        'singleField': SingleGalleryField,
        'sortSelect': SortSelect
    }

  }
</script>
