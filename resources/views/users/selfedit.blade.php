@extends('layouts.app')


@section('header')
  <link href="{{ asset("js/croppie/croppie.css") }}" rel="stylesheet" type="text/css">
  <script src="{{ asset("js/croppie/croppie.js") }}"></script>


@endsection

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left float-left">
            <h2>{{ __("Edit your profile") }}</h2>
        </div>
        <div class="float-right pull-right">
            <a class="btn btn-primary" href="{{ url('/')}}"> Back</a>
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
<div id="">
<div id="avatar"></div>
<script>

var avatarResize;
var backgroundResize;
  $( document ).ready(function() {
    var el = document.getElementById('avatar');
    avatarResize = new Croppie(el, {
        viewport: { width: 180, height: 180, type: 'circle' },
        boundary: { width: 200, height: 200 },
        showZoomer: true,
        //enableResize: true,
    });
    avatarResize.bind({
      url: '{{ url($user->avatar()) }}',
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


      var el1 = document.getElementById('background');
      backgroundResize = new Croppie(el1, {
          viewport: { width: 800, height: 300},
          boundary: { width: 900, height: 350 },
          showZoomer: true,
          //enableResize: true,
      });
      backgroundResize.bind({
        url: '{{ url($user->background()) }}',
      });
      $('#backgroundUpload').on('change', function () {
  	      var reader = new FileReader();
         reader.onload = function (e) {
    	      backgroundResize.bind({
      		     url: e.target.result
      	     }).then(function(){
      		      console.log('jQuery bind complete');
      	     });
          }
          reader.readAsDataURL(this.files[0]);
        });

        $('#backgroundUploadAction').on('click', function (ev) {
        	backgroundResize.result({
        		type: 'canvas',
        		size: 'viewport'
        	}).then(function (resp) {
        		$.ajax({
        			url: "/user/updateBackground",
        			type: "PUT",
        			data: {"image":resp},
        			success: function (data) {
                // success!
        			}
        		});
        	});
        });
    //  resize.refresh();
  });
</script>
</div>

{!! Form::open(array('method' => 'POST', 'route' => ['users.updateAvatar'],'files'=>'true'))  !!}
{!! Form::file('avatar_source', ['id' => 'avatarUpload'])  !!}
<input type="button" value="Save avatar-changes" id="avatarUploadAction" />
{!! Form::close()  !!}



<div id="background"></div>
{!! Form::open(array('method' => 'POST', 'route' => ['users.updateBackground'],'files'=>'true'))  !!}
{!! Form::file('background_source', ['id' => 'backgroundUpload'])  !!}
<input type="button" value="Save background-changes" id="backgroundUploadAction" />
{!! Form::close()  !!}

{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {!! Form::text('name', $user->name, array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', $user->email, array('placeholder' => 'Email','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Tags (separate with spaces):</strong>
            <input id="tags" type="text" class="form-control" name="tags" value="{{ $user->tagString() }}" >
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
          <label>Bio:</label>
          {!! Form::textarea('bio', $user->bio, array('placeholder' => 'Media-description','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Password:</strong>
            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Confirm Password:</strong>
            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
{!! Form::close() !!}



@endsection
