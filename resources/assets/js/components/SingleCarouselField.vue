<template>
  <div class="card mb-1">
          <router-link  :to="'/media/'+item.urlTitle" class="d-block h-100">
            <b-card :title="item.title"
                    :img-src="item.poster_source"
                    :img-alt="item.title"
                    img-top
                    tag="article"
                    style=""
                    class="mb-2">
              <p class="card-text">
                {{ shorteneddescription(item) }}
              </p>
              <p>{{ item.simpleType }}</p>
            </b-card>
          </router-link>
        </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['item','loggeduserid'],
    methods: {
      shorteneddescription: function (item) {
        if(item.description.length>83){
          return item.description.substring(0,80)+"..."
        }
        return item.description;
      },
    },
    watch:{
      medias:function(val){
        console.log("do swiper update")
        this.swiper.update()
      }
    },
    computed: {
      swiper() {
        return this.$refs.mySwiper.swiper
      },
      shortenedtags: function () {
        if(this.item.tagString.length>60){
          return this.item.tagString.substring(0,57)+"..."
        }
        return this.item.tagString;
      }
    },
  }
</script>
