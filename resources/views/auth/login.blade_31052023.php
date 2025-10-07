@extends('auth.layouts.app')

@section('title', 'Login')

@section('content')
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <span class="signup">If you are facing trouble to Login, Please contact admin@masolution.co.uk
                            {{ $MessageMaster->strMessage ?? '' }}</span>
                    </div>
                    <?php 
                        //dd(Session::get('errors'));
                    ?>
                    
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        @csrf
                        <h3 class="title">Member Login</h3>
                        @if(session('success'))
                            <div class="alert alert-success" role="alert"> {{ session('success') }}</div>
                        @endif
                        @error('email')
                            <div class="alert alert-danger" role="alert">
                            {{ $message }}
                            </div>
                        @enderror
                        @error('password')
                            <div class="alert alert-danger" role="alert">
                                <p>{{ $message }}</p>
                            </div>
                        @enderror
                        @if ($errors->has('captcha'))
                            <div class="alert alert-danger" role="alert">
                                <p>{{ $errors->first('captcha') }}</p>
                            </div>
                        @endif
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
                            <div class="col-lg-5 col-md-12 captcha">
                                <span>{!! captcha_img() !!}</span>
                                <h5><a href="#" onclick="reloadCaptchaData();">Reload Image</a></h5>
                            </div>
                            <div class="col-lg-7 col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="captcha"/>
                                </div>
                            </div> <!-- /.col -->
                        </div>

                        

                        <button class="btn signin" type="submit">
                            Login
                        </button>

                        <span class="forgot-pass"><a href="{{ route('forgotpassword.index') }}" id="forgot-password">Forgot Password?</a></span>
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
    <script type="text/javascript">
        function reloadCaptchaData() {
            $.ajax({
                type: 'GET',
                url: 'reload-captcha',
                success: function (data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        }
    </script>
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
