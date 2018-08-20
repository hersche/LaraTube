@extends('layouts.app')
@section('header')
  <link href="{{ asset("js/croppie/croppie.css") }}" rel="stylesheet" type="text/css">
  <script src="{{ asset("js/croppie/croppie.js") }}"></script>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit New User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('media.show',$media->title) }}"> Back</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif


<div class="row justify-content-center">
    <div class="col-md-8">
{!! Form::open(array('route' => ['medias.edit',$media->title],'files'=>'true','id'=>'addMediaForm'))  !!}
<div class="col-xs-12 col-sm-12 col-md-12">

<h4>{{ __("Edit media") }}</h4>
<div class="form-group">
    <label>Media-poster:</label>
    <div id="poster"></div>
    <script>

    var posterResize;
      $( document ).ready(function() {
        var el = document.getElementById('poster');
        posterResize = new Croppie(el, {
            viewport: { width: 100, height: 60 },
            boundary: { width: 120, height: 100 },
            showZoomer: true,
            //enableResize: true,
        });
        posterResize.bind({
          url: '{{ url($media->poster()) }}',
        });
        $('#posterUpload').on('change', function () {
    	      var reader = new FileReader();
           reader.onload = function (e) {
      	      posterResize.bind({
        		     url: e.target.result
        	     }).then(function(){
        		      console.log('jQuery bind complete');
        	     });
            }
            reader.readAsDataURL(this.files[0]);
          });

          $('#posterUploadAction').on('click', function (ev) {
          	posterResize.result({
          		type: 'canvas',
          		size: 'viewport'
          	}).then(function (resp) {
              $("#addMediaImage").attr("value",resp);
              $("#addMediaForm").submit();
          	});
          });

        });
        </script>

    {!! Form::file('poster', ['id' => 'posterUpload'])  !!}
</div>
<div class="form-group">
    <label>Media-title:</label>
    <input type="hidden" value="" name="image" id="addMediaImage" />
    {!! Form::text('title', $media->title, array('placeholder' => 'Media-title','class' => 'form-control')) !!}
</div>

<div class="form-group">
    <label>Media-description:</label>
    {!! Form::textarea('description', $media->description, array('placeholder' => 'Media-description','class' => 'form-control')) !!}
</div>

<div class="form-group">
    <label>Media-source:</label>
    {!! Form::text('source', $media->source, array('placeholder' => 'Media-description','class' => 'form-control')) !!}
</div>

    <div class="form-group">
    <label>Media-type:</label>
    {!! Form::select('type', ['localAudio' => 'Local audio', 'localVideo' => 'Local video', 'directVideo' => 'Direct video', 'directAudio' => 'Direct audio', 'torrentAudio' => 'Torrent audio', 'torrentVideo' => 'Torrent video'], $media->pluck('type')) !!}
</div>
    <div class="form-group">
        <strong>Tags (separate with spaces):</strong>
        <input id="tags" type="text" class="form-control" name="tags" value="{{ $media->tagString() }}" >
    </div>
</div>
@csrf
<input class="btn btn-primary" type="button" onclick="" value="Edit media" id="posterUploadAction" />
{!! Form::close()  !!}
    </div>
</div>


@endsection
