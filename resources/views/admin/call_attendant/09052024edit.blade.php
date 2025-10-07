@extends('layouts.admin')

@section('title', 'Add New Call Attendant')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Edit Call Attendant</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active"> Edit Call Attendant </li>
            </ol>
        </nav>
    </div>
    <!-- first row starts here -->
    @include('admin.common.alert')
    <div class="alert alert-success" id="successalert" role="alert" style="display:none">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <span id="msgdata"></span>
    </div>
    <div class="alert alert-danger" id="erroralert" role="alert" style="display:none">
        <strong>Error !</strong> {{ session('Error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <span id="msgdata"></span>
    </div>
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
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{route('call_attendant.update')}}" method="post">
                                            <input type="hidden" name="iCallAttendentId" id="iCallAttendentId" value="{{ $CallAttendent->iCallAttendentId }}">
                                            <input type="hidden" name="iUserId" id="iUserId" value="{{ $CallAttendent->iUserId }}">
                                            @csrf
                                            <div class="field_wrapper">
                                                <div class="accordion" id="accordionExample">
                                                    <div id="add-option" class="accordion-item">
                                                        <h2 class="accordion-header" id="heading0">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse0" aria-expanded="true" aria-controls="collapse0">
                                                                <span class="drag-icon pull-left">
                                                                    <i class="mas-arrow-move"></i>
                                                                </span>
                                                                {{ $CallAttendent->strFirstName }}  {{ $CallAttendent->strLastName }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapse0" class="accordion-collapse collapse show" aria-labelledby="heading0" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>First Name*</label>
                                                                                <input type="text" name="strFirstName" id="strFirstName" value="{{ $CallAttendent->strFirstName }}" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Last Name*</label>
                                                                                <input type="text" name="strLastName" id="strLastName" value="{{ $CallAttendent->strLastName }}" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Contact*</label>
                                                                                <input type="text" name="strContact" id="strContact" value="{{ $CallAttendent->strContact }}" class="form-control"  required="" onkeypress="return isNumber(event)">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Email ID*</label>
                                                                                <input type="email" name="strEmailId" id="strEmailId" value="{{ $CallAttendent->strEmailId }}" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Select Executive
                                                                                    Level</label>
                                                                                <select class="js-example-basic-single" name="iExecutiveLevel" id="iExecutiveLevel" style="width: 100%;" required>
                                                                                    <option label="Please Select" value="">-- Select
                                                                                        --</option>
                                                                                    <option value="1" @if($CallAttendent->iExecutiveLevel == 1) {{ 'selected' }} @endif>Level
                                                                                        1</option>
                                                                                    <option value="2" @if($CallAttendent->iExecutiveLevel == 2) {{ 'selected' }} @endif>Level
                                                                                        2</option>
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Select OEM
                                                                                    Company*</label>
                                                                                <select class="js-example-basic-single" name="iOEMCompany" id="iOEMCompany" style="width: 100%;" required>
                                                                                    <option label="Please Select" value="">-- Select
                                                                                        --</option>
                                                                                    @foreach($companyMasters as $companyMaster)
                                                                                    <option value="{{ $companyMaster->iCompanyId }}" @if($CallAttendent->iExecutiveLevel == $companyMaster->iCompanyId) {{ 'selected' }} @endif>{{ $companyMaster->strOEMCompanyName }}</option>
                                                                                    @endforeach
                                                                                    <!-- <option value="VI">
                                                                                            Attentive Fire
                                                                                        </option> -->
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <!-- <div class="col-md-4">
                                                                                <button type="button" class="btn delete-option pull-right remove_button" title="Delete Option">
                                                                                    <i class="mas-trash"></i>
                                                                                </button>
                                                                            </div> -->
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
                                                <div class="col-md-6 d-flex justify-content-end">
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
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43)
            return false;
        return true;
    }
</script>
@endsection
