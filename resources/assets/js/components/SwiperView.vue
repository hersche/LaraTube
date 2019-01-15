
<template>
  <div>
      <!-- swiper -->
      <swiper :options="swiperOption">
        <swiper-slide v-for="item in medias" :key="item.title">
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

import { swiper, swiperSlide } from 'vue-awesome-swiper'
  import SingleGalleryField from './SingleGalleryField'
  export default {
    props: ['medias','baseUrl','loggeduserid','canloadmore'],
    components: {
      swiper,
      swiperSlide,
      'singleField': SingleGalleryField
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
