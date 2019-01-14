<template>
  <div>
    <vs-table search :data="users">
          <template slot="header">
            <h3>
              Users
            </h3>
          </template>
          <template slot="thead">
            <vs-th sort-key="name">Name</vs-th>
            <vs-th sort-key="email">Email</vs-th>
            <vs-th sort-key="created_at">{{ $t('Created at') }}</vs-th>
            <vs-th sort-key="updated_at">{{ $t('Updated at') }}</vs-th>
            <vs-th sort-key="admin">Admin</vs-th>
          </template>

          <template slot-scope="{data}">
            <vs-tr :key="indextr" v-for="(tr, indextr) in data" >
              <vs-td :data="data[indextr].name">
                {{data[indextr].name}}
              </vs-td>
              <vs-td :data="data[indextr].email">
                {{data[indextr].email}}
              </vs-td>
              <vs-td :data="data[indextr].created_at">
                {{data[indextr].created_at}}
              </vs-td>
              <vs-td :data="data[indextr].updated_at">
                {{data[indextr].updated_at}}
              </vs-td>
              <vs-td :data="data[indextr].admin">
                {{data[indextr].admin}}
              </vs-td>
              <template class="expand-user" slot="expand">
  <div class="con-expand-users">
    <div class="con-btns-user">
      <div class="con-userx">
        <vs-avatar :badge="tr.id" size="45px" :src="data[indextr].avatar"/>
        <span>
          {{ tr.name }}
        </span>
      </div>

      <div>
        <vs-button vs-type="gradient" size="small" :to="'/profile/'+tr.id" color="success" icon="send"></vs-button>
        <vs-button vs-type="flat" @click="openConfirm(tr.id)" size="small" color="danger" icon="delete_sweep"></vs-button>
        <vs-button v-if="tr.admin" vs-type="flat" @click="rmAdmin(tr.id)" size="small" color="danger" icon="">Unmake admin</vs-button>
        <vs-button v-if="tr.admin==false" vs-type="flat" @click="mkAdmin(tr.id)" size="small" color="danger" icon="">Make admin</vs-button>
      </div>
    </div>
    <vs-list>
      <vs-list-item icon="mail" title="Email" :subtitle="tr.email"></vs-list-item>
    </vs-list>
  </div>
</template>
            </vs-tr>
          </template>


        </vs-table>
        <form id="hiddenCSRFForm" class="d-none">
          <input type="hidden" name="_token" :value="csrf">
        </form>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['users','baseUrl','canloadmore','loggeduserid'],
    data(){
      return {
        tmpid: 0
      }
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
