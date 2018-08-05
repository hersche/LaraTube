@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
{!! Form::open(array('route' => ['medias.directuploadAjax'],'files'=>'true'))  !!}
<div class="col-xs-12 col-sm-12 col-md-12">

<h4>Direct upload</h4>
    <div class="form-group">
        <label>Media-title:</label>
        {!! Form::text('title', null, array('placeholder' => 'Media-title','class' => 'form-control')) !!}
    </div>

    <div class="form-group">
        <label>Media-description:</label>
        {!! Form::textarea('description', null, array('placeholder' => 'Media-description','class' => 'form-control')) !!}
    </div>

</div>
<p>Upload your mp4, webm, mp3 or ogg-files here.</p>
{!! Form::file('directMedia')  !!}
@csrf
{!! Form::submit('Upload File')  !!}
{!! Form::close()  !!}

        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
{!! Form::open(array('route' => ['medias.create'],'files'=>'true'))  !!}
<div class="col-xs-12 col-sm-12 col-md-12">

<h4>Direct upload</h4>
    <div class="form-group">
        <label>Media-title:</label>
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
</div>
@endsection
