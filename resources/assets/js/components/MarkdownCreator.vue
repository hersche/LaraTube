<template>
  <v-tabs
    v-model="active"
    color="cyan"
    dark
    slider-color="yellow"
  >
  <v-tab
  key="0"
  ripple
>
  {{  $t('Edit') }}
</v-tab>
  <v-tab
  key="1"
  ripple
  >
  {{  $t('Preview') }}
</v-tab>
  <v-tab-item key="0">
    <div>
      <v-textarea
  :label="$t(theTitle)"
  v-model="tmpText"
  :id="theId"
  :name="theId"
  :placeholder="tmpHint"
  :hint="$t('You can use markdown and preview it!')"
></v-textarea>
</div>
</v-tab-item>
<v-tab-item key="1">
  <div>
    <VueMarkdown v-if="tmpText!=''" :source="tmpText"></VueMarkdown>
    <h1 v-if="tmpText==''||tmpText==null">No text for preview</h1>
  </div>
</v-tab-item>
</v-tabs>
</template>

<script>
  import VueMarkdown from 'vue-markdown'
  export default {
    props: ['theText','theId','theTitle','theHint'],
  components : {
      VueMarkdown
  },

  mounted(){
    this.tmpText=this.theText
    if(this.theHint!=undefined){
      this.tmpHint=this.theHint
    }
  },
  data(){
    return {
      tmpText:'',
      tmpHint:'',
      active:0
    }
  }
}
</script>
  