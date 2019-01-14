<template>
  <select @change="sortBy()" id="sortBy" value="created_at" v-model="selectVal">
    <option value="created_at">{{ $t('Created at') }}</option>
    <option value="created_at_reverse">{{ $t('Created at') }} (reverse)</option>
    <option value="updated_at">{{ $t('Updated at') }}</option>
    <option value="updated_at_reverse">{{ $t('Updated at') }} (reverse)</option>
    <option value="title">{{ $t('Title') }}</option>
    <option value="title_reverse">{{ $t('Title') }} (reverse)</option>
    <option value="type">By type</option>
    <option value="type_reverse">By type (reverse)</option>
    <option value="simpleType">By simpletype</option>
    <option value="simpleType_reverse">By simpletype (reverse)</option>
    <option value="comments">By comments</option>
    <option value="comments_reverse">By comments (reverse)</option>
    <option value="likes">By likes</option>
    <option value="likes_reverse">By likes (reverse)</option>
    <option value="dislikes">By dislikes</option>
    <option value="dislikes_reverse">By dislikes (reverse)</option>
  </select>
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
