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
    $.each( data.data, function( key, value ) {
      val1 = value;
      carouselHtml += '<div class="carousel-item bg-dark '+firstHtml+'"><img src="'+ baseUrl + val1.poster_source + '" alt="'+val1.title+'"><div class="carousel-caption" style="color: black; background: lightgrey; opacity:0.9;"><h3>'+val1.title+'</h3><p>'+val1.description+'<span class="float-right"><a class="btn btn-primary mr-2" href="'+baseUrl+'media/'+val1.title+'">Play</a></span></p></div></div>';
      carouselIndicatorsHtml += '<li data-target="#demo" data-slide-to="0" class="'+firstHtml+'"></li>';
      items += '<div style="min-width: 300px;" class="col-lg-4 col-md-4 col-xs-6 card"><a href="'+baseUrl+'media/'+val1.title+'" class="d-block h-100"><img class="card-img-top" src="'+ baseUrl + val1.poster_source + '" alt=""><div class="card-img-overlay"><h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">'+val1.title+'</h4></div></a></div>';
      if(first){
        firstHtml = "";
        first = false;
      }
    });
    $("#carouselIndicatorsBody").html(carouselIndicatorsHtml);
    $("#carouselInnerBody").html(carouselHtml);
    $("#galleryBody").html(items);
});

});
