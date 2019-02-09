<template>
    <div class="col-xs-12 col-sm-12 col-md-12">
    <h4>Add media</h4>
    <form id="theForm">
    <div class="form-group">
        <label>Media-type:</label>
        <input type="hidden" name="_token" :value="csrf">
         <select v-model="mediaType" name="type">
           <option selected value="localAudio">{{ $t('Local') }} {{ $t('audio') }}</option>
           <option value="directAudio">{{ $t('Direct') }} {{ $t('audio') }}</option>
           <option value="torrentAudio">Torrent {{ $t('audio') }}</option>
           <option value="localVideo">{{ $t('Local') }} {{ $t('video') }}</option>
           <option value="directVideo">{{ $t('Direct') }} {{ $t('video') }}</option>
           <option value="torrentVideo">Torrent {{ $t('video') }}</option>
           <option value="youtube">Youtube</option>
           <option value="vimeo">Vimeo</option>
         </select>
    </div>
    <div v-if="mediaType=='localAudio'|mediaType=='localVideo'" class="form-group">
        <label>Media-file:</label>
        <p>Local mean you upload it directly. </p>
         <input id="directMedia" accept=".mp4,.mp3,.ogg" class="directMedia" name="directMedia" type="file">
    </div>
    <div v-if="mediaType=='directAudio'|mediaType=='directVideo'" class="form-group">
        <label>Media-source:</label>
        <p>Direct mean you put a link from another server here. It needs to be a link, where you get the media, no html.</p>
         <input placeholder="https://server/file.mp4.mp3" class="form-control" id="source" name="source" type="text">
    </div>
    <div v-if="mediaType=='torrentAudio'|mediaType=='torrentVideo'" class="form-group">
        <label>Torrent (magnet-link)</label>
        <p>A webtorrent magnet-link, for example from peertube-videos</p>
         <input placeholder="magnet://" class="form-control" id="source" name="source" type="text">
    </div>
    <div v-if="mediaType=='youtube'|mediaType=='vimeo'" class="form-group">
        <label>Youtube or vimeo</label>
        <p>Add the id only</p>
         <input placeholder="Like bTqVqk7FSmY or 76979871" class="form-control" id="source" name="source" type="text">

    </div>

    <mediaView v-bind:currentmedia="theTestMedia" v-if="theTestMedia!=undefined" v-bind:autoplay="false"></mediaView>
    <span class="btn btn-primary" v-if="theTestMedia==undefined" @click="testMedia()">Test link and extend infos</span>
    <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="removeTestMedia()">Remove test</span>
    <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="durationTestMedia()">Add duration</span>
    <span class="btn btn-primary" v-if="currentmedia!=undefined" @click="positionTestMedia('intro_start')">Set intro start</span>
    <span class="btn btn-primary" v-if="currentmedia!=undefined" @click="positionTestMedia('outro_start')">Set outro start</span>
    <span class="btn btn-primary" v-if="currentmedia!=undefined" @click="positionTestMedia('intro_end')">Set intro end</span>
    <span class="btn btn-primary" v-if="currentmedia!=undefined" @click="positionTestMedia('outro_end')">Set outro end</span>
    
    <div v-if="mediaType!='localAudio'&mediaType!='localVideo'" class="form-group">
        <label>{{ $t('Duration') }}</label>
        <input placeholder="00:00:00" class="form-control" id="duration" name="duration" type="text">
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
    <div class="form-group">
        <label>Media-poster:</label>
        <!-- the result -->
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
          <button @click="rotate(-90,$event)">Rotate Left</button>
          <button @click="rotate(90,$event)">Rotate Right</button>
        <input id="posterUpload" accept=".png,.jpg,.jpeg" @change="posterChange()" name="poster" type="file">
        <div id="poster"></div>
    </div>
      <div class="form-group">
          <label>Mediatitle</label>
          <input type="hidden" value="" name="image" id="addMediaImage" />
               <input placeholder="Media-title" class="form-control" name="title" type="text">
      </div>


      <div class="form-group">
          <label>{{ $t('Description') }}</label>
          <MarkdownCreator theText="" theId="description" theTitle="Description" ></MarkdownCreator>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
              <strong>Tags (separate with spaces):</strong>
              <input id="tags" type="text" class="form-control" name="tags" value="" >
          </div>
      </div>


    </form>
    <p v-if="uploadPercent!=-1">Please wait, you will be redirected to startpage after upload</p>
    <vs-progress :percent="uploadPercent" v-if="uploadPercent!=-1" color="primary">primary</vs-progress>
    <button @click="submitAction();" >Submit</button>
    </div>
</template>
<script>
  import { eventBus,store } from '../eventBus.js';
  import { User, Media, Tag } from '../models';
  import SingleMediaView from './SingleMediaView'
  import MarkdownCreator from './MarkdownCreator'
  export default {
    props: ['baseUrl','treecatptions'],
    components : {
        'mediaView' : SingleMediaView,
        MarkdownCreator
    },
    computed: {
      csrf: function(){
        return store.getters.getCSRF()
      },
    },
    mounted: function () {
      let that = this;
    //  this.$refs.croppieRef.bind({
      //  url: '/img/404/image.png',
      //})
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

    methods: {
      secondsToHms(d) {
        // THX stackoverflow! i'm lazy ;)
        d = Number(d);
        var h = Math.floor(d / 3600);
        var m = Math.floor(d % 3600 / 60);
        var s = Math.floor(d % 3600 % 60);
        return ('0' + h).slice(-2) + ":" + ('0' + m).slice(-2) + ":" + ('0' + s).slice(-2);
      },
      testMedia(){
        var techType;
        if(this.mediaType=="localAudio"||this.mediaType=="directAudio"){
          techType = "audio";
        } else if(this.mediaType=="localVideo"||this.mediaType=="directVideo"){
          techType = "video";
        } else if(this.mediaType=="torrentAudio"||this.mediaType=="torrentVideo"){
          techType = "torrent";
        }
        this.linkTested = true;
        this.theTestMedia = new Media(0,"None","",$("#source").val(),"","","",techType,this.mediaType,new User(0,"None","img/404/avatar.png","img/404/background.png","", "", {},false),"","","","","",0,0,0,[],0);

      },
      removeTestMedia(){
        this.theTestMedia = undefined;
      },
      durationTestMedia(){
        eventBus.$emit('playerGetDuration','');
      },
      positionTestMedia(type){
        eventBus.$emit('playerGetPosition',type);
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
      uploadProgress(){

    },
      submitAction() {
        let that = this;
        if(this.linkTested==false){

        }
        $.ajax({
          xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
              if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                that.uploadPercent=percentComplete*100;
              }
            }, false);

            xhr.addEventListener("progress", function(evt) {
              if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                //Do something with download progress
              }
            }, false);

            return xhr;
          },
            url: '/media/create',
            type: 'POST',
            data: new FormData($("#theForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==201){
                eventBus.$emit('videoCreated',res.responseJSON);
              }
            },

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
        linkTested:false,
        cropped: null,
        uploadPercent:-1,
        theTestMedia:undefined
      }
    }
  }
</script>
