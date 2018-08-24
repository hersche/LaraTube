@extends('layouts.app')

@section('header')
  <script>
  function getMediaByTags(tags){
    $.ajax({
      url: '{{ url("/api/tags") }}/'+tags+'?tagsCombine='+$("#tagsCombine").prop('checked'),
      type: 'GET',
      success: function(data) {
      //  $("#cid"+id).html("");
      var items = "";
      console.log("data: "+data.data);
      $.each( data.data, function( key, value ) {
        console.log("do round!");
        val1 = value;
        items += '<div style="min-width: 300px;" class="col-lg-4 col-md-4 col-xs-6 card"><a href="'+baseUrl+'media/'+val1.title+'" class="d-block h-100"><img class="card-img-top" src="'+ baseUrl + val1.poster_source + '" alt=""><div class="card-img-overlay"><h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">'+val1.title+'</h4></div></a></div>';
      });
      var pages = "<input type='button' class='btn btn-info mr-1' value='First' onclick='rebuildVideo(\""+data.links.first+"\");' /> <input class='btn btn-info' type='button' value='Prev' onclick='rebuildVideo(\""+data.links.prev+"\");' /><span class='ml-1 mr-1'>"+data.meta.current_page+"/"+data.meta.last_page+"</span><input class='btn btn-info' type='button' value='Next' onclick='rebuildVideo(\""+data.links.next+"\");' /><input class='btn btn-info ml-1' type='button' value='Last' onclick='rebuildVideo(\""+data.links.last+"\");' />";
      $("#galleryBody").html(items);
      $("#pagesBody").html(pages);
        console.log(data);
      }
    });
  }
      $( document ).ready(function() {
    $("#tagSearch").keyup( function(){
      getMediaByTags($("#tagSearch").val());
    });
  });
  </script>
@endsection

@section('content')
<div class="float-right">
    <div class="col-lg-12 margin-tb">
      @foreach ($tags as $tag)
        <p><a href="{{ url("/tags/") }}/{{ $tag->name }}">#{{ $tag->name }}</a></p>
      @endforeach
    </div>
</div>
<div class="row">
  <input placeholder="Tag-search - do not use # and use space for multiple" id="tagSearch" type="text" /><input type="checkbox" id="tagsCombine" unchecked />Combined / Strict
</div>

<div id="galleryBody" class="row">
</div>
<div id="pagesBody" class="row">
</div>



@endsection
