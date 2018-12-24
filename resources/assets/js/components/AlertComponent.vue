<template>
<b-alert :show="dismisscountdown"
         dismissible
         variant="warning"
         @dismissed="dismisscountdown=0"
         @dismiss-count-down="countDownChanged">
  <p>This alert will dismiss after {{dismisscountdown}} seconds...</p>
  <b-progress variant="warning"
              :max="dismisssecs"
              :value="dismisscountdown"
              height="4px">
  </b-progress>
</b-alert>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import GeneralUploadComponent from './GeneralUploadComponent'
  export default {
    props: ['medias','currentTitle','baseUrl','dismisssecs','dismisscountdown','showdismissiblealert'],
    components : {
      'generalUpload': GeneralUploadComponent
    },
    methods: {
      countDownChanged (dismisscountdown) {
        this.dismisscountdown = dismisscountdown
      }
    },
    data(){
      return {
      }
    }
  }
</script>
