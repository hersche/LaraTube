@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Medias Management</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('users.create') }}"> Create New Media</a>
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
   <th>Name</th>
   <th>Email</th>
   <th>Roles</th>
   <th width="280px">Action</th>
 </tr>

 @foreach ($data as $key => $media)

  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $media->title }}</td>
    <td>{{ $media->source }}</td>
    <td>{{ $media->users_id }}</td>
    <td>
       <a class="btn btn-info" href="{{ route('media.show',$media->title) }}">Show</a>
    </td>

  </tr>

 @endforeach

</table>


{!! $data->render() !!}


@endsection
