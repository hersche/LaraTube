@extends('layouts.app')

@section('header')


@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="" id="content">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="theModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="theModal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="theModal-body" >
      </div>
      <div class="modal-footer" id="theModal-footer" >
      </div>
    </div>
  </div>
</div>
@endsection
