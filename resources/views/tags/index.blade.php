@extends('layouts.app')

@section('header')
  <script>
  function sendFriendRequest(id,type){
    console.log("i should send a request to userid "+id);
    $.ajax({
      url: '{{ url("/friends") }}',
      type: 'PUT',
      data: "users_id="+id+"&type="+type,
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
    <div class="col-lg-12 margin-tb">
      @foreach ($tags as $tag)
        <p><a href="{{ url("/tags/") }}/{{ $tag->name }}">{{ $tag->name }}</a></p>
      @endforeach
    </div>
</div>


@endsection
