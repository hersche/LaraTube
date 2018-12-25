<template>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <b-alert style="position: fixed; top: 75px;" :show="dismisscountdown"
               dismissible
               :variant="alertType"
               @dismissed="dismisscountdown=0"
               @dismiss-count-down="countDownChanged">
        <p>{{ alertMsg }}</p>
        <b-progress :variant="alertType"
                    :max="dismisssecs"
                    :value="dismisscountdown"
                    height="4px">
        </b-progress>
      </b-alert>
    <h4>Edit media</h4>
    <form id="theForm">
      <div class="form-group">
          <label>Media-title</label>
          <input type="hidden" value="" name="image" id="addMediaImage" />
               <input placeholder="Media-title" class="form-control" :value="currentmedia.title" name="title" type="text">
      </div>
    <div class="form-group">
        <label>Media-type (only for restore):</label>
         <select name="type" v-model="currentmedia.type"><option value="localAudio">Local audio</option><option value="localVideo">Local video</option><option value="directVideo">Direct video</option><option value="directAudio">Direct audio</option><option value="torrentAudio">Torrent audio</option><option value="torrentVideo">Torrent video</option></select>
    </div>
    <div class="form-group">
        <label>Source:</label>
         <p>{{currentmedia.source}}</p>
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
          <label>Media-description:</label>
          <textarea placeholder="Media-description" id="addMediaDescription" class="form-control" :value="currentmedia.description" name="description" cols="50" rows="10"></textarea>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
              <strong>Tags (separate with spaces):</strong>
              <input id="tags" type="text" class="form-control" name="tags" :value="currentmedia.tagString" >
          </div>
      </div>


    </form>

    <button @click="submitAction();" class="btn btn-success" >Save</button> <button @click="deleteAction();" class="btn btn-danger" >Delete</button>
    </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import { Media }  from '../models';
  export default {
    props: ['medias','currentTitle','baseUrl'],
    mounted: function () {
      this.$refs.croppieRef.bind({
        url: '/img/404/image.png',
      })
    },
    updated: function () {
      this.$nextTick(function () {
        if(this.$refs.croppieRef!=undefined){
          this.$refs.croppieRef.bind({
            url: this.currentmedia.poster_source,
          })
        }
      })
    },
    computed: {
      // a computed getter
      currentmedia: function () {
        var m = this.getCurrentMedia();
        if(m==undefined){
          return new Media(0,"None","","","","","","","","","","","")
        }
        return m;
      }
    },

    methods: {
      getCurrentMedia(){
        let that = this;
        var theMedia;
        this.medias.forEach(function(val,key){
          if(val.title==that.$route.params.editTitle){
            theMedia = val;
          }
        });
        return theMedia;
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
      submitAction() {
        let that = this;
        $.ajax({
            url: '/api/media/'+this.currentmedia.title,
            type: 'POST',
            data: new FormData($("#theForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                that.dismisscountdown = 20;
                that.alertMsg = "Video edited"
                that.alertType = "success"
                //eventBus.$emit('showAlert',['success','Video uploaded']);
              }
              eventBus.$emit('videoDeleted',that.currentmedia.title);
              eventBus.$emit('videoCreated',res.responseJSON);
            }

        });
        return false;
      },
      deleteAction() {
        let that = this;
        $.ajax({
            url: '/api/media/'+this.currentmedia.title,
            type: 'DELETE',
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                that.dismisscountdown = 20;
                that.alertMsg = "Video deleted"
                that.alertType = "success"
                //eventBus.$emit('showAlert',['success','Video uploaded']);

              }
              eventBus.$emit('videoDeleted',that.currentmedia.title);
            }

        });
        return false;
      },
      countDownChanged (dismisscountdown) {
        this.dismisscountdown = dismisscountdown
      },
      showAlert() {
        this.dismisscountdown = this.dismisssecs
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
        dismisssecs: 20,
        dismisscountdown: 0,
        alertType: 'warning',
        alertMsg: '',
        showdismissiblealert: false,
        cropped: null,
      }
    }
  }
</script>
