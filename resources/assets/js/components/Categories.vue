<template>
  <div>



    <p v-if="catlevel==0" class="float-left col-3">
      <router-link v-if="currentuser.admin" to="/newcat/" class="btn btn-block btn-warning btn-sm">
        <vs-icon icon="create"></vs-icon>Create category
      </router-link>
      <treeselect v-if="treecatptions!=undefined" :multiple="false" :always-open="true" v-model="catids" name="parent_id"  :options="treecatptions" />

    </p>

    <p class="float-right col-9">
      <div class="float-right col-9" v-if="currentcat!=undefined">
        <span class="text-right float-right" v-if="currentuser.admin"><router-link :to="'/editcat/'+currentcat.id" class="btn btn-warning btn-sm mr-1"><vs-icon icon="edit"></vs-icon>Edit</router-link>
          <button @click="deleteAction(currentcat.id)" class="btn btn-danger btn-sm"><vs-icon icon="delete"></vs-icon>Delete</button></span>
        <h5><router-link :to="'/category/'+currentcat.urlTitle">{{ currentcat.title }}</router-link></h5>
        <p>{{ currentcat.description }}</p>
         <p v-if="currentcat.children.length>0">Subcategories</p>
         <p v-for="subcat in currentcat.children" v-if="currentcat.children.length>0"><router-link :to="'/category/'+subcat.urlTitle">{{ subcat.title }}</router-link></p>
         <div class="row text-center">
            <div v-for="media in currentcat.medias"  class="col-lg-6 col-md-6 col-xs-6">
               <singleField v-bind:item="media" v-bind:loggeduserid="loggeduserid"></singleField>
            </div>
         </div>
       </div>
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
    mounted: function () {
      if(localStorage.getItem("categories_remember")!=undefined&&localStorage.getItem("categories_remember")!=null){
        this.catids = localStorage.getItem("categories_remember")
      }
    },
    watch: {
      catids:function(val){
        this.currentcat = this.getCurrentCategory(val)
        localStorage.setItem("categories_remember",val)
      }
    },
    methods: {
      getCurrentCategory(id,data=undefined) {
        // `this` points to the vm instance
        let that = this;
        var theC = undefined
        var idata = this.categories
        if(data!=undefined){
          idata = data
        }
        idata.forEach(function(val,key){
          if(val.children.length>0){
            var tmpResult = that.getCurrentCategory(id,val.children)
            if(tmpResult!=undefined){
              theC = tmpResult
            }
          }
          if(val.id==id){
            eventBus.$emit('getMediasByCatId',id);
            theC = val;
          }
        });
        return theC;
      },
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
        catids:0,
        currentcat:undefined
      }
    }
  }
</script>
