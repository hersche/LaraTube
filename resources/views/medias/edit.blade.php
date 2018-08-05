@extends('layouts.app')


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
{!! Form::open(array('route' => ['medias.create'],'files'=>'true'))  !!}
<div class="col-xs-12 col-sm-12 col-md-12">

<h4>Direct upload</h4>
<div class="form-group">
    <label>Media-title:</label>
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
    {!! Form::select('type', ['localAudio' => 'Local audio', 'localVideo' => 'Local video', 'directVideo' => 'Direct video', 'directAudio' => 'Direct audio']) !!}
</div>

</div>
@csrf
{!! Form::submit('Add media')  !!}
{!! Form::close()  !!}
    </div>
</div>


@endsection
