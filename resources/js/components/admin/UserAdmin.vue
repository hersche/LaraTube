<template>
  <div>
    
    <v-data-table
    :items="users"
    :headers="[
      { text: 'Id', value: 'id' },
      { text: 'Name', value: 'name' },
      { text: 'Email', value: 'email' },
      { text: 'Roles', value: 'roles' },
      ]"
      :expand="true"
      >
        <template slot="items" slot-scope="props">
          <tr @click="props.expanded = !props.expanded">
            <td class="text-xs-right">{{ props.item.id }}</td>
            <td class="text-xs-right">{{ props.item.name }}</td>
            <td class="text-xs-right">{{ props.item.email }}</td>
            <td class="text-xs-right">{{ props.item.roles }}</td>
          </tr>
        </template>
        <template slot="expand" slot-scope="props">
          <v-card flat>
            <form :id="'urolesform'+props.item.id">
              <v-select
              :items="selectRoles"
              v-model="selectedRoles[props.item.id]"
              style="z-index:99999 !important"
              deletable-chips
              attach
              :label="$t('Roles (empty on start)')"
              multiple
              ></v-select>
              <input type="hidden" :value="csrf" name="_token">
              <input type="hidden" :value="props.item.id" name="uid">
            </form>
            <v-btn @click="changeRoles(props.item.id)" small color="green">
              <v-icon>edit</v-icon>Apply roles
            </v-btn>
            <v-img :src="props.item.background">
              <v-container fill-height fluid>
                <v-layout fill-height>
                  <v-flex xs12 align-end flexbox> 
                    <v-avatar large>
                      <img :src="props.item.avatar" :alt="props.item.name">
                    </v-avatar>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-img>
            <v-card-title primary-title>
              {{ props.item.name }}
            </v-card-title>
            <v-card-text>
              <VueMarkdown :source="props.item.bio"></VueMarkdown>
            </v-card-text>
            <v-card-actions>
              <v-btn small :to="'/profile/'+props.item.id" color="green" icon><v-icon>send</v-icon></v-btn>
              <v-btn  @click="openConfirm(props.item.id)" small color="red" icon><v-icon>delete_sweep</v-icon></v-btn>
            </v-card-actions>
          </v-card>
        </template>
      </v-data-table>
        <form id="hiddenCSRFForm" class="d-none">
          <input type="hidden" name="_token" :value="csrf">
        </form>
      </div>
    </template>
<script>
import { eventBus,store } from '../../eventBus.js';
  import VueMarkdown from 'vue-markdown'
  const $ = require('jquery');
  export default {
    props: ['baseUrl','canloadmore'],
    data(){
      return {
        tmpid: 0,
        selectedRoles:[]
      }
    },
    mounted: function () {

    },
    computed: {
      csrf: function(){
        return store.getters.getCSRF()
      },
      currentuser(){
        return store.getters.getUserById(store.state.loginId)
      },
      loggeduserid(){
        return store.state.loginId
      },
      users:function(){
        return store.state.users
      },
      selectRoles:function(){
        var sr = [];
        let that = this
      /*  $.each( store.state.users, function( key, value ) {
          //sr.push({value:value.slug,text:value.name})
          that.selectedRoles[value.id] = that.convertRoles(value.roles)
        });*/
        $.each( store.state.roles, function( key, value ) {
          sr.push({value:value.slug,text:value.name})

        });
        return sr
      }
    },
    methods: {
      convertRoles(r){
        var sr = [];
        $.each( r.roles, function( key, value ) {
          sr.push(value)
        });
        this.selectedRoles[r.id] = r.roles;
        return sr
      },
      openConfirm(id){
        this.tmpid = id
        this.$vs.dialog({
          type:'confirm',
          color: 'danger',
          title: `Delete user?`,
          text: 'Delete a user can not be reverted. Are you shure?',
          accept:this.deleteAction
        })
      },
      changeRoles(id) {
        var sr = this.selectedRoles[id];
        var d = new FormData($("#urolesform"+id)[0]);
        d.append('roles',String(sr))
        $.ajax({
            url: '/internal-api/users/changeroles',
            type: 'POST',
            data:d,
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                store.commit("setUsers",res.responseJSON.data)
              }
            }
        });
        return false;
      },
      deleteAction() {
        $.ajax({
            url: '/internal-api/user/'+this.tmpid,
            type: 'DELETE',
            data:new FormData($("#hiddenCSRFForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                store.commit("setUsers",res.responseJSON.data)
              }
            }
        });
        return false;
      },
    },
    components : {
        VueMarkdown
    }
  }
</script>

<style lang="stylus">
.con-expand-users
  .con-btns-user
    display flex
    padding 10px
    padding-bottom 0px
    align-items center
    justify-content space-between
    .con-userx
      display flex
      align-items center
      justify-content flex-start
  .list-icon
    i
      font-size .9rem !important
</style>
