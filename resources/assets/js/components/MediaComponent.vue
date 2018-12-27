<template>
  <div v-if="currentmedia!=undefined">
      <div class="row" >
        <div class="col-xs-12 col-sm-12 col-md-12">
          <h3> {{ currentmedia.title }} </h3>
          <audio id="player" v-if="currentmedia.simpleType=='audio'" controls :poster="currentmedia.poster_source">
            <source :src="currentmedia.source" type="audio/mp3"></source>
          </audio>
          <video class="col-12" id="player" v-if="currentmedia.simpleType=='video'" controls :poster="currentmedia.poster_source">
            <source :src="currentmedia.source" type="video/mp4"></source>
          </video>
          <video class="col-12" id="player" v-if="currentmedia.simpleType=='torrent'" controls :poster="currentmedia.poster_source">
          </video>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12"></div>
      <div class="card">
        <div class="card-header">
          <span class='h3'>{{ currentmedia.title }}</span>
          <div class="float-right">
            <span class="btn btn-info mr-1">{{ currentmedia.created_at_readable }}</span>
              <b-btn v-b-modal.torrentmodal class="mr-1" v-if="currentmedia.type=='torrentAudio'|currentmedia.type=='torrentVideo'" >Torrent-info</b-btn>
              <router-link class="btn btn-primary" :to="'/profile/'+currentmedia.user.id">
                <img v-if="currentmedia.user.avatar==''" class="mx-auto rounded-circle img-fluid" src="/img/404/avatar.png" alt="avatar" style="max-height: 20px;" />
                <img v-else class="mx-auto rounded-circle img-fluid" :src="'/'+currentmedia.user.avatar" alt="avatar" style="max-height: 20px;" />
              </router-link>
            <button id="like" v-if="mylike==1" type="button" @click="like(0,'like')" class="btn btn-success">
              <ion-icon name="thumbs-up"></ion-icon>
              <span class="ml-1" id="likeCount">{{ likes }}</span>
            </button>
            <button id="like" v-else type="button" @click="like(1,'like')" class="btn btn-primary">
              <ion-icon name="thumbs-up"></ion-icon>
              <span class="ml-1" id="likeCount">{{ likes }}</span>
            </button>


            <button id="dislike" v-if="mylike==-1" type="button" @click="like(0,'dislike')" class="btn btn-success">
              <ion-icon name="thumbs-down"></ion-icon>
              <span class="ml-1" id="likeCount">{{ dislikes }}</span>
            </button>
            <button id="dislike" v-else type="button" @click="like(-1,'dislike')" class="btn btn-primary">
              <ion-icon name="thumbs-down"></ion-icon>
              <span class="ml-1" id="likeCount">{{ dislikes }}</span>
            </button>

            <span v-if="loggeduserid==currentmedia.user.id" class=""><router-link class="btn btn-sm btn-info float-right" :to="'/mediaedit/'+currentmedia.title">Edit</router-link></span>
          </div>
          <div class="card-body">{{ currentmedia.description }}</div>
          <div class="card-footer">Tags:<span v-for="tag in currentmedia.tags"> <router-link class="btn btn-xs btn-info mr-1"  :to="'/tags/'+tag.name" >{{ tag.name }} ({{ tag.count }}x)</router-link></span>

          </div>

        </div>
      </div>
      <div class="comments">
        <h4>Comments</h4>
        <form class="form-inline mb-1" id="commentForm">
          <input id="medias_id" name="medias_id" type="hidden" :value="currentmedia.id">
          <input id="medias_title" name="medias_title" type="hidden" :value="currentmedia.title">
          <input placeholder="Comment..." class="col-9" id="medias_body" name="body" type="text">
          <input type="button" class="ml-1" value="Send comment!" @click="sendComment();" />
        </form>
        <div v-for="comment in currentmedia.comments" class="comment mb-2 row" :id='"cid"+comment.id'>
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
                    </p>
                    <p>
                        <a href="" class="text-right small"><i class="ion-reply"></i> Reply</a>
                    </p>
                </div>
            </div>
          </div>
      </div>
      <b-modal id="torrentmodal" title="Torrent-infos">
        <p>Peers: {{ peers }}</p>
        <p>Downloadspeed: {{ downloadspeed }}</p>
        <p>Uploadspeed: {{ uploadspeed }}</p>
        <p>Downloadpercent: {{ downloadpercent }}</p>
      </b-modal>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['medias','baseUrl','loggeduserid'],
    methods: {
      prettyBytes(num) {
        var exponent, unit, neg = num < 0, units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        if (neg) num = -num;
        if (num < 1) return (neg ? '-' : '') + num + ' B';
        exponent = Math.min(Math.floor(Math.log(num) / Math.log(1000)), units.length - 1);
        num = Number((num / Math.pow(1000, exponent)).toFixed(2));
        unit = units[exponent];
        return (neg ? '-' : '') + num + ' ' + unit;
      },
      emitBackClicked(title) {
        eventBus.$emit('playerBackClick',title);
      },
      like(l,kind){
        let that = this;
        if(kind=="like"){
          if(l==0){
            this.likes-=1;
          } else {
            this.likes+=1;
          }
        }
        if(kind=="dislike"){
        if(this.mylike==1){
          this.likes-=1;
        }
        }
        if(kind=="like"){
        if(this.mylike==-1){
          this.dislikes-=1;
        }
      }
        if(kind=="dislike"){
          if(l==0){
            this.dislikes-=1;
          } else {
            this.dislikes+=1;
          }
        }
        /*if(l==1){
          if(this.mylike==1){
            this.mylike=0
          } else {
            this.mylike=1
          }
        }
        if(l==-1){
          if(this.mylike==-1){
            this.mylike=0
          } else {
            this.mylike=-1
          }
        }*/
        $.ajax({
            url: '/like?media_id='+this.currentmedia.id+'&count='+l,
            type: 'GET',
            contentType: "application/json",
            cache: false,
            complete : function(res) {
              /*if(res.status==201){
                eventBus.$emit('videoCreated',res.responseJSON);
              }*/
              console.log("completed")
              console.log(l)
              that.mylike=l;
            }

        });
      },
       deleteComment(id){
         // TODO
          /*$.ajax({
            url: '{{ url("/comment") }}',
            type: 'DELETE',
            data: "comments_id="+id,
            success: function(data) {
              $("#cid"+id).html("");
              console.log("dynamicly deleted comment");
            }
          });*/
        },

      sendComment(){
        //console.log($("#commentForm")[0])
        //console.log(new FormData($("#commentForm")[0]))
        $.ajax({
            url: '/comment',
            type: 'POST',
            data: new FormData($("#commentForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){

                //eventBus.$emit('showAlert',['success','Video uploaded']);
              }
              eventBus.$emit('commentCreated',res.responseJSON);
            }

        });
      }
    },
    computed: {
      // a computed getter
      currentmedia: function () {
        // `this` points to the vm instance
        let that = this;
        var theMedia;// = new Media("None","","","","","","","","","","","")
        this.medias.forEach(function(val,key){
          if(val.title==that.$route.params.currentTitle){
            theMedia = val;
          }
        });
        return theMedia;
      }
    },
    updated: function () {
      this.$nextTick(function () {
    if((this.currentmedia!=undefined)&&(this.inited==false)){
      this.mylike = Number(this.currentmedia.myLike);
      this.likes = this.currentmedia.likes;
      this.dislikes = this.currentmedia.dislikes;
      this.inited=true
    if(this.currentmedia.type=="torrentAudio"||this.currentmedia.type=="torrentVideo"){
      var WebTorrent = require('webtorrent')
      var client = new WebTorrent();
      let that = this;
      client.add(this.currentmedia.source, function (torrent) {
          // Torrents can contain many files. Let's use the .mp4 file
          var file = torrent.files.find(function (file) {
            return file.name.endsWith('.mp4')
          })

            torrent.on('done', onDone);
            setInterval(onProgress, 500);
            onProgress();
            file.getBlobURL(function (err, url) {
              if (err){
                console.log(err.message);
              }
              //$("#downloadButton").attr("href",url);
            });
            //$("#torrentDL").html("<a href='"+torrent.torrentFileBlobURL+"' download='Stimulate....torrent'>Torrent-file</a>");
            function onProgress () {
              // Peers
              that.peers = torrent.numPeers + (torrent.numPeers === 1 ? ' peer' : ' peers');
              // Progress
              var percent = Math.round(torrent.progress * 100 * 100) / 100;
              that.downloadpercent = that.prettyBytes(torrent.downloaded) + " / " + that.prettyBytes(torrent.length) + " ("+percent+"%)";

              // Speed rates
              that.downloadspeed = that.prettyBytes(torrent.downloadSpeed) + '/s (down)';
              that.uploadspeed = that.prettyBytes(torrent.uploadSpeed) + '/s (up)';
            }
            function onDone () {
              onProgress();
            }

          file.renderTo('#player');
        });
    }
  } });
    },
    mounted(){

  },
  data(){
    return {
      mylike:0,
      likes:0,
      dislikes:0,
      inited: false,
      peers: '',
      downloadspeed: '',
      downloadpercent: '',
      uploadspeed: '',
    }
  }
  }
</script>
