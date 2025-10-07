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
        <div class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8">
            <div class="form-container">

                <div class="form-icon">
                    <i class="mas-user"></i>
                    {{-- <span class="signup">If you are facing trouble to Login, Please contact admin@masolution.co.uk
                            {{ $MessageMaster->strMessage ?? '' }}</span> --}}
                </div>

                <form id="companyLoginForm" class="form-horizontal mt-4" method="POST"
                    action="{{ route('User.switchWLsubmit') }}">
                    @csrf
                    <h3 class="title">Company List</h3>
                    <div class="form-group">
                        <select class="form-control" name="iOEMCompany" id="iOEMCompany">
                            <option value="">Select Company</option>
                            @foreach ($CompanyMaster as $item)
                            <option value="{{ $item->iCompanyId }}">
                                {{ $item->strOEMCompanyName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn signin" type="submit">
                        Select
                    </button>
                </form>

                {{-- <div class="form-group">
                            <span class="input-icon"><i class="mas-lock"></i></span>
                            <input class="form-control" name="password" type="password" placeholder="Password">
                        </div>  --}}



            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#iOEMCompany').on('change', function() {
            var selectedRole = $(this).find(':selected').data('role');
            $('#companyLoginForm').append('<input type="hidden" name="iRoleId" value="' + selectedRole +
                '">');
        });
    });
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