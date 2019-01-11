<template>
  <div v-if="currentmedia!=undefined">
      <mediaView v-bind:currentmedia="currentmedia" v-bind:autoplay="autoplay"></mediaView>
      <div class="col-xs-12 col-sm-12 col-md-12"></div>
      <div class="card">
        <div class="card-header">
          <span class='h3'>{{ currentmedia.title }}</span>
          <div class="float-right">

            <span class="float-left"><vs-input-number v-if="currentmedia.type=='localAudio'" v-model="audioVisualChangeSeconds" :step="0.1"/></span>
            <span v-if="currentmedia.type=='localAudio'" >
              <button class="btn btn-sm btn-primary" @click="previousVisual()"><vs-icon icon="skip_previous"></vs-icon></button>
              <select id="visualList" value="Flexi - alien fish pond" v-model="audiovisualtype">
                <option value="Poster">Poster</option>
                <option v-for="(value, key, index) in visualPresets" :value="key">{{ visualTypesShort(key) }}</option>
              </select>
              <button class="btn btn-sm btn-primary" @click="nextVisual()"><vs-icon icon="skip_next"></vs-icon></button>
            </span>
            <a :href="torrentdownloadurl" v-b-modal.torrentmodal class="mr-1" v-if="torrentdownloadurl!=''&(currentmedia.techType=='torrent')" >Download file</a>
            <b-btn v-b-modal.torrentmodal class="mr-1" v-if="currentmedia.techType=='torrent'" >Torrent-info</b-btn>
            <span class="btn btn-sm btn-info mr-1">{{ currentmedia.created_at_readable }}</span>
            <span class="btn btn-sm btn-info mr-1">{{ currentmedia.type }}</span>
            <router-link class="btn btn-sm btn-primary" :to="'/profile/'+currentmedia.user.id">
              <div id="userAvatar" :text="currentmedia.user.name">
                <img v-if="currentmedia.user.avatar==''" class="mx-auto rounded-circle img-fluid" src="/img/404/avatar.png" alt="avatar" style="max-height: 20px;" />
                <img v-else class="mx-auto rounded-circle img-fluid" :src="'/'+currentmedia.user.avatar" alt="avatar" style="max-height: 20px;" />
              </div>
              <b-tooltip target="userAvatar" placement="top">
                <h5>{{ currentmedia.user.name }}</h5>
              </b-tooltip>
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

            <span v-if="loggeduserid==currentmedia.user.id|currentuser.admin" class="">
              <router-link class="btn btn-sm btn-info float-right" :to="'/mediaedit/'+currentmedia.urlTitle">Edit</router-link>
            </span>
          </div>
          <div class="card-body" v-html="currentmedia.description"></div>
          <div class="card-footer">
            <span v-for="tag in currentmedia.tags">
              <router-link class=""  :to="'/tags/'+tag.name" >
                <vs-chip color="primary">
                  <vs-avatar icon="tag" />{{ tag.name }}
                </vs-chip>
              </router-link>
            </span>
          </div>
        </div>
      </div>


      <div class="comments col-sm-8 col-12 float-left">
        <comments v-bind:csrf="csrf" v-bind:level="'0'" v-bind:commentlist="currentmedia.comments" v-bind:loggeduserid="loggeduserid" v-bind:currentmedia="currentmedia"></comments>
      </div>

      <div class="col-sm-4 col-12 float-right">
        <h4>Next videos</h4>
        <p><sortSelect></sortSelect></p>
        <p>
          <vs-switch v-model="autoplay"/>
          <span for="">Autoplay</span></p>
            <div v-for="item in nextvideos"  class="" v-if="item.id!=currentmedia.id">
              <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
            </div>
            <div v-if="nextvideos.length==0">No more next medias</div>
            <button class="btn btn-block btn-danger" v-if="canloadmore" @click="emitLoadMore()">Load more</button>
      </div>
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
  import SingleMediaView from './SingleMediaView'
  import SortSelect from './SortSelect'
  import { User, Media, Tag } from '../models';
  import butterchurnPresets from 'butterchurn-presets';
  var emptyMedia = new Media(0,"None","","","","","","","",new User(0,"None","img/404/avatar.png","img/404/background.png","", "", {},false),"","","","","",0,0,0,[],0);
  const presets = butterchurnPresets.getPresets();

  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore','currentuser','nextvideos','csrf'],
    components : {
        'singleField': SingleGalleryField,
        'comments': Comments,
        'mediaView' : SingleMediaView,
        'sortSelect': SortSelect
    },
    methods: {
      nextVisual(){
        let e = $( "#visualList option:selected" )
        e.prop('selected', false);
        e.next().prop('selected', true);
        this.audiovisualtype = e.next().prop('value')
      },
      previousVisual(){
        let e = $( "#visualList option:selected" )
        e.prop('selected', false);
        e.prev().prop('selected', true);
        this.audiovisualtype = e.prev().prop('value')
      },
      visualTypesShort(val){
        if(val.length>20){
          return val.substring(0,19);
        }else{
          return val
        }
      },
      like(l,kind){
        if(this.loggeduserid==0){
          this.$vs.notify({title:'You can not vote',text:'Log in to like or comment',icon:'',color:'danger',position:'bottom-center'})
          return
        }
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
        this.mylike=l;
        $.ajax({
            url: '/like?media_id='+this.currentmedia.id+'&count='+l+'&_token='+that.csrf,
            type: 'GET',
            contentType: "application/json",
            cache: false,
            complete : function(res) {
              that.mylike=l;
              eventBus.$emit('refreshMedia',comment.media_id);
            }

        });
      },
        emitLoadMore() {
          eventBus.$emit('loadMore','');
        },
        getCurrentMedia() {
          // `this` points to the vm instance
          let that = this;
          var theMedia = emptyMedia
          this.medias.forEach(function(val,key){
            if(val.urlTitle==encodeURIComponent(that.$route.params.currentTitle)){
              theMedia = val;
            }
          });
          if(theMedia==emptyMedia){
            console.log("media not there yet, want it!");
            eventBus.$emit('loadMedia',encodeURIComponent(that.$route.params.currentTitle));
          }
          return theMedia;
        }

    },
    watch: {
      autoplay:function(val){
        localStorage.setItem('autoplay',String(this.autoplay));
      },
      '$route.params.currentTitle': function (val) {
        this.inited = false;
        this.currentmedia = this.getCurrentMedia()
      },
      audiovisualtype: function(val){
        localStorage.setItem('audioVisualType',this.audiovisualtype);
        eventBus.$emit('audioVisualType',[this.audiovisualtype,this.audioVisualChangeSeconds]);
  },
  medias: function(val){
    this.currentmedia = this.getCurrentMedia();
    this.mylike = Number(this.currentmedia.myLike);
    this.likes = this.currentmedia.likes;
    this.dislikes = this.currentmedia.dislikes;
},
  audioVisualChangeSeconds:function(val){
    localStorage.setItem('audioVisualChangeSeconds',this.audioVisualChangeSeconds);
    eventBus.$emit('audioVisualType',[this.audiovisualtype,this.audioVisualChangeSeconds]);
  /*  if(visualizer!=undefined){
      var preset = presets[this.audiovisualtype];
      console.log("change preeset seconds")
      visualizer.loadPreset(preset, this.audioVisualChangeSeconds);
    }*/
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
        if(this.inited==false){
          this.inited=true;
          this.currentmedia = this.getCurrentMedia();
          this.mylike = Number(this.currentmedia.myLike);
          this.likes = this.currentmedia.likes;
          this.dislikes = this.currentmedia.dislikes;
        }
      });
    },
    mounted(){
      let that = this;
      console.log("autoplay-var")
      console.log(localStorage.getItem("autoplay"))
      if(localStorage.getItem("autoplay")=='true'){
        this.autoplay=true;
      }
      //this.currentmedia = this.getCurrentMedia()
      if(localStorage.getItem('audioVisualType')!=undefined&localStorage.getItem('audioVisualType')!=''){
        this.audiovisualtype=localStorage.getItem('audioVisualType');
      }
      if(localStorage.getItem('audioVisualChangeSeconds')!=undefined&localStorage.getItem('audioVisualChangeSeconds')!=''){
        this.audioVisualChangeSeconds=localStorage.getItem('audioVisualChangeSeconds');
      }
      this.currentmedia = this.getCurrentMedia();
      this.mylike = Number(this.currentmedia.myLike);
      this.likes = this.currentmedia.likes;
      this.dislikes = this.currentmedia.dislikes;
      eventBus.$emit('audioVisualType',[this.audiovisualtype,this.audioVisualChangeSeconds]);
      eventBus.$on('torrentChartData', chartData => {
        // [that.peers,that.downloadpercent,that.downloadspeed,that.uploadspeed],{x:datetime,y:percent},{x:datetime,y:ds},{x:datetime,y:us}]
        that.peers = chartData[0][0];
        that.downloadpercent = chartData[0][1];
        that.downloadspeed = chartData[0][2];
        that.uploadspeed = chartData[0][3];
        that.chartData[0].data.push(chartData[1])
        that.chartData[1].data.push(chartData[2])
        that.chartData[2].data.push(chartData[3])
      });
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
