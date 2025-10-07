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
                                            <form class="was-validated pb-3" name="frmparameter" id="frmparameter"
                                                action="{{ route('call_attendant.update') }}" method="post">
                                                <input type="hidden" name="iCallAttendentId" id="iCallAttendentId"
                                                    value="{{ $CallAttendent->iCallAttendentId }}">
                                                <input type="hidden" name="iUserId" id="iUserId"
                                                    value="{{ $CallAttendent->iUserId }}">
                                                @csrf
                                                <div class="field_wrapper">
                                                    <div class="accordion" id="accordionExample">
                                                        <div id="add-option" class="accordion-item">
                                                            <h2 class="accordion-header" id="heading0">
                                                                <button class="accordion-button" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#collapse0"
                                                                    aria-expanded="true" aria-controls="collapse0">
                                                                    <span class="drag-icon pull-left">
                                                                        <i class="mas-arrow-move"></i>
                                                                    </span>
                                                                    {{ $CallAttendent->strFirstName }}
                                                                    {{ $CallAttendent->strLastName }}
                                                                </button>
                                                            </h2>
                                                            <div id="collapse0" class="accordion-collapse collapse show"
                                                                aria-labelledby="heading0"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="new-option clearfix">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>First Name*</label>
                                                                                    <input type="text"
                                                                                        name="strFirstName"
                                                                                        id="strFirstName"
                                                                                        value="{{ $CallAttendent->strFirstName }}"
                                                                                        class="form-control" required="">
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Last Name*</label>
                                                                                    <input type="text" name="strLastName"
                                                                                        id="strLastName"
                                                                                        value="{{ $CallAttendent->strLastName }}"
                                                                                        class="form-control"
                                                                                        required="">
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Contact*</label>
                                                                                    <input type="text"
                                                                                        name="strContact" id="strContact"
                                                                                        value="{{ $CallAttendent->strContact }}"
                                                                                        class="form-control"
                                                                                        required=""
                                                                                        onkeypress="return isNumber(event)">
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Email ID*</label>
                                                                                    <input type="email"
                                                                                        name="strEmailId" id="strEmailId"
                                                                                        value="{{ $CallAttendent->strEmailId }}"
                                                                                        class="form-control"
                                                                                        required="">
                                                                                        <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->

                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <!--<br>-->
                                                                                    {{--  <input class="" type="checkbox"
                                                                                        onclick="companyroleshow();"
                                                                                        name="isCanSwitchProfile"
                                                                                        id="isCanSwitchProfile"
                                                                                        {{ $User->isCanSwitchProfile == 1 ? 'checked' : null }}>
                                                                                    Switch User  --}}
 <label>Can user switch?</label>
                                                                                    <input type="checkbox"
                                                                                        name="isCanSwitchProfile"
                                                                                        id="isCanSwitchProfile"
                                                                                        value="Yes"
                                                                                        onchange="companyroleshow();"
                                                                                        data-toggle="toggle"
                                                                                        data-width="90"
                                                                                        {{ $User->isCanSwitchProfile == 1 ? 'checked' : null }}
                                                                                        data-on="Yes" data-off="No">

                                                                                </div>
                                                                            </div>


                                                                        </div>
                                                                    </div>
                                                                    <div class="row" id="CompanyRoleDiv">
                                                                        @foreach ($companyMasters as $companyMaster)
                                                                            <div class="col-md-6">

                                                                                <input type="hidden"
                                                                                    name="iOEMCompanyId[]"
                                                                                    value="{{ $companyMaster->iCompanyId }}">
                                                                                <div class="form-group">
                                                                                    <input type="text"
                                                                                        name="iOEMCompany[]"
                                                                                        id="iOEMCompany"
                                                                                        class="form-control"
                                                                                        value="{{ $companyMaster->strOEMCompanyName }}"
                                                                                        readonly>
                                                                                    {{--  <p>
                                                                                            {{ $companyMaster->strOEMCompanyName }}
                                                                                        </p>  --}}
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                            $Mapping = App\Models\Role::where(['iStatus' => 1, 'isDelete' => 0])
                                                                                ->where(['iCompanyId' => $companyMaster->iCompanyId])
                                                                                ->get();
                                                                            
                                                                            ?>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <select class="js-example-basic-single"
                                                                                        name="iOEMCompanyRoleAddMore[]"
                                                                                        id="iOEMCompanyRoleID_{{ $companyMaster->iCompanyId }}"
                                                                                        style="width: 100%;">
                                                                                        <option label="Please Select"
                                                                                            value="">
                                                                                            -- Select
                                                                                            --</option>
                                                                                        @foreach ($Mapping as $mapping)
                                                                                            <?php
                                                                                            $data = DB::table('multiplecompanyrole')
                                                                                                ->where(['iStatus' => 1, 'isDelete' => 0, 'iOEMCompany' => $companyMaster->iCompanyId, 'userid' => $id, 'iRoleId' => $mapping->id])
                                                                                                ->first();
                                                                                            ?>
                                                                                            @if (!empty($data))
                                                                                                <option
                                                                                                    value="{{ $mapping->id }}"
                                                                                                    selected>
                                                                                                    {{ $mapping->name }}
                                                                                                </option>
                                                                                            @else
                                                                                                <option
                                                                                                    value="{{ $mapping->id }}">
                                                                                                    {{ $mapping->name }}
                                                                                                </option>
                                                                                            @endif
                                                                                        @endforeach

                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

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
                                                        <button type="submit"
                                                            class="btn btn-success text-uppercase mt-4">
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
    <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
    <script>
        function isNumber(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43)
                return false;
            return true;
        }
    </script>

    <script>
    $("#strEmailId").blur(function() {
            var email = $(this).val();
            var iUserId = $("#iUserId").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('call_attendant.emailvalidate') }}",
                data: {
                    email: email,
                    iUserId: iUserId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response == 0) {
                        $("#erremail").html("Email is already in used.");
                        $('#strEmailId').val('');
                        $('#submit').prop('disabled', true);
                        $('#savesubmit').prop('disabled', true);
                        return false;
                    } else {
                        $("#erremail").html('');
                        $('#submit').prop('disabled', false);
                        $('#savesubmit').prop('disabled', false);
                        //     var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
                        //     if (!pattern.test(email)) {
                        //         $("#erremail").html('not a valid e-mail address');
                        //         $('#strEmailId').val('');
                        //         $('#submit').prop('disabled', true);
                        //         $('#savesubmit').prop('disabled', true);
                        //         return false;
                        //     } else {
                        //         $("#erremail").html("");
                        //         $('#strEmailId').val('');
                        //         $('#submit').prop('disabled', false);
                        //         $('#savesubmit').prop('disabled', false);
                        //         return true;
                        //     }
                    }
                }
            });
        });
        $(document).ready(function() {
            function companyroleshow() {
                {{--  var company = $('#isCanSwitchProfile').prop('checked');
                if (company) {
                    $('#CompanyRoleDiv').show();
                } else {
                    $('#CompanyRoleDiv').hide();
                }  --}}

                var checkbox = document.getElementById('isCanSwitchProfile');
                if (checkbox.checked) {
                    $('#CompanyRoleDiv').show();
                } else {
                    $('#CompanyRoleDiv').hide();
                }
            }

            // Call the function initially to ensure proper visibility
            companyroleshow();

            // Attach an event listener to the checkbox
            $('#isCanSwitchProfile').change(function() {
                companyroleshow();
            });
        });
    </script>
@endsection
