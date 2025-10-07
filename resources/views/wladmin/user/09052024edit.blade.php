@extends('layouts.wladmin')

@section('title', 'Add New Call Attendant')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Edit Call Attendant</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item"><a href="{{route('user.index')}}">User List</a></li>
                <li class="breadcrumb-item active"> Edit User </li>
            </ol>
        </nav>
    </div>

    <!-- first row starts here -->
    @include('wladmin.wlcommon.alert')
    <div class="row">
        <div class="col-xl-12 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="accordion-content clearfix">
                        <div class="col-lg-3 col-md-4">
                            <div class="accordion-box">
                                <div class="panel-group" id="RoleTabs">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a>
                                                    User Information
                                                </a>
                                            </h4>
                                        </div>

                                        <div id="role_information" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <ul class="accordion-tab nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#account" data-toggle="tab">Account</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8">
                            <div class="accordion-box-content">
                                <div class="tab-content clearfix">
                                    <div class="tab-pane fade in active" id="account">
                                        <h3 class="tab-content-title">Account</h3>
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{route('user.update')}}" method="post">
                                            <input type="hidden" name="iCompanyUserId" id="iCompanyUserId" value="{{ $WlUser->iCompanyUserId }}">
                                            <input type="hidden" name="iUserId" id="iUserId" value="{{ $WlUser->iUserId }}">
                                            <input type="hidden" name="save" id="save" value="0">
                                            @csrf
                                            <div class="field_wrapper">
                                                <div class="accordion" id="accordionExample">
                                                    <div id="add-option" class="accordion-item">
                                                        <h2 class="accordion-header" id="heading0">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse0" aria-expanded="true" aria-controls="collapse0">
                                                                <span class="drag-icon pull-left">
                                                                    <i class="mas-arrow-move"></i>
                                                                </span>
                                                                {{ $WlUser->strFirstName }} {{ $WlUser->strLastName }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapse0" class="accordion-collapse collapse show" aria-labelledby="heading0" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>First Name*</label>
                                                                                <input type="text" name="strFirstName" id="strFirstName" value="{{ $WlUser->strFirstName }}" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Last Name*</label>
                                                                                <input type="text" name="strLastName" id="strLastName" value="{{ $WlUser->strLastName }}" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Contact*</label>
                                                                                <input type="text" name="strContact" id="strContact" value="{{ $WlUser->strContact }}" class="form-control" required="" onkeypress="return isNumber(event)">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Email ID*</label>
                                                                                <input type="email" name="strEmail" id="strEmail" value="{{ $WlUser->strEmail }}" class="form-control" required="">
                                                                                <span id="erremail" class="text-danger"></span>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Select Role </label>
                                                                                <select class="js-example-basic-single" name="iRoleId" id="iRoleId" style="width: 100%;" required>
                                                                                    <option label="Please Select" value="">-- Select
                                                                                        --</option>
                                                                                    @foreach ($Roles as $role)
                                                                                    <option value="{{$role->id}}" @if($WlUser->iRoleId == $role->id) {{ 'selected' }} @endif>{{$role->name}}</option>

                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->

                                                                    </div>
                                                                </div>
                                                                <!--new option-->
                                                            </div>
                                                            <!--accordion body-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-success text-uppercase mt-4">
                                                        Save & Exit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!--Advance information form start-->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--card body end-->
            </div>
            <!--card end-->
        </div>
    </div>
    <!--row-->

</div>
<!--END notify messages-->


@endsection

@section('script')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function isNumber(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)  && charCode != 43)
            return false;
        return true;
    }
    $("#strEmail").blur(function() {
        var email = $(this).val();
        var userId=$('#iCompanyUserId').val();
        $.ajax({
            type: 'POST',
            url: "{{route('user.emailvalidate')}}",
            data: {
                email: email,ID:userId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response == 0) {
                    $("#erremail").html("Email is already in used.");
                    $('#strEmail').val('');
                    $('#submit').prop('disabled', true);
                    $('#savesubmit').prop('disabled', true);
                    return false;
                } else {
                    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
                    if (!pattern.test(email)) {
                        $("#erremail").html('not a valid e-mail address');
                        $('#strEmail').val('');
                        $('#submit').prop('disabled', true);
                        $('#savesubmit').prop('disabled', true);
                        return false;
                    } else {
                        $("#erremail").html("");
                        $('#submit').prop('disabled', false);
                        $('#savesubmit').prop('disabled', false);
                        return true;
                    }
                }
            }
        });
    });
</script>
@endsection
