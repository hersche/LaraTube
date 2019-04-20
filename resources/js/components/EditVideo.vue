<template>
    <div v-if="currentmedia!=undefined" class="col-xs-12 col-sm-12 col-md-12">
      <h1 class="text-center">{{ $t('Edit') }} {{ $t('media') }}</h1>
      <form id="theForm">
        
        <v-text-field
          :label="$t('Title')"
          name="title"
          :value="currentmedia.title"
          ></v-text-field>
          <div class="form-group row">
            <label>{{ $t('Category') }}</label>
            <treeselect v-model="catid" name="category_id" :multiple="false" :options="treecatptions" />
            <!-- <select name="category_id" v-model="catid" ><option value="">None</option><option v-for="item in categories" :value="item.id">{{ item.title }}</option></select> -->
          </div>
          <MarkdownCreator :theText="currentmedia.description" theId="description" theTitle="Description" ></MarkdownCreator>
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" value="" name="image" id="addMediaImage" />

        <v-select
        v-model="mediaType" name="type"
        attach
        :items="[
        {text:$t('Local') +' '+$t('audio'),value:'localAudio'},
        {text:$t('Direct') +' '+$t('audio'),value:'directAudio'},
        {text:$t('Local') +' '+$t('video'),value:'localVideo'},
        {text:$t('Direct') +' '+$t('video'),value:'directVideo'},
        {text:$t('Torrent') +' '+$t('video'),value:'torrentVideo'},
        {text:$t('Youtube'),value:'youtube'},
        {text:$t('Vimeo'),value:'vimeo'},
        ]"
        :label="$t('Mediatype')"
        ></v-select>
        <div class="form-group row">
          <label>{{ $t('Category') }}</label>
          <treeselect v-model="catid" name="category_id" :multiple="false" :options="treecatptions" />
          <!-- <select name="category_id" v-model="catid" ><option value="">None</option><option v-for="item in categories" :value="item.id">{{ item.title }}</option></select> -->
        </div>
        <div class="form-group row">
          <label>{{ $t('Source') }}</label>
          <input readonly class="form-control" :value="currentmedia.source" id="" name="" type="text">
        </div>
        <mediaView v-bind:currentmedia="currentmedia" v-if="currentmedia!=undefined" v-bind:autoplay="false"></mediaView>
        <v-btn color="blue" @click="durationTestMedia()">Set duration</v-btn>
        <v-btn color="blue" v-if="currentmedia!=undefined" @click="positionTestMedia('intro_start')">Set intro start</v-btn>
        <v-btn color="blue" v-if="currentmedia!=undefined" @click="positionTestMedia('outro_start')">Set outro start</v-btn>
        <v-btn color="blue" v-if="currentmedia!=undefined" @click="positionTestMedia('intro_end')">Set intro end</v-btn>
        <v-btn color="blue" v-if="currentmedia!=undefined" @click="positionTestMedia('outro_end')">Set outro end</v-btn>
        
        <v-text-field
          v-if="currentmedia.type!='localAudio'&currentmedia.type!='localVideo'"
          :label="$t('Duration')"
          name="duration"
          v-model="duration"
          ></v-text-field>
          <v-layout row wrap>
        <v-flex xs12 sm6>  
          <v-text-field
            :label="$t('Intro start')"
            name="intro_start"
            v-model="intro_start"
            :placeholder="$t('Time in seconds')"
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm6>
            <v-text-field
              :label="$t('Intro end')"
              name="intro_end"
              v-model="intro_end"
              :placeholder="$t('Time in seconds')"
              ></v-text-field>  
            </v-flex>
          <v-flex xs12 sm6>
            <v-text-field
              :label="$t('Outro start')"
              name="outro_start"
              v-model="outro_start"
              :placeholder="$t('Time in seconds')"
              ></v-text-field>
            </v-flex>

              <v-flex xs12 sm6>    
              <v-text-field
                :label="$t('Outro end')"
                name="outro_end"
                v-model="outro_end"
                :placeholder="$t('Time in seconds')"
                ></v-text-field>
              </v-flex>
        </v-layout>
        
        

        <div class="form-group row">
          <label>{{ $t('Poster') }}</label>
          <Cropper v-bind:theurl="currentmedia.poster" v-bind:width="700" v-bind:height="394" type="square" name="avatar" ></Cropper>
          <!-- <vue-croppie
            ref="croppieRef"
            :enableOrientation="true"
            :enableResize="false"
            @result="result"
            :viewport="{ width: 700, height: 394, type: 'square' }"
            :boundary="{ width: 700, height: 394 }"
            @update="update">
          </vue-croppie>
          <input type="hidden" id="posterBase" name="poster" :value="cropped" />
        -->
          <!-- Rotate angle is Number -->
          <!-- <v-btn color="blue" @click="rotate(-90,$event)">{{ $t("Rotate") }} {{ $t("left") }}</v-btn>
          <v-btn color="blue" @click="rotate(90,$event)">{{ $t("Rotate") }} {{ $t("right") }}</v-btn>
          <input id="posterUpload" accept=".png,.jpg,.jpeg" @change="posterChange()" name="unimportant" type="file">
          <div id="poster"></div> -->
        </div>
        <v-text-field
          :label="$t('Tags')"
          name="tags"
          :value="currentmedia.tagString"
          :hint="$t('Separate tags with space')"
          ></v-text-field>
          
          
    <v-dialog
        v-model="dialog"
        width="500"
      >
      <v-btn
      slot="activator"
      color="red lighten-2"
        dark
      >
        {{ $t('Manage') }} {{ $t('subtitles')}}
      </v-btn>
      <v-card v-if="currentmedia.tracks!=undefined">
        <v-card-title
          class="headline grey lighten-2"
          primary-title
          v-if="currentmedia.tracks.length>0"
          >
            Existing tracks
          </v-card-title>

          <v-card-text>
            <ul >
              <li v-for="track in currentmedia.tracks" class="mb-3">
                <a class="text-left" target="_blank" :href="track.source">{{ track.lang }}</a> <a class="btn btn-danger btn-sm float-right" @click="deleteTrack(track.id)">{{ track.lang }} <v-icon>delete</v-icon></a>
              </li>
            </ul>
  
            <div class="d-block text-center">
              <h4>Add track</h4>
              <form id="addTrackForm">
                <div class="form-group row">
                  <label>Language</label>
                  <input type="text" class="form-control" name="lang" />
                  <input type="hidden" class="form-control" name="media_id" :value="currentmedia.id" />
                </div>
                <div class="form-group row">
                    <label>File</label>
                    <input id="track" name="track" accept=".srt,.vtt" class="form-control" type="file">
                  </div>
                </form>
              </div>
            </v-card-text>
            <v-card-actions>
              <v-btn @click="submitTrack();" color="green" >Upload track</v-btn>
              <v-btn @click="hideModal">Upload track and close</v-btn>
              <v-btn @click="dialog=false" color="yellow">Cancel</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>  
      </form>
      <v-btn @click="submitAction();" color="green" ><v-icon>save</v-icon>{{ $t('Save') }}</v-btn> <v-btn @click="openConfirm();" color="red" class="float-right" ><v-icon>delete</v-icon>{{ $t('Delete') }}</v-btn>
    </div>
</template>
<script>
  import Cropper from './cropp'
  import { eventBus, store } from '../eventBus.js';
  import { Media }  from '../models';
  import MarkdownCreator from './MarkdownCreator'
  import SingleMediaView from './SingleMediaView'
  export default {
    props: ['baseUrl','treecatptions'],
    mounted: function () {
    //  this.$refs.croppieRef.bind({
    //    url: '/img/404/poster.png',
    //  })
    //this.currentmedia=this.getCurrentMedia();
    eventBus.$on('playerSetDuration', duration => {
      console.log("receive duration: "+this.secondsToHms(duration))
      //$("#duration").val(this.secondsToHms(duration))
      this.duration = this.secondsToHms(duration)
    });
    eventBus.$on('playerSetIntroStart', is => {
      //$("#intro_start").val(duration)
      this.intro_start = is
    });
    eventBus.$on('playerSetOutroStart', o => {
      this.outro_start = o
    });
    eventBus.$on('playerSetIntroEnd', ie => {
      this.intro_end = ie
    });
    eventBus.$on('playerSetOutroEnd', oe => {
      this.outro_end = oe
    });
    },
    components: {
      'mediaView' : SingleMediaView,
      MarkdownCreator
    },
    updated: function () {
      this.$nextTick(function () {
        if(this.$refs.croppieRef!=undefined&this.editpicloaded==false){
        //  this.currentmedia=this.getCurrentMedia();
          this.editpicloaded=true;
          this.$refs.croppieRef.bind({
            url: this.currentmedia.poster_source,
          })
        }
      })
    },
    computed: {
      categories: function(){
        return store.state.categories
      },
      csrf: function(){
        return store.getters.getCSRF()
      },
      currentmedia: function(){
        var m = this.getCurrentMedia()
        if(m!=undefined){
          this.duration = m.duration 
          this.intro_start = m.intro_start
          this.intro_end = m.intro_end
          this.outro_start = m.outro_start
          this.outro_end = m.outro_end
          this.mediaType = m.type
        }
        return m
      }
    },
    watch:{
      currentmedia: function(val){
        //this.currentmedia = this.getCurrentMedia();
      },
    },
    methods: {
      secondsToHms(d) {
        // THX stackoverflow! i'm lazy ;)
        d = Number(d);
        var h = Math.floor(d / 3600);
        var m = Math.floor(d % 3600 / 60);
        var s = Math.floor(d % 3600 % 60);
        return ('0' + h).slice(-2) + ":" + ('0' + m).slice(-2) + ":" + ('0' + s).slice(-2);
      },
      durationTestMedia(){
        eventBus.$emit('playerGetDuration','');
      },
      positionTestMedia(type){
        eventBus.$emit('playerGetPosition',type);
      },
      rmBr(str) {
        return str.replace(/<br\s*\/?>/mg,"");
      },
      openConfirm(){
        this.$vs.dialog({
          type:'confirm',
          color: 'danger',
          title: `Delete media?`,
          text: 'Delete a media can not be reverted. Are you shure?',
          accept:this.deleteAction
        })
      },
      showModal () {
        this.$refs.myModalRef.show()
      },
      hideModal () {
        this.submitTrack();
        this.$refs.myModalRef.hide()
      },
      getCurrentMedia() {
        var m = store.getters.getMediaByTitle(this.$route.params.editTitle)
        if(m!=undefined){
          this.catid = m.category_id
        }
        return m
      },

      posterChange(){
        var reader = new FileReader();
        let that = this;
        reader.onload = function (e) {
          that.$refs.croppieRef.bind({
             url: e.target.result,
           });
        }
        reader.readAsDataURL($("#posterUpload")[0].files[0]);

      },
      submitTrack() {
        let that = this;
        $.ajax({
            url: '/internal-api/medias/addTrack',
            type: 'POST',
            data: new FormData($("#addTrackForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
              }
              console.log("received create")
              eventBus.$emit('refreshMedia',that.currentmedia.id);
            //  eventBus.$emit('videoEdited',[that.currentmedia.title,res.responseJSON])
            }

        });
        return false;
      },

      deleteTrack(id) {
        let that = this;
        console.log("send "+id)
        $.ajax({
            url: '/internal-api/medias/deleteTrack/'+id,
            type: 'POST',
            data: '',
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
              }
              console.log("received delete")
              eventBus.$emit('refreshMedia',that.currentmedia.id);
            //  eventBus.$emit('videoEdited',[that.currentmedia.title,res.responseJSON])
            }

        });
        return false;
      },
      submitAction() {
        let that = this;
        $.ajax({
            url: '/internal-api/media/'+this.currentmedia.id,
            type: 'POST',
            data: new FormData($("#theForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
              }
              eventBus.$emit('videoEdited',[that.currentmedia.title,res.responseJSON])
            }

        });
        return false;
      },
      deleteAction() {
        let that = this;
        $.ajax({
            url: '/internal-api/media/'+this.currentmedia.id,
            type: 'DELETE',
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                //eventBus.$emit('showAlert',['success','Video uploaded']);

              }
              eventBus.$emit('videoDeleted',that.currentmedia.title);
            }

        });
        return false;
      },
// CALBACK USAGE
result(output) {
    this.cropped = output;
},
update(val) {
  let options = {
      format: 'png'
  }
  this.$refs.croppieRef.result(options, (output) => {
      this.cropped = output;
  });
},
rotate(rotationAngle,event) {
    // Rotates the image
    if (event) event.preventDefault()
    this.$refs.croppieRef.rotate(rotationAngle);
}
    },
    data(){
      return {
        mediaType: '',
        catid:0,
        tmpid:0,
        editpicloaded:false,
        showdismissiblealert: false,
        cropped: null,
        duration: '',
        intro_start:0,
        intro_end:0,
        outro_start:0,
        outro_end:0,
        dialog:false,
        blockGetRequest:false,
        medias:store.state.medias
      }
    }
  }
</script>
