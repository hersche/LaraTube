<template>
  <div>
    <div v-for="item in medias"  class="col-lg-4 col-md-4 col-xs-6">
        <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
    </div>
    <vs-table search :data="users">
          <template slot="header">
            <h3>
              Users
            </h3>
          </template>
          <template slot="thead">
            <vs-th sort-key="name">
              Name
            </vs-th>
            <vs-th sort-key="email">
              Email
            </vs-th>
            <vs-th sort-key="created_at">
              Created
            </vs-th>
            <vs-th sort-key="updated_at">
              Updated
            </vs-th>
            <vs-th sort-key="id">
              Id
            </vs-th>
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
              <vs-td :data="data[indextr].id">
                {{data[indextr].id}}
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
        <vs-button vs-type="flat" size="small" color="danger" icon="delete_sweep"></vs-button>
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
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['users','baseUrl','canloadmore','loggeduserid'],
    methods: {
      emitLoadMore() {
        eventBus.$emit('loadMore','');
      }
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
