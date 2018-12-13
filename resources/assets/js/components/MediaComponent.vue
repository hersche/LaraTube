<template><div>
      <div v-for="(item,index) in medias"  v-if="item.title==currentTitle">
  <div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12">
    <span class="float-right">
      <button @click="emitBackClicked(currentTitle)" class="btn btn-primary mr-2" >Back</button>
    </span>

      <h3> {{ item.title }} </h3>
      <audio id="player" v-if="item.simpleType=='audio'" controls :poster="item.poster_source">
        <source :src="item.source" type="audio/mp3">
      </audio>
      <video class="col-12" id="player" v-else controls :poster="item.poster_source">
        <source :src="item.source" type="video/mp4">
      </video>

  </div></div>
  <div class="col-xs-12 col-sm-12 col-md-12">
  </div>
  <div class="card">
    <div class="card-header">
      <span class='h3'>{{ item.title }}</span>
      <div class="float-right">
        <span class="btn btn-info mr-1">{{ item.created_at_readable }}</span>
        <a class="btn btn-primary" :href="'/profile/'+item.user.name">
          <img v-if="item.user.name!=undefined" class="mx-auto rounded-circle img-fluid" src="/img/404/avatar.png" alt="avatar" style="max-height: 20px;" />
          <img v-else class="mx-auto rounded-circle img-fluid" :src="item.user.avatar" alt="avatar" style="max-height: 20px;" />
        </a>
      </div>
    <div class="card-body">{{ item.description }}

    </div>
     <div class="card-footer">Tags: {{ item.tagString }}</div>
  </div>
</div>
</div>    </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['medias','currentTitle','swapComponent','baseUrl'],
    methods: {
      emitBackClicked(title) {
        eventBus.$emit('playerBackClick',title);
      }
    }
  }
</script>
