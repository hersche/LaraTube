<template>
  <div v-if="currentmedia!=undefined">
      <div class="row" >
        <div class="col-xs-12 col-sm-12 col-md-12">


<div class="text-center" v-if="currentmedia.techType=='audio'">

          <!-- <av-circle v-show="audiovisualtype=='circle'"
          ref-link="player1"
                :outline-width="0"
                :progress-width="5"
                :outline-meter-space="5"
                :playtime="true"
                playtime-font="18px Monaco"
                >
              </av-circle>
              <av-line v-show="audiovisualtype=='line'"
                :line-width="2"
                line-color="lime"
              ref-link="player1"
              ></av-line>

           <av-bars
          caps-color="#FFF"
:bar-color="['#f00', '#ff0', '#0f0']"
canv-fill-color="#000"
:caps-height="2"
    ref-link="player1"
    v-show="audiovisualtype=='bar'">
  </av-bars> -->
  <p>
  <img class="img-fluid" :src="currentmedia.poster_source"></p>
  <audio class="text-center" ref="player1" id="player"  preload autobuffer v-if="currentmedia.techType=='audio'" controls :poster="currentmedia.poster_source">
     <source :src="currentmedia.source" type="audio/mp3"></source>
   </audio>
</div>

          <video class="col-12" id="player" v-if="currentmedia.techType=='video'" controls :poster="currentmedia.poster_source">
            <source :src="currentmedia.source" type="video/mp4"></source>
          </video>
          <video class="col-12" id="player" v-if="currentmedia.techType=='torrent'" controls :poster="currentmedia.poster_source">
          </video>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12"></div>
      <div class="card">
        <div class="card-header">
          <span class='h3'>{{ currentmedia.title }}</span>
          <div class="float-right">

                          <span v-if="currentmedia.techType=='audio'" >visualizer <select value="bar" v-model="audiovisualtype">
                            <option value="bar">Bar</option>
                            <option value="circle">Circle</option>
                            <option value="line">Line</option>
                          </select></span>

                          <a :href="torrentdownloadurl" v-b-modal.torrentmodal class="mr-1" v-if="torrentdownloadurl!=''&(currentmedia.type=='torrentAudio'|currentmedia.type=='torrentVideo')" >Download file</a>
                          <b-btn v-b-modal.torrentmodal class="mr-1" v-if="currentmedia.type=='torrentAudio'|currentmedia.type=='torrentVideo'" >Torrent-info</b-btn>
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
              <span class="ml-1" id="likeCount">{{ dislikes }}</span>
            </button>
            <button id="dislike" v-else type="button" @click="like(-1,'dislike')" class="btn btn-sm btn-primary">
              <vs-icon icon="thumb_down"></vs-icon>
              <span class="ml-1" id="likeCount">{{ dislikes }}</span>
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
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore'],
    components : {
        'singleField': SingleGalleryField,
        'comments': Comments
    },
    methods: {
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

        // TODO review this logic..
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
        emitLoadMore() {
          eventBus.$emit('loadMore','');
        },

    },
    computed: {
      series2: function () {
        return this.chartData;
      },
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
      if(this.currentmedia.techType=="audio"){
      //  console.log("init visualizer")





      }

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
            setInterval(onProgress, 2000);
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
              console.log(url)
              console.log(torrent.torrentFileBlobURL)
              that.torrentdownloadurl = torrent.torrentFileBlobURL
            }

          file.renderTo('#player');
        });
    }
  } });
    },
    mounted(){
      if(localStorage.getItem("autoplay")!=''){
        this.autoplay=true;
      }
  },
  data(){
    return {
      mylike:0,
      likes:0,

      dislikes:0,
      inited: false,
      peers: '',
      data:'',
      autoplay:false,
      audiovisualtype:'bar',
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
