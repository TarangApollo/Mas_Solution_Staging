@extends('layouts.callAttendant')

@section('title', 'Add New Complaint')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- css for this page -->
    <!--step wizard-->
    <link href="{{ asset('global/assets/vendors/wizard/css/material-bootstrap-wizard.css') }}" rel="stylesheet" />
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <!--bootstrap table-->
    <link href="{{ asset('global/assets/vendors/bootstrap-table/css/fresh-bootstrap-table.css') }}" rel="stylesheet" />

    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">

                @include('call_attendant.callattendantcommon.alert')
                <!-- first row starts here -->
                <div class="content-wrapper" id="hero">

                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 d-flex flex-column justify-content-center">
                                <h1 class="text-orange mt-5">Welcome {{auth()->user()->first_name ." " . auth()->user()->last_name}}</h1>
                                <h3 class="text-black-50">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</h3>
                                <a class="col-3 btn btn-sm btn-fill btn-success text-uppercase mt-3 p-3" href="{{ route('callattendantAdmin.dashboard') }}">Add Complaint</a>
                            </div>
                            <div class="col-md-6 hero-img stage">
                                <img src="../global/assets/images/hero-img.png" class="img-fluid box bounce-1" alt="">
                            </div>





                        </div>
                    </div>


                    <!-- <h3 class="text-orange text-center mt-5">Welcome {User name}</h3> -->

                  </div>

                <!--table start-->

                <!--table end-->
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                            2022
                            Mas Solutions. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                                href="https://www.excellentcomputers.co.in/" target="_blank"> Excellent Computers </a> </span>
                    </div>
                </div>
            </footer>
            <!-- partial -->

        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    <!-- back to top button -->
      <a id="button"></a>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>

    <!-- Plugin js for this page -->
    <!--step wizard-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/material-bootstrap-wizard.js') }}"></script>

    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>

    <!--form validation-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.validate.min.js') }}"></script>

   <!--table plugin-->
    <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">


@endsection
