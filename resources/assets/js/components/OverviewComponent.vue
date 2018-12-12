<template><div><h1>Overview!!</h1>
  <ul id="example-1">
    <li v-for="item in medias">
      {{ item.title }}
    </li>
  </ul></div>
</template>
<script>
 export default {
  props: [
   'medias' ]
 }
</script>
