@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Your profile ({{ Auth::user()->name }})</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('medias.add') }}"> Create New Media</a>
        </div>
    </div>
</div>


@endsection
