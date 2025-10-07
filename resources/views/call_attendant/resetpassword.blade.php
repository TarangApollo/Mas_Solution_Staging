@extends('layouts.callAttendant')

@section('title', 'RESET YOUR PASSWORD')

@section('content')

    <!-- css for this page -->
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />

    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />

    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex flex-row-reverse">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-md-2 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <p class="m-0 pr-8">Reset Account Password</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alert Messages --}}
                @include('call_attendant.callattendantcommon.alert')

                <!-- first row starts here -->
                <div class="row d-flex justify-content-center my-5">
                    <div class="col-sm-10">
                        <div class="card">
                            <div class="card-body p-0">
                                <h4 class="card-title mt-0">Reset your Password</h4>
                                <form class="was-validated p-4 pb-3"
                                    action="{{ route('callattendantresetpassword.changepassword') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Current Password*</label>
                                                <input type="password" name="current_password"
                                                    class="form-control @error('current_password') is-invalid @enderror"
                                                    placeholder="Current Password" required />
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
                                                    placeholder="New Password" minlength="5" maxlength="20" required />
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
                                                    placeholder="Confirm Password" minlength="5" maxlength="20" required />
                                                @error('new_confirm_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div> <!-- /.col -->
                                    </div>
                                    <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3"
                                        value="Submit">
                                </form>
                            </div>
                        </div>
                        <!--card end-->
                    </div>
                </div><!-- end row -->

                <!--notify messages-->

                <!--END notify messages-->
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                            2022 Mas Solutions. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                                href="#"> Excellent Computers </a> </span>
                    </div>
                </div>
            </footer>
            <!-- partial -->

        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->

    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>

    <!--Plugin js for this page -->
    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>

    <!--form validation-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.validate.min.js') }}"></script>
@endsection
