
<template>
  <div>
      <!-- swiper -->
      <swiper refs="mySwiper" :options="swiperOption">
        <swiper-slide v-for="(item,index) in medias" :key="index">
          <singleField v-bind:item="item" v-bind:loggeduserid="loggeduserid"></singleField>
        </swiper-slide>
        <div class="swiper-pagination" slot="pagination"></div>
        <div class="swiper-button-prev" style="" slot="button-prev"></div>
        <div class="swiper-button-next" slot="button-next"></div>
      </swiper>
  </div>
</template>

<script>
import 'swiper/dist/css/swiper.css'
import { eventBus } from '../eventBus.js';
import { swiper, swiperSlide } from 'vue-awesome-swiper'
  import SingleGalleryField from './SingleCarouselField'
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore'],
    components: {
      swiper,
      swiperSlide,
      'singleField': SingleGalleryField
    },
    mounted(){
      let that = this;
      eventBus.$on('mediasByCatIdReceived', id => {
        var tmpCatId = that.catid;
        that.catid=0;
        that.catid=tmpCatId;
      });
    },
    computed: {
      swiper() {
        return this.$refs.mySwiper.swiper
      }
    },
    data() {
      return {
        swiperOption: {
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
