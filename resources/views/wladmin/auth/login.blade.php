@extends('auth.layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8 text-center">
                <img src="global/assets/images/logo-large.png" alt="logo" height="80">
            </div>
        </div>
        <div class="row my-5">
            <div class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8">
                <div class="form-container">
                    <div class="form-icon">
                        <i class="mas-user"></i>
                        <span class="signup">If you are facing trouble to Login, Please contact
                            admin@masolution.co.uk</span>
                    </div>

                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        @csrf
                        <h3 class="title">Member Login</h3>
                        <div class="form-group">
                            <span class="input-icon"><i class="mas-envelope"></i></span>
                            <input class="form-control" name="email" type="email" placeholder="Email Address">
                        </div>
                        <div class="form-group">
                            <span class="input-icon"><i class="mas-lock"></i></span>
                            <input class="form-control" name="password" type="password" placeholder="Password">
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input"> Remember me
                                <i class="input-helper"></i>
                            </label>
                        </div>
                        <!--captcha box-->
                        <div class="row my-3">
                            <h5>Enter below code to the box.</h5>
                            <div class="col-lg-5 col-md-12">
                                <img src="global/assets/images/captcha.jpg" alt="captcha image" />
                                <h5><a href="#">Reload Image</a></h5>
                            </div>
                            <div class="col-lg-7 col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" />
                                </div>
                            </div> <!-- /.col -->
                        </div>

                        <button class="btn signin" type="submit">
                            Login
                        </button>

                        <span class="forgot-pass"><a href="#" id="forgot-password">Forgot Password?</a></span>
                    </form>

                    <form class="form-horizontal" id="register-form" style="display: none;">
                        <h3 class="title">Forgot Password?</h3>
                        <div class="alert alert-success" role="alert">
                            Password sent Successfully.
                        </div>
                        <div class="form-group">
                            <span class="input-icon"><i class="mas-envelope"></i></span>
                            <input class="form-control" type="email" placeholder="Email Address">
                        </div>
                        <button class="btn signin">Submit</button>
                        <span class="forgot-pass"><a href="#" id="login-form-link">Back to Login?</a></span>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!--container end-->
    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/custom.js') }}"></script>

    <!-- Plugin js for this page -->
    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>

    <!--form validation-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.validate.min.js') }}"></script>
@endsection
