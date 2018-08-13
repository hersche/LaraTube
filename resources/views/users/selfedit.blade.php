@extends('layouts.app')


@section('header')
<!--
  <script src="{{ asset("js/cropper/dist/cropper.js") }}"></script>
  <script src="{{ asset("js/croppie/dist/jquery-cropper.js") }}"></script>
  <link href="{{ asset("js/cropper/dist/cropper.css") }}" rel="stylesheet" type="text/css">
  <script>

  $( document ).ready(function() {
    var $image = $('#avsrc');

    $image.cropper({
      aspectRatio: 16 / 9,
      crop: function(event) {
        console.log(event.detail.x);
        console.log(event.detail.y);
        console.log(event.detail.width);
        console.log(event.detail.height);
        console.log(event.detail.rotate);
        console.log(event.detail.scaleX);
        console.log(event.detail.scaleY);
      }
    });
}); -->
  </script>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit New User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
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

<div id="page">
<img id="avsrc" src="{{ url($user->avatar_source) }}" />
</div>

{!! Form::open(array('method' => 'POST', 'route' => ['users.updateAvatar'],'files'=>'true'))  !!}
{!! Form::file('avatar_source')  !!}
{!! Form::submit('Upload avatar')  !!}
{!! Form::close()  !!}



<img src="{{ url($user->background_source) }}" />
{!! Form::open(array('method' => 'POST', 'route' => ['users.updateBackground'],'files'=>'true'))  !!}
{!! Form::file('background_source')  !!}
{!! Form::submit('Upload background')  !!}
{!! Form::close()  !!}

{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
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
