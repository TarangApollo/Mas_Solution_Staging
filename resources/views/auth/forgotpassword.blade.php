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
                        //dd(Session::get('error'));
                    ?>
                    

                    <form class="form-horizontal forgot-pw_box" id="register-form" method="post" action="{{ route('forgotpassword.forgotsubmit') }}">
                        @csrf
                        <h3 class="title">Forgot Password?</h3>
                        @if(session('sussess'))
                            <div class="alert alert-success" role="alert">
                                {{ $message }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger" role="alert"> {{ session('error') }}</div>
                        @endif
                        <div class="form-group">
                            <span class="input-icon"><i class="mas-envelope"></i></span>
                            <input class="form-control" type="email" name="email" id="email" placeholder="Email Address">
                        </div>
                        <button class="btn signin">Submit</button>
                        <span class="forgot-pass"><a href="{{ route('login') }}" >Back to Login?</a></span>
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
