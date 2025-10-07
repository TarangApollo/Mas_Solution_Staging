@extends('layouts.admin')

@section('title', 'Add New Call Attendant')

@section('content')

    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Add New Call Attendant</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active"> Add New Call Attendant </li>
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
                                                action="{{ route('call_attendant.store') }}" method="post">
                                                <input type="hidden" name="save" id="save" value="0">
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
                                                                    New Option
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
                                                                                        class="form-control" required="">
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Last Name*</label>
                                                                                    <input type="text" name="strLastName"
                                                                                        id="strLastName"
                                                                                        class="form-control" required="">
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Contact*</label>
                                                                                    <input type="text" name="strContact"
                                                                                        id="strContact"
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
                                                                                        class="form-control"
                                                                                        required="">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Password*</label>
                                                                                    <input type="password"
                                                                                        name="strPassword"
                                                                                        id="strPassword"
                                                                                        class="form-control"
                                                                                        required="">
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Confirm
                                                                                        Password*</label>
                                                                                    <input type="password"
                                                                                        name="strConfirmPassword"
                                                                                        id="strConfirmPassword"
                                                                                        class="form-control"
                                                                                        required="">
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <br>
                                                                                    <input type="checkbox"
                                                                                        onclick="companyroleshow();"
                                                                                        name="isCanSwitchProfile"
                                                                                        id="isCanSwitchProfile"> Switch
                                                                                    User
                                                                                </div>
                                                                            </div>
                                                                        </div>



                                                                        <div class="row" id="CompanyRoleDiv"
                                                                            style="display: none;">
                                                                            @foreach ($companyMasters as $companyMaster)
                                                                                <div class="col-md-6">

                                                                                    <input type="hidden"
                                                                                        name="iOEMCompanyId[]"
                                                                                        value="{{ $companyMaster->iCompanyId }}">
                                                                                    <div class="form-group">
                                                                                        <input type="text"
                                                                                            name="iOEMCompany[]"
                                                                                            class="form-control"
                                                                                            id="iOEMCompany"
                                                                                            value="{{ $companyMaster->strOEMCompanyName }}"
                                                                                            readonly>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                                $Mapping = App\Models\Role::where(['iStatus' => 1, 'isDelete' => 0])
                                                                                    ->where(['iCompanyId' => $companyMaster->iCompanyId])
                                                                                    ->get();
                                                                                ?>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <select
                                                                                            class="js-example-basic-single"
                                                                                            name="iOEMCompanyRoleAddMore[]"
                                                                                            id="iOEMCompanyRoleID_{{ $companyMaster->iCompanyId }}"
                                                                                            style="width: 100%;">
                                                                                            <option label="Please Select"
                                                                                                value="">
                                                                                                -- Select
                                                                                                --</option>
                                                                                            @foreach ($Mapping as $mapping)
                                                                                                <option
                                                                                                    value="{{ $mapping->id }}">
                                                                                                    {{ $mapping->name }}
                                                                                                </option>
                                                                                            @endforeach

                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach

                                                                        </div>


                                                                    </div>
                                                                    <!--/.col-md-12 issue type list-->
                                                                </div>


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

                                    <div class="col-md-6">
                                        {{-- <button type="button"
                                                            class="btn btn-default text-uppercase mt-4 add_button"
                                                            id="add-new-option">
                                                            Add New User
                                                        </button> --}}
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <button type="button" class="btn btn-success text-uppercase mt-4 mr-2"
                                            name="submit" id="submit">
                                            Save
                                        </button>
                                        <button type="submit" class="btn btn-success text-uppercase mt-4"
                                            id="savesubmit">
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

    {{-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}
    <script>
        function isNumber(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43)
                return false;
            return true;
        }


        $('#submit').on("click", function() {
            $('#save').val('1');
            var isValid = validateForm();
            $("#submit").prepend('<i class="fa fa-spinner fa-spin"></i>');
            $("#submit").attr("disabled", 'disabled');
            $("#savesubmit").attr("disabled", 'disabled');
            $("#add-new-option").attr("disabled", 'disabled');
            if (isValid == true) {
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: "{{ route('call_attendant.store') }}",
                    data: $('#frmparameter').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#loading').css("display", "none");

                        if (response > 0) {
                            $('#loading').css("display", "none");
                            $("#successalert").show();
                            $("#msgdata").html(
                                "<strong>Success !</strong> Call Attendant Created Successfully.");
                            $('#save').val('0');
                            window.location.href = "";
                            return true;
                        } else {
                            $('#loading').css("display", "none");
                            $("#erroralert").show();
                            $("#msgdata").html("<strong>Error !</strong> Somthing want wrong.");
                            return false;
                        }
                    }
                });
            }
        });

        function validateForm() {
            //$("#EmailId").blur();
            let strFirstName = document.forms["frmparameter"]["strFirstName"].value;
            let strLastName = document.forms["frmparameter"]["strLastName"].value;
            let strContact = document.forms["frmparameter"]["strContact"].value;
            let strEmailId = document.forms["frmparameter"]["strEmailId"].value;
            let strPassword = document.forms["frmparameter"]["strPassword"].value;
            let iExecutiveLevel = document.forms["frmparameter"]["iExecutiveLevel"].value;
            let iOEMCompany = document.forms["frmparameter"]["iOEMCompany"].value;
            let strConfirmPassword = document.forms["frmparameter"]["strConfirmPassword"].value;
            var isValid = true;

            //var strGSTNo = document.getElementById("strGSTNo").value;

            // if (companyName == "") {
            //     $("#errstrOEMCompanyName").html("Please enter company name");
            //     isValid = false;
            // } else {
            //     $("#errstrOEMCompanyName").html("");
            // }
            // if (companyID == "") {
            //     $("#errstrOEMCompanyId").html("Please enter companyID");
            //     isValid = false;
            // } else {
            //     $("#errstrOEMCompanyId").html("");
            // }
            // if (contactPerson == "") {
            //     $("#errcontactPerson").html("Please enter contact Person");
            //     isValid = false;
            // } else {
            //     $("#errcontactPerson").html("");
            // }
            // if (email == "") {
            //     $("#erremail").html("Please enter email");
            //     isValid = false;
            // } else {
            //     $("#erremail").html("");
            // }
            // if (mobile_number == "") {
            //     $("#errmobile_number").html("Please enter mobile number");
            //     isValid = false;
            // } else if (mobile_number.length != 10) {
            //     $("#errmobile_number").html("InvalidQ mobile number");
            //     isValid = false;
            // } else {
            //     $("#errmobile_number").html("");
            // }
            // if (address1 == "") {
            //     $("#erraddress1").html("Please enter address1");
            //     isValid = false;
            // } else {
            //     $("#erraddress1").html("");
            // }
            // if (address2 == "") {
            //     $("#erraddress2").html("Please enter address2");
            //     isValid = false;
            // } else {
            //     $("#erraddress2").html("");
            // }
            // if (postCode == "") {
            //     $("#errpostCode").html("Please enter Pin Code");
            //     isValid = false;
            // } else {
            //     $("#errpostCode").html("");
            // }
            // if (iStateId == "") {
            //     $("#errStateId").html("Please enter State");
            //     isValid = false;
            // } else {
            //     $("#errStateId").html("");
            // }
            // if (iCityId == "") {
            //     $("#erriCityId").html("Please enter City");
            //     isValid = false;
            // } else {
            //     $("#erriCityId").html("");
            // }
            // strGSTNo = strGSTNo.toUpperCase();
            // var expr = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
            // if (strGSTNo == "") {
            //     $("#errstrGSTNo").html("Please enter GSTNo");
            //     isValid = false;
            // } else if (!expr.test(strGSTNo)) {

            //     $("#errstrGSTNo").html("Invalid GST Number.");
            //     isValid = false;
            // } else {
            //     $("#errstrGSTNo").html("");
            // }

            return isValid;
        }
        $("#strConfirmPassword").blur(function() {

            let strPassword = $("#strPassword").val();
            let strConfirmPassword = $("#strConfirmPassword").val();
            if (strPassword != strConfirmPassword) {
                $("#strPassword").val('');
                $("#strConfirmPassword").val('');
                $("#erroralert").css("display", "block");
                $("#msgdataError").html("Password and Confirm Password not match");
            } else {

                $("#erroralert").css("display", "none");
                $("#msgdataError").html("");
            }

        });
        $("#strEmailId").blur(function() {
            var email = $(this).val();
            $.ajax({
                type: 'POST',
                url: "{{ route('call_attendant.emailvalidate') }}",
                data: {
                    email: email
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
            var maxField = 5; //Input fields increment limitation
            var addButton1 = $('.add_button1'); //Add button selector
            var wrapper = $('.field_wrapper1'); //Input field wrapper

            var x = 1; //Initial field counter is 1

            $("body").on("click", ".add_button1", function() {

                if (x < maxField) {

                    var fieldHTML =
                        `<div class="form-group d-flex">
                            <select class="js-example-basic-single select2" name="iOEMCompanyAddMore[]" id="iOEMCompanyAddMore_` +
                        x +
                        `" onchange="getroleinmultiple(` +
                        x +
                        `);" style="width: 100%;" required>
                                                <option label="Please Select" value="">
                                                    -- Select--
                                                </option>
                                                <?php foreach ($companyMasters as $companyMaster) {  ?>
                                                <option value="<?= $companyMaster->iCompanyId ?>">
                                                    <?= $companyMaster->strOEMCompanyName ?></option>
                                                        <?php } ?>
                                                    </select>

                                                    <select class="js-example-basic-single select2" name="iOEMCompanyRoleAddMore[]" id="iOEMCompanyRoleAddMore_` +
                        x + `" style="width: 100%;" required>
                                                        <option label="Please Select" value="">
                                                            -- Select--
                                                        </option>

                                                    </select>



                                                    <a href="javascript:void(0);" class="btn btn-danger remove_button1 pull-right add-more" title="Remove Option"><i class="mas-minus-circle lh-normal"></i>
                                                    </a>
                                                </div>`; //New input field html


                    x++;
                    $(wrapper).append(fieldHTML);
                }

            });

            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button1', function(e) {
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                //$(".accordion" + (x - 1)).remove(); //Remove field html
                x--; //Decrement field counter
            });
            $(addButton).click(function() {
                //Check maximum number of input fields
                if (x < maxField) {
                    var fieldHTML = `<div class="accordion` + x + `" id="accordionExample">
                <div id="add-option" class="accordion-item">
                    <h2 class="accordion-header" id="heading` + x +
                        `">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse` +
                        x +
                        `" aria-expanded="true" aria-controls="collapse` + x + `">
                            <span class="drag-icon pull-left">
                                <i class="mas-arrow-move"></i>
                            </span>
                            New Option
                        </button>
                    </h2>
                    <div id="collapse` + x + `" class="accordion-collapse collapse show" aria-labelledby="heading` +
                        x + `" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="new-option clearfix">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>First Name*</label>
                                            <input type="text" name="strFirstName[]" id="strFirstName" class="form-control" required="">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Last Name*</label>
                                            <input type="text" name="strLastName[]" id="strLastName" class="form-control" required="">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contact*</label>
                                            <input type="text" name="strContact[]" id="strContact" class="form-control" required=""  onkeypress="return isNumber(event)">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email ID*</label>
                                            <input type="email" name="strEmailId[]" id="strEmailId" class="form-control" required="">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Password*</label>
                                            <input type="password" name="strPassword[]" id="strPassword" class="form-control" required="">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Confirm
                                                Password*</label>
                                            <input type="password" name="strConfirmPassword[]" id="strConfirmPassword" class="form-control" required="">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Executive
                                                Level</label>
                                            <select class="js-example-basic-single select2" name="iExecutiveLevel[]" id="iExecutiveLevel" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select
                                                    --</option>
                                                <option value="1">Level
                                                    1</option>
                                                <option value="2">Level
                                                    2</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select OEM
                                                Company*</label>
                                            <select class="js-example-basic-single select2" name="iOEMCompany[]" id="iOEMCompany" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select
                                                    --</option><?php foreach ($companyMasters as $companyMaster) {  ?>
                                                <option value="<?= $companyMaster->iCompanyId ?>"><?= $companyMaster->strOEMCompanyName ?></option>
                                                        <?php } ?></select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <a type="button" href="javascript:void(0);" class="btn delete-option pull-right remove_button" title="Delete Option">
                                            <i class="mas-trash"></i>
                                        </a>
                                        <!--/.delete icon-->
                                    </div> <!-- /.col -->
                                </div>
                            </div>
                            <!--new option-->
                        </div>
                        <!--accordion body-->
                    </div>
                </div>
            </div>`;
                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); //Add field html
                    selectRefresh();
                }
            });

            companyroleshow();
        });

        $("#iExecutiveLevel").change(function() {
            if ($("#iExecutiveLevel").val() == 2) {
                $("#oemCompanyDiv").css("display", "block");
                $("#oemCompanylvl2Div").css("display", "none");
                $("#iOEMCompany").attr("required", true);
                $("#iOEMCompanyAddMore").attr("required", false);
                $("#iOEMCompanyRoleID").attr("required", false);
            } else {

                $("#oemCompanyDiv").css("display", "none");
                $("#oemCompanylvl2Div").css("display", "block");
                $("#iOEMCompany").attr("required", false);
            }
        });
    </script>

    {{--  07-05-2024  --}}
    <script>
        function getrole() {
            var company = $('#iOEMCompanyAddMore').val();
            var url = "{{ route('call_attendant.mappingcompanyrole') }}";
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    company: company,
                },
                success: function(data) {
                    $("#iOEMCompanyRoleID").html('');
                    $("#iOEMCompanyRoleID").append(data);
                }
            });
        }
    </script>

    <script>
        function getroleinmultiple(x) {

            var company = $('#iOEMCompanyAddMore_' + x).val();
            var url = "{{ route('call_attendant.mappingcompanyrole') }}";
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    company: company,
                },
                success: function(data) {
                    $("#iOEMCompanyRoleAddMore_" + x).html('');
                    $("#iOEMCompanyRoleAddMore_" + x).append(data);
                }
            });
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function companyroleshow() {
                var company = $('#isCanSwitchProfile').prop('checked');
                if (company) {
                    $('#CompanyRoleDiv').show();
                    {{--  $('#iOEMCompanyRoleID').attr('required', true);  --}}
                } else {
                    $('#CompanyRoleDiv').hide();
                    {{--  $('#iOEMCompanyRoleID').attr('required', false);  --}}
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
