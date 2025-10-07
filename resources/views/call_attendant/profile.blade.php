@extends('layouts.callAttendant')

@section('title', 'Profile')

@section('content')
    <!-- css for this page -->
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />

    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex justify-content-center">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-sm-5 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <h3 class="m-0">User Profile</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alert Messages --}}
                

                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <!--<div class="card">
                            <div class="card-body p-0">-->
                        <div class="wizard-container">
                            @include('call_attendant.callattendantcommon.alert')
                            <div class="card wizard-card" data-color="orange" id="wizardProfile">
                                <h4 class="card-title mt-0">Edit your Profile</h4>
                                <form class="was-validated p-4 pb-3" action="{{ route('callattendantprofile.update') }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="picture-container">
                                                <div class="picture">
                                                    <?php $photo = auth()->user()->photo;
                                                if($photo != ""){ ?>
                                                    <img src="{{ asset('/UserProfilePhoto/' . $photo) }}"
                                                        class="picture-src" id="wizardPicturePreview" title=""
                                                        alt="Profile Pic">
                                                    <?php }else{ ?>
                                                    <img src="{{ asset('/images/noimage.jpg') }}" class="picture-src h-100"
                                                        id="wizardPicturePreview" title="" alt="Profile Pic">
                                                    <?php } ?>
                                                    <input type="file" name="photo" id="wizard-picture" class="">
                                                    <input type="hidden" name="hiddenPhoto" class="form-control"
                                                        id="hiddenPhoto" value="{{ $photo }}">

                                                </div>
                                                <h6 class="">Choose Picture</h6>
                                            </div>
                                        </div>
                                        <!--/. col 4-->
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control"name="first_name"
                                                            value="{{ old('first_name') ? old('first_name') : auth()->user()->full_name }}"
                                                            required />
                                                    </div>
                                                </div> <!-- /.col -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email ID</label>
                                                        <input type="email" class="form-control"
                                                            onkeyup="checkemail(this)" name="email" id="email"
                                                            value="{{ old('email') ? old('email') : auth()->user()->email }}"
                                                            required />
                                                        <b><span style="color:red;" id="mailerror"></span></b>
                                                    </div>
                                                </div> <!-- /.col -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Contact Number</label>
                                                        <input type="text" class="form-control" name="mobile_number"
                                                            id="MobileValidate"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                            value="{{ old('mobile_number') ? old('mobile_number') : auth()->user()->mobile_number }}"
                                                            required />
                                                    </div>
                                                </div> <!-- /.col -->
                                            </div>
                                            <!--/. row-->
                                        </div>
                                        <!--/. col 8-->
                                    </div>
                                    <input type="Submit" onclick="return validateData();"
                                        class="btn btn-fill btn-success text-uppercase mt-3" value="Submit">
                                </form>
                            </div>
                        </div>
                        <!--card end-->
                    </div>
                </div><!-- end row -->

            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                            2022 Mas Solutions. All rights reserved.</span>
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
    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/custom.js') }}"></script>

    <!--Plugin js for this page -->
    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>

    <script>
        $("#MobileValidate").on('blur', function() {
            MobileValidate();
        });

        function MobileValidate() {
            var mobNum = $('#MobileValidate').val();
            var filter = /^\d*(?:\.\d{1,2})?$/;

            if (filter.test(mobNum)) {
               return true;
            } else {
                alert('Not a valid number');
                return false;
            }
        }

        function checkemail(data) {
            console.log(data.value);
            let filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (filter.test(data.value)) {
                console.log("inside if")
                document.getElementById("mailerror").innerHTML = "";
            } else {
                console.log("inside eles");
                document.getElementById("mailerror").innerHTML = "Invalid Email Address"
            }
        }
    </script>

    <script>
        function validateData() {
            if (MobileValidate()) {
                return true;
            } else {
                return false;
            }
        }
    </script>
@endsection
