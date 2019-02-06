<template>
    <div class="">
      <p>The numbers on this chart represent only your loaded medias. To see all medias, go to the menu -> Developer options -> Load all medias</p>
      <p>This can take a while / need performance.</p>
      <div class="">
        <span class="col-6 col-sm-12" >
          <apexchart width="100%" type="bar" :options="chartOptions" :series="series"></apexchart>
        </span>
        <span class="col-6 col-sm-12" style="">
          <apexchart width="100%" type="pie" id="chart2" :options="chartOptions2" :series="series2"></apexchart>
        </span>
      </div>
        <span class="col-6 col-sm-12" >
          <apexchart width="100%" type="bar" :options="likeOptions" :series="likeSeries"></apexchart>
        </span>
      </div>
</template>
<script>
  import { eventBus,store } from '../eventBus.js';
  export default {
    props: ['loggeduserid'],
    methods:{

    },
    computed: {
      medias:function(){
        return store.getters.getMediasByTypes()
      },
      likeOptions: function () {
        // `this` points to the vm instance
        var titles = []
        this.medias.forEach( function(item, index) {
          titles.push(item.title)
        });
        return {
          chart: {
            id: 'vuechart-likes'
          },
          xaxis: {
            categories: titles
          }
      };
    },
      likeSeries: function () {
        var likeArray = []
        var dislikeArray = []
        this.medias.forEach( function(item, index) {
          likeArray.push(item.likes)
          dislikeArray.push(item.dislikes)
        });
        return [{
          name: 'Likes',
          data: likeArray
        },{
          name: 'Dislikes',
          data: dislikeArray
        },
      ];
      },
      // a computed getter
      chartOptions: function () {
        // `this` points to the vm instance

        return {
          chart: {
            id: 'vuechart-example'
          },
          xaxis: {
            categories: ["Local audio", "Local video", "Direct audio", "Direct Video","Torrent audio", "Torrent video","Youtube","Vimeo"]
          }
      };
    },
      series: function () {
        var localVideo=0,localAudio=0,directAudio=0,directVideo=0,torrentAudio=0,torrentVideo=0,youtube=0,vimeo=0;
        this.medias.forEach( function(item, index) {
          if(item.type=="localVideo"){
            localVideo++;
          } else if (item.type=="localAudio"){
            localAudio++;
          } else if (item.type=="directAudio"){
            directAudio++;
          } else if (item.type=="directVideo"){
            directVideo++;
          } else if (item.type=="torrentAudio"){
            torrentAudio++;
          } else if (item.type=="torrentVideo"){
            torrentVideo++;
          }else if (item.type=="youtube"){
            youtube++;
          } else if (item.type=="vimeo"){
            vimeo++;
          } else {
            console.warn("Weird type? "+item.type)
          }
        });
        return [{
          name: 'Medias',
          data: [localAudio,localVideo,directAudio,directVideo,torrentAudio,torrentVideo,youtube,vimeo]
        }];
      },
      chartOptions2: function () {
        // `this` points to the vm instance
        var videoCount=0,audioCount=0,torrentCount=0;
        this.medias.forEach( function(item, index) {
          if(item.techType=="video"){
            videoCount++;
          } else if (item.techType=="audio"){
            audioCount++;
          } else {
            torrentCount++;
          }
        });
        return {
            chart: {
                width: 380,
            },
            labels: ["Video", "Audio"],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
    },
      series2: function () {
        var videoCount=0,audioCount=0;
        this.medias.forEach( function(item, index) {
          if(item.simpleType=="video"){
            videoCount++;
          } else if (item.simpleType=="audio"){
            audioCount++;
          }
        });
        return [videoCount,audioCount];
      }
    },
  }
</script>
