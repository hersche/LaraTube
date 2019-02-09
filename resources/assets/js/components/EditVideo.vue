<template>
    <div v-if="currentmedia!=undefined" class="col-xs-12 col-sm-12 col-md-12">
      <h4>{{ $t('Edit') }} {{ $t('media') }}</h4>
      <form id="theForm">
        <div class="form-group row">
          <label>{{ $t('Title') }}</label>
          <input type="hidden" name="_token" :value="csrf">
          <input type="hidden" value="" name="image" id="addMediaImage" />
          <input placeholder="Media-title" class="form-control" :value="currentmedia.title" name="title" type="text">
        </div>
        <div class="form-group row">
          <label>{{ $t('Type') }} (only for restore):</label>
          <select name="type" v-model="mediaType">
            <option value="localAudio">Local audio</option>
            <option value="localVideo">Local video</option>
            <option value="directVideo">Direct video</option>
            <option value="directAudio">Direct audio</option>
            <option value="torrentAudio">Torrent audio</option>
            <option value="torrentVideo">Torrent video</option>
            <option value="youtube">Youtube</option>
            <option value="vimeo">Vimeo</option>
          </select>
        </div>
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
        <span class="btn btn-primary" v-if="currentmedia!=undefined" @click="durationTestMedia()">Set duration</span>
        <span class="btn btn-primary" v-if="currentmedia!=undefined" @click="positionTestMedia('intro_start')">Set intro start</span>
        <span class="btn btn-primary" v-if="currentmedia!=undefined" @click="positionTestMedia('outro_start')">Set outro start</span>
        <span class="btn btn-primary" v-if="currentmedia!=undefined" @click="positionTestMedia('intro_end')">Set intro end</span>
        <span class="btn btn-primary" v-if="currentmedia!=undefined" @click="positionTestMedia('outro_end')">Set outro end</span>
        <div class="form-group row">
            <label>{{ $t('Duration') }}</label>
            <input placeholder="00:00:00" class="form-control" :value="currentmedia.duration" id="duration" name="duration" type="text">
        </div>
        <div class="form-group row">
            <label>{{ $t('Intro start') }}</label>
            <input placeholder="Time in seconds" class="form-control" :value="currentmedia.intro_start" id="intro_start" name="intro_start" type="text">
        </div>
        <div class="form-group row">
            <label>{{ $t('Outro start') }}</label>
            <input placeholder="Time in seconds" class="form-control" :value="currentmedia.outro_start" id="outro_start" name="outro_start" type="text">
        </div>
        <div class="form-group row">
            <label>{{ $t('Intro end') }}</label>
            <input placeholder="Time in seconds" class="form-control" :value="currentmedia.intro_end" id="intro_end" name="intro_end" type="text">
        </div>
        <div class="form-group row">
            <label>{{ $t('Outro end') }}</label>
            <input placeholder="Time in seconds" class="form-control" :value="currentmedia.outro_end" id="outro_end" name="outro_end" type="text">
        </div>
        <div class="form-group row">
          <label>{{ $t('Poster') }}</label>
          <vue-croppie
            ref="croppieRef"
            :enableOrientation="true"
            :enableResize="false"
            @result="result"
            :viewport="{ width: 700, height: 394, type: 'square' }"
            :boundary="{ width: 700, height: 394 }"
            @update="update">
          </vue-croppie>
          <input type="hidden" id="posterBase" name="poster" :value="cropped" />
          <!-- Rotate angle is Number -->
          <button @click="rotate(-90,$event)">{{ $t("Rotate") }} {{ $t("left") }}</button>
          <button @click="rotate(90,$event)">{{ $t("Rotate") }} {{ $t("right") }}</button>
          <input id="posterUpload" accept=".png,.jpg,.jpeg" @change="posterChange()" name="unimportant" type="file">
          <div id="poster"></div>
        </div>
        <div class="form-group row">
          <label>{{ $t('Description') }}</label>
          <MarkdownCreator :theText="currentmedia.description" theId="description" theTitle="Description" ></MarkdownCreator>
        </div>
        <div class="form-group row">
          <label>Tags (separate with spaces):</label>
          <input id="tags" type="text" class="form-control" name="tags" :value="currentmedia.tagString" >
        </div>
        <div class="form-group row">
          <label>Subtitles</label>
          <b-button class="float-right text-right" @click="showModal">Manage</b-button>
        </div>
      </form>
      <b-modal ref="myModalRef" hide-footer title="Using Component Methods">
        <h4 v-if="currentmedia.tracks.length>0">Existing tracks</h4>
        <ul >
          <li v-for="track in currentmedia.tracks" class="mb-3">
            <a class="text-left" target="_blank" :href="track.source">{{ track.lang }}</a> <a class="btn btn-danger btn-sm float-right" @click="deleteTrack(track.id)">{{ track.lang }} <vs-icon icon="delete"></vs-icon></a>
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
        <div class="row">
          <b-btn @click="submitTrack();" class="mt-3 col-4" variant="outline-success" block >Upload track</b-btn>
          <b-btn class="mt-3 col-4" variant="outline-danger" block @click="hideModal">Upload track and close</b-btn>
          <b-btn @click="$refs.myModalRef.hide()" class="mt-3 col-4" variant="outline-success" block >Cancel</b-btn>
        </div>
      </b-modal>
      <button @click="submitAction();" class="btn btn-success" ><vs-icon icon="save"></vs-icon>{{ $t('Save') }}</button> <button @click="openConfirm();" class="btn btn-danger float-right" ><vs-icon icon="delete"></vs-icon>{{ $t('Delete') }}</button>
    </div>
</template>
<script>
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
      $("#duration").val(this.secondsToHms(duration))
    });
    eventBus.$on('playerSetIntroStart', duration => {
      $("#intro_start").val(duration)
    });
    eventBus.$on('playerSetOutroStart', duration => {
      $("#outro_start").val(duration)
    });
    eventBus.$on('playerSetIntroEnd', duration => {
      $("#intro_end").val(duration)
    });
    eventBus.$on('playerSetOutroEnd', duration => {
      $("#outro_end").val(duration)
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
        return this.getCurrentMedia()
      }
    },
    watch:{
      medias: function(val){
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
        blockGetRequest:false,
        medias:store.state.medias
      }
    }
  }
</script>
