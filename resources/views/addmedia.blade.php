@extends('layouts.app')

@section('header')
  <link href="{{ asset("js/croppie/croppie.css") }}" rel="stylesheet" type="text/css">
  <script src="{{ asset("js/croppie/croppie.js") }}"></script>
@endsection

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

<div class="container">
  <nav class="nav">
    <a class="nav-link active" href="#add" data-toggle="tab">Add media</a>
    <a class="nav-link" href="#upload" data-toggle="tab">Direct upload</a>
  </nav>
  <hr />
  <div class="tab-content clearfix">
  			  <div class="tab-pane active containert" id="add">
  <div class="row justify-content-center">
      <div class="col-md-8">
{!! Form::open(array('route' => ['medias.create'],'files'=>'true'))  !!}
<div class="col-xs-12 col-sm-12 col-md-12">

<h4>{{ __('Add media') }}</h4>

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
          url: '{{ asset("img/404/image.png") }}',
        });
        $('#posterUpload').on('change', function () {
    	      var reader = new FileReader();
           reader.onload = function (e) {
      	      avatarResize.bind({
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
          		$.ajax({
          			url: "/user/updateAvatar",
          			type: "PUT",
          			data: {"image":resp},
          			success: function (data) {
                  // success!
          			}
          		});
          	});
          });

        });
        </script>

    {!! Form::file('poster', ['id' => 'avatarUpload'])  !!}
</div>
  <div class="form-group">
      <label>{{ __('Media-title') }}</label>
      {!! Form::text('title', null, array('placeholder' => 'Media-title','class' => 'form-control')) !!}
  </div>

  <div class="form-group">
      <label>Media-description:</label>
      {!! Form::textarea('description', null, array('placeholder' => 'Media-description','class' => 'form-control')) !!}
  </div>

  <div class="form-group">
      <label>Media-source:</label>
      {!! Form::text('source', null, array('placeholder' => 'Media-description','class' => 'form-control')) !!}
  </div>
  <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
          <strong>Tags (separate with spaces):</strong>
          <input id="tags" type="text" class="form-control" name="tags" value="" >
      </div>
  </div>
  <div class="form-group">
      <label>Media-type:</label>
      {!! Form::select('type', ['localAudio' => 'Local audio', 'localVideo' => 'Local video', 'directVideo' => 'Direct video', 'directAudio' => 'Direct audio', 'torrentAudio' => 'Torrent audio', 'torrentVideo' => 'Torrent video']) !!}
  </div>

</div>
@csrf
{!! Form::submit('Add media')  !!}
{!! Form::close()  !!}
      </div>
  </div>
</div>  			  <div class="tab-pane container fade" id="upload">
    <div class="row justify-content-center bg-secondary">
        <div class="col-md-8">
{!! Form::open(array('route' => ['medias.directuploadAjax'],'files'=>'true'))  !!}
<div class="col-xs-12 col-sm-12 col-md-12 ">

<h4>Direct upload</h4>

<div class="form-group">
    <label>Media-poster:</label>

    <div id="avatar"></div>
    <script>

    var avatarResize;
      $( document ).ready(function() {
        var el = document.getElementById('avatar');
        avatarResize = new Croppie(el, {
            viewport: { width: 100, height: 60 },
            boundary: { width: 120, height: 100 },
            showZoomer: true,
            //enableResize: true,
        });
        avatarResize.bind({
          url: '{{ asset("img/404/image.png") }}',
        });
        $('#avatarUpload').on('change', function () {
    	      var reader = new FileReader();
           reader.onload = function (e) {
      	      avatarResize.bind({
        		     url: e.target.result
        	     }).then(function(){
        		      console.log('jQuery bind complete');
        	     });
            }
            reader.readAsDataURL(this.files[0]);
          });

          $('#avatarUploadAction').on('click', function (ev) {
          	avatarResize.result({
          		type: 'canvas',
          		size: 'viewport'
          	}).then(function (resp) {
          		$.ajax({
          			url: "/user/updateAvatar",
          			type: "PUT",
          			data: {"image":resp},
          			success: function (data) {
                  // success!
          			}
          		});
          	});
          });

        });
        </script>

    {!! Form::file('poster', ['id' => 'avatarUpload'])  !!}
</div>

    <div class="form-group">
        <label>Media-title:</label>
        {!! Form::text('title', null, array('placeholder' => 'Media-title','class' => 'form-control')) !!}
    </div>

    <div class="form-group">
        <label>Media-description:</label>
        {!! Form::textarea('description', null, array('placeholder' => 'Media-description','class' => 'form-control')) !!}
    </div>

</div>
<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <strong>Tags (separate with spaces):</strong>
        <input id="tags" type="text" class="form-control" name="tags" value="" >
    </div>
</div>
<p>Upload your mp4, webm, mp3 or ogg-files here.</p>
{!! Form::file('directMedia')  !!}
@csrf
{!! Form::submit('Upload file')  !!}
{!! Form::close()  !!}

        </div>
    </div>
</div></div>

</div>
@endsection
