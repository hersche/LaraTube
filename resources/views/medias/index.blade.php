@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Medias Management</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('medias.add') }}"> Create New Media</a>
        </div>
    </div>
</div>

@include('include.videoTable', [])


{!! $data->render() !!}


@endsection
