<template>

  <v-select
  v-model="selectVal"
  @change="sortBy()"
  attach
  :items="[
  {text:$t('Created at'),value:'created_at'},
  {text:$t('Created at')+' '+$t('reverse'),value:'created_at_reverse'},
  {text:$t('Updated at'),value:'updated_at'},
  {text:$t('Updated at') +' '+$t('reverse'),value:'updated_at_reverse'},
  {text:$t('Title'),value:'title'},
  {text:$t('Title')+' '+$t('reverse'),value:'title_reverse'},
  {text:$t('Title'),value:'title'},
  {text:$t('Title')+' '+$t('reverse'),value:'title_reverse'},
  {text:$t('Type'),value:'type'},
  {text:$t('Type')+' '+$t('reverse'),value:'type_reverse'},
  {text:$t('SimpleType'),value:'simpleType'},
  {text:$t('SimpleType')+' '+$t('reverse'),value:'simpleType_reverse'},
  {text:$t('Comments'),value:'comments'},
  {text:$t('Comments')+' '+$t('reverse'),value:'comments_reverse'},  
  {text:$t('Likes'),value:'likes'},
  {text:$t('Likes')+' '+$t('reverse'),value:'likes_reverse'}, 
  {text:$t('Dislikes'),value:'dislikes'},
  {text:$t('Disikes')+' '+$t('reverse'),value:'dislikes_reverse'},   
  ]"
  :label="$t('Sort by')"
  ></v-select>
</template>
<script>
  import { eventBus } from '../eventBus.js';
export default {
  props: ['medias','baseUrl','loggeduserid','canloadmore','currentuser'],
  methods: {
    sortBy: function() {
      localStorage.setItem("choosenSort",this.selectVal)
      eventBus.$emit('sortBy',this.selectVal);
    }
  },
  mounted(){
    this.$nextTick(function () {
      var cs = localStorage.getItem("choosenSort")
      if(cs!=null&&cs!=''){
        console.log("set sortSelect to "+cs)
        this.selectVal = cs;
      }
      eventBus.$emit('sortBy',this.selectVal);
    })
  },
  data(){
    return {
      selectVal:"created_at"
    }
  }
}
</script>
