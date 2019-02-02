<template>
  <div>
    <h3>{{ $t("My") }} {{ $t("medias") }}</h3>
    <div class="row text-center text-lg-left" id="profilevideos">
    <div v-for="item in medias" v-if="item.user_id==loggeduserid"  class="col-lg-4 col-md-4 col-xs-6">
        <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
        <router-link class="btn btn-sm btn-info float-left" :to="'/mediaedit/'+item.urlTitle"><vs-icon icon="edit"></vs-icon>{{ $t("Edit") }}</router-link>
        <button class="btn btn-sm btn-danger float-right" @click="openConfirm(item.id,item.title)" ><vs-icon icon="delete"></vs-icon>{{ $t("Delete") }}</button>
    </div>

    </div>
  </div>
</template>
<script>
  import { eventBus, store } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['baseUrl','canloadmore','loggeduserid'],
    data(){
      return {
        tmpid: 0,
        tmptitle:'',
        medias:store.state.medias

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
