<template>
  <div>
    
    <v-data-table
  :items="users"
  :headers="[
  { text: 'Id', value: 'id' },
  { text: 'Name', value: 'name' },
  { text: 'Email', value: 'email' },
  { text: 'Admin', value: 'admin' },
  ]"
  :expand="true"
>
  <template slot="items" slot-scope="props">
    <tr @click="props.expanded = !props.expanded">
    <td class="text-xs-right">{{ props.item.id }}</td>
    <td class="text-xs-right">{{ props.item.name }}</td>
    <td class="text-xs-right">{{ props.item.email }}</td>
    <td class="text-xs-right">{{ props.item.admin }}</td>
  </tr>
  </template>
  <template slot="expand" slot-scope="props">
  <v-card flat>
    <v-card-title>
      {{ props.item.name }}
    </v-card-title>
    <v-card-text>
      {{ props.item.bio }}

    </v-card-text>
    <v-card-actions>
          <v-btn small :to="'/profile/'+props.item.id" color="green" icon><v-icon>send</v-icon></v-btn>
          <v-btn  @click="openConfirm(props.item.id)" small color="red" icon><v-icon>delete_sweep</v-icon></v-btn>
          <v-btn v-if="props.item.admin"  @click="rmAdmin(props.item.id)" small color="red" >Unmake admin</v-btn>
          <v-btn v-if="props.item.admin==false"  @click="mkAdmin(props.item.id)" small color="red">Make admin</v-btn>
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
  import { eventBus, store } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['baseUrl','canloadmore'],
    data(){
      return {
        tmpid: 0
      }
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
    },
    methods: {
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
      mkAdmin(id) {
        $.ajax({
            url: '/internal-api/users/mkAdmin/'+id,
            type: 'POST',
            data:new FormData($("#hiddenCSRFForm")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                eventBus.$emit('userEdited','');
              }
            }
        });
        return false;
      },
      rmAdmin(id) {
        $.ajax({
            url: '/internal-api/users/rmAdmin/'+id,
            type: 'POST',
            cache: false,
            data:new FormData($("#hiddenCSRFForm")[0]),
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                eventBus.$emit('userEdited','');
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
                eventBus.$emit('userEdited','');
              }
            }
        });
        return false;
      },
    },
    components : {
        'singleField': SingleGalleryField
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
