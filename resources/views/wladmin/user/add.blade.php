@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Add New User</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active"> Add New User </li>
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

                                            <form class="was-validated pb-3" name="frmparameter" id="frmparameter"
                                                action="{{ route('user.store') }}" method="post">
                                                <input type="hidden" name="save" id="save" value="0">
                                                <input type="hidden" name="iCompanyId" id="iCompanyId"
                                                    value="{{ $CompanyMaster->iCompanyId }}">

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
                                                                                        id="strContact" class="form-control"
                                                                                        required=""
                                                                                        onkeypress="return isNumber(event)">
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Email ID*</label>
                                                                                    <input type="email" name="strEmail"
                                                                                        id="strEmail" class="form-control"
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
                                                                                    <label>Select Role * </label>
                                                                                    <select class="js-example-basic-single"
                                                                                        name="iRoleId" id="iRoleId"
                                                                                        style="width: 100%;" required>
                                                                                        <option label="Please Select"
                                                                                            value="">-- Select
                                                                                            --</option>
                                                                                        @foreach ($Roles as $role)
                                                                                            <option
                                                                                                value="{{ $role->id }}">
                                                                                                {{ $role->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <span id="errRole"
                                                                                        class="text-danger"></span>
                                                                                </div> <!-- /.form-group -->
                                                                            </div> <!-- /.col -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <br>
                                                                                    <input type="checkbox"
                                                                                        name="isCanSwitchProfile"> Switch
                                                                                    User
                                                                                </div>
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
                                                        {{--  <button type="button" class="btn btn-default text-uppercase mt-4 add_button" id="add-new-option">
                                                        Add New User
                                                    </button>  --}}
                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-end">
                                                        <button type="button"
                                                            class="btn btn-success text-uppercase mt-4 mr-2"
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
                    url: "{{ route('user.store') }}",
                    data: $('#frmparameter').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#loading').css("display", "none");

                        if (response > 0) {
                            $('#loading').css("display", "none");

                            //var company_id = response;
                            $('#save').val('0');
                            var url = "{{ route('user.edit', ':id') }}";
                            url = url.replace(':id', response);
                            url = url.replace('?', '/');
                            window.location.href = url;
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
            let strEmail = document.forms["frmparameter"]["strEmail"].value;
            let strPassword = document.forms["frmparameter"]["strPassword"].value;
            let iRoleId = document.forms["frmparameter"]["iRoleId"].value;
            //let iExecutiveLevel = document.forms["frmparameter"]["iExecutiveLevel"].value;
            //let iOEMCompany = document.forms["frmparameter"]["iOEMCompany"].value;
            let strConfirmPassword = document.forms["frmparameter"]["strConfirmPassword"].value;
            var isValid = true;
            var errorMsg = "";

            if (iRoleId === "") {
                errorMsg += "Role is required.";
                isValid = false;
            }
            if (strPassword != strConfirmPassword) {
                isValid = false;
            }

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
        $("#strEmail").blur(function() {
            var email = $(this).val();
            $.ajax({
                type: 'POST',
                url: "{{ route('user.emailvalidate') }}",
                data: {
                    email: email
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

        $(document).ready(function() {
            var maxField = 10; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper

            var x = 1; //Initial field counter is 1

            //Once add button is clicked
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
                                            <input type="text" name="strContact[]" id="strContact" class="form-control" required="" onkeypress="return isNumber(event)">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email ID*</label>
                                            <input type="email" name="strEmail[]" id="strEmail" class="form-control" required="">
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
                                            <label>Select Role </label>
                                            <select class="js-example-basic-single select2" name="iRoleId[]" id="iRoleId" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select
                                                    --</option>
                                                        <option value="1">Admin</option>
                                                        <option value="2">Operation</option>
                                                        <option value="3">Support Team</option>
                                                    </select>
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

            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e) {
                e.preventDefault();
                //$(this).parent('div').remove(); //Remove field html
                $(".accordion" + (x - 1)).remove(); //Remove field html
                x--; //Decrement field counter
            });
        });
    </script>
@endsection
