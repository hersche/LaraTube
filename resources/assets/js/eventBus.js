// https://github.com/robinvdvleuten/vuex-persistedstate
import Vue from 'vue';
import Vuex from 'vuex'
import { MediaSorter } from './tools'
Vue.use(Vuex)
export const eventBus = new Vue();
var mediaSorter=new MediaSorter()
export const store = new Vuex.Store({
      state: {
        filterTypes:["video","audio"],
        medias:[],
        categories:[],
        users:[],
        tags:[],
        notifications:[],
        totalMedias:0,
        blockGetRequest:false,
      },
      getters: {
        getMediasByTypes: (state) => () => {
          var m = state.medias.filter(media => state.filterTypes.includes(media.simpleType))
          return m
        },
        getMediaById: (state) => (id) => {
          var m = state.medias.find(media => media.id === id)
          if(m==undefined){
            if(state.blockGetRequest==false){
              state.blockGetRequest=true
              eventBus.$emit('loadMediaById',id);
            } else {
              state.blockGetRequest=false
            }
          }
          return m
        },
        getMediaByTitle: (state) => (title) => {
          title = encodeURIComponent(title)
          var m = state.medias.find(media => media.urlTitle === title)
          if(m==undefined){
            if(state.blockGetRequest==false){
              state.blockGetRequest=true
              eventBus.$emit('loadMedia',title);
            } else {
              state.blockGetRequest=false
            }
          }
          return m
        },
        getCategoryByTitle: (state) => (title) => {
          // This will need to be recursive too
          title = encodeURIComponent(title)
          var m = state.categories.find(cat => cat.urlTitle === title)
          if(m==undefined){
            state.categories.forEach(function(cat,key){

            });
          }
          return m
        },
        getUserById: (state) => (id) => {
          eventBus.$emit('loadUserVideos',id);
          return state.users.find(u => u.id == id)
        }
      },
      mutations: {
        disableBlockRequest (state) {
          state.disableBlockRequest=false
        },
        updateOrAddMedia(state,replaceMedia){
          // Does this really work? ^^
          var tmpMedias = []
          var found = false
          state.medias.forEach(function(value,key){
            if(value.id===replaceMedia.id){
              found = true
              tmpMedias.push(replaceMedia)
            } else {
              tmpMedias.push(value)
            }
          });
          if(found==false){
            tmpMedias.push(replaceMedia)
          }
          state.medias = tmpMedias
          state.medias = mediaSorter.sort(state.medias)
        },
        addMedia(state,media){
          if(state.medias.indexOf(media)==-1){
            state.medias.push(media)
            state.medias = mediaSorter.sort(state.medias)
          }
        },
        clearMedias(state){
          state.medias = []
        },
        sortMediasBy(state,sortBy){
          mediaSorter.setSortBy(sortBy)
          state.medias = mediaSorter.sort(state.medias)
        },
        setFilterTypes(state,types){
          state.filterTypes = types
          state.medias = mediaSorter.sort(state.medias)
        },
        setTags(state,tags){
          state.tags = tags
        },
        setCategories(state,categories){
          state.categories = categories
        },
        setUsers(state,users){
          state.users = users
        },
        setTotalMedias(state,totalMedias){
          state.totalMedias = totalMedias
        },
      }
    })
