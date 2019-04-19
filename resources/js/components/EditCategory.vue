<template>
    <div v-if="currentcat!=undefined" class="col-xs-12 col-sm-12 col-md-12">
      <h4>Edit category</h4>
      <form id="theForm">
          <input type="hidden" name="_token" :value="csrf">
          <input type="hidden" value="" name="image" id="addMediaImage" />
          <v-text-field
            :label="$t('Title')"
            name="title"
            :value="currentcat.title"
            ></v-text-field>
            <MarkdownCreator :theText="currentcat.description" theId="description" theTitle="Description" ></MarkdownCreator>

        <div class="form-group row">
          <label>{{ $t('Parent') }}-{{ $t('category') }}</label>
          <treeselect v-model="catid" name="parent_id" :multiple="false" :options="treecatptions" />
        </div>

      </form>

      <v-btn @click="submitAction();" color="green" ><v-icon>save</v-icon>{{ $t('Save') }}</v-btn> <v-btn @click="openConfirm();" color="red" class="float-right" ><v-icon>delete</v-icon>Delete</v-btn>
    </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import { Media }  from '../models';
  import MarkdownCreator from './MarkdownCreator'
  export default {
    props: ['baseUrl','treecatptions'],
    components: {
      MarkdownCreator
    },
    mounted: function () {
    //  this.$refs.croppieRef.bind({
    //    url: '/img/404/poster.png',
    //  })
      this.getCurrentCat();
    },
    updated: function () {
      this.$nextTick(function () {
        if(this.$refs.croppieRef!=undefined&this.editpicloaded==false){
        //  this.currentmedia=this.getCurrentMedia();
          this.editpicloaded=true;
          this.getCurrentCat();
        //  this.getCurrentCat();
          this.$refs.croppieRef.bind({
            url: this.currentmedia.poster_source,
          })
        }
      })
    },
    computed: {
      csrf: function(){
        return store.getters.getCSRF()
      },
      categories:function(){
        return store.state.categories
      }
    },
    watch:{
      categories: function(val){
        this.getCurrentCat();
      }
    },
    methods: {
      getCurrentCat(l=undefined){
        console.log("getCurrentCat")
        if(l==undefined){
          l = this.categories
        }
        //if(this.currentcat!=undefined){
          //return;
        //}
        let that = this;
        l.forEach(function(val,key){
          if(that.$route.params.catId==val.id){
            that.catid = val.parent_id
            console.log("found cat")
            that.currentcat=val;
          }
          if(val.children.length>0){
            that.getCurrentCat(val.children)
          }
        });
      },
      rmBr(str) {
        return str.replace(/<br\s*\/?>/mg,"");
      },
      openConfirm(){
        this.$vs.dialog({
          type:'confirm',
          color: 'danger',
          title: `Delete category?`,
          text: 'Delete a category can not be reverted. Are you shure?',
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
            url: '/internal-api/category/'+this.currentcat.id,
            type: 'POST',
            data: new FormData($("#theForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
              }
              eventBus.$emit('categoriesRefreshed','')
            }

        });
        return false;
      },
      deleteAction() {
        let that = this;
        $.ajax({
            url: '/internal-api/category/'+this.currentcat.id,
            type: 'DELETE',
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                //eventBus.$emit('showAlert',['success','Video uploaded']);

              }
              eventBus.$emit('categoriesRefreshed','')
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
        catid:0,
        tmpid:0,
        currentcat:undefined,
        editpicloaded:false,
        showdismissiblealert: false,
        cropped: null,
      }
    }
  }
</script>
