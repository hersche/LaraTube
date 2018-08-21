@extends('layouts.app')

@section('header')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/plyr/3.3.23/plyr.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/plyr/3.3.23/plyr.css" rel="stylesheet" type="text/css">
  @if ($media->simpleType()=="torrent")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webtorrent/0.102.0/webtorrent.min.js" ></script>
  @endif
  <script>

  var users = [];



  function getUser(id){
    for (i = 0; i < users.length; i++) {
        if(users[i].id==id){
          return users[i];
        }
    }
    // retrive user-data
  }

  function User(id, name, avatar_source) {
      this.id = id;
      this.name = name;
      this.avatar_source = avatar_source;
  }

  </script>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h2> {{ $media->title }}</h2>
        </div>
        <div class="float-right">
            <a class="btn btn-primary float-right" href="{{ url('/') }}"> Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <p>{{ $media->description }}</p>
        <p>Tags: {{ $media->tagString() }}</p>
          @if ($media->type=="localVideo" || $media->type=="directVideo"|| $media->type=="torrentVideo")
            <video class="col-12" id="player" poster="{{ url($media->poster_source) }}" playsinline controls>
              @if ($media->type=="localVideo" || $media->type=="directVideo")
                <source src="{{ url($media->source) }}" type="video/mp4">
              @endif
            </video>
          @endif

          @if ($media->type=="localAudio" || $media->type=="directAudio"|| $media->type=="torrentAudio")
            <audio id="player" poster="{{ url($media->poster_source) }}" playsinline controls>
              @if ($media->type=="localAudio" || $media->type=="directAudio")
                <source src="{{ url($media->source) }}" >
              @endif
            </audio>
          @endif
          @if ($media->simpleType()=="torrent")
            <script>
                var client = new WebTorrent();
            $(document).ready(function () {
                client.add('{{ $media->source }}', function (torrent) {
                // Torrents can contain many files. Let's use the .mp4 file
                var file = torrent.files.find(function (file) {
                  return file.name.endsWith('.mp4');
                });

                // Display the file by adding it to the DOM. Supports video, audio, image, etc. files
                file.renderTo('#player');
                // Trigger statistics refresh
              /*  torrent.on('done', onDone);
                setInterval(onProgress, 500);
                onProgress();
                file.getBlobURL(function (err, url) {
                  if (err){
                    console.log(err.message);
                  }
                  $("#downloadButton").attr("href",url);
                  $("#downloadButton").removeClass("d-none");
                });
                $("#torrentDL").html("<a href='"+torrent.torrentFileBlobURL+"' download='Stimulate....torrent'>Torrent-file</a>");
                function onProgress () {
                  // Peers
                  $("#torrentSeeders").html(torrent.numPeers + (torrent.numPeers === 1 ? ' peer' : ' peers'));
                  // Progress
                  var percent = Math.round(torrent.progress * 100 * 100) / 100;
                  $("#torrentDownloaded").html(prettyBytes(torrent.downloaded) + " / " + prettyBytes(torrent.length) + " ("+percent+"%)");

                  // Speed rates
                  $("#torrentDownloadSpeed").html(prettyBytes(torrent.downloadSpeed) + '/s (down)');
                  $("#torrentUploadSpeed").html(prettyBytes(torrent.uploadSpeed) + '/s (up)');
                }
                function onDone () {
                  onProgress();
                }
                function prettyBytes(num) {
                  var exponent, unit, neg = num < 0, units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                  if (neg) num = -num;
                  if (num < 1) return (neg ? '-' : '') + num + ' B';
                  exponent = Math.min(Math.floor(Math.log(num) / Math.log(1000)), units.length - 1);
                  num = Number((num / Math.pow(1000, exponent)).toFixed(2));
                  unit = units[exponent];
                  return (neg ? '-' : '') + num + ' ' + unit;
                } */
              });
            });
            </script>
            @endif
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    </div>
    <div><button type="button"  onclick="like({{ $media->id }},'media');" class="btn btn-primary"><ion-icon name="thumbs-up"></ion-icon><span class="ml-1">{{ $media->likes() }}</span></button>
       <button onclick="dislike({{ $media->id }},'media');"  class="btn btn-primary"><ion-icon name="thumbs-down"></ion-icon><span class="ml-1">{{ $media->dislikes() }}</span></button></div>
</div>
@auth
{!! Form::open(['method' => 'PUT','route' => ['comments.add']]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-inline" id="commentForm">
            {!! Form::hidden('medias_id', $media->id,array('id' => 'medias_id')) !!}
            {!! Form::hidden('medias_title', $media->title,array('id' => 'medias_title')) !!}
            {!! Form::text('body', null, array('placeholder' => 'Comment...','class' => 'col-9','id' => 'medias_body')) !!}
            <input type="button" class="ml-1" value="Send comment!" onclick="sendComment();" />
        </div>
        <script>
        var nextTitle = "";

        $( document ).ready(function() {
        const player = new Plyr('#player');
        getAutoplay();
          var playerEl = document.getElementById("player");
         if(localStorage.getItem("autoplay")=="true"){
          //  $("#player").autoplay = true;
            player.play();
          }
          playerEl.onended = function() {
            console.log("Media ended, if autoplay enabled, next video!");
            if(localStorage.getItem("autoplay")=="true"){
              document.location = "{{ url("/media" ) }}/"+nextTitle;
            }
          };
          //function loadMedias(){
            $.getJSON( "{{ url('/api/media/not') }}/{{ $media->title }}", function( data ) {
              var items = "";
              var indicators = "";
              var first=true;
              nextTitle = data.data[0].title;
              console.log(nextTitle);
              $.each( data.data, function( key, value ) {
                val1 = value;
          //      indicators += '<li data-target="#carouselExampleIndicators" data-slide-to="'+(key-1)+'" class="'+active+'"></li>'
                items += '<div style="min-width: 180px;" class="col-lg-3 col-md-4 col-xs-6 card"><a href="{{ url("/media/") }}/'+val1.title+'" class="d-block h-100"><img class="card-img-top" src="{{ url("/") }}/'+ val1.poster_source + '" alt=""><div class="card-img-overlay"><h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">'+val1.title+'</h4></div></a></div></div></div>';
              });
          //    $("#carouselIndicators").html(indicators);
              $("#nextVideos").html(items);
            //  $("#carouselExampleIndicators").carousel(0);
          });
          //}

        });

        function deleteComment(id){
          $.ajax({
            url: '{{ url("/comment") }}',
            type: 'DELETE',
            data: "comments_id="+id,
            success: function(data) {
              $("#cid"+id).html("");
              console.log("dynamicly deleted comment");
            }
          });
        }

        function setAutoplay(){
          if(localStorage.getItem("autoplay")=="true"){
            localStorage.setItem("autoplay", "false");
          } else {
            localStorage.setItem("autoplay", "true");
          }
          getAutoplay();
        }

        function getAutoplay(){
          if(localStorage.getItem("autoplay")=="true"){
            $("#autoplayBtn").attr("value", "Autoplay enabled");
          } else {
            $("#autoplayBtn").attr("value", "Autoplay disabled");
          }
        }

        function sendComment(){
        $.ajax({
          url: '{{ url("/comment") }}',
          type: 'PUT',
          data: "medias_title="+$('#medias_title').val()+"&medias_id="+$('#medias_id').val()+"&body="+$('#medias_body').val(),
          success: function(data) {
            var commentHtml = "<table class='table table-fluid'><tbody><tr><td class='col-4'>{{ Auth::user()->name }}</td><td class='col-4 float-right'>Created now!</td></tr><tr><td class='col-8'>"+$('#medias_body').val()+"</td></tbody></table>";
            $("#comments").html(commentHtml + $("#comments").html());
          }
        });
      }
      function like(id,type){
      $.ajax({
        url: '{{ url("/like") }}',
        type: 'PUT',
        data: type+"_id="+id+"&count=1",
        success: function(data) {
          alert("liked");
        }
      });
    }
    function dislike(id,type){
    $.ajax({
      url: '{{ url("/like") }}',
      type: 'PUT',
      data: type+"_id="+id+"&count=-1",
      success: function(data) {
        alert("disliked");
      }
    });
  }
        </script>
    </div>

</div>

{!! Form::close() !!}
@endauth
<hr />
<div id="comments" class="container-fluid mt-2">
@foreach($media->comments() as $comment)

<div class="comment mb-2 row" id='cid{{ $comment->id }}'>
    <div class="comment-avatar col-md-1 col-sm-2 text-center pr-1">
        <a href=""><img class="mx-auto rounded-circle img-fluid" src="{{ url($comment->user()->avatar()) }}" alt="avatar"></a>
    </div>
    <div class="comment-content col-md-11 col-sm-10">
        <h6 class="small comment-meta"><a href="#">{{ $comment->user()->name }}</a> {{ $comment->created_at }}
          @if (Auth::id() == $comment->users_id)
            <span class="float-right" onclick="deleteComment({{ $comment->id }});">Delete</span>
          @endif
        </h6>
        <div class="comment-body">
            <p>
                {{ $comment->body }}
                <br>
                <a href="" class="text-right small"><i class="ion-reply"></i> Reply</a>
            </p>
        </div>
    </div>
  </div>
@endforeach
</div>
<div class='row'><h4>{{ __("Next videos") }}<input class="float-right pull-right" value="Set autoplay" id="autoplayBtn" type="button" onclick="setAutoplay();" /></h4></div>
<div class='row'>
<div id="nextVideos" class="row text-center text-lg-left">

</div>
@endsection
