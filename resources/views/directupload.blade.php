@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
{!! Form::open(array('url' => '/directUpload','files'=>'true'))  !!}
<div class="col-xs-12 col-sm-12 col-md-12">

    <div class="form-group">

        <strong>Media-title:</strong>

        {!! Form::text('title', null, array('placeholder' => 'Media-title','class' => 'form-control')) !!}

    </div>

</div>
<p>Select the file to upload.</p>
{!! Form::file('directMedia')  !!}
{!! Form::submit('Upload File')  !!}
{!! Form::close()  !!}

        </div>
    </div>
</div>
@endsection
