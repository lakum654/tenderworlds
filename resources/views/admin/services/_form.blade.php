@extends('master')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{ $moduleName }}
      </h1>

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">{{ $moduleName }}</a></li>
        <li><a href="#" active>Edit</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Edit {{$moduleName}}</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">

         <form action="{{ route("$route.update", $service->id) }}" method="POST" enctype="multipart/form-data">
          @csrf()
          @method('PUT')

          <div class="form-group">
            <div class="row">

            <div class="col-md-6 col-sm-12">
              <div class="form-group">
                  <label for="name">Name: *</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Service Name" value="{{ old('name', $service->title) }}">
                  <span class="error"> {{ $errors->first('name') }}</span>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="status">Status: *</label><br>
                     <span class="py-5"><input type="radio" name="status" value="1" {{ $service->status == 1 ? 'checked' : '' }}> Active</span>
                     <span class="py-34"><input type="radio" name="status" value="0" {{ $service->status == 0 ? 'checked' : '' }}> Inactive</span>
                </div>
                <span class="error"> {{ $errors->first('status') }}</span>
              </div>

            <div class="col-6 col-sm-12">
                <div class="form-group">
                    <textarea id="ck-editor" class="form-control" style="height: 300px" name="content">{{ old('content', $service->content) }}</textarea>
                  </div>
                  <span class="error"> {{ $errors->first('content') }}</span>
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer text-center">
          <a href="{{ route("$route.index") }}" class="btn btn-sm btn-default">Cancel</a>
          <input type="submit" value="Update" class="btn btn-sm btn-info">
        </div>
      </form>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
