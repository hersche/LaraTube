<template>
  <div>
    <h3>{{ $t("My") }} {{ $t("medias") }}</h3>
    <div class="row text-center text-lg-left" id="profilevideos">
    <div v-for="item in medias" v-if="item.user_id==loggeduserid"  class="col-lg-4 col-md-4 col-xs-6">
        <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
        <v-btn color="green" class="float-left" :to="'/mediaedit/'+item.urlTitle"><v-icon>edit</v-icon>{{ $t("Edit") }}</v-btn>
        <v-btn color="red" class="float-right" @click="openConfirm(item.id,item.title)" ><v-icon>delete</v-icon>{{ $t("Delete") }}</v-btn>
    </div>

    </div>
  </div>
</template>
<script>
  import { eventBus, store } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['baseUrl','canloadmore'],
    computed:{
      medias:function(){
        return store.getters.getMediasByUserId(store.state.loginId)
      },
      loggeduserid: function(){
        return store.state.loginId
      }
    },
    data(){
      return {
        tmpid: 0,
        tmptitle:''

      }
    },
    methods: {
      openConfirm(id,title){
        this.tmpid = id;
        this.tmptitle = title
        this.$vs.dialog({
          type:'confirm',
          color: 'danger',
          title: $t('Delete')+' '+$t('media')+'?',
          text: $t('Delete')+' a media can not be reverted. Are you shure?',
          accept:this.deleteAction
        })
      },
      emitLoadMore() {
        eventBus.$emit('loadMore','');
      },
      deleteAction() {
        let that = this;
        $.ajax({
            url: '/internal-api/media/'+that.tmpid,
            type: 'DELETE',
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                //eventBus.$emit('showAlert',['success','Video uploaded']);

              }
              eventBus.$emit('videoDeleted',that.tmptitle);
            }

        });
        return false;
      },
    },
    mounted(){
      eventBus.$emit('loadUserVideos',this.loggeduserid);
    },
    components : {
        'singleField': SingleGalleryField
    }
  }
</script>
