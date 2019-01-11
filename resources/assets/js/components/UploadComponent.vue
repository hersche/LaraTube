<template>
    <div class="col-xs-12 col-sm-12 col-md-12">
    <h4>Add media</h4>
    <form id="theForm">
    <div class="form-group">
        <label>Media-type:</label>
        <input type="hidden" name="_token" :value="csrf">
         <select v-model="mediaType" name="type"><option selected value="localAudio">Local audio</option><option value="localVideo">Local video</option><option value="directVideo">Direct video</option><option value="directAudio">Direct audio</option><option value="torrentAudio">Torrent audio</option><option value="torrentVideo">Torrent video</option></select>
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
         <span class="btn btn-primary" @click="testMedia()">Test link</span>
         <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="removeTestMedia()">Remove test</span>
    </div>
    <div v-if="mediaType=='torrentAudio'|mediaType=='torrentVideo'" class="form-group">
        <label>Torrent (magnet-link)</label>
        <p>A webtorrent magnet-link, for example from peertube-videos</p>
         <input placeholder="magnet://" class="form-control" id="source" name="source" type="text">
         <span class="btn btn-primary" @click="testMedia()">Test link</span>
         <span class="btn btn-primary" v-if="theTestMedia!=undefined" @click="removeTestMedia()">Remove test</span>

    </div>
    <mediaView v-bind:currentmedia="theTestMedia" v-if="theTestMedia!=undefined" v-bind:autoplay="false"></mediaView>
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
          <label>Category:</label>
          <treeselect v-model="catid" name="category_id" :multiple="false" :options="treecatptions" />
      </div>
      <div class="form-group">
          <label>Media-description:</label>
          <textarea placeholder="Media-description" id="addMediaDescription" class="form-control" name="description" cols="50" rows="10"></textarea>
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
  import { eventBus } from '../eventBus.js';
  import { User, Media, Tag } from '../models';
  import SingleMediaView from './SingleMediaView'

  export default {
    props: ['medias','baseUrl','csrf','treecatptions'],
    components : {
        'mediaView' : SingleMediaView
    },
    mounted: function () {
      this.$refs.croppieRef.bind({
        url: '/img/404/image.png',
      })
    },

    methods: {
      testMedia(){
        var techType;
        if(this.mediaType=="localAudio"||this.mediaType=="directAudio"){
          techType = "audio";
        } else if(this.mediaType=="localVideo"||this.mediaType=="directVideo"){
          techType = "video";
        } else if(this.mediaType=="torrentAudio"||this.mediaType=="torrentVideo"){
          techType = "torrent";
        }
        this.theTestMedia = new Media(0,"None","",$("#source").val(),"","","",techType,this.mediaType,new User(0,"None","img/404/avatar.png","img/404/background.png","", "", {},false),"","","","","",0,0,0,[],0);

      },
      removeTestMedia(){

        this.theTestMedia = undefined;
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
        $.ajax({
          xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
              if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                console.log(percentComplete);
                that.uploadPercent=percentComplete*100;
                //Do something with upload progress here
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
        cropped: null,
        uploadPercent:-1,
        theTestMedia:undefined
      }
    }
  }
</script>
