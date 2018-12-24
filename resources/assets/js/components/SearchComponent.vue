<template>
<div >
  <span class="btn btn-success"  @click='emitRefreshSearch("Users")' ><input id="theLiveSearchUsers" checked="checked" type="checkbox" class="d-none" />Users</span>
  <span class="btn btn-success" @click='emitRefreshSearch("MediaTitle")' ><input id="theLiveSearchMediaTitle" checked="checked" type="checkbox" class="d-none" />Media-title</span>
  <span class="btn btn-primary" @click='emitRefreshSearch("MediaDescription")' ><input id="theLiveSearchMediaDescription" type="checkbox" class="d-none" />Media-description</span>
  <span class="btn btn-primary" @click='emitRefreshSearch("Tags")' ><input id="theLiveSearchTags" type="checkbox" class="d-none" />Tags</span>
  <h3>Media-results</h3>
  <div >
    <gallery v-bind:tagenabled="tagsen" v-bind:medias="search.mediaResult" v-bind:tags="tags" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></gallery>
  </div>
    <h3>User-results</h3>
    <div v-if="users.length>0" >
      <userlist v-bind:medias="medias" v-bind:users="search.userResult" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></userlist>
    </div>
      <div v-else>
        No user-results
      </div>
</div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import TagComponent from './TagComponent'
  import UserList from './UserList'
  export default {
    props: ['item','loggeduserid','search','medias','canloadmore','loggeduserid','tags','users'],
    data(){
      return {
        tagsen:false
      }
    },
    components : {
        'userlist': UserList,
        'gallery': TagComponent
    },
    mounted(){
      //searching('');
      console.log(this)
      this.$parent.$nextTick(function () {
  // DOM updated
  eventBus.$emit('refreshSearch','');
      })

    },
    methods: {
      emitRefreshSearch(id) {

        if($("#theLiveSearch"+id).is(':checked')){
          $("#theLiveSearch"+id).prop('checked', false);
          $("#theLiveSearch"+id).parent().addClass("btn-primary")
          $("#theLiveSearch"+id).parent().removeClass("btn-success")
        } else {
          $("#theLiveSearch"+id).prop('checked', true);
          $("#theLiveSearch"+id).parent().removeClass("btn-primary")
          $("#theLiveSearch"+id).parent().addClass("btn-success")
        }
        if(id=="Tags"){
          this.tagsen = $("#theLiveSearch"+id).is(':checked');
        }
        eventBus.$emit('refreshSearch','');
      }
    }
  }
</script>
