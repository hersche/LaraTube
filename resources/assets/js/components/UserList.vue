<template>
        <div class="row">

          <div v-for="(item,index) in users" style="max-height: 200px; overflow-y: auto"  class="col-lg-4 col-md-4 col-xs-6">
            <v-card flat>
              <v-img
  :src="item.background"
>                <v-avatar>
            <img
              :src="item.avatar"
              :alt="item.name"
            >
          </v-avatar></v-img>
              <v-card-title>

                {{ item.name }}
              </v-card-title>
              <v-card-text>
                <VueMarkdown :source="item.bio"></VueMarkdown>

              </v-card-text>
              <v-card-actions>
                    <v-btn small :to="'/profile/'+item.id" color="green" icon><v-icon>send</v-icon></v-btn>
                  </v-card-actions>
            </v-card>
          </div>

        </div>
</template>
<script>
  import { eventBus, store } from '../eventBus.js';
  import VueMarkdown from 'vue-markdown'
  export default {
    props: ['users'],
    components : {
        VueMarkdown,
        
    },
    computed: {
      currentuser(){
        return store.getters.getUserById(store.state.loginId)
      },
      loggeduserid(){
        return store.state.loginId
      },
    },
  }
</script>
