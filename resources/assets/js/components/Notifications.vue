<template>
  <div>
    <div class="" id="">
      <button class="btn btn-sm btn-primary" @click="emitMarkNotifications('/internal-api/notifications/markasread')">Mark all as read</button>
      <button class="btn btn-sm btn-primary" @click="emitMarkNotifications('/internal-api/notifications')">Refresh notifications</button>
      <button class="btn btn-sm btn-danger" @click="emitMarkNotifications('/internal-api/notifications/delete')">Delete all notifications</button>

      <h4>Notifications</h4>
      <div v-for="item in notifications"  class="text-center">
        <div v-if="item.type=='App\\Notifications\\LikeReceived'">
          <vs-divider color="success">{{ item.created_at }}</vs-divider>
           <vs-chip color="warning" class="float-right" v-if="item.read_at==null" @click="emitMarkNotifications('/internal-api/notifications/markasread/'+item.id)" closable><vs-avatar icon="markunread" />This is unread!</vs-chip>

          <p> User <router-link :to="'/profile/'+item.data.user_id">{{ getUserById(item.data.user_id).name }}</router-link> {{ getLikeString(item.data.like) }}
            <span v-if="(item.data.media_id!=null&&item.data.media_id!=0)">
              your <b>media</b>
              <router-link class="" :to="'/media/'+encodeURIComponent(getMediaById2(item.data.media_id).title)">{{ getMediaById2(item.data.media_id).title }}</router-link>
            </span>
             <span v-if="(item.data.media_id==null||item.data.media_id==0)">
               your <b>comment</b>: {{ getCommentById2(item.data.comment_id).body }} @ media
               <router-link v-if="getMediaById2(getCommentById2(item.data.comment_id).media_id)!=undefined" class="" :to="'/media/'+encodeURIComponent(getMediaById2(getCommentById2(item.data.comment_id).media_id).title)">{{ getMediaById2(getCommentById2(item.data.comment_id).media_id).title }}</router-link>
            </span>
          </p>



        </div>
    </div>

    </div>
<p class="text-center" v-if="canloadmore">Scroll to bottom to load more</p>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['fullmedias','notifications','users','baseUrl','canloadmore','loggeduserid'],
    methods: {
      getLikeString(nr) {
        if(nr==-1){
          return "disliked"
        }
        if(nr==0){
          return "reseted"
        }
        if(nr==1){
          return "liked"
        }
      },
      emitLoadMore() {
        eventBus.$emit('loadMore','');
      },
      emitMarkNotifications(url) {
        eventBus.$emit('getNotifications',url);
      },
      getMediaById2(id){
        console.log("mediabyid "+id)
        if(id==null||id==0){
          return;
        }
        var theMedia = undefined;
        this.fullmedias.forEach(function(val,key){
          if(val.id==id){
            theMedia = val;
          }
        });
        if(theMedia==undefined){
          eventBus.$emit('loadMediaById',id);
        }
        return theMedia
      },
      getCommentById2(id){
        if(id==null||id==0){
          return;
        }
        var theMedia = undefined;
        this.fullmedias.forEach(function(val,key){
          val.comments.forEach(function(val2,key2){
          if(val2.id==id){
            theMedia = val2;
          }
          });
        });
        if(theMedia==undefined){
          eventBus.$emit('loadMediaByCommentId',id);
        }
        return theMedia
      },
      getUserById(id){
        var theMedia = undefined;
        this.users.forEach(function(val,key){
          if(val.id==id){
            theMedia = val;
          }
        });
        return theMedia
      }
    },
    components : {
    }
  }
</script>
