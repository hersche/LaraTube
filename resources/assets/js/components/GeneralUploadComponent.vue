<template>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <b-alert style="position: fixed; top: 2px;" :show="dismissCountDown"
               dismissible
               :variant="alertType"
               @dismissed="dismissCountDown=0"
               @dismiss-count-down="countDownChanged">
        <p>{{ alertMsg }}</p>
        <b-progress :variant="alertType"
                    :max="dismissSecs"
                    :value="dismissCountDown"
                    height="4px">
        </b-progress>
      </b-alert>
    <h4>Add media</h4>
    <form id="theForm">
    <div class="form-group">
        <label>Media-type:</label>
         <select v-model="mediaType" name="type"><option selected value="localAudio">Local audio</option><option value="localVideo">Local video</option><option value="directVideo">Direct video</option><option value="directAudio">Direct audio</option><option value="torrentAudio">Torrent audio</option><option value="torrentVideo">Torrent video</option></select>
    </div>
    <div v-if="mediaType=='localAudio'|mediaType=='localVideo'" class="form-group">
        <label>Media-file:</label>
         <input id="directMedia" class="directMedia" name="directMedia" type="file">
    </div>
    <div v-if="mediaType=='directAudio'|mediaType=='directVideo'" class="form-group">
        <label>Media-source:</label>
         <input placeholder="https://server/file.mp4.mp3" class="form-control" name="source" type="text">
    </div>
    <div v-if="mediaType=='torrentAudio'|mediaType=='torrentVideo'" class="form-group">
        <label>Torrent (magnet-link)</label>
         <input placeholder="magnet://" class="form-control" name="source" type="text">
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
        <input id="posterUpload" @change="posterChange()" name="poster" type="file">
        <div id="poster"></div>
    </div>
      <div class="form-group">
          <label>Mediatitle</label>
          <input type="hidden" value="" name="image" id="addMediaImage" />
               <input placeholder="Media-title" class="form-control" name="title" type="text">
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
    <button @click="submitAction();" >Submit</button>
    </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['medias','currentTitle','swapComponent','baseUrl'],
    mounted: function () {
      this.$refs.croppieRef.bind({
        url: '/img/404/image.png',
      })
    },

    methods: {
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
      submitAction() {
        let that = this;
        $.ajax({
            url: '/media/create',
            type: 'POST',
            data: new FormData($("#theForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                that.dismissCountDown = 20;
                that.alertMsg = "Video added"
                that.alertType = "success"
                //eventBus.$emit('showAlert',['success','Video uploaded']);
              }
            }

        });
        return false;
      },
      countDownChanged (dismissCountDown) {
        this.dismissCountDown = dismissCountDown
      },
      showAlert() {
        this.dismissCountDown = this.dismissSecs
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
        dismissSecs: 20,
        dismissCountDown: 0,
        alertType: 'warning',
        alertMsg: '',
        showDismissibleAlert: false,
        cropped: null,
      }
    }
  }
</script>
