<template>
<div v-if="search!=undefined">
  <span class="btn btn-success"  @click='emitRefreshSearch("Users")' ><input id="theLiveSearchUsers" checked="checked" type="checkbox" class="d-none" />{{ $t('Users') }}</span>
  <span class="btn btn-success" @click='emitRefreshSearch("MediaTitle")' ><input id="theLiveSearchMediaTitle" checked="checked" type="checkbox" class="d-none" />{{ $t('Media') }} {{ $t('Title') }}</span>
  <span class="btn btn-primary" @click='emitRefreshSearch("MediaDescription")' ><input id="theLiveSearchMediaDescription" type="checkbox" class="d-none" />{{ $t('Media') }} {{ $t('Description') }}</span>
  <span class="btn btn-primary" @click='emitRefreshSearch("Tags")' ><input id="theLiveSearchTags" type="checkbox" class="d-none" />{{ $t('Tags') }}</span>
  <h3>{{ $t("Media") }}-{{ $t("results") }}</h3>
  <div >
    <gallery v-bind:tagenabled="tagsen" v-bind:medias="search.mediaResult" v-bind:tags="tags" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></gallery>
  </div>
    <h3>{{ $t("User") }}-{{ $t("results") }}</h3>
    <div v-if="search.userResult.length>0" >
      <userlist v-bind:users="search.userResult" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></userlist>
    </div>
      <div v-else>
        {{ $t("No user-results") }}
      </div>
</div>
</template>
<script>
  import { eventBus,store } from '../eventBus.js';
  import Tags from './Tags'
  import UserList from './UserList'
  export default {
    props: ['item','search','canloadmore'],
    data(){
      return {
        tagsen:false,
        
      }
    },
    components : {
        'userlist': UserList,
        'gallery': Tags
    },
    mounted(){
      this.$parent.$nextTick(function () {
        eventBus.$emit('refreshSearch','');
      })

    },
    computed: {
      currentuser:function(){
        return store.getters.getUserById(store.state.loginId)
      },
      loggeduserid:function(){
        return store.state.loginId
      },
      tags:function(){
        return store.state.tags
      },
      medias:function(){
        return store.getters.getMediasByTypes()
      }
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
