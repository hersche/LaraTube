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
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Your profile ({{ Auth::user()->name }})</h2>
        </div>
        <div class="pull-right">
            <h4> Pending friends</h4>
            @foreach ($users as $pendingFriend)
              @if (Auth::user()->hasFriendRequestFrom($pendingFriend))

              <p><img src="{{ $pendingFriend->avatar() }}" />{{ $pendingFriend->name }}
                <span class="float-right"><input type="button" value="Confirm request" onclick="sendFriendRequest({{ $pendingFriend->id }},'confirm')" /></span>
              </p>
              <p class="pl-3">Mutual friends: {{ Auth::user()->getMutualFriendsCount($pendingFriend) }} users</p>
              @endif
            @endforeach
        </div>
        <div class="pull-right">
            <h4> Accepted friends</h4>
            @foreach ($users as $pendingFriend)
              @if (Auth::user()->isFriendWith($pendingFriend))
                <p><img src="{{ $pendingFriend->avatar() }}" />{{ $pendingFriend->name }}
                  <span class="float-right"><input type="button" value="Unfriend" onclick="sendFriendRequest({{ $pendingFriend->id }},'unfriend')" /></span>
                </p>
                <p class="pl-3">Mutual friends: {{ Auth::user()->getMutualFriendsCount($pendingFriend) }} users</p>
              @endif
            @endforeach
        </div>
        <div class="pull-right">
            <h4> Not-friends</h4>
            @foreach ($users as $pendingFriend)
              @if (Auth::user()->isFriendWith($pendingFriend)==false)

                  @if ($pendingFriend->email!=Auth::user()->email)
                  <p><img src="{{ $pendingFriend->avatar() }}" style="max-height:90px;" /><span class="ml-2">{{ $pendingFriend->name }}</span>
                    <span class="float-right"><input type="button" value="Friend request" onclick="sendFriendRequest({{ $pendingFriend->id }},'request')" /></span>
                    <p class="pl-3">Mutual friends: {{ Auth::user()->getMutualFriendsCount($pendingFriend) }} users</p></p>
                  @endif

              @endif

            @endforeach
        </div>
    </div>
</div>


@endsection
