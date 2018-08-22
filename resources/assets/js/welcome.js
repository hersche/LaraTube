require('./bootstrap');

window.Vue = require('vue');

Vue.component('media-component', require('./components/MediaComponent.vue'));

$( document ).ready(function() {
  $.getJSON( baseUrl+"api/media", function( data ) {
    var items = "";
    var indicators = "";
    var first=true;
    $.each( data.data, function( key, value ) {
      val1 = value;
      items += '<div style="min-width: 300px;" class="col-lg-4 col-md-4 col-xs-6 card"><a href="{{ url("/media/") }}/'+val1.title+'" class="d-block h-100"><img class="card-img-top" src="'+ val1.poster_source + '" alt=""><div class="card-img-overlay"><h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">'+val1.title+'</h4></div></a></div></div></div>';
    });
    $("#carouselBody").html(items);
});

});
