<template>
  <div>
    <vue-croppie
      ref="croppieRef"
      :enableOrientation="true"
      :enableExif="true"
      :enableResize="false"
      @result="result"
      size="original"
      :viewport="{ width: Number(width), height: Number(height), type: type }"
      :boundary="{ width: (Number(width)+20), height: (Number(height)+20) }"
      @update="update">
      </vue-croppie>
      
      <input type="hidden" :id="name+'Base'" :name="name" :value="croppedBase64" />
      <v-btn small @click="croppedBase64=null; $refs.croppieRef.refresh();" icon title="Set no picture"><v-icon>delete</v-icon></v-btn>
      <!-- Rotate angle is Number -->
      <v-btn small @click="rotate(90,$event)" icon><v-icon>rotate_left</v-icon></v-btn>
      <v-btn small @click="rotate(-90,$event)" icon><v-icon>rotate_right</v-icon></v-btn>
      <input :id="name+'Upload'" accept=".png,.jpg,.jpeg" @change="change()" :name="name+'File'" type="file">
    </div>
</template>

<script>
const $ = require("jquery");
export default {
  props: ['baseUrl','height','width','name','type','theurl'],
  mounted(){
    // this.$refs.croppieRef.refresh();
    let that = this
    if(this.theurl==undefined){
      this.$refs.croppieRef.bind({
        url: '/img/404/avatar.png'
      }).then(function(){
        that.$refs.croppieRef.setZoom(0)
      })
   } else {
     if(this.theurl!=''){
       this.$refs.croppieRef.bind({
        url: this.theurl
      }).then(function(){
        that.$refs.croppieRef.setZoom(0)
      })
    }
   }
   //defaultInitialZoom = !isNaN(parseInt(this.$refs.croppieRef.options.initialZoom)) ? this.$refs.croppieRef.options.initialZoom : Math.max((boundaryData.width / imgData.width), (boundaryData.height / imgData.height));
  },
  methods: {
    change(){
      var reader = new FileReader();
      let that = this;  
      reader.onload = function (e) {
       console.log("avatar-change!!!!", that.$refs.croppieRef)
       that.$refs.croppieRef.bind({
         url: e.target.result,
        });
      }
      reader.readAsDataURL($("#"+this.name+"Upload")[0].files[0]);
    },
    // CALBACK USAGE
    result(output) {
      console.log("result")
      this.croppedBase64 = output;
    },
    update(val) {
      let options = {
        type: 'base64',
        size: 'viewport',
        format: 'png'
      }
      this.$refs.croppieRef.result(options, (output) => {
        this.croppedBase64 = output;
      });
    },

    rotate(rotationAngle,event) {
      // Rotates the image
      if (event) event.preventDefault()
        this.$refs.croppieRef.rotate(rotationAngle);
      },
    },
    data(){
      return {
        croppedBase64: null,
      }
    }
  }
</script>