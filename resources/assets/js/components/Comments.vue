<template>
  <div>
    <div v-if="level==0">
    <h4>{{ $t('Comments') }} <a class="btn btn-sm" @click="refreshMedia()"><vs-icon icon="refresh"></vs-icon></a></h4>
<!--  <vs-tabs v-if="loggeduserid!=0">
    <vs-tab vs-label="Edit">
      <div>
    <form class="form-inline mb-1" id="commentForm" >
      <input type="hidden" name="_token" :value="csrf">
      <input id="media_id" name="media_id" type="hidden" :value="currentmedia.id">
      <input id="parent_id" name="parent_id" type="hidden" value="0">
      <p class="col-12"><vs-textarea :label="$t('Comment')" :placeholder="$t('Comment')+'...'" v-model="tmptexts[0]" id="comment_body" name="body" /></p>
      <input type="button" class="ml-1 btn btn-sm btn-success float-right" :value="$t('Send')+' '+$t('comment')" @click="sendComment();" />
    </form>
  </div>
  </vs-tab>
  <vs-tab vs-label="Preview">
    <div>
      <VueMarkdown v-if="tmptexts[0]!=''" :source="tmptexts[0]"></VueMarkdown>
      <h1 v-if="tmptexts[0]==''||tmptexts[0]==null">No text for preview</h1>
    </div>
  </vs-tab>
</vs-tabs> -->
    <form class="form-inline mb-1 col-12" id="commentForm" >
      <input type="hidden" name="_token" :value="csrf">
      <input id="media_id" name="media_id" type="hidden" :value="currentmedia.id">
      <input id="parent_id" name="parent_id" type="hidden" value="0">
      <MarkdownCreator class="col-12" v-if="loggeduserid!=0" :theText="tmptexts[0]" theId="body" :theTitle="$t('Comment')" :theHint="$t('What you think about it')+'...'" ></MarkdownCreator>
      <input type="button" class="ml-1 btn btn-sm btn-success float-right" :value="$t('Send')+' '+$t('comment')" @click="sendComment();" />
    </form>

  </div>
  <div v-for="(comment,i) in commentlist" :class="'comment mb-2 row col-12 pl-'+Number(level)" :id='"cid"+comment.id'>
      <div class="comment-content col-12">
          <h6 class="small comment-meta"><router-link class="btn btn-sm btn-primary mr-2" :to="'/profile/'+comment.user.id"><img class="" style="width:25px;"  :src="'/'+comment.user.avatar" alt="avatar" /> {{ comment.user.name }}</router-link> {{ comment.created_at_readable}}
              <span @click="openConfirm(comment.id)" v-if="loggeduserid==comment.user_id" class="float-right btn btn-sm btn-danger" onclick=""><vs-icon icon="delete"></vs-icon></span>
          </h6>
          <div class="comment-body">
          <p><VueMarkdown :source="comment.body"></VueMarkdown></p>
          <div class="row">

            <!--  <vs-collapse class="col-8 col-md-8 col-sm-12 float-left" v-if="loggeduserid!=0">
                <vs-collapse-item >
                  <div slot="header">
                    <vs-icon icon="reply"></vs-icon>{{ $t("Reply") }}
                  </div>
                  <vs-tabs v-if="loggeduserid!=0">
                    <vs-tab vs-label="Edit">
                      <div>
                  <form class="form-inline mb-1" :id="'commentForm'+comment.id" >
                    <input type="hidden" name="_token" :value="csrf">
                    <input id="media_id" name="media_id" type="hidden" :value="currentmedia.id">
                    <input id="parent_id" name="parent_id" type="hidden" :value="comment.id">
                    <p class="col-12"><vs-textarea :label="$t('Comment')" :placeholder="$t('Comment')+'...'" v-model="tmptexts[comment.title]"  :id="'comment_body'+comment.id" name="body" /></p>
                    <input type="button" class="ml-1 btn btn-sm btn-success float-right" :value="$t('Send')+' '+$t('comment')" @click="sendComment(comment.id);" />
                  </form>
                </div>
              </vs-tab>
              <vs-tab vs-label="Preview">
                <div>
                  <VueMarkdown v-if="tmptexts[comment.title]!=''" :source="tmptexts[comment.title]"></VueMarkdown>
                  <h1 v-if="tmptexts[comment.title]==''||tmptexts[comment.title]==null">No text for preview</h1>
                </div>
              </vs-tab>
            </vs-tabs> -->
            <div class="col-12 pl-0 pr-0">
            <v-btn id="like" color="success" v-if="comment.myLike==1" type="button" @click="like(comment,0,'like')" class="float-left">
              <v-icon>thumb_up</v-icon>
              <span class="small" id="likeCount">{{ comment.likes }}</span>
            </v-btn>
            <v-btn id="like" v-else type="button" @click="like(comment,1,'like')" class="float-left">
              <v-icon>thumb_up</v-icon>
              <span class="small" id="likeCount">{{ comment.likes }}</span>
            </v-btn>

            <v-btn id="dislike" color="success" v-if="comment.myLike==-1" type="button" @click="like(comment,0,'dislike')" class="float-right">
              <v-icon>thumb_down</v-icon>
              <span class="small ml-1" id="dislikeCount">{{ comment.dislikes }}</span>
            </v-btn>
            <v-btn id="dislike" v-else type="button" @click="like(comment,-1,'dislike')" class="float-right">
              <v-icon>thumb_down</v-icon>
              <span class="small ml-1" id="dislikeCount">{{ comment.dislikes }}</span>
            </v-btn>
          </div>
            <v-expansion-panel
             v-if="loggeduserid!=0"
            expand
            >
              <v-expansion-panel-content
              >        
        <div slot="header">{{ $t('Answer') }}</div>
            <form class="form-inline mb-1 col-12" id="commentForm" >
              <input type="hidden" name="_token" :value="csrf">
              <input id="media_id" name="media_id" type="hidden" :value="currentmedia.id">
              <input id="parent_id" name="parent_id" type="hidden" :value="comment.id">
              <MarkdownCreator class="col-12" v-if="loggeduserid!=0" :theText="tmptexts[0]" theId="body" :theTitle="$t('Comment')" :theHint="$t('What you think about it')+'...'" ></MarkdownCreator>
              <input type="button" class="ml-1 btn btn-sm btn-success float-right" :value="$t('Send')+' '+$t('comment')" @click="sendComment(comment.id);" />
            </form>
            

          </v-expansion-panel-content>
        </v-expansion-panel>


      </div>

            <div class="col-12">
      <comments v-bind:csrf="csrf" v-bind:commentlist="comment.childs" v-bind:level="Number(level)+1" v-bind:loggeduserid="loggeduserid" v-bind:currentmedia="currentmedia" ></comments>
    </div>
          </div>

      </div>
    </div>
  </div>
</template>

<script>
import { eventBus, store } from '../eventBus.js';
import VueMarkdown from 'vue-markdown'
  import MarkdownCreator from './MarkdownCreator'
  export default {
    props: [ 'commentlist','currentmedia','level'],
    name: 'comments',
    components: {
      VueMarkdown,
      MarkdownCreator
    },
    computed: {
      csrf: function(){
        return store.getters.getCSRF()
      },
      loggeduserid: function(){
        return store.state.loginId
      },
    },
        methods: {
          openConfirm(id){
            this.tmpid = id
            this.$vs.dialog({
              type:'confirm',
              color: 'danger',
              title: this.$t('Delete')+' '+this.$t('comment')+'?',
              text: 'Delete a comment can not be reverted. Are you shure?',
              accept:this.deleteComment
            })
          },
          deleteComment() {
            let that = this;
            $.ajax({
                url: '/internal-api/comment/'+this.tmpid,
                type: 'DELETE',
                cache: false,
                contentType: false,
                processData: false,
                complete : function(res) {
                  if(res.status==200){
                    that.$vs.notify({title:that.$t("Comment") + " " + that.$t("deleted"),text:'',icon:'',color:'success',position:'bottom-center'})
                    eventBus.$emit('refreshMedia',that.currentmedia.id);
                  }
                }

            });
            return false;
          },
          refreshMedia(id=''){
            this.$vs.notify({title:this.$t("Media")+" "+this.$t("refreshed"),text:'',icon:'',color:'success',position:'bottom-center'})
            eventBus.$emit('refreshMedia',this.currentmedia.id);
          },
          sendComment(id=''){
            if(this.loggeduserid==0){
              this.$vs.notify({title:'You can not comment',text:'Log in to like or comment',icon:'',color:'danger',position:'bottom-center'})
              return
            }
            let that = this
            //console.log($("#commentForm")[0])
            //console.log(new FormData($("#commentForm")[0]))
            $.ajax({
                url: '/comment',
                type: 'POST',
                data: new FormData($("#commentForm"+id)[0]),
                cache: false,
                mylike:0,
                likes:0,
                dislikes:0,
                contentType: false,
                processData: false,
                complete : function(res) {
                  if(res.status==200){
                  }
                  $("#comment_body"+id).val("")
                  that.$vs.notify({title:that.$t("Comment") + " " + that.$t("created"),text:'',icon:'',color:'success',position:'bottom-center'})
                  eventBus.$emit('refreshMedia',that.currentmedia.id);
                }

            });
          },
          like(comment,l,kind){
            if(this.loggeduserid==0){
              this.$vs.notify({title:'You can not vote',text:'Log in to like or comment',icon:'',color:'danger',position:'bottom-center'})
              return
            }
            let that = this;
            // TODO review this logic.. done?
            if((kind=="like")){
              if(comment.myLike==-1){
                comment.dislikes -= 1;
              }
              if(l==0&&comment.myLike==1){
                comment.likes-=1;
              } else if(l==1&&comment.myLike!=1) {
                comment.likes += 1;
              }
            }
            if(kind=="dislike"){
              if(comment.myLike==1){
                comment.likes -= 1;
              }
              if(l==0&&comment.myLike==-1){
                comment.dislikes-=1;
              } else if(l==-1&&comment.myLike!=-1) {
                comment.dislikes+=1;
              }
            }
            $.ajax({
                url: '/like?comment_id='+comment.id+'&count='+l+'&_token='+that.csrf,
                type: 'GET',
                contentType: "application/json",
                cache: false,
                complete : function(res) {
                  /*if(res.status==201){

                  }*/
                  eventBus.$emit('refreshMedia',comment.media_id);
                  comment.myLike=l;
                }

            });
          },
        },

        data(){
          return {
            tmpid: 0,
            tmptexts:[]
          }
        }
  }
</script>
