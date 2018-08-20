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
          console.log("dynamicly deleted comment");
        }
      });
    }
  </script>
@endsection

@section('content')
  <div id="profileheader">
    <img class='float-left mr-3' src='{{ url($user->avatar()) }}' /><h1 class='ml-3'>{{ $user->name }}</h1>
  </div>
  <div id="profilebody">
    <p>{{ $user->getFriendsCount() }} friends</p>
    <p>{{ Auth::user()->getMutualFriendsCount($user) }} mutual friends</p>
    @if (!empty($user->bio))
      {{ $user->bio }}
    @endif
  </div>
  <div id="profilevideos">
    @foreach ($user->medias as $media)
      <div style="min-width: 300px;" class="col-lg-4 col-md-4 col-xs-6 card"><a href="{{ url("/media/") }}/{{ $media->title }}" class="d-block h-100"><img class="card-img-top" src="{{ url($media->poster()) }}" alt=""><div class="card-img-overlay"><h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">{{ $media->title }}</h4></div></a></div>
    @endforeach
  </div>
@endsection
