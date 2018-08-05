@extends('layouts.app')


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


            <video poster="{{ $media->poster_source }}" playsinline controls>
              <source src="{{ $media->source }}" type="video/mp4">
            </video>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    </div>
</div>
@endsection
