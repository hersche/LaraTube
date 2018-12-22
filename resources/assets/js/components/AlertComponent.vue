<template>
<b-alert :show="dismissCountDown"
         dismissible
         variant="warning"
         @dismissed="dismissCountDown=0"
         @dismiss-count-down="countDownChanged">
  <p>This alert will dismiss after {{dismissCountDown}} seconds...</p>
  <b-progress variant="warning"
              :max="dismissSecs"
              :value="dismissCountDown"
              height="4px">
  </b-progress>
</b-alert>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import GeneralUploadComponent from './GeneralUploadComponent'
  export default {
    props: ['medias','currentTitle','swapComponent','baseUrl','dismissSecs','dismissCountDown','showDismissibleAlert'],
    components : {
      'generalUpload': GeneralUploadComponent
    },
    methods: {
      countDownChanged (dismissCountDown) {
        this.dismissCountDown = dismissCountDown
      }
    },
    data(){
      return {
      }
    }
  }
</script>
