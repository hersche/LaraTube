<template>
  <div>
    <div v-if="level==0">
    <h4>Comments <a class="btn btn-sm" @click="refreshMedia()"><vs-icon icon="refresh"></vs-icon></a></h4>
    <form class="form-inline mb-1" id="commentForm" v-if="loggeduserid!=0">
      <input id="medias_id" name="medias_id" type="hidden" :value="currentmedia.id">
      <input id="medias_title" name="medias_title" type="hidden" :value="currentmedia.title">
      <input id="parent_id" name="parent_id" type="hidden" value="0">
      <input placeholder="Comment..." class="col-9" id="comment_body" name="body" type="text">
      <input type="button" class="ml-1" value="Send comment!" @click="sendComment();" />
    </form>
  </div>
  <div v-for="comment in commentlist" class="comment mb-2 row" :style="'margin-left:'+Number(level*10)+'px'" :id='"cid"+comment.id'>
      <div class="comment-content col-md-11 col-sm-10">
          <h6 class="small comment-meta"><router-link class="btn btn-sm btn-primary mr-2" :to="'/profile/'+comment.user.id"><img class="" style="width:25px;"  :src="'/'+comment.user.avatar" alt="avatar" /> {{ comment.user.name }}</router-link> {{ comment.created_at_readable}}
              <span @click="openConfirm(comment.id)" v-if="loggeduserid==comment.user_id" class="float-right btn btn-sm btn-danger" onclick=""><vs-icon icon="delete"></vs-icon></span>
          </h6>
          <div class="comment-body">
          <p v-html="comment.body"></p>
          <p>
            <div class="">
            <button id="like" v-if="comment.myLike==1" type="button" @click="like(comment,0,'like')" class="btn btn-sm btn-success">
              <vs-icon icon="thumb_up"></vs-icon>
              <span class="ml-1" id="likeCount">{{ comment.likes }}</span>
            </button>
            <button id="like" v-else type="button" @click="like(comment,1,'like')" class="btn btn-sm btn-primary">
              <vs-icon icon="thumb_up"></vs-icon>
              <span class="ml-1" id="likeCount">{{ comment.likes }}</span>
            </button>

            <button id="dislike" v-if="comment.myLike==-1" type="button" @click="like(comment,0,'dislike')" class="btn btn-sm btn-success">
              <vs-icon icon="thumb_down"></vs-icon>
              <span class="ml-1" id="dislikeCount">{{ comment.dislikes }}</span>
            </button>
            <button id="dislike" v-else type="button" @click="like(comment,-1,'dislike')" class="btn btn-sm btn-primary">
              <vs-icon icon="thumb_down"></vs-icon>
              <span class="ml-1" id="dislikeCount">{{ comment.dislikes }}</span>
            </button>
          </div>
              <vs-collapse class="">
                <vs-collapse-item>
                  <div slot="header">
                    <vs-icon icon="reply"></vs-icon> Reply
                  </div>
                  <form class="form-inline mb-1" :id="'commentForm'+comment.id" v-if="loggeduserid!=0">
                    <input id="medias_id" name="medias_id" type="hidden" :value="currentmedia.id">
                    <input id="medias_title" name="medias_title" type="hidden" :value="currentmedia.title">
                    <input id="parent_id" name="parent_id" type="hidden" :value="comment.id">
                    <input placeholder="Comment..." class="col-9" :id="'comment_body'+comment.id" name="body" type="text">
                    <input type="button" class="ml-1" value="Send comment!" @click="sendComment(comment.id);" />
                  </form>

                </vs-collapse-item>
              </vs-collapse>

            </p>

            <div class="col-12">
      <comments v-bind:commentlist="comment.childs" v-bind:level="Number(level)+1" v-bind:loggeduserid="loggeduserid" v-bind:currentmedia="currentmedia" ></comments>
    </div>
          </div>

      </div>
    </div>
  </div>
</template>

<script>
import { eventBus } from '../eventBus.js';
  export default {
    props: [ 'commentlist','loggeduserid','currentmedia','level'],
    name: 'comments',
        methods: {
          openConfirm(id){
            this.tmpid = id
            this.$vs.dialog({
              type:'confirm',
              color: 'danger',
              title: `Delete comment?`,
              text: 'Delete a comment can not be reverted. Are you shure?',
              accept:this.deleteComment
            })
          },
          deleteComment() {
            console.log(this.tmpid)
            let that = this;
            $.ajax({
                url: '/internal-api/comment/'+this.tmpid,
                type: 'DELETE',
                cache: false,
                contentType: false,
                processData: false,
                complete : function(res) {
                  if(res.status==200){
                    eventBus.$emit('commentCreated',res.responseJSON);
                  }
                }

            });
            return false;
          },
          refreshMedia(id=''){
            eventBus.$emit('refreshMedia',this.currentmedia.id);
          },
          sendComment(id=''){
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
                  eventBus.$emit('commentCreated',res.responseJSON);
                }

            });
          },
          like(comment,l,kind){
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
                url: '/like?comment_id='+comment.id+'&count='+l,
                type: 'GET',
                contentType: "application/json",
                cache: false,
                complete : function(res) {
                  /*if(res.status==201){
                    eventBus.$emit('videoCreated',res.responseJSON);
                  }*/
                  comment.myLike=l;
                }

            });
          },
        },

        data(){
          return {
            tmpid: 0
          }
        }
  }
</script>
