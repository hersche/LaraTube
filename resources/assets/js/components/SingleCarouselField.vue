<template>
  <div :style="'background-image:url(\''+item.poster_source+'\');'">

                <vs-row vs-justify="center">
                  <vs-col type="flex" vs-justify="center" vs-align="center" vs-w="10">
                    <vs-card style="opacity: 0.75; "  >
                      <div style="height: 35vh; overflow-x: auto;">
                    <div slot="header">
                        <h3>{{ item.title }}</h3>
                        <h5>({{ item.created_at_readable }})</h5>
                      </div>
                      <div>
                        <div style=""><VueMarkdown :source="item.description"></VueMarkdown></div>
                        <div>
                          <span v-for="tag in item.tags" >
                            <router-link class="" :to="'/tags/'+tag.name" >
                              <vs-chip color="primary">
                                <vs-avatar icon="tag" />
                                {{ tag.name }}
                              </vs-chip>
                            </router-link>
                          </span>
                        </div>
                      </div>
                    </div>
                      <div slot="footer">
                        <vs-row vs-justify="flex-end" style="flex-flow: row!important;">
                          <vs-button class="mr-1" v-if="loggeduserid==item.user.id" icon="settings" :title="$t('Edit')+' '+$t('media')" :to="'/mediaedit/'+item.title"></vs-button>
                          <vs-button icon="play_circle_filled" :title="$t('Play')+' '+$t('media')" :to="'/media/'+item.urlTitle"></vs-button>
                        </vs-row>
                      </div>
                    </vs-card>
                  </vs-col>
                </vs-row>
        </div>
</template>
<script>
  import { eventBus, store } from '../eventBus.js';
  import VueMarkdown from 'vue-markdown'
  export default {
    props: ['item'],
    components : {
        VueMarkdown
    },
    methods: {
      shorteneddescription: function (item) {
        if(item.description.length>83){
          return item.description.substring(0,80)+"..."
        }
        return item.description;
      },
    },
    watch:{
    },
    computed: {
      loggeduserid: function(){
        return store.state.loginId
      },
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
