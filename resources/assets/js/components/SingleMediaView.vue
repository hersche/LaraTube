<template>
  <div v-if="currentmedia!=undefined">
    <div class="row" >
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="text-center">
          <p>
            <img class="img-fluid" :src="currentmedia.poster_source" v-if="currentmedia.type=='directAudio'|(currentmedia.type=='localAudio'&audiovisualtype=='Poster')">
          </p>
          <canvas v-if="currentmedia.type=='localAudio'&audiovisualtype!='Poster'"  class="col-12" height="400" style="height: 400px; width:100%;" id="audioVisual"></canvas>
          <vue-plyr v-if="currentmedia.type!='youtube'&&currentmedia.type!='vimeo'" :options="playerConfig" ref="player">
            <audio v-if="currentmedia.type=='localAudio'" class="text-center col-11"  :src="currentmedia.source" id="audioPlayer"  preload autobuffer   controls :poster="currentmedia.poster_source">
              <source id="audioSource" :src="currentmedia.source" type="audio/mp3"></source>
            </audio>
            <audio v-if="currentmedia.type=='directAudio'" class="text-center" :src="currentmedia.source" id="audioPlayer222"  preload autobuffer controls :poster="currentmedia.poster_source">
              <source id="audioSource" :src="currentmedia.source" type="audio/mp3"></source>
            </audio>
            <video v-if="currentmedia.techType=='video'&&currentmedia.type!='youtube'&&currentmedia.type!='vimeo'" controls :src="currentmedia.source" :poster="currentmedia.poster_source" class="col-12" id="videoPlayer"  >
              <source :src="currentmedia.source" type="video/mp4"></source>
              <track v-for="track in currentmedia.tracks" :label="track.title" kind="subtitles" :srclang="track.title" :src="'/'+track.source">
            </video>
            <div data-plyr-provider="youtube" v-if="currentmedia.type=='youtube'" :data-plyr-embed-id="currentmedia.source"></div>
            <div data-plyr-provider="vimeo" v-if="currentmedia.type=='vimeo'" :data-plyr-embed-id="currentmedia.source"></div>

            <video class="col-12" id="torrentPlayer" v-if="currentmedia.type=='torrentVideo'" controls :poster="currentmedia.poster_source">
              <track v-for="track in currentmedia.tracks" :label="track.title" kind="subtitles" :srclang="track.title" :src="'/'+track.source">
            </video>
            <audio class="col-12" id="torrentPlayer" v-if="currentmedia.type=='torrentAudio'" controls :poster="currentmedia.poster_source">
              <track v-for="track in currentmedia.tracks" :label="track.title" kind="subtitles" :srclang="track.title" :src="'/'+track.source">
            </audio>
          </vue-plyr>
          <vue-plyr v-if="currentmedia.type=='youtube'||currentmedia.type=='vimeo'" :options="playerConfig" ref="player">
            <div data-plyr-provider="youtube" v-if="currentmedia.type=='youtube'" :data-plyr-embed-id="currentmedia.source"></div>
            <div data-plyr-provider="vimeo" v-if="currentmedia.type=='vimeo'" :data-plyr-embed-id="currentmedia.source"></div>
          </vue-plyr>
          <a v-if="currentmedia.type=='localAudio'" class="btn btn-primary col-1 float-right" @click="visualFullScreen()"><vs-icon size="big" icon="fullscreen"></vs-icon></a>

        </div>

      </div>
    </div>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  //import plyr from 'plyr'
  import { User, Media, Tag } from '../models';
  import butterchurn from 'butterchurn';
  import butterchurnPresets from 'butterchurn-presets';
  var emptyMedia = new Media(0,"None","","","","","","","",new User(0,"None","img/404/avatar.png","img/404/background.png","", "", {},false),"","","","","",0,0,0,[],0);
  var WebTorrent = require('webtorrent')
  var client = new WebTorrent();
  let theTorrent;
  var currsrc;
  var torrentInterval;
  var audioCtx, audioNode, gainNode, visualizer;
  const presets = butterchurnPresets.getPresets();

  export default {
    props: ['autoplay','medias','baseUrl','loggeduserid','canloadmore','currentuser','currentmedia'],


    methods: {

      visualFullScreen(){
        if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
          if (document.exitFullscreen) {
            document.exitFullscreen();
          } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
          } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
          } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
          }
          if(visualizer!=undefined){
            visualizer.setRendererSize(400, 400);
          }
        } else {
          var element = $('#audioVisual')[0];
          if (element.requestFullscreen) {
            element.requestFullscreen();
          } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
          } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
          } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
          }
          if(visualizer!=undefined){
            visualizer.setRendererSize($(window).width(), $(window).height());
          }
        }
      },

      // this and it's initTorrentAfterRemove method exist this way by try to remove the old torrent properly.
      // i did this as proper as possible, but it's still in the client's array :/
      initTorrent(){
        let that = this;
        //if(client==undefined){

          if(client.torrents.length>0){
            //client.remove(client.torrents[0].magnetURI,function(err){
              theTorrent.destroy(function(){
                that.initTorrent();
              })

            //});

          } else {
            that.initTorrentAfterRemove();
          }
      },

      initTorrentAfterRemove(){
        let that = this;
        if(this.currentmedia.techType=="video"){
          this.inited=true
        }
        else if(this.currentmedia.techType=="torrent"){
          console.log("start to setup torrent")
          this.inited=true
          let that = this;
          //  var player = new plyr('#torrentPlayer');
          client.add(this.currentmedia.source, function (torrent) {
            theTorrent = torrent;
            // Torrents can contain many files. Let's use the .mp4 file
            var file = theTorrent.files.find(function (file) {
            that.lasttorrentid = theTorrent.magnetURI;
            currsrc = theTorrent.magnetURI;
            if(that.currentmedia.type=='torrentVideo'){
              return file.name.endsWith('.mp4')
            }
            if(that.currentmedia.type=='torrentAudio'){
              return file.name.endsWith('.mp3')
            }
          })

          theTorrent.on('done', onDone);
            torrentInterval = setInterval(onProgress, 500);
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
              if(that.lasttorrentid == theTorrent.magnetURI&&torrent.numPeers>0){
                var percent = Math.round(torrent.progress * 100 * 100) / 100;
                var datetime = new Date();
                datetime = datetime.getTime();
                var ds = torrent.downloadSpeed/1000000;
                var us = torrent.uploadSpeed/1000000;
                that.downloadpercent = that.prettyBytes(torrent.downloaded) + " / " + that.prettyBytes(torrent.length) + " ("+percent+"%)";
                that.downloadspeed = that.prettyBytes(torrent.downloadSpeed) + '/s (down)';
                that.uploadspeed = that.prettyBytes(torrent.uploadSpeed) + '/s (up)';
                eventBus.$emit('torrentChartData',[[that.peers,that.downloadpercent,that.downloadspeed,that.uploadspeed],{x:datetime,y:percent},{x:datetime,y:ds},{x:datetime,y:us}]);
              }
            }
            function onDone () {
              onProgress();
              //  console.log(url)
              //console.log(torrent.torrentFileBlobURL)
              that.torrentdownloadurl = torrent.torrentFileBlobURL
            }
            file.renderTo('video#torrentPlayer');
          });
      } else if(this.currentmedia.type=='localAudio'&this.audiovisualtype!='Poster'){
          this.inited=true
          $('#audioPlayer')[0].crossOrigin = 'Anonymous'
          audioCtx = new AudioContext();
          audioNode = audioCtx.createMediaElementSource($('#audioPlayer')[0]);
          gainNode = audioCtx.createGain();
          visualizer = butterchurn.createVisualizer(audioCtx, $('#audioVisual')[0], {
            width: '100%',
            height: 400
          });
          // get audioNode from audio source or microphone
          audioNode.connect(gainNode);
          gainNode.connect(audioCtx.destination);
          visualizer.connectAudio(gainNode);
          visualizer.loadPreset(presets[this.audiovisualtype], this.audioVisualChangeSeconds); // 2nd argument is the number of seconds to blend presets
          visualizer.setRendererSize(400, 400);
          torrentInterval = setInterval(function(){
            visualizer.render();
          }, 100);
        }
      },
      prettyBytes(num,label=true) {
        var exponent, unit, neg = num < 0, units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        if (neg) num = -num;
        if (num < 1) return (neg ? '-' : '') + num + ' B';
        exponent = Math.min(Math.floor(Math.log(num) / Math.log(1000)), units.length - 1);
        num = Number((num / Math.pow(1000, exponent)).toFixed(2));
        unit = units[exponent];
        if(label){
          return (neg ? '-' : '') + num + ' ' + unit;
        } else {
          return (neg ? '-' : '') + num;
        }
      },
      emitBackClicked(title) {
        eventBus.$emit('playerBackClick',title);
      },
    },
    watch: {
      '$route.params.currentTitle': function (val) {
        //this.fullName = val + ' ' + this.lastName
      //  console.log("route-watch")
      //  console.log(val)
      //  this.inited = false;
      //  this.currentmedia = this.getCurrentMedia()
      //  this.initTorrent()
      },
    },
    computed: {
          player () { return this.$refs.player.player }
    },
    updated: function () {
      let that = this
      this.$nextTick(function () {
        if(that.inited==false){
          console.log("#updated")
          that.initTorrent()
        }
      });
    },
    destroyed(){
      eventBus.$emit('setCurrentMedia',0);
      if(theTorrent!=undefined){
        theTorrent.destroy(function(){
          console.log("torrent destroyed on vue-destroyed-method")
        });
      }
    },
    mounted(){
      let that = this;
      this.initTorrent()

      eventBus.$emit('setCurrentMedia',this.currentmedia.id);
      eventBus.$on('playerGetDuration', title => {
        eventBus.$emit('playerSetDuration',that.player.duration);
      });
      eventBus.$on('audioVisualType', visArgs => {
        that.audiovisualtype = visArgs[0];
        this.audioVisualChangeSeconds = visArgs[1]
        if(visualizer!=undefined){
          visualizer.loadPreset(presets[this.audiovisualtype], this.audioVisualChangeSeconds);
        }
      });
      if(this.currentmedia.type=="torrentAudio"||this.currentmedia.type=="torrentVideo"){
        setTimeout(function(){
          if(that.autoplay){
            that.player.play();
            //  this.player.fullscreen.enter();
          }
        }, 3000);
      } else {
        if(that.autoplay){
          that.player.play();
        }
      }
      this.player.on('ended', () => {
        if(that.autoplay){
          console.log('movie ended')
          //console.log(that.medias.indexOf(that.currentmedia))
          eventBus.$emit('autoplayNextVideo',that.currentmedia.id);
        }
      })

  },
  data(){
    return {
      inited: false,
      peers: '',
      data:'',
      audioVisualChangeSeconds:0.0,
      visualizer:'',
      lasttorrentid:'',
      audiovisualtype:'Flexi - alien fish pond',
      downloadspeed: '',
      torrentdownloadurl:'',
      downloadpercent: '',
      uploadspeed: '',
      playerConfig:{ keyboard: { focused: false, global: true } }
    }
  }
  }
</script>
