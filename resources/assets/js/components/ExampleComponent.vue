<template>
  <div>
    <h1>{{ aSubFirst }}</h1>
      <button @click="swapComponent('overview')" type="submit">Overview</button>
      <button @click="swapComponent('player')" type="submit">Whatever</button>
  </div>
</template>
<script>
 export default {
  props: [
   'aSubFirst','swapComponent' ]
 }
</script>
