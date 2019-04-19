
<template>
  <div v-if="medias.length>0">
      <!-- swiper -->
      <swiper refs="mySwiper" id="theSwiper" :options="swiperOption">
        <swiper-slide v-for="(item,index) in medias" :key="index">
          <singleField v-bind:item="item"></singleField>
        </swiper-slide>
        <div class="swiper-pagination" slot="pagination"></div>
        <div class="swiper-button-prev" style="" slot="button-prev"></div>
        <div class="swiper-button-next" slot="button-next"></div>
      </swiper>
  </div>
</template>

<script>
  import 'swiper/dist/css/swiper.css'
  import { eventBus, store } from '../eventBus.js';
  import { swiper, swiperSlide } from 'vue-awesome-swiper'
  import SingleGalleryField from './SingleCarouselField'
  export default {
    props: ['baseUrl','canloadmore'],
    components: {
      swiper,
      swiperSlide,
      'singleField': SingleGalleryField
    },
    watch:{
      medias:function(val){
      //  console.log("do swiper update")
        if(this.$refs.mySwiper!=undefined){
          this.swiper.update()
        }
      }
    },
    mounted(){
      let that = this;
      eventBus.$on('mediasByCatIdReceived', id => {
        var tmpCatId = that.catid;
        that.catid=0;
        that.catid=tmpCatId;
        console.log("do swiper update 111")
      //  that.swiper.update()
      });
    },
    computed: {
      loggeduserid: function(){
        return store.state.loginId
      },
      swiper:function() {
        return this.$refs.mySwiper.swiper
      },
      medias:function() {
        //this.swiper.destroy();
        //this.$refs.mySwiper.swiper = new Swiper('#theSwiper', this.swiperOption)
        return store.getters.getMediasByTypes()
      }
    },
    data() {
      return {
        swiperOption: {
          initial:0,
          slidesPerView: 2,
          spaceBetween: 10,
          effect: 'coverflow',
          grabCursor: true,
          centeredSlides: true,
          loop: true,
          pagination: {
            el: '.swiper-pagination',
            clickable: true
          },
          coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows : true
          },
          keyboard: {
            enabled: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
          }
        }
      }
    }
  }
</script>
