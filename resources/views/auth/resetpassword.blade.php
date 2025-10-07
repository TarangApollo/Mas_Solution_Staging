@extends('auth.layouts.app')

@section('title', 'Login')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="row my-5">
        <div class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8 text-center">
            <img src="{{ asset('global/assets/images/logo-large.png') }}" alt="logo" height="80">
        </div>
    </div>
    <div class="row my-5">
        <div class="form-container border-0 d-flex justify-content-center">
            
                <form class="form-horizontal" method="post" action="{{ route('forgotpassword.resetpasswordsubmit') }}">
                        @csrf
                        <h3 class="title">Reset Password</h3>
                        @if($flag == 1)
                        @if(session('error'))
                            <div class="alert alert-success" role="alert">
                                Password reset Successfully.
                            </div>
                        @endif
                        <div class="alert alert-danger" role="alert" style="display: none">
                        </div>
                        <input class="form-control" type="hidden" name="id" id="id" value="{{ $users->id }}" >
                        <div class="form-group">
                            <span class="input-icon"><i class="mas-envelope"></i></span>
                            <input class="form-control" type="email" name="email" id="email" value="{{ $users->email }}" placeholder="Email Address" readonly>
                        </div>
                        <div class="form-group">
                            <span class="input-icon"><i class="mas-lock"></i></span>
                            <input class="form-control" type="password" name="password" id="password" placeholder="New Password" required="">
                        </div>
                        <div class="form-group">
                            <span class="input-icon"><i class="mas-lock"></i></span>
                            <input class="form-control" type="password" name="cpassword" id="cpassword" placeholder="Confirm New Password" required="">
                        </div>
                    
                        <button class="btn signin" type="submit" onclick="return validateData();">
                            <a class="text-white"
                                href="#">Login</a>
                        </button>
                    @else    
                        <div class="alert alert-danger" role="alert">
                            Sorry, your reset password link is no longer valid. you can request another one.
                        </div>   
                    @endif
                </form>
            
        </div><!-- /. form container -->
    </div><!-- /. row -->
</div>
<script>
    function validateData(){
        var password = $("#password").val();
        var cpassword = $("#cpassword").val();
        if(password === cpassword){
            return true;
        } else {
            $(".alert").css('display','block');
            $(".alert").html('Password and Confirm password not match.!');
            return false;
        }
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