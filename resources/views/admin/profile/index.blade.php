@extends('master')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $moduleName }}
            {{-- <small>it all starts here</small> --}}
        </h1>

        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">{{ $moduleName }}</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Update Profile</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                        title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <!-- Profile Update Form -->
                <form action="{{ route($route . '.update', auth()->user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Name: *</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Your Name" value="{{ old('name', auth()->user()->name) }}">
                                    <span class="error"> {{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="email">Email: *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Your Email" value="{{ old('email', auth()->user()->email) }}">
                                    <span class="error"> {{ $errors->first('email') }}</span>
                                </div>
                            </div>

                            <div class="col-12 col-sm-12">
                                <div class="form-group">
                                    <label for="profile">Profile Picture:</label>
                                    <input type="file" class="form-control" id="profile" name="profile">
                                    <span class="error"> {{ $errors->first('profile') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <a href="{{ route($route . '.index') }}" class="btn btn-sm btn-default">Cancel</a>
                        <input type="submit" value="Update Profile" class="btn btn-sm btn-info">
                    </div>
                </form>

                <hr>

                <!-- Password Update Form -->
                <form action="{{ route($route . '.updatePassword', auth()->user()->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="current_password">Current Password: *</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password"
                                        placeholder="Enter Current Password">
                                    <span class="error"> {{ $errors->first('current_password') }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="new_password">New Password: *</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                        placeholder="Enter New Password">
                                    <span class="error"> {{ $errors->first('new_password') }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="new_password_confirmation">Confirm New Password: *</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation"
                                        placeholder="Confirm New Password">
                                    <span class="error"> {{ $errors->first('new_password_confirmation') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <a href="{{ route($route . '.index') }}" class="btn btn-sm btn-default">Cancel</a>
                        <input type="submit" value="Update Password" class="btn btn-sm btn-warning">
                    </div>
                </form>
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection
