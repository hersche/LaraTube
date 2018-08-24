@extends('layouts.app')

@section('header')
  <script>
  function getMediaByTags(tags){
    console.log("i should send a request to userid "+id);
    $.ajax({
      url: '{{ url("/api/tags") }}/'+tags,
      type: 'GET',
      success: function(data) {
      //  $("#cid"+id).html("");
        console.log("friend-request done: "+type);
      }
    });
  }
  </script>
@endsection

@section('content')
<div class="row">
<input placeholder="Tag-search" id="tagSearch" type="text" />
</div>
<div class="row">
    <div class="col-lg-12 margin-tb">
      @foreach ($medias as $media)
        <p><a href="{{ url("/media/") }}/{{ $media->title }}">#{{ $media->title }}</a></p>
      @endforeach
    </div>
</div>


@endsection
