<template>
  <vs-tabs>
  <vs-tab vs-label="Edit">
    <div>
  <vs-textarea :label="$t(theTitle)" :placeholder="$t(theTitle)+'...'" v-model="tmpText"  :id="theId" :name="theId" />
</div>
</vs-tab>
<vs-tab vs-label="Preview">
  <div>
    <VueMarkdown v-if="tmpText!=''"> {{ tmpText }}</VueMarkdown>
    <h1 v-if="tmpText==''||tmpText==null">No text for preview</h1>
  </div>
</vs-tab>
</vs-tabs>
</template>

<script>
  import VueMarkdown from 'vue-markdown'
  export default {
    props: ['theText','theId','theTitle'],
  components : {
      VueMarkdown
  },
  mounted(){
    this.tmpText=this.theText
  },
  data(){
    return {
      tmpText:''
    }
  }
}
</script>
  