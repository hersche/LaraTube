<template>
  <div>
    <nav class="nav">
      <a class="nav-link active" href="#add" data-toggle="tab">Add media</a>
      <a class="nav-link" href="#upload" data-toggle="tab">Direct upload</a>
    </nav>
    <generalUpload></generalUpload>

  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  import GeneralUploadComponent from './GeneralUploadComponent'
  export default {
    props: ['medias','currentTitle','swapComponent','baseUrl'],
    components : {
      'generalUpload': GeneralUploadComponent
    },
    data(){
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }
  }
</script>
