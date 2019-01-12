<template>
  <div>
    <p v-if="catlevel==0&&currentuser.admin">
      <router-link to="/newcat/" class="btn btn-warning btn-sm">
        <vs-icon icon="create"></vs-icon>Create
      </router-link>
    </p>
  <!-- <div class="row text-center text-lg-left" id="profilevideos"> -->
    <!-- <vs-collapse>
      <vs-collapse-item v-for="cat in categories" :key="cat.id" class=""> -->
      <ul>
        <li v-for="cat in categories">
          <router-link :to="'/category/'+cat.urlTitle">{{ cat.title }}</router-link>
           <p v-if="currentuser.admin"><router-link :to="'/editcat/'+cat.id" class="btn btn-warning btn-sm mr-1"><vs-icon icon="edit"></vs-icon>Edit</router-link>
          <button @click="deleteAction(cat.id)" class="btn btn-danger btn-sm"><vs-icon icon="delete"></vs-icon>Delete</button></p>
          <div class="ml-3">
            <p v-if="cat.children.length>0">Subs</p>
          <cat v-bind:catlevel="Number(catlevel)+1" v-bind:currentuser="currentuser" v-bind:categories="cat.children" v-if="cat.children.length>0"></cat>
        </div>
        </li>
      </ul>
      <!--  <div slot="header">
          {{ cat.title }}
        </div>
        <p>{{ cat.description }}</p>
        <p v-if="currentuser.admin"><router-link :to="'/editcat/'+cat.id" class="btn btn-warning btn-sm mr-1"><vs-icon icon="edit"></vs-icon>Edit</router-link>
        <button @click="deleteAction(cat.id)" class="btn btn-danger btn-sm"><vs-icon icon="delete"></vs-icon>Delete</button></p>-->

        <!-- <div class="row text-center">
          <div v-for="media in cat.medias"  class="col-lg-3 col-md-3 col-xs-6">
            <singleField v-bind:item="media" v-bind:loggeduserid="loggeduserid"></singleField>
          </div>
        </div>
      </vs-collapse-item>
    </vs-collapse> -->
  </div>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['medias','baseUrl','canloadmore','loggeduserid','categories','catlevel','currentuser'],
    name: 'cat',
    methods: {
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
    }
  }
</script>
