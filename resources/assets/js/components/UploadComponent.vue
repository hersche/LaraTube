<template>
    <div class="col-xs-12 col-sm-12 col-md-12">
    <h1 class="text-center">{{ $t('Add media')}}</h1>
    <form id="theForm">
      <v-text-field
        :label="$t('Title')"
        name="title"
        ></v-text-field>
        <div class="form-group row">
          <label>{{ $t('Category') }}</label>
          <treeselect v-model="catid" name="category_id" :multiple="false" :options="treecatptions" />
          <!-- <select name="category_id" v-model="catid" ><option value="">None</option><option v-for="item in categories" :value="item.id">{{ item.title }}</option></select> -->
        </div>
        <MarkdownCreator theText="" theId="description" theTitle="Description" ></MarkdownCreator>
        <input type="hidden" name="_token" :value="csrf">
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
    <div v-if="mediaType=='localAudio'|mediaType=='localVideo'" class="form-group">
        <label>Media-file</label>
        <p>Local mean you upload it directly. </p>
         <input id="directMedia" accept=".mp4,.mp3,.ogg" class="directMedia" name="directMedia" type="file">
    </div>
    <v-text-field
      v-if="mediaType=='directAudio'|mediaType=='directVideo'"
      :label="$t('Media-link')"
      placeholder="https://server/file.mp4.mp3"
      :hint="$t('Can be from a foreign server')"
      name="source"
      ></v-text-field>  
    <v-text-field
      v-if="mediaType=='torrentAudio'|mediaType=='torrentVideo'"
      :label="$t('Torrent (magnet-link)')"
      placeholder="magnet://"
      :hint="$t('Often very long addresses')"
      name="source"
      ></v-text-field>
    <v-text-field
      v-if="mediaType=='youtube'|mediaType=='vimeo'"
      :label="$t('Youtube or Vimeo')"
      :placeholder="$t('Add only the ID of the video')"
      :hint="$t('Like bTqVqk7FSmY or 76979871')"
      name="source"
      ></v-text-field>
    <mediaView v-bind:currentmedia="theTestMedia" v-if="theTestMedia!=undefined" v-bind:autoplay="false"></mediaView>
    <span class="btn btn-primary" v-if="theTestMedia==undefined" @click="testMedia()">Test link and extend infos</span>
    <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="removeTestMedia()">Remove test</span>
    <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="durationTestMedia()">Add duration</span>
    <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="positionTestMedia('intro_start')">Set intro start</span>
    <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="positionTestMedia('outro_start')">Set outro start</span>
    <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="positionTestMedia('intro_end')">Set intro end</span>
    <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="positionTestMedia('outro_end')">Set outro end</span>
    
    <v-text-field
    v-if="mediaType!='localAudio'&mediaType!='localVideo'"
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
    <div class="form-group">
        <label>Media-poster</label>
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
    <input type="hidden" value="" name="image" id="addMediaImage" />


      
      <v-text-field
        :label="$t('Tags')"
        name="tags"
        ></v-text-field>


    </form>
    <p v-if="uploadPercent!=-1">Please wait, you will be redirected to startpage after upload</p>
    <v-progress-linear v-model="uploadPercent" v-if="uploadPercent!=-1"></v-progress-linear>
    <v-btn color="green" @click="submitAction();"  ><v-icon>library_add</v-icon>{{ $t('Add') }} {{ $t('media') }}</v-btn>
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
      this.$refs.croppieRef.bind({
        url: '/img/404/image.png',
      })
      eventBus.$on('playerSetDuration', duration => {
        console.log("receive duration: "+this.secondsToHms(duration))
        //$("#duration").val(this.secondsToHms(duration))
        this.duration = this.secondsToHms(duration)
      });
      eventBus.$on('playerSetIntroStart', duration => {
        // $("#intro_start").val(duration)
        this.intro_start = duration
      });
      eventBus.$on('playerSetOutroStart', duration => {
        //$("#outro_start").val(duration)
        this.outro_start = duration
      });
      eventBus.$on('playerSetIntroEnd', duration => {
        //$("#intro_end").val(duration)
        this.intro_end = duration
      });
      eventBus.$on('playerSetOutroEnd', duration => {
        //$("#outro_end").val(duration)
        this.outro_end = duration
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
        duration: '',
        intro_start:0,
        intro_end:0,
        outro_start:0,
        outro_end:0,
        theTestMedia:undefined,
        mty:[
          {text:'Local audio',value:'localAudio'},
          {text:'Direct audio',value:'directAudio'},
          {text:'Local video',value:'localVideo'},
          {text:'Direct video',value:'directVideo'},
        ]
      }
    }
  }
</script>
