<template>
  <div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <span class="float-right">
            <router-link to="/" class="btn btn-primary">Back</router-link>
          </span>
          <h3> {{ currentMedia.title }} </h3>
          <audio id="player" v-if="currentMedia.simpleType=='audio'" controls :poster="currentMedia.poster_source">
            <source :src="currentMedia.source" type="audio/mp3"></source>
          </audio>
          <video class="col-12" id="player" v-if="currentMedia.simpleType=='video'" controls :poster="currentMedia.poster_source">
            <source :src="currentMedia.source" type="video/mp4"></source>
          </video>
          <video class="col-12" id="player" v-if="currentMedia.simpleType=='torrent'" controls :poster="currentMedia.poster_source">
          </video>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12"></div>
      <div class="card">
        <div class="card-header">
          <span class='h3'>{{ currentMedia.title }}</span>
          <div class="float-right">
            <span class="btn btn-info mr-1">{{ currentMedia.created_at_readable }}</span>
              <router-link class="btn btn-primary" :to="'/profile/'+currentMedia.user.id">
                <img v-if="currentMedia.user.avatar==''" class="mx-auto rounded-circle img-fluid" src="/img/404/avatar.png" alt="avatar" style="max-height: 20px;" />
              <img v-else class="mx-auto rounded-circle img-fluid" :src="'/'+currentMedia.user.avatar" alt="avatar" style="max-height: 20px;" />
            </router-link>
          </div>
          <div class="card-body">{{ currentMedia.description }}</div>
          <div class="card-footer">Tags: <a class="btn btn-xs btn-info mr-1" v-for="tag in currentMedia.tags" :href="'/tags/'+tag.name" >{{ tag.name }} ({{ tag.count }}x)</a>

          </div>

        </div>
      </div>
      <div class="comments">
        <h4>Comments</h4>
        <div v-for="comment in currentMedia.comments" class="comment mb-2 row" :id='"cid"+comment.id'>
            <div class="comment-avatar col-md-1 col-sm-2 text-center pr-1">
                <a href=""><img class="mx-auto rounded-circle img-fluid" :src="'/'+comment.user.avatar" alt="avatar" /></a>
            </div>
            <div class="comment-content col-md-11 col-sm-10">
                <h6 class="small comment-meta"><router-link class="btn btn-primary mr-2" :to="'/profile/'+comment.user.id">{{ comment.user.name }}</router-link> {{ comment.created_at }}
                  <!-- @if (Auth::id() == $comment->user_id)
                    <span class="float-right btn btn-danger" onclick="deleteComment({{ comment.id }});"><ion-icon name="trash"></ion-icon></span>
                  @endif -->
                </h6>
                <div class="comment-body">
                    <p>
                        {{ comment.body }}
                        <br>
                        <a href="" class="text-right small"><i class="ion-reply"></i> Reply</a>
                    </p>
                </div>
            </div>
          </div>
      </div>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['medias','currentTitle','swapComponent','baseUrl'],
    methods: {
      emitBackClicked(title) {
        eventBus.$emit('playerBackClick',title);
      }
    },
    computed: {
      // a computed getter
      currentMedia: function () {
        // `this` points to the vm instance
        let that = this;
        var theMedia;
        console.log("CRAAASH");
        this.medias.forEach(function(val,key){
          if(val.title==that.$route.params.currentTitle){
            theMedia = val;
          }
        });
        return theMedia;
      }
    },
    mounted(){
      if(this.currentMedia.type=="torrentAudio"|this.currentMedia.type=="torrentVideo"){
        var WebTorrent = require('webtorrent')
        var client = new WebTorrent();
        client.add(this.currentMedia.source, function (torrent) {
            // Torrents can contain many files. Let's use the .mp4 file
            var file = torrent.files.find(function (file) {
              return file.name.endsWith('.mp4')
            })
            file.renderTo('#player');
          });
      }
    }
  }
</script>
