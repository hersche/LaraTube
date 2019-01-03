<template>
  <div v-if="currentmedia!=undefined">
      <div class="row" >
        <div class="col-xs-12 col-sm-12 col-md-12">


<div class="text-center" v-if="currentmedia.techType=='audio'">


  <p>
  <img class="img-fluid" :src="currentmedia.poster_source" v-if="currentmedia.type=='directAudio'|(currentmedia.type=='localAudio'&audiovisualtype=='Poster')"></p>
  <canvas v-if="currentmedia.type=='localAudio'&audiovisualtype!='Poster'"  class="col-12" height="400" style="height: 400px" id="audioVisual"></canvas>
  <vue-plyr v-if="currentmedia.type=='localAudio'"><audio class="text-center"  :src="currentmedia.source" id="audioPlayer"  preload autobuffer   controls :poster="currentmedia.poster_source">
     <source id="audioSource" :src="currentmedia.source" type="audio/mp3"></source>
   </audio>
 </vue-plyr>
   <a v-if="currentmedia.type=='localAudio'" class="btn btn-primary" @click="visualFullScreen()"><vs-icon size="big" icon="fullscreen"></vs-icon></a>
<vue-plyr v-if="currentmedia.type=='directAudio'">
   <audio class="text-center" :src="currentmedia.source" id="audioPlayer222"  preload autobuffer v-if="currentmedia.type=='directAudio'"   controls :poster="currentmedia.poster_source">
      <source id="audioSource" :src="currentmedia.source" type="audio/mp3"></source>
    </audio>
    </vue-plyr>
</div>

          <vue-plyr v-if="currentmedia.techType=='video'"><video controls :src="currentmedia.source" :poster="currentmedia.poster_source" class="col-12" id="videoPlayer"  >
            <source :src="currentmedia.source" type="video/mp4"></source>
          </video>
          </vue-plyr>
          <vue-plyr v-if="currentmedia.techType=='torrent'" >
          <video class="col-12" id="torrentPlayer" controls :poster="currentmedia.poster_source">
          </video>
          </vue-plyr>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12"></div>
      <div class="card">
        <div class="card-header">
          <span class='h3'>{{ currentmedia.title }}</span>
          <div class="float-right">

            <span class="float-left"><vs-input-number v-if="currentmedia.type=='localAudio'" v-model="audioVisualChangeSeconds" :step="0.1"/></span>
            <span v-if="currentmedia.type=='localAudio'" >visualizer <select  value="Flexi - alien fish pond" v-model="audiovisualtype">
              <option value="Poster">Poster</option>
                <option v-for="(value, key, index) in visualPresets" :value="key">{{ visualTypesShort(key) }}</option>
                            <!--<option value="Cope - The Neverending Explosion of Red Liquid Fire">Cope - The Neverending Explosion of Red Liquid Fire</option>
                            <option value="Flexi - alien fish pond">Flexi - alien fish pond</option>-->
            </select></span>

                          <a :href="torrentdownloadurl" v-b-modal.torrentmodal class="mr-1" v-if="torrentdownloadurl!=''&(currentmedia.techType=='torrent')" >Download file</a>
                          <b-btn v-b-modal.torrentmodal class="mr-1" v-if="currentmedia.techType=='torrent'" >Torrent-info</b-btn>
            <span class="btn btn-sm btn-info mr-1">{{ currentmedia.created_at_readable }}</span>

              <router-link class="btn btn-sm btn-primary" :to="'/profile/'+currentmedia.user.id">
                <img v-if="currentmedia.user.avatar==''" class="mx-auto rounded-circle img-fluid" src="/img/404/avatar.png" alt="avatar" style="max-height: 20px;" />
                <img v-else class="mx-auto rounded-circle img-fluid" :src="'/'+currentmedia.user.avatar" alt="avatar" style="max-height: 20px;" />
              </router-link>


            <button id="like" v-if="mylike==1" type="button" @click="like(0,'like')" class="btn btn-sm btn-success">
              <vs-icon icon="thumb_up"></vs-icon>
              <span class="ml-1" id="likeCount">{{ likes }}</span>
            </button>
            <button id="like" v-else type="button" @click="like(1,'like')" class="btn btn-sm btn-primary">
              <vs-icon icon="thumb_up"></vs-icon>
              <span class="ml-1" id="likeCount">{{ likes }}</span>
            </button>


            <button id="dislike" v-if="mylike==-1" type="button" @click="like(0,'dislike')" class="btn btn-sm btn-success">
              <vs-icon icon="thumb_down"></vs-icon>
              <span class="ml-1" id="dislikeCount">{{ dislikes }}</span>
            </button>
            <button id="dislike" v-else type="button" @click="like(-1,'dislike')" class="btn btn-sm btn-primary">
              <vs-icon icon="thumb_down"></vs-icon>
              <span class="ml-1" id="dislikeCount">{{ dislikes }}</span>
            </button>

            <span v-if="loggeduserid==currentmedia.user.id" class=""><router-link class="btn btn-sm btn-info float-right" :to="'/mediaedit/'+currentmedia.title">Edit</router-link></span>
          </div>
          <div class="card-body">{{ currentmedia.description }}</div>
          <div class="card-footer"><span v-for="tag in currentmedia.tags"> <router-link class=""  :to="'/tags/'+tag.name" >
<vs-chip color="primary">
            <vs-avatar icon="tag" />
            {{ tag.name }}
          </vs-chip></router-link></span>

          </div>

        </div>
      </div>


      <div class="comments col-sm-8 col-12 float-left">

        <comments v-bind:level="'0'" v-bind:commentlist="currentmedia.comments" v-bind:loggeduserid="loggeduserid" v-bind:currentmedia="currentmedia"></comments>
      </div>

      <div class="col-sm-4 col-12 float-right">
        <h4>Next videos</h4>
        <p>
      <vs-switch v-model="autoplay"/>
    <span for="">Autoplay</span></p>
        <div v-for="item in medias"  class="" v-if="item.id!=currentmedia.id">
            <div class="card">
              <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
            </div>
        </div>
        <button class="btn btn-block btn-danger" v-if="canloadmore" @click="emitLoadMore()">Load more</button>
      </div>

      <p class="" style="">

      </p>
      <b-modal  style="width:520px;" id="torrentmodal" title="Torrent-infos">
        <p>Peers: {{ peers }}</p>
        <p>Downloadspeed: {{ downloadspeed }}</p>
        <p>Uploadspeed: {{ uploadspeed }}</p>
        <p>Downloadpercent: {{ downloadpercent }}</p>
        <p><vs-switch v-model="chartEnabled"/><label>Enable chart (workaround)</label></p>
        <p><apexchart v-if="chartEnabled" width="500" type="line" id="chart3" :options="chartOptions2" :series="chartData"></apexchart></p>
      </b-modal>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  import Comments from './Comments'
  //import plyr from 'plyr'
  import { User, Media, Tag } from '../models';
  import butterchurn from 'butterchurn';
  import butterchurnPresets from 'butterchurn-presets';
  var emptyMedia = new Media(0,"None","","","","","","","",new User(0,"None","img/404/avatar.png","img/404/background.png","", "", {},false),"","","","","",0,0,0);
  var WebTorrent = require('webtorrent')
  var client = new WebTorrent();
  var theTorrent;
  var torrentInterval;
  var audioCtx, audioNode, gainNode, visualizer;
  const presets = butterchurnPresets.getPresets();
  console.log(emptyMedia)
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore'],
    components : {
        'singleField': SingleGalleryField,
        'comments': Comments
    },
    methods: {
      visualFullScreen(){
        if (
  document.fullscreenElement ||
  document.webkitFullscreenElement ||
  document.mozFullScreenElement ||
  document.msFullscreenElement
) {
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  } else if (document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) {
    document.msExitFullscreen();
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
}
},
      visualTypesShort(val){
        if(val.length>20){
          return val.substring(0,19);
        }else{
          return val
        }
      },
      initTorrent(){
        console.log("run init")
        if(torrentInterval!=undefined){
          //clearInterval(torrentInterval)
          //torrentInterval=undefined
          /*if(audioNode!=undefined){
            //audioCtx.close()
            //audioCtx=undefined
            audioNode.disconnect();
            gainNode.disconnect();
            audioNode=undefined;
            gainNode=undefined;
          }*/
          if(theTorrent!=undefined){
            theTorrent.destroy();
          }
        }
        if(this.currentmedia.techType=="video"){
          this.inited=true
        //  var player = new plyr('#videoPlayer');
        }
        else if(this.currentmedia.techType=="torrent"){
          this.inited=true
          console.log("run init for torrent")
              let that = this;
              this.lasttorrentid = this.currentmedia.source;
            //  var player = new plyr('#torrentPlayer');
              client.add(this.currentmedia.source, function (torrent) {
                theTorrent = torrent;
                  // Torrents can contain many files. Let's use the .mp4 file
                  var file = torrent.files.find(function (file) {
                    return file.name.endsWith('.mp4')
                  })

                    torrent.on('done', onDone);
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
                      // Progress
                      var percent = Math.round(torrent.progress * 100 * 100) / 100;
                      var datetime = new Date();
                      datetime = datetime.getTime();
                      var ds = torrent.downloadSpeed/1000000;
                      var us = torrent.uploadSpeed/1000000;
                      that.downloadpercent = that.prettyBytes(torrent.downloaded) + " / " + that.prettyBytes(torrent.length) + " ("+percent+"%)";
                      that.chartData[0].data.push({x:datetime,y:percent})
                      that.chartData[1].data.push({x:datetime,y:ds})
                      that.chartData[2].data.push({x:datetime,y:us})
                      // Speed rates
                      that.downloadspeed = that.prettyBytes(torrent.downloadSpeed) + '/s (down)';
                      that.uploadspeed = that.prettyBytes(torrent.uploadSpeed) + '/s (up)';
                    }
                    function onDone () {
                      onProgress();
                    //  console.log(url)
                      console.log(torrent.torrentFileBlobURL)
                      that.torrentdownloadurl = torrent.torrentFileBlobURL
                    }

                  file.renderTo('video#torrentPlayer');
                });
            } else if(this.currentmedia.type=='localAudio'&this.audiovisualtype!='Poster'){
              this.inited=true
              $('#audioPlayer')[0].crossOrigin = 'Anonymous'
              audioCtx = new AudioContext();
              //console.log($('#audioPlayer')[0])
              audioNode = audioCtx.createMediaElementSource($('#audioPlayer')[0]);
              gainNode = audioCtx.createGain();
              //console.log(audioNode)

              visualizer = butterchurn.createVisualizer(audioCtx, $('#audioVisual')[0], {
                width: 400,
                height: 400
              });

// get audioNode from audio source or microphone


audioNode.connect(gainNode);
gainNode.connect(audioCtx.destination);
// load a preset
visualizer.connectAudio(gainNode);
var preset = presets[this.audiovisualtype];
//console.log(butterchurnPresets.getPresets())
visualizer.loadPreset(preset, 0.0); // 2nd argument is the number of seconds to blend presets

// resize visualizer

visualizer.setRendererSize(400, 400);

// render a frame
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
      like(l,kind){
        let that = this;
        // TODO review this logic.. done?
        if((kind=="like")){
          if(this.mylike==-1){
            this.dislikes -= 1;
          }
          if(l==0&&this.mylike==1){
            this.likes-=1;
          } else if(l==1&&this.mylike!=1) {
            this.likes += 1;
          }
        }
        if(kind=="dislike"){
          if(this.mylike==1){
            this.likes -= 1;
          }
          if(l==0&&this.mylike==-1){
            this.dislikes-=1;
          } else if(l==-1&&this.mylike!=-1) {
            this.dislikes+=1;
          }
        }
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
        emitLoadMore() {
          eventBus.$emit('loadMore','');
        },
        getCurrentMedia() {
          // `this` points to the vm instance
          let that = this;
          var theMedia = emptyMedia
          this.medias.forEach(function(val,key){
            if(val.title==that.$route.params.currentTitle){
              //this.originalLikes = val.likes
              //this.originalDislikes = val.dislikes
              theMedia = val;
            }
          });
          return theMedia;
        }

    },
    watch: {
      '$route.params.currentTitle': function (val) {
        //this.fullName = val + ' ' + this.lastName
      //  console.log("route-watch")
      //  console.log(val)
      this.inited = false;
        this.currentmedia = this.getCurrentMedia()
        this.initTorrent()
      },
      audiovisualtype: function(val){
        /*if(torrentInterval!=undefined&this.audiovisualtype=='Poster'){
          clearInterval(torrentInterval)
        }
        this.initTorrent();*/
        localStorage.setItem('audioVisualType',this.audiovisualtype);
        if(visualizer!=undefined){
          var preset = presets[this.audiovisualtype];
          console.log("change preeset")
          visualizer.loadPreset(preset, this.audioVisualChangeSeconds);
        }
  //      console.log("change visualtype")
    //    visualizer.loadPreset(presets[val], 0.0);
  },
  audioVisualChangeSeconds:function(val){
    localStorage.setItem('audioVisualChangeSeconds',this.audioVisualChangeSeconds);
    if(visualizer!=undefined){
      var preset = presets[this.audiovisualtype];
      console.log("change preeset seconds")
      visualizer.loadPreset(preset, this.audioVisualChangeSeconds);
    }
  }
    },
    computed: {
      series2: function () {
        return this.chartData;
      },
      visualPresets: function () {
        return butterchurnPresets.getPresets();
      },
      // a computed getter
    },
    updated: function () {
      this.$nextTick(function () {

    if((this.currentmedia!=undefined)&&(this.inited==false)){
      this.currentmedia = this.getCurrentMedia()
      this.initTorrent()
      //this.initTorrent();
      this.mylike = Number(this.currentmedia.myLike);
      this.likes = this.currentmedia.likes;
      this.dislikes = this.currentmedia.dislikes;

      if(this.currentmedia.techType=="audio"){
      //  console.log("init visualizer")





      }

  } });
    },
    mounted(){
      if(localStorage.getItem("autoplay")!=''){
        this.autoplay=true;
      }
      this.currentmedia = this.getCurrentMedia()
      if(localStorage.getItem('audioVisualType')!=undefined&localStorage.getItem('audioVisualType')!=''){
        this.audiovisualtype=localStorage.getItem('audioVisualType');
      }
      if(localStorage.getItem('audioVisualChangeSeconds')!=undefined&localStorage.getItem('audioVisualChangeSeconds')!=''){
        this.audioVisualChangeSeconds=localStorage.getItem('audioVisualChangeSeconds');
      }
      //this.currentmedia = this.getCurrentMedia()
     ///this.initTorrent()
  },
  data(){
    return {
      mylike:0,
      likes:0,
      dislikes:0,
      inited: false,
      peers: '',
      data:'',
      currentmedia:emptyMedia,
      originalLikes: 0,
      originalDislikes: 0,
      audioVisualChangeSeconds:0.0,
      autoplay:false,
      visualizer:'',
      lasttorrentid:'',
      audiovisualtype:'Flexi - alien fish pond',
      downloadspeed: '',
      torrentdownloadurl:'',
      downloadpercent: '',
      uploadspeed: '',
      chartOptions2:{
            chart: {
                height: 350,
                width: 450,
                type: 'line',
                animations: {
                    enabled: false,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 400
                    }
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Dynamic Updating Chart',
                align: 'left'
            },
            markers: {
                size: 0
            },
            xaxis: {
                type: 'datetime',
                format: 'hh:mm:ss'
            },
            yaxis: {
            },
            legend: {
                show: false
            },
    },
    chartData:[{
          name: 'Percent',
          data: []
        },{
              name: 'Mb download',
              data: []
            },{
                  name: 'Mb upload',
                  data: []
                },],
    chartEnabled:false,
    }
  }
  }
</script>
