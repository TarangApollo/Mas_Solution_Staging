@extends('layouts.admin')

@section('title', 'Add New Company')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Add New Company</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Company</li>
                    <li class="breadcrumb-item active"> Add New Company </li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
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
                                                        Company Information
                                                    </a>
                                                </h4>
                                            </div>

                                            @include('admin.company.companysidebar')

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('admin.common.loader')
                            <div class="col-lg-9 col-md-8">
                                <div class="accordion-box-content">
                                    <div class="tab-content clearfix">
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter"
                                            action="{{ route('company.store') }}" method="post">
                                            <input type="hidden" name="company_id" id="company_id"
                                                value="{{ $Company->iCompanyId ?? 0 }}">
                                            <input type="hidden" name="save" id="save" value="0">
                                            @csrf
                                            <div class="tab-pane fade in active" id="general">
                                                <h3 class="tab-content-title">General</h3>

                                                <div class="row">

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>OEM Company Name*</label>
                                                            <input type="text" name="strOEMCompanyName"
                                                                id="strOEMCompanyName"
                                                                value="{{ $Company->strOEMCompanyName ?? '' }}"
                                                                class="form-control" required="required" />
                                                            <span id="errstrOEMCompanyName" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>OEM Company Prefix*</label>
                                                            <input type="text" name="strCompanyPrefix"
                                                                id="strCompanyPrefix"
                                                                value="{{ $Company->strCompanyPrefix ?? '' }}"
                                                                class="form-control" required="required" minlength="1"
                                                                maxlength="5"
                                                                {{ isset($Company->strCompanyPrefix) && $Company->strCompanyPrefix != '' ? 'readonly' : '' }} />
                                                            <span id="errstrCompanyPrefix" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>OEM Company ID*</label>
                                                            <input type="text" name="strOEMCompanyId"
                                                                id="strOEMCompanyId"
                                                                value="{{ $Company->strOEMCompanyId ?? '' }}"
                                                                class="form-control" readonly />
                                                            <span id="errstrOEMCompanyId" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 d-flex">
                                                        <button type="button" href="#permissions"
                                                            class="btn btn-success text-uppercase mt-4 mr-2" id="next"
                                                            data-toggle="tab" aria-expanded="true" onclick="nextData();">
                                                            Next
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                            <!--Advance information form start-->
                                            <div class="tab-pane fade" id="permissions">
                                                <h3 class="tab-content-title">Company Details</h3>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Contact Person*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $Company->ContactPerson ?? '' }}"
                                                                name="ContactPerson" id="ContactPerson" required="">
                                                            <span id="errcontactPerson" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Mail ID*</label>
                                                            <input type="email" class="form-control"
                                                                value="{{ $Company->EmailId ?? '' }}" name="EmailId"
                                                                id="EmailId" required="">
                                                            <span id="erremail" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Contact No*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $Company->ContactNo ?? '' }}" name="ContactNo"
                                                                id="ContactNo" onkeypress="return isNumber(event)"
                                                                required="">
                                                            <span id="errmobile_number" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Address 1*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $Company->Address1 ?? '' }}" name="Address1"
                                                                id="Address1" required="">
                                                            <span id="erraddress1" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Address 2*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $Company->Address2 ?? '' }}" name="Address2"
                                                                id="Address2" required="">
                                                            <span id="erraddress2" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Address 3</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $Company->Address3 ?? '' }}" name="Address3"
                                                                id="Address3">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Postcode*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $Company->Pincode ?? '' }}" name="Pincode"
                                                                id="Pincode" required="" maxlength="6"
                                                                minlength="6" onkeypress="return isNumber(event)">
                                                            <span id="errpostCode" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Select State*</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                name="iStateId" id="iStateId" onchange="getCity();"
                                                                required>
                                                                <option label="Please Select" value="">--
                                                                    Select --</option>
                                                                @foreach ($statemasters as $state)
                                                                    <option value="{{ $state->iStateId }}"
                                                                        @if (isset($Company->iStateId) && $Company->iStateId == $state->iStateId) {{ 'selected' }} @endif>
                                                                        {{ $state->strStateName }}</option>
                                                                @endforeach

                                                            </select>
                                                            <span id="errStateId" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Select City*</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                name="iCityId" id="iCityId" required>
                                                                <option label="Please Select" value="">--
                                                                    Select --</option>
                                                                @foreach ($citymasters as $cities)
                                                                    <option value="{{ $cities->iCityId }}"
                                                                        @if (isset($Company->iCityId) && $Company->iCityId == $cities->iCityId) {{ 'selected' }} @endif>
                                                                        {{ $cities->strCityName }} </option>
                                                                @endforeach
                                                            </select>
                                                            <span id="erriCityId" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>GST Number*</label>
                                                            <input type="text" class="form-control text-uppercase"
                                                                value="{{ $Company->strGSTNo ?? '' }}" name="strGSTNo"
                                                                id="strGSTNo" required="" minlength="15"
                                                                maxlength="15">
                                                            <span id="errstrGSTNo" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                </div>
                                                {{-- <div class="row">
                                                    <div class="preloader" id="loading" style="display: none;"
                                                        role="status">
                                                        <img src="{{ asset('images/loader.gif') }}">
                                                    </div>
                                                </div> --}}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <button type="button" class="btn btn-default text-uppercase mt-4"
                                                            onClick="checkclose();">
                                                            Clear
                                                        </button>

                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-end">
                                                        <button type="button"
                                                            class="btn btn-success text-uppercase mt-4 mr-2"
                                                            value="Submit" name="submit" id="submit">
                                                            Save
                                                        </button>
                                                        <button type="submit" value="saveSubmit" name="savesubmit"
                                                            class="btn btn-success text-uppercase mt-4" id="savesubmit"
                                                            onclick="validateForm()">
                                                            Save & Exit
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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

    </div>


@endsection

@section('script')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            //$("#strCompanyPrefix").focus();
            $("#strOEMCompanyName").focus();
            $("#general").css("display", "block");
            $("#general").attr("class", 'active');
            $("#generalLi").attr("class", 'active');

            $("#permissions").css("display", "none");
            $("#permissionsLi").removeAttr("class");
            $("#permissions").removeAttr("class");
        });

        function getAbsolutePath() {
            var loc = window.location;
            var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
            return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName
                .length));
        }

        function isNumber(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43)
                return false;
            return true;
        }

        //$("#iStateId").change(function(){
        function getCity() {
            var iStateId = $("#iStateId").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('company.getCity') }}",
                data: {
                    iStateId: iStateId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#iCityId").html(response);
                }
            });
        }

        function getCompany() {
            $("#generalLi").attr("class", 'active');
            $("#general").css("display", "block");
            // $("#general").attr("data-toggle",'tab');

            $("#permissions").css("display", "none");
            $("#permissionsLi").removeAttr("class");
        }

        function nextData() {
            var strCompanyPrefix = $("#strCompanyPrefix").val();
            var tStatus = false;
            var strOEMCompanyName = $("#strOEMCompanyName").val();
            if (strCompanyPrefix != "" && strCompanyPrefix != null) {
                tStatus = true;
            } else {
                $("#errstrCompanyPrefix").html("Please enter company prefix");
                tStatus = false;
            }
            if (strOEMCompanyName != "" && strOEMCompanyName != null) {
                tStatus = true;
                $.ajax({
                    type: 'POST',
                    url: "{{ route('company.OEMCompanyNamevalidate') }}",
                    data: {
                        strOEMCompanyName: strOEMCompanyName,
                        company_id: $('#company_id').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response == 0) {
                            $("#errstrOEMCompanyName").html("Company name already exists.");
                            $('#strOEMCompanyName').val('');
                            $('#submit').prop('disabled', true);
                            $('#savesubmit').prop('disabled', true);
                            return false;
                        } else {
                            $("#next").prop("disabled", false);
                            $("#generalLi").removeAttr("class");
                            $("#general").css("display", "none");
                            $("#permissions").css("display", "block");
                            $("#permissions").attr("class", 'active');
                            $("#permissionsLi").attr("class", 'active');
                        }
                    }
                });
            } else {
                $("#errstrOEMCompanyName").html("Please enter company name");
                tStatus = false;
            }
            if (tStatus) {

            }
        }

        $("#strCompanyPrefix").on("blur", function() {
            var strCompanyPrefix = $("#strCompanyPrefix").val();
            if (strCompanyPrefix == "" || strCompanyPrefix == null) {
                $("#errstrCompanyPrefix").html("Please enter company name");
                return false;
            } else {
                $("#errstrCompanyPrefix").html("");
                return true;
            }
        });

        $("#strOEMCompanyName").on("blur", function() {
            var strCompanyPrefix = $("#strCompanyPrefix").val();
            if (strCompanyPrefix == "" || strCompanyPrefix == null) {
                $("#errstrCompanyPrefix").html("Please enter company name");
                return false;
            } else {
                $("#errstrCompanyPrefix").html("");
                return true;
            }
            var strOEMCompanyName = $(this).val();
            if (strOEMCompanyName == "" || strOEMCompanyName == null) {
                $("#errstrOEMCompanyName").html("Please enter company name");
                return false;
            } else {
                $("#errstrOEMCompanyName").html("");
                nextData();
                return true;
            }


        });

        $('#submit').on("click", function() {
            $('#save').val('1');
            var isValid = validateForm();
            if (isValid == true) {
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: "{{ route('company.store') }}",
                    data: $('#frmparameter').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        $('#loading').css("display", "none");
                        if (response > 0) {
                            $('#loading').css("display", "none");
                            $("#successalert").show();
                            $("#msgdata").html(
                                "<strong>Success !</strong> Company Created Successfully.");
                            $('#company_id').val(response);
                            var company_id = response;
                            $('#save').val('0');
                            var url = "{{ route('company.create', ':company_id') }}";
                            url = url.replace(':company_id', company_id);
                            url = url.replace('?', '/');

                            window.location.href = url;
                            return false;
                        } else {
                            $('#loading').css("display", "none");
                            $('#loading').css("display", "none");
                            $("#erroralert").show();
                            $("#msgdata").html("<strong>Error !</strong> Somthing want wrong.");
                            $('#company_id').val(response);
                            return false;
                        }
                    }
                });
            }
        });

        function validateForm() {
            //$("#EmailId").blur();
            let companyName = document.forms["frmparameter"]["strOEMCompanyName"].value;
            let strCompanyPrefix = document.forms["frmparameter"]["strCompanyPrefix"].value;
            let contactPerson = document.forms["frmparameter"]["ContactPerson"].value;
            let email = document.forms["frmparameter"]["EmailId"].value;
            let mobile_number = document.forms["frmparameter"]["ContactNo"].value;
            let address1 = document.forms["frmparameter"]["Address1"].value;
            let address2 = document.forms["frmparameter"]["Address2"].value;
            let postCode = document.forms["frmparameter"]["Pincode"].value;
            let iCityId = document.forms["frmparameter"]["iCityId"].value;
            let strGSTNo = document.forms["frmparameter"]["strGSTNo"].value;
            let iStateId = document.forms["frmparameter"]["iStateId"].value;
            var isValid = true;

            //var strGSTNo = document.getElementById("strGSTNo").value;
            if (strCompanyPrefix == "") {
                $("#errstrCompanyPrefix").html("Please enter company Prefix");
                isValid = false;
            } else {
                $("#errstrCompanyPrefix").html("");
            }

            if (companyName == "") {
                $("#errstrOEMCompanyName").html("Please enter company name");
                isValid = false;
            } else {
                $("#errstrOEMCompanyName").html("");
            }

            if (contactPerson == "") {
                $("#errcontactPerson").html("Please enter contact Person");
                isValid = false;
            } else {
                $("#errcontactPerson").html("");
            }
            if (email == "") {
                $("#erremail").html("Please enter email");
                isValid = false;
            } else {
                $("#erremail").html("");
            }
            if (mobile_number == "") {
                $("#errmobile_number").html("Please enter mobile number");
                isValid = false;
            } else if (mobile_number.length != 10) {
                $("#errmobile_number").html("InvalidQ mobile number");
                isValid = false;
            } else {
                $("#errmobile_number").html("");
            }
            if (address1 == "") {
                $("#erraddress1").html("Please enter address1");
                isValid = false;
            } else {
                $("#erraddress1").html("");
            }
            if (address2 == "") {
                $("#erraddress2").html("Please enter address2");
                isValid = false;
            } else {
                $("#erraddress2").html("");
            }
            if (postCode == "") {
                $("#errpostCode").html("Please enter Pin Code");
                isValid = false;
            } else {
                $("#errpostCode").html("");
            }
            if (iStateId == "") {
                $("#errStateId").html("Please enter State");
                isValid = false;
            } else {
                $("#errStateId").html("");
            }
            if (iCityId == "") {
                $("#erriCityId").html("Please enter City");
                isValid = false;
            } else {
                $("#erriCityId").html("");
            }
            strGSTNo = strGSTNo.toUpperCase();
            var expr = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
            if (strGSTNo == "") {
                $("#errstrGSTNo").html("Please enter GSTNo");
                isValid = false;
            } else if (!expr.test(strGSTNo)) {

                $("#errstrGSTNo").html("Invalid GST Number.");
                isValid = false;
            } else {
                $("#errstrGSTNo").html("");
            }

            return isValid;
        }

        $("#EmailId").blur(function() {
            var email = $(this).val();
            var company_id = $("#company_id").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('company.emailvalidate') }}",
                data: {
                    email: email,
                    company_id: company_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response == 0) {
                        $("#erremail").html("Email is already in used.");
                        $('#EmailId').val('');
                        $('#submit').prop('disabled', true);
                        $('#savesubmit').prop('disabled', true);
                        return false;
                    } else {
                        var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
                        if (!pattern.test(email)) {
                            $("#erremail").html('not a valid e-mail address');
                            $('#EmailId').val('');
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

        function checkclose() {

            $("#generalLi").removeAttr("class");
            $("#general").css("display", "none");
            // $("#companyForm").attr("data-toggle", 'tab');
            // $("#companyLi").attr("class", 'active');

            $("#permissions").css("display", "block");
            //$("#permissions").attr("data-toggle",'tab');
            $("#permissions").attr("class", 'active');
            $("#permissionsLi").attr("class", 'active');

            $('#ContactPerson').val('');
            $('#EmailId').val('');
            $('#ContactNo').val('');
            $('#Address1').val('');
            $('#Address2').val('');
            $('#Address3').val('');
            $('#Pincode').val('');
            $('#iStateId').val('');
            $('#iCityId').val('');
            $('#strGSTNo').val('');
            //      $('#loading').css("display", "none");

            //window.location.href = "{{ route('company.create') }}";
        }

        $(document).ready(function() {
            // $("#frmparameter").validate({
            //     errorClass: "error fail-alert",
            //     validClass: "valid success-alert",
            //     rules: {
            //         strOEMCompanyName: {
            //             required: true
            //         },
            //         ContactPerson: {
            //             required: true,
            //         },
            //         EmailId: {
            //             required: true,
            //             email: true
            //         },
            //         ContactNo: {
            //             required: true,
            //             number: true,
            //             minlength: 10
            //         },
            //         weight: {
            //             // required: {
            //             //     depends: function(elem) {
            //             //         return $("#age").val() > 50
            //             //     }
            //             // },
            //             // number: true,
            //             // min: 0
            //         }
            //     },
            //     messages: {
            //         strOEMCompanyName: {
            //             required: "Please enter your OEM Company Name."
            //         },
            //         ContactPerson: {
            //             required: "Please enter your Company Contact Person."
            //         },
            //         EmailId: {
            //             email: "The email should be in the format: abc@domain.tld"
            //         },
            //         weight: {
            //             required: "People with age over 50 have to enter their weight",
            //             number: "Please enter your weight as a numerical value"
            //         }
            //     }
            // });
        });
    </script>

@endsection
