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
        loginId:0,
        categories:[],
        users:[],
        tags:[],
        notifications:[],
        totalMedias:0,
        blockGetRequest:false,
        CSRF:document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      getters: {
        nextMediasList: (state) => (id) => {
          var nextVideos = []
          var startAdd = false;
          $.each( state.medias, function( key, value ) {
            if(startAdd){
              nextVideos.push(value)
            }
            if(value.id==id){
              startAdd=true;
            }

          });
          return nextVideos;
        },
        getMediasByTypes: (state) => () => {
          var m = state.medias.filter(media => state.filterTypes.includes(media.simpleType))
          return m
        },
        getMediasByUserId: (state) => (userId, filtered=true) => {
          var m = state.medias.filter(media => media.user_id===userId)
          if(filtered){
            m = state.medias.filter(media => state.filterTypes.includes(media.simpleType))
          }
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
        },
        getCSRF: (state) => () => {
          let that = this
          $.getJSON('/internal-api/refresh-csrf').done(function(data){
            if(data.csrf != store.state.CSRF){
              store.commit("setCSRF",data.csrf)
            }
            if(data.totalMedias != store.state.totalMedias){
              store.commit("setTotalMedias",data.totalMedias)
            }
          });
          return state.CSRF
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
        deleteMediaByTitle(state,title){
          // Does this really work? ^^
          var tmpMedias = []
          var found = false
          state.medias.forEach(function(value,key){
            if(value.title!=title){
              tmpMedias.push(value)
            }
          });
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
        setNotifications(state,notifications){
          state.notifications = notifications
        },
        setCSRF(state,CSRF){
          $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': CSRF }});
          state.CSRF = CSRF;
        },
        setTotalMedias(state,totalMedias){
          state.totalMedias = totalMedias
        },
        setLoginId(state,loginId){
          state.loginId = loginId
        },
      }
    }) 
  export const controls = `
            
    <div class="plyr__controls">
    <!-- <div class="text-center row plyr__control mb-5" id="title"> hkjhsohkshjd sdklhjdshdshk </div> -->
        <button type="button" class="plyr__control" data-plyr="restart">
            <svg role="presentation"><use xlink:href="#plyr-restart"></use></svg>
            <span class="plyr__tooltip" role="tooltip">Restart</span>
        </button>
        <button type="button" id="jumpToSavedPositionBtn" class="plyr__control" data-plyr="restart1">
            <i class="material-icons md-18">directions</i>
            <span id="jumpToSavedPositionBtnTooltip" class="plyr__tooltip" role="tooltip">New</span>
        </button>
        <button type="button" id="savePositionBtn" class="plyr__control" data-plyr="restart2">
            <i class="material-icons md-18">save</i>
            <span id="savePositionBtnTooltip" class="plyr__tooltip" role="tooltip">Save position</span>
        </button>
        <button type="button" id="skipIntroBtn" class="plyr__control" data-plyr="restart3">
            <i class="material-icons md-18">arrow_forward</i>
            <span id="skipIntroBtnTooltip" class="plyr__tooltip" role="tooltip">Skip intro</span>
        </button>
        <button type="button" class="plyr__control" data-plyr="rewind">
            <svg role="presentation"><use xlink:href="#plyr-rewind"></use></svg>
            <span class="plyr__tooltip" role="tooltip">Rewind {seektime} secs</span>
        </button>
        <button type="button" class="plyr__control" aria-label="Play, {title}" data-plyr="play">
            <svg class="icon--pressed" role="presentation"><use xlink:href="#plyr-pause"></use></svg>
            <svg class="icon--not-pressed" role="presentation"><use xlink:href="#plyr-play"></use></svg>
            <span class="label--pressed plyr__tooltip" role="tooltip">Pause</span>
            <span class="label--not-pressed plyr__tooltip" role="tooltip">Play</span>
        </button>
        <button type="button" class="plyr__control" data-plyr="fast-forward">
            <svg role="presentation"><use xlink:href="#plyr-fast-forward"></use></svg>
            <span class="plyr__tooltip" role="tooltip">Forward {seektime} secs</span>
        </button>
        <div class="plyr__progress">
            <input data-plyr="seek" type="range" min="0" max="100" step="0.01" value="0" aria-label="Seek">
            <progress class="plyr__progress__buffer" min="0" max="100" value="0">% buffered</progress>
            <span role="tooltip" class="plyr__tooltip">00:00</span>
        </div>
        <div class="plyr__time plyr__time--current" aria-label="Current time">00:00</div>
        <div class="plyr__time plyr__time--duration" aria-label="Duration">00:00</div>
        <button type="button" class="plyr__control" aria-label="Mute" data-plyr="mute">
            <svg class="icon--pressed" role="presentation"><use xlink:href="#plyr-muted"></use></svg>
            <svg class="icon--not-pressed" role="presentation"><use xlink:href="#plyr-volume"></use></svg>
            <span class="label--pressed plyr__tooltip" role="tooltip">Unmute</span>
            <span class="label--not-pressed plyr__tooltip" role="tooltip">Mute</span>
        </button>
        <div class="plyr__volume">
            <input data-plyr="volume" type="range" min="0" max="1" step="0.05" value="1" autocomplete="off" aria-label="Volume">
        </div>
        <button type="button" class="plyr__control" data-plyr="captions">
            <svg class="icon--pressed" role="presentation"><use xlink:href="#plyr-captions-on"></use></svg>
            <svg class="icon--not-pressed" role="presentation"><use xlink:href="#plyr-captions-off"></use></svg>
            <span class="label--pressed plyr__tooltip" role="tooltip">Disable captions</span>
            <span class="label--not-pressed plyr__tooltip" role="tooltip">Enable captions</span>
        </button>
        <button type="button" class="plyr__control" data-plyr="fullscreen">
            <svg class="icon--pressed" role="presentation"><use xlink:href="#plyr-exit-fullscreen"></use></svg>
            <svg class="icon--not-pressed" role="presentation"><use xlink:href="#plyr-enter-fullscreen"></use></svg>
            <span class="label--pressed plyr__tooltip" role="tooltip">Exit fullscreen</span>
            <span class="label--not-pressed plyr__tooltip" role="tooltip">Enter fullscreen</span>
        </button>
    </div>

    

    `;