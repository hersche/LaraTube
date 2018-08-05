@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-fluid">
                     <tr>
                       <th>Title</th>
                       <th>Description</th>
                       <th>Source</th>
                       <th>User</th>
                       <th width="280px">Action</th>
                     </tr>

                     @foreach (Auth::User()->medias() as $key => $media)

                      <tr>

                        <td>{{ $media->title }}</td>
                        <td>{{ $media->description }}</td>
                        <td>{{ $media->source }}</td>
                        <td>{{ $media->user()->name }}</td>
                        <td>
                           <a class="btn btn-info" href="{{ route('media.show',$media->title) }}"><ion-icon name="eye"></ion-icon></a>
                           <a class="btn btn-info" href="{{ route('medias.editView',$media->title) }}"><ion-icon name="create"></ion-icon></a>
                           <a class="btn btn-danger" href="{{ route('medias.delete',$media->title) }}"><ion-icon name="trash"></ion-icon></a>
                        </td>

                      </tr>

                     @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
