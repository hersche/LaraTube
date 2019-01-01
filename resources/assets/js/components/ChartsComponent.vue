<template>
    <div class="row">
        <span class="" >
          <apexchart width="500" type="bar" :options="chartOptions" :series="series"></apexchart>
        </span>
        <span class="" style="">
          <apexchart width="500" type="pie" id="chart2" :options="chartOptions2" :series="series2"></apexchart>
        </span>
      </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['medias','loggeduserid'],
    computed: {
      // a computed getter
      chartOptions: function () {
        // `this` points to the vm instance

        return {
          chart: {
            id: 'vuechart-example'
          },
          xaxis: {
            categories: ["Local audio", "Local video", "Direct audio", "Direct Video","Torrent audio", "Torrent video"]
          }
      };
    },
      series: function () {
        var localVideo=0,localAudio=0,directAudio=0,directVideo=0,torrentAudio=0,torrentVideo=0;
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
          } else {
            console.warn("Weird type? "+item.type)
          }
        });
        return [{
          name: 'series-1',
          data: [localVideo,localAudio,directAudio,directVideo,torrentAudio,torrentVideo]
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
