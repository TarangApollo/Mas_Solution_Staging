@extends('layouts.wladmin')

@section('title', 'Reset Account Password')

@section('content')
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Reset Account Password</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active"> Reset Password </li>
                </ol>
            </nav>
        </div>

        {{-- Alert Messages --}}
        @include('wladmin.wlcommon.alert')

        <!--/. page header ends-->
        <!-- first row starts here -->
        <div class="row">
            <div class="col-xl-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">Reset your Password</h4>
                        <form class="was-validated p-4 pb-3" action="{{ route('wlresetpassword.changepassword') }}"
                            method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Current Password*</label>
                                        <input type="password" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            required />
                                        @error('current_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>New Password*</label>
                                        <input type="password" name="new_password"
                                            class="form-control @error('new_password') is-invalid @enderror"
                                            minlength="5" maxlength="20" required />
                                        @error('new_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Confirm Password*</label>
                                        <input type="password" name="new_confirm_password"
                                            class="form-control  @error('new_confirm_password') is-invalid @enderror"
                                            minlength="5" maxlength="20" required />
                                        @error('new_confirm_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> <!-- /.col -->
                            </div>
                            <input type="submit" class="btn btn-success text-uppercase mt-3 mr-2" value="Save">
                            <a class="btn btn-default text-uppercase mt-3"
                                href="{{ route('wlresetpassword.index') }}">Clear</a>
                            {{-- <input type="button" class="btn btn-default text-uppercase mt-3" value="Clear"> --}}
                        </form>
                    </div>
                    <!--card body end-->
                </div>
                <!--card end-->
            </div>
        </div>
        <!--row-->
    </div>
@endsection
