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

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered table-fluid">
 <tr>
   <th>No</th>
   <th>Title</th>
   <th>Description</th>
   <th>Source</th>
   <th>Type</th>
   <th>User</th>
   <th width="280px">Action</th>
 </tr>

 @foreach ($data as $key => $media)

  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $media->title }}</td>
    <td>{{ $media->description }}</td>
    <td>{{ $media->source }}</td>
    <td>{{ $media->type }}</td>
    <td>{{ $media->user()->name }}</td>
    <td>
      <a class="btn btn-info" href="{{ route('media.show',$media->title) }}"><ion-icon name="eye"></ion-icon></a>
      <a class="btn btn-info" href="{{ route('medias.editView',$media->title) }}"><ion-icon name="create"></ion-icon></a>
      <a class="btn btn-danger" href="{{ route('medias.delete',$media->title) }}"><ion-icon name="trash"></ion-icon></a>
    </td>

  </tr>

 @endforeach

</table>


{!! $data->render() !!}


@endsection
