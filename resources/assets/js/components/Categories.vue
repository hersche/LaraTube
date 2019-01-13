<template>
  <div>



    <p v-if="catlevel==0" class="float-left col-4">
      <router-link v-if="currentuser.admin" to="/newcat/" class="btn btn-warning btn-sm">
        <vs-icon icon="create"></vs-icon>Create
      </router-link>
      <treeselect v-if="treecatptions!=undefined" :multiple="true" :always-open="true" v-model="catids" name="parent_id"  :options="treecatptions" />

    </p>

    <p class="float-right col-8">
      <div class="float-right col-8" v-for="cat in categories" v-if="isIdIncluded(cat.id)">
        <span class="text-right float-right" v-if="currentuser.admin"><router-link :to="'/editcat/'+cat.id" class="btn btn-warning btn-sm mr-1"><vs-icon icon="edit"></vs-icon>Edit</router-link>
          <button @click="deleteAction(cat.id)" class="btn btn-danger btn-sm"><vs-icon icon="delete"></vs-icon>Delete</button></span>
        <h5><router-link :to="'/category/'+cat.urlTitle">{{ cat.title }}</router-link></h5>
        <p>{{ cat.description }}</p>
         <p v-if="cat.children.length>0">Subcategories</p>
         <p v-for="subcat in cat.children" v-if="cat.children.length>0"><router-link :to="'/category/'+subcat.urlTitle">{{ subcat.title }}</router-link></p>
         <div class="row text-center">
            <div v-for="media in cat.medias"  class="col-lg-6 col-md-6 col-xs-6">
               <singleField v-bind:item="media" v-bind:loggeduserid="loggeduserid"></singleField>
            </div>
         </div>
         <vs-divider color="primary" icon=""></vs-divider>
       </div>
       <p class="float-right col-8 btn-block btn-sm btn btn-info" @click="emitLoadMore()" v-if="canloadmore">Scroll down or click to load more</p>
    </p>

  </div>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['medias','baseUrl','canloadmore','loggeduserid','categories','catlevel','currentuser','treecatptions'],
    name: 'categoriesTag',
    watch: {
      catids:function(val){

      }
    },
    methods: {
      emitLoadMore() {
        eventBus.$emit('loadMore','');
      },
      isIdIncluded(id){
        var ret = false;
        this.catids.forEach(function(val,key){
          if(val==id){
            ret = true;
          }
        });
        return ret;
      },
      deleteAction(id) {
        let that = this;
        $.ajax({
            url: '/internal-api/category/'+id,
            type: 'DELETE',
            cache: false,
            contentType: false,
            processData: false,
            complete : function(res) {
              if(res.status==200){
                //eventBus.$emit('showAlert',['success','Video uploaded']);

              }
              eventBus.$emit('categoriesRefreshed','')
            }

        });
        return false;
      },
    },
    components : {
        'singleField': SingleGalleryField
    },
    data(){
      return {
        catids:[],
      }
    }
  }
</script>
