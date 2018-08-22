require('./bootstrap');

//window.Vue = require('vue');

//Vue.component('media-component', require('./components/MediaComponent.vue'));



$( document ).ready(function() {
  $.getJSON( baseUrl+"api/media", function( data ) {
    var items = "";
    var carouselHtml = "";
    var carouselIndicatorsHtml = "";
    var first = true;
    var firstHtml = "active";
    console.log(data);
    $.each( data.data, function( key, value ) {
      val1 = value;
      carouselHtml += '<div class="carousel-item bg-dark '+firstHtml+'"><img src="'+ baseUrl + val1.poster_source + '" alt="'+val1.title+'"><div class="carousel-caption" style="color: black; background: lightgrey; opacity:0.9;"><h3>'+val1.title+' ('+val1.created_at_readable+')</h3><p>'+val1.description+'<span class="float-right"><a class="btn btn-primary mr-2" href="'+baseUrl+'media/'+val1.title+'">Play</a></span></p></div></div>';
      carouselIndicatorsHtml += '<li data-target="#demo" data-slide-to="0" class="'+firstHtml+'"></li>';
      items += '<div style="min-width: 300px;" class="col-lg-4 col-md-4 col-xs-6 card"><a href="'+baseUrl+'media/'+val1.title+'" class="d-block h-100"><img class="card-img-top" src="'+ baseUrl + val1.poster_source + '" alt=""><div class="card-img-overlay"><h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">'+val1.title+'</h4></div></a></div>';
      if(first){
        firstHtml = "";
        first = false;
      }
    });
    $("#carouselIndicatorsBody").html(carouselIndicatorsHtml);
    $("#carouselInnerBody").html(carouselHtml);
    var pages = "<input type='button' class='btn btn-info mr-1' value='First' onclick='rebuildVideo(\""+data.links.first+"\");' /> <input class='btn btn-info' type='button' value='Prev' onclick='rebuildVideo(\""+data.links.prev+"\");' /><span class='ml-1 mr-1'>"+data.meta.current_page+"/"+data.meta.last_page+"</span><input class='btn btn-info' type='button' value='Next' onclick='rebuildVideo(\""+data.links.next+"\");' /><input class='btn btn-info ml-1' type='button' value='Last' onclick='rebuildVideo(\""+data.links.last+"\");' />";
    $("#galleryBody").html(items);
    $("#pagesBody").html(pages);
});

});
