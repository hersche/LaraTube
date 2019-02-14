<template>
  <div v-if="currentmedia!=undefined" style="overflow-y:auto;overflow-x:hidden;" class="bg-light" id="mediaDiv">
    <mediaView :key="$route.fullPath" v-bind:currentmedia="currentmedia" v-bind:autoplay="autoplay"></mediaView>
    <div class="col-xs-12 col-sm-12 col-md-12"></div>
    <div class="card">
      <div class="card-header">
      
        <div style="float:right;width:100%;">
          <span class="float-left"><vs-input-number v-if="currentmedia.type=='localAudio'" v-model="audioVisualChangeSeconds" :step="0.1"/></span>
          <span v-if="currentmedia.type=='localAudio'" class="" >
            <v-btn color="blue" small @click="previousVisual()"><v-icon>skip_previous</v-icon></v-btn>
            <select id="visualList" value="Flexi - alien fish pond" v-model="audiovisualtype">
              <option value="Poster">Poster</option>
              <option v-for="(value, key, index) in visualPresets" :value="key">{{ visualTypesShort(key) }}</option>
            </select>
            <v-btn color="blue" small @click="nextVisual()"><v-icon>skip_next</v-icon></v-btn>
          </span>
        </div>
        <div style="float:right;width:100%;">

          <v-btn id="mfs" small color="blue" @click="mediaGoFullscreen()"><v-icon>fullscreen</v-icon></v-btn>
          <vs-dropdown vs-trigger-click class="btn-sm">
            <a href.prevent class="btn btn-sm btn-primary">More</a>
            <vs-dropdown-menu class="">
              <vs-dropdown-item v-b-modal.torrentmodal class="mr-1 btn-sm" v-if="currentmedia.techType=='torrent'">Torrent-info</vs-dropdown-item>
              <vs-dropdown-item>{{ currentmedia.type }}</vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
          
          <a href.prevent :href="torrentdownloadurl" v-b-modal.torrentmodal class="mr-1" v-if="torrentdownloadurl!=''&(currentmedia.techType=='torrent')" >Download file</a>
          <v-btn color="blue" small @click="skipIntro(currentmedia.intro_end)" v-if="currentmedia.intro_end!=0">Skip intro ({{ currentmedia.intro_end.toFixed(1) }}s)</v-btn>          
          <span id="created_at" class="btn btn-sm btn-info mr-1">{{ currentmedia.created_at_readable }}</span>
          <router-link id="category" :to="'/category/'+currentCat.urlTitle" v-if="currentCat!=undefined" class="btn btn-sm btn-info mr-1">{{ currentCat.title }}</router-link>
          <span v-else class="btn btn-sm btn-warning mr-1">{{ $t('No category') }}</span>
          <router-link class="btn btn-sm btn-primary" :to="'/profile/'+currentmedia.user.id">
          <div id="userAvatar" :text="currentmedia.user.name">
            <img v-if="currentmedia.user.avatar==''" class="mx-auto rounded-circle img-fluid" src="/img/404/avatar.png" alt="avatar" style="max-height: 20px;" />
            <img v-else class="mx-auto rounded-circle img-fluid" :src="'/'+currentmedia.user.avatar" alt="avatar" style="max-height: 20px;" />
          </div>
            </router-link>
            <b-tooltip target="userAvatar" placement="top">
              <p>Uploaded by {{ currentmedia.user.name }}</p>
            </b-tooltip>
            <b-tooltip target="mfs" placement="top">
              <p>Document fullscreen</p>
            </b-tooltip>
            <b-tooltip target="created_at" placement="top">
              <p>{{ $t('Created at') }} {{ $d(new Date(currentmedia.created_at.date),'short') }}</p>
              <p>{{ $t('Updated at') }} {{ $d(new Date(currentmedia.updated_at.date),'short') }}</p>
            </b-tooltip>
            <b-tooltip target="category" v-if="currentCat!=undefined" placement="top">
              <h5>{{ currentCat.title }}</h5>
              <p>{{ currentCat.description }}</p>
            </b-tooltip>
            
            
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
              <router-link class="btn btn-sm btn-info ml-1" :to="'/mediaedit/'+currentmedia.urlTitle"><vs-icon icon="edit"></vs-icon>{{ $t('Edit') }}</router-link>
            </span>
          </div>
          <div class="card-body">
            <span class='h3'>{{ currentmedia.title }}</span>
            <VueMarkdown :source="currentmedia.description"></VueMarkdown></div>
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
        <comments class="bg-light" v-bind:csrf="csrf" v-bind:level="'0'" v-bind:commentlist="currentmedia.comments" v-bind:loggeduserid="loggeduserid" v-bind:currentmedia="currentmedia"></comments>
      </div>

      <div class="col-sm-4 col-12 float-right bg-light">
        <h4>{{ $t('Next') }} {{ $t('medias') }}</h4>
        <p>{{ $t('Sort by') }} <sortSelect></sortSelect></p>
        <p>
          <vs-switch v-model="autoplay"/>
          <span for="">Autoplay</span>
        </p>
        <div v-for="item in nextMedias"  class="" v-if="item.id!=currentmedia.id">
          <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
        </div>
        <div v-if="nextMedias.length==0">No more next medias</div>
        <button class="btn btn-block btn-danger" v-if="canloadmore" @click="emitLoadMore()">Load more</button>
      </div>
      <b-modal  style="width:520px;" id="torrentmodal" title="Torrent-infos">
        <p>Peers: {{ peers }}</p>
        <p>Downloadspeed: {{ downloadspeed }}</p>
        <p>Uploadspeed: {{ uploadspeed }}</p>
        <p>Downloadpercent: {{ downloadpercent }}</p>
        <p><vs-switch v-model="chartEnabled"/><label>Enable chart (workaround)</label></p>
        <p><apexchart v-if="chartEnabled" width="100%" type="line" id="chart3" :options="chartOptions2" :series="chartData"></apexchart></p>
      </b-modal>
  </div>
</template>
<script>
  import { eventBus, store } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  import Comments from './Comments'
  import SingleMediaView from './SingleMediaView'
  import SortSelect from './SortSelect'
  import VueMarkdown from 'vue-markdown'
  
  import { User, Media, Tag } from '../models';
  import butterchurnPresets from 'butterchurn-presets';
  var emptyMedia = new Media(0,"None","","","","","","","",new User(0,"None","img/404/avatar.png","img/404/background.png","", "", {},false),"","","","","",0,0,0,[],0);
  const presets = butterchurnPresets.getPresets();

  export default {
    props: ['medias','baseUrl','canloadmore'],
    components : {
        'singleField': SingleGalleryField,
        'comments': Comments,
        'mediaView' : SingleMediaView,
        'sortSelect': SortSelect,
        VueMarkdown
    },
    methods: {
      skipIntro(s){
        eventBus.$emit('playerJumpTo',s);
      },
      mediaGoFullscreen(){
        eventBus.$emit('mediaGoFullscreen','');
      },
      goFullscreen(){
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
          $('#mediaDiv').css("height","100%")
          eventBus.$emit('playerGoFullscreen',false);
          
        } else {
          var element = $('#mediaDiv');
          element.css("height","100vh")
          element = element[0];
          if (element.requestFullscreen) {
            element.requestFullscreen();
          } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
          } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
          } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
          }
          eventBus.$emit('playerGoFullscreen',true);
        }
      },
      getCategoryById(category_id,data=undefined){
        var res;
        let that = this;
        $.each( data, function( key, value ) {
          if(value.children.length>0){
            var t = that.getCategoryById(category_id,value.children)
            if(t!=undefined){
              res = t;
            }
          }
          if(value.id==category_id){
            res = value;
          }
        });
        return res;
      },
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
          return store.getters.getMediaByTitle(this.$route.params.currentTitle)
        }

    },
    watch: {
      autoplay:function(val){
        localStorage.setItem('autoplay',String(this.autoplay));
      },
      '$route.params.currentTitle': function (val) {
        this.inited = false;
        //this.currentmedia = this.getCurrentMedia()
      },
      audiovisualtype: function(val){
        localStorage.setItem('audioVisualType',this.audiovisualtype);
        eventBus.$emit('audioVisualType',[this.audiovisualtype,this.audioVisualChangeSeconds]);
      },
      medias: function(val){
        console.log("medias change")
        //  this.currentmedia = this.getCurrentMedia();
        if(this.currentmedia!=undefined){
          this.mylike = Number(this.currentmedia.myLike);
          this.likes = this.currentmedia.likes;
          this.dislikes = this.currentmedia.dislikes;
        }
      },
      audioVisualChangeSeconds:function(val){
        localStorage.setItem('audioVisualChangeSeconds',this.audioVisualChangeSeconds);
        eventBus.$emit('audioVisualType',[this.audiovisualtype,this.audioVisualChangeSeconds]);
      }
    },
    computed: {
      csrf: function(){
        return store.getters.getCSRF()
      },
      currentuser: function(){
        return store.getters.getUserById(store.state.loginId)
      },
      loggeduserid: function(){
        return store.state.loginId
      },
      nextMedias: function() {
        return store.getters.nextMediasList(this.currentmedia.id)
      },
      series2: function () {
        return this.chartData;
      },
      currentmedia: function () {
        var theMedia = store.getters.getMediaByTitle(this.$route.params.currentTitle)
        return theMedia;
      },
    },
    updated: function () {
      this.$nextTick(function () {
        
      });
    },

    mounted(){
      let that = this;
      if(localStorage.getItem("autoplay")=='true'){
        this.autoplay=true;
      }
      if(localStorage.getItem('audioVisualType')!=undefined&localStorage.getItem('audioVisualType')!=''){
        this.audiovisualtype=localStorage.getItem('audioVisualType');
      }
      if(localStorage.getItem('audioVisualChangeSeconds')!=undefined&localStorage.getItem('audioVisualChangeSeconds')!=''){
        this.audioVisualChangeSeconds=localStorage.getItem('audioVisualChangeSeconds');
      }
      //this.currentmedia = this.getCurrentMedia();
      if(this.currentmedia!=undefined){
        this.mylike = Number(this.currentmedia.myLike);
        this.likes = this.currentmedia.likes;
        this.dislikes = this.currentmedia.dislikes;
      }

      eventBus.$on('mediaGoFullscreen', isFullscreen => {
        that.goFullscreen()
      });
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
  },
  data(){
    return {
      blockGetRequest:false,
      mylike:0,
      savedPosition:0,
      likes:0,
      dislikes:0,
      inited: false,
      peers: '',
      visualPresets:butterchurnPresets.getPresets(),
      currentCat: undefined,
      data:'',
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
