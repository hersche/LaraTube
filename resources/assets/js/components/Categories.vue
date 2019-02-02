<template>
  <div>



    <p v-if="catlevel==0" class="float-left col-md-3 col-sm-6 ">
      <router-link v-if="currentuser.admin" to="/newcat/" class="btn btn-block btn-warning btn-sm">
        <vs-icon icon="create"></vs-icon>{{ $t("Create") }} {{ $t("category") }}
      </router-link>
      <treeselect instanceId="catoverviewtree" v-if="treecatptions!=undefined" :multiple="false" :append-to-body="false" :always-open="true" v-model="catids" name="parent_id"  :options="treecatptions" />

    </p>

    <div class="float-right col-md-9 col-sm-6 mt-sm-5 mt-md-0 pt-sm-5 pt-md-0">
      <div class="" v-if="currentcat!=undefined">
        <span class="text-right float-right" v-if="currentuser.admin"><router-link :to="'/editcat/'+currentcat.id" class="btn btn-warning btn-sm mr-1"><vs-icon icon="edit"></vs-icon>{{ $t("Edit") }}</router-link>
          <button @click="deleteAction(currentcat.id)" class="btn btn-danger btn-sm"><vs-icon icon="delete"></vs-icon>{{ $t("Delete") }}</button></span>
        <h5><router-link :to="'/category/'+currentcat.urlTitle">{{ currentcat.title }}</router-link></h5>
        <p>{{ currentcat.description }}</p>
         <p v-if="currentcat.children.length>0">Subcategories</p>
         <p v-for="subcat in currentcat.children" v-if="currentcat.children.length>0">
           <router-link :to="'/category/'+subcat.urlTitle">{{ subcat.title }} ({{ subcat.medias.length }} medias)</router-link>
         </p>

             <h5>{{ currentmedias.length }} {{ $t("medias") }}</h5>
          <div class="row">
            <div v-for="media in currentmedias"  class="col-lg-6 col-md-6 col-xs-6">
               <singleField v-bind:item="media" v-bind:loggeduserid="loggeduserid"></singleField>
            </div>

              <!--  <SwiperView class="" v-if="currentmedias.length>0" v-bind:medias="currentmedias" v-bind:currentuser="currentuser" v-bind:canloadmore="canloadmore" v-bind:loggeduserid="loggeduserid"></SwiperView> -->
         </div>
       </div>
    </div>

  </div>
  </div>
</template>
<script>
  import { eventBus,store } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  import SwiperView from './SingleSwiperView'
  import { User, Media, Tag, Category } from '../models';
  export default {
    props: ['baseUrl','canloadmore','loggeduserid','categories','catlevel','currentuser','treecatptions'],
    name: 'categoriesTag',
    mounted: function () {
      let that = this
      if(localStorage.getItem("categories_remember")!=undefined&&localStorage.getItem("categories_remember")!=null){
        this.catids = localStorage.getItem("categories_remember")
      }
      eventBus.$on('mediasByCatIdReceived', id => {
        if(id==this.currentcat.id){
          //this.currentmedias=this.currentcat.medias
        }
        var tmpMedias = []
        this.medias.forEach(function(val,key){
          if(val.category_id==that.catids){
            tmpMedias.push(val)
          }
        });
        this.currentmedias = tmpMedias
      });
    },
    watch: {
      catids:function(val){
        let that = this
        that.currentcat = this.getCurrentCategory(val)
        localStorage.setItem("categories_remember",val)
        var tmpMedias = []
        this.medias.forEach(function(val,key){
          if(val.category_id==that.catids){
            tmpMedias.push(val)
          }
        });
        this.currentmedias = tmpMedias
      },
      medias:function(val){
      /*  var tmpMedias = []
        let that = this
        this.medias.forEach(function(val,key){
          if(val.category_id==that.catids){
            tmpMedias.push(val)
          }
        });
        this.currentmedias = tmpMedias*/
      }
    },
    methods: {
      getCurrentCategory(id,data=undefined) {
        // `this` points to the vm instance
        let that = this;
        var emptyCat = new Category(0,"None","All medias which are in no category","","");
        var theC = emptyCat
        if(id==0){
          eventBus.$emit('getMediasByCatId',0);
        }
        var idata = this.categories
        if(data!=undefined){
          idata = data
        }
        idata.forEach(function(val,key){
          if(val.children.length>0){
            var tmpResult = that.getCurrentCategory(id,val.children)
            if(tmpResult.id!=0){
              theC = tmpResult
            }
          }
          if(val.id==id){
            eventBus.$emit('getMediasByCatId',id);
            theC = val
          }
        });
        if(theC.id!=0){
          if(theC.children.length>0){
            theC.children.forEach(function(val,key){
              eventBus.$emit('getMediasByCatId',val.id);
            });
          }
        }
        return theC;
      },
      emitLoadMore() {
        eventBus.$emit('loadMore','');
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
        'singleField': SingleGalleryField,
        SwiperView
    },
    data(){
      return {
        catids:0,
        currentcat:undefined,
        currentmedias:[],
        medias:store.state.medias
      }
    }
  }
</script>


<style lang="stylus">
.vue-treeselect__menu
  position:relative !important;

</style>
