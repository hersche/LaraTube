<template>
    <div v-if="currentmedia!=undefined" class="col-xs-12 col-sm-12 col-md-12">
      <h4>Edit media</h4>
      <form id="theForm">
        <div class="form-group row">
          <label>Media-title</label>
          <input type="hidden" name="_token" :value="csrf">
          <input type="hidden" value="" name="image" id="addMediaImage" />
          <input placeholder="Media-title" class="form-control" :value="currentmedia.title" name="title" type="text">
        </div>
        <div class="form-group row">
          <label>Media-type (only for restore):</label>
          <select name="type" v-model="mediaType"><option value="localAudio">Local audio</option><option value="localVideo">Local video</option><option value="directVideo">Direct video</option><option value="directAudio">Direct audio</option><option value="torrentAudio">Torrent audio</option><option value="torrentVideo">Torrent video</option></select>
        </div>
        <div class="form-group row">
          <label>Category</label>
          <treeselect v-model="catid" name="category_id" :multiple="false" :options="treecatptions" />
          <!-- <select name="category_id" v-model="catid" ><option value="">None</option><option v-for="item in categories" :value="item.id">{{ item.title }}</option></select> -->
        </div>
        <div class="form-group row">
          <label>Source:</label>
          <p>{{currentmedia.source}}</p>
        </div>
        <div class="form-group row">
          <label>Media-poster:</label>
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
          <input id="posterUpload" accept=".png,.jpg,.jpeg" @change="posterChange()" name="unimportant" type="file">
          <div id="poster"></div>
        </div>
        <div class="form-group row">
          <label>Media-description:</label>
          <textarea placeholder="Media-description" id="addMediaDescription" class="form-control" :value="rmBr(currentmedia.description)" name="description" cols="50" rows="10"></textarea>
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
      <button @click="submitAction();" class="btn btn-success" >Save</button> <button @click="openConfirm();" class="btn btn-danger float-right" >Delete</button>
    </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import { Media }  from '../models';
  export default {
    props: ['medias','baseUrl','categories','csrf','treecatptions'],
    mounted: function () {
    //  this.$refs.croppieRef.bind({
    //    url: '/img/404/poster.png',
    //  })
    this.currentmedia=this.getCurrentMedia();
    },
    updated: function () {
      this.$nextTick(function () {
        if(this.$refs.croppieRef!=undefined&this.editpicloaded==false){
          this.currentmedia=this.getCurrentMedia();
          this.editpicloaded=true;
          this.$refs.croppieRef.bind({
            url: this.currentmedia.poster_source,
          })
        }
      })
    },
    computed: {
      // a computed getter
    },
    watch:{
      medias: function(val){
        this.currentmedia = this.getCurrentMedia();
      },
    },
    methods: {
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
      getCurrentMedia(){
        let that = this;
        var theMedia;
        this.medias.forEach(function(val,key){
          if(val.urlTitle==encodeURIComponent(that.$route.params.editTitle)){
            theMedia = val;
            that.mediaType=val.type;
            that.catid = val.category_id;
            //console.log(val.tracks)
          }
        });
        if(theMedia==undefined){
          console.log("media not there for edit yet, want it!");
          eventBus.$emit('loadMedia',encodeURIComponent(that.$route.params.editTitle));
        }
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
        currentmedia:undefined,
        catid:'',
        tmpid:0,
        editpicloaded:false,
        showdismissiblealert: false,
        cropped: null,
      }
    }
  }
</script>
