<table class="table table-bordered table-fluid">
 <tr>
   <th>Title</th>
   <th>Description</th>
   <th>Source</th>
   <th>User</th>
   <th width="280px">Action</th>
 </tr>

 @foreach (Auth::User()->medias as $key => $media)

  <tr>
    <td><img src="{{ url($media->poster_source) }}" /></td>
    <td>{{ $media->title }}</td>
    <td>{{ $media->description }}</td>

    <td>{{ $media->user()->name }}</td>
    <td>
       <a class="btn btn-info" href="{{ route('media.show',$media->title) }}"><ion-icon name="eye"></ion-icon></a>
       <a class="btn btn-info" href="{{ route('medias.editView',$media->title) }}"><ion-icon name="create"></ion-icon></a>
       <a class="btn btn-danger" href="{{ route('medias.delete',$media->title) }}"><ion-icon name="trash"></ion-icon></a>
    </td>

  </tr>

 @endforeach

</table>
