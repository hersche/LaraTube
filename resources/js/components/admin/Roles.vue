<template>
  <div>
    <v-dialog v-model="deleteDialog" class="mt-2" max-width="290">
      <v-card>
        <v-card-title class="headline">Delete role?</v-card-title>
        <v-card-text>
          Shure?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
          color="green darken-1"
          flat="flat"
          @click="deleteDialog = false"
          >
          Disagree
        </v-btn>
        <v-btn
          color="green darken-1"
          flat="flat"
          @click="deleteAction()"
          >
          <v-icon>delete</v-icon> {{ $t('Delete') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>


  <v-dialog v-model="editDialog" class="mt-2" max-width="80%">
    <v-card>
      <v-card-title class="headline">Edit role</v-card-title>
      <v-card-text>
        <form id="peform" v-if="currentEditProject!=undefined">
          <input type="hidden" name="_token" :value="csrf">
          <input type="hidden" name="rid" :value="currentEditProject.id">
          <v-text-field
            :label="$t('Name')"
            name="name"
            :value="currentEditProject.name"
            required
            ></v-text-field>
            <MarkdownCreator :key="currentEditProject.id" :theText="currentEditProject.description" theId="description" :theTitle="$t('Description')" ></MarkdownCreator>
            <v-text-field
              :label="$t('Slug')"
              name="slug"
              :value="currentEditProject.slug"
              required
            ></v-text-field>

            <v-text-field
              :label="$t('Level')"
              name="level"
              :value="currentEditProject.level"
              ></v-text-field>
            </form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
        color="green darken-1"
        flat="flat"
        @click="editDialog = false"
        >
        Disagree
      </v-btn>
      <v-btn
        color="green darken-1"
        flat="flat"
        @click="editAction()"
        >
        <v-icon>edit</v-icon> {{ $t('Edit') }}
      </v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>
    
    
<v-dialog v-model="createDialog" class="mt-2" max-width="80%">
  <v-card>
    <v-card-title class="headline">Create project</v-card-title>
    <v-card-text>
      <form id="pform">
        <input type="hidden" name="_token" :value="csrf">
        <v-text-field
          :label="$t('Name')"
          name="name"
          required
          ></v-text-field>
          <v-text-field
            :label="$t('Slug')"
            name="slug"
            required
          ></v-text-field>
          <MarkdownCreator theText="" theId="description" :theTitle="$t('Description')" ></MarkdownCreator>


          <v-text-field
            :label="$t('Level')"
            name="level"
            ></v-text-field>
          </form>
  </v-card-text>
  <v-card-actions>
    <v-spacer></v-spacer>
    <v-btn
    color="green darken-1"
    flat="flat"
    @click="createDialog = false"
    >
    Disagree
  </v-btn>
  <v-btn
    color="green darken-1"
    flat="flat"
    @click="submitAction()"
    >
    <v-icon>save</v-icon> {{ $t('Save') }}
  </v-btn>
</v-card-actions>
</v-card>
</v-dialog>
    
    
    
    <h1 class="text-center">Projects</h1>
    <div class="text-center">
      <p>Minimum level for admin is {{ Number(mixconfig.MIX_APP_ADMINLEVEL)+1 }}.</p>
      <p>Minimum level for being enabled is 1. Roles with level 0 disable a user.</p>
      <p>Always the highest level does count. If the project works with names, the slug is the identifier.</p>
      <v-btn @click="openCreateDialog();"color="green" ><v-icon>save</v-icon>{{ $t('Create') }} {{ $t('role') }}</v-btn>
    </div>
    
    <v-data-table
  :headers="headers"
  :items="roles"
  class="elevation-1"
>
  <template v-slot:items="props">
    <td>{{ props.item.name }}</td>
    <td class="">{{ props.item.slug }}</td>
    <td class="">{{ props.item.description }}</td>
    <td class="">{{ props.item.level }}</td>
    <td class=""><v-btn icon @click="openEditDialog(props.item)" ><v-icon>edit</v-icon></v-btn></td>
    <td class=""><v-btn icon @click="openConfirm(props.item.id)"><v-icon>delete</v-icon></v-btn></td>
  </template>
</v-data-table>
  </div>
</template>
<script>
  import { eventBus,store } from '../../eventBus.js';
  import MarkdownCreator from '../MarkdownCreator'
  import VueMarkdown from 'vue-markdown'
  const axios = require("axios");
  const $ = require("jquery");
  export default {
    props: ['baseUrl','mixconfig'],
    data(){
      return {
        tmpid: 0,
        editDialog:false,
        createDialog:false,
        currentEditProject:undefined,
        deleteDialog:false,
        headers: [
  {
    text: 'Name',
    align: 'left',
    value: 'name'
  },
  { text: 'Slug', value: 'slug' },
  { text: 'Description', value: 'description' },
  { text: 'Level', value: 'level' },
  { text: 'Edit', value: '' },
  { text: 'Delete', value: '' }
],
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
      roles:function(){
        return store.state.roles
      },
    },
    methods: {
      openCreateDialog(){
        this.createDialog = true
      },
      openConfirm(id){
        this.tmpid = id
        this.deleteDialog = true
      },
      openEditDialog(d){
        this.currentEditProject = d
        this.editDialog = true
      },
      deleteAction() {
        var d = new FormData($("#logoutForm")[0])
        d.append('rid',this.tmpid)    
        let that = this
        $.ajax({
            url: '/internal-api/role/delete',
            type: 'POST',
            data:d,
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                that.tmpid = 0
                that.deleteDialog = false
                store.commit("setRoles",res.responseJSON)
              }
            }
        });
        return false;
      },
      editAction() {
        console.log("edit-data",$("#peform")[0])
        let that = this
        $.ajax({
            url: '/internal-api/role',
            type: 'POST',
            data:new FormData($("#peform")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                //eventBus.$emit('userEdited','');
                that.editDialog=false;
                that.currentEditProject=undefined;
                store.commit("setRoles",res.responseJSON)
              }
            }
        });
        return false;
      },  
      submitAction() {
        let that = this
        $.ajax({
            url: '/internal-api/role/create',
            type: 'POST',
            data:new FormData($("#pform")[0]),
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                that.createDialog=false
                store.commit("setRoles",res.responseJSON)
                //eventBus.$emit('userEdited','');
              }
            }
        });
        return false;
      },
      
    },
    components : {
        VueMarkdown,
        MarkdownCreator
    }
  }
</script>

<style>
.v-dialog__content--active{
  z-index: 999999 !important;
}
</style>