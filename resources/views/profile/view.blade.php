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
  <style>
    #profile{
      background-image: url({{ url($user->background()) }});
    }
  </style>
@endsection

@section('content')
<div id="profile" >
  <div id="profileheader">
    <img class='mr-3' src='{{ url($user->avatar()) }}' />
    @auth
    @if (Auth::user()->isFriendWith($user))
      <span class="float-right"><input type="button" value="Unfriend" onclick="sendFriendRequest({{ $user->id }},'unfriend')" /></span>
    @elseif (Auth::user()->hasSentFriendRequestTo($user))
      <span class="float-right"><input type="button" value="(Friendrequest already sent) Unfriend" onclick="sendFriendRequest({{ $user->id }},'unfriend')" /></span>
    @else
      <span class="float-right"><input type="button" value="Add to friends" onclick="sendFriendRequest({{ $user->id }},'request')" />
      </span>
    @endif
    @endauth
  </div>
  <div id="profilebody" style="background-color: lightgrey; opacity: 0.8;">
    <h1 class='ml-3'>{{ $user->name }}</h1>
    <p>{{ $user->getFriendsCount() }} friends</p>
    @auth
      <p>{{ Auth::user()->getMutualFriendsCount($user) }} mutual friends</p>
    @endauth
    @if (!empty($user->bio))
      {{ $user->bio }}
    @endif

  </div>

</div>
<div id="profilevideos">
  @foreach ($user->medias as $media)
    <div style="min-width: 300px;" class="col-lg-4 col-md-4 col-xs-6 card"><a href="{{ url("/media/") }}/{{ $media->title }}" class="d-block h-100"><img class="card-img-top" src="{{ url($media->poster()) }}" alt=""><div class="card-img-overlay"><h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">{{ $media->title }}</h4></div></a></div>
  @endforeach
</div>
@endsection
