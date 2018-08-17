@extends('layouts.app')

@section('header')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/plyr/3.3.23/plyr.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/plyr/3.3.23/plyr.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Show Media</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('media') }}"> Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h2>{{ $media->title }}</h2>
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/webtorrent/0.102.0/webtorrent.min.js" ></script>
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
</div>
@auth
{!! Form::open(['method' => 'PUT','route' => ['comments.add']]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group" id="commentForm">
            <strong>Name:</strong>
            {!! Form::hidden('medias_id', $media->id,array('id' => 'medias_id')) !!}
            {!! Form::hidden('medias_title', $media->title,array('id' => 'medias_title')) !!}
            {!! Form::text('body', null, array('placeholder' => 'Comment...','class' => 'form-control','id' => 'medias_body')) !!}
        </div>
        <script>
        function sendComment(){
        $.ajax({
          url: '{{ url("/comment/add") }}',
          type: 'PUT',
          data: "medias_title="+$('#medias_title').val()+"&medias_id="+$('#medias_id').val()+"&body="+$('#medias_body').val(),
          success: function(data) {
            var commentHtml = "<table class='table table-fluid'><tbody><tr><td class='col-6'>{{ Auth::user()->name }}</td><td class='col-6 float-right'>Created now!</td></tr><tr><td class='col-8'>"+$('#medias_body').val()+"</td></tbody></table>";
            $("#comments").html(commentHtml + $("#comments").html());
          }
        });
      }
        </script>
    </div>
    <input type="button" value="Send comment!" onclick="sendComment();" />
</div>

{!! Form::close() !!}
@endauth

<div id="comments" class="container-fluid">
@foreach($media->comments() as $comment)
  <table class='table table-fluid table-dark mb-2'><tbody><tr><td class='col-6'>{{ $comment->user()->name }}</td><td class='col-6 float-right'>{{ $comment->created_at }}</td></tr><tr><td class='col-8'>{{ $comment->body }}</td></tbody></table>
@endforeach
</div>
@endsection
