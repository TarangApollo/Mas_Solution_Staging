@extends('layouts.admin')

@section('title', 'Setting')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Settings</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </nav>
    </div>
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
    <!-- first row starts here -->
    <div class="row">
        <div class="col-xl-12 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="accordion-content clearfix">
                        <div class="col-lg-3 col-md-4">
                            <div class="accordion-box">
                                <div class="panel-group" id="RoleTabs">
                                    <div class="panel panel-default">
                                        <div id="role_information" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <ul class="accordion-tab nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#general" data-toggle="tab">General Settings</a>
                                                    </li>
                                                    <li>
                                                        <a href="#permissions" data-toggle="tab"
                                                            aria-expanded="true">Social Media Links</a>
                                                    </li>
                                                    <li>
                                                        <a href="#engineer" data-toggle="tab"
                                                            aria-expanded="true">Inactive Users</a>
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
                                    <div class="tab-pane fade in active" id="general">
                                        <h3 class="tab-content-title">General</h3>
                                        <form class="was-validated pb-3" name="frmGeneral" id="frmGeneral"
                                            action="" method="post">
                                            @csrf
                                            <input type="hidden" name="CompanyInfoId" id="CompanyInfoId"
                                                value="{{ $CompanyInfo->iCompanyInfoId ?? '0' }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Company Name*</label>
                                                        <input type="text" class="form-control" name="strCompanyName"
                                                            id="strCompanyName"
                                                            value="{{ $CompanyInfo->strCompanyName ?? 'Mas Solution' }}"
                                                            disabled>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Address 1*</label>
                                                        <input type="text" class="form-control" name="Address1"
                                                            id="Address1" value="{{ $CompanyInfo->Address1 ?? '' }}"
                                                            require>
                                                        <span id="errAddress1" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Address 2</label>
                                                        <input type="text" class="form-control" name="address2"
                                                            id="address2" value="{{ $CompanyInfo->address2 ?? '' }}">
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>City*</label>
                                                        <input type="text" class="form-control" name="strCity"
                                                            id="strCity" value="{{ $CompanyInfo->strCity ?? '' }}">
                                                        <span id="errstrCity" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>State*</label>
                                                        <input type="text" class="form-control" name="strState"
                                                            id="strState" value="{{ $CompanyInfo->strState ?? '' }}">
                                                        <span id="errstrState" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Country*</label>
                                                        <input type="text" class="form-control" name="strCountry"
                                                            id="strCountry"
                                                            value="{{ $CompanyInfo->strCountry ?? '' }}">
                                                        <span id="errstrCountry" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Postcode*</label>
                                                        <input type="text" class="form-control" name="Pincode"
                                                            id="Pincode" value="{{ $CompanyInfo->Pincode ?? '' }}" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
                                                        <span id="errPincode" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" class="form-control" name="Phone" id="Phone"
                                                            value="{{ $CompanyInfo->Phone ?? '' }}"  onkeypress="return isNumber(event)">
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Contact No*</label>
                                                        <input type="text" class="form-control" name="ContactNo"
                                                            id="ContactNo" value="{{ $CompanyInfo->ContactNo ?? '' }}"  onkeypress="return isNumber(event)">
                                                        <span id="errContactNo" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Mail ID*</label>
                                                        <input type="email" class="form-control" name="EmailId"
                                                            id="EmailId" value="{{ $CompanyInfo->EmailId ?? '' }}">
                                                        <span id="errEmailId" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Another Mail ID</label>
                                                        <input type="email" class="form-control" name="AnotherEmailId"
                                                            id="AnotherEmailId"
                                                            value="{{ $CompanyInfo->AnotherEmailId ?? '' }}">
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-default text-uppercase mt-4"
                                                        onclick="ClearGeneralData();">
                                                        Clear
                                                    </button>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-end">
                                                    <button type="button"
                                                        class="btn btn-success text-uppercase mt-4 mr-2" value="Submit"
                                                        name="submit" id="GeneralDataSubmit">
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--buttons start-->

                                        <!--/.buttons end-->
                                    </div>
                                    <!--social media form start-->
                                    <div class="tab-pane fade" id="permissions">
                                        <h3 class="tab-content-title">Social Media Links</h3>
                                        <form class="was-validated pb-3" name="frmSocialMediaLinks"
                                            id="frmSocialMediaLinks" action="{{route('companyinfo.SocialMediaLinkStore')}}"
                                            method="post">
                                            @csrf
                                            <input type="hidden" name="iCompanyInfoId" id="iCompanyInfoId"
                                                value="{{ $CompanyInfo->iCompanyInfoId ?? '0' }}">
                                            <input type="hidden" name="SocialMediaSave" id="SocialMediaSave" value="0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Facebook</label>
                                                        <input type="text" class="form-control" name="facebookLink"
                                                            id="facebookLink"
                                                            value="{{ $CompanyInfo->facebookLink ?? '' }}" required="">
                                                            <span id="errfacebookLink" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Twitter</label>
                                                        <input type="text" class="form-control" name="twitterlink"
                                                            id="twitterlink"
                                                            value="{{ $CompanyInfo->twitterlink ?? '' }}" required="">
                                                            <span id="errtwitterlink" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Instagram</label>
                                                        <input type="text" class="form-control" name="instaLink"
                                                            id="instaLink" value="{{ $CompanyInfo->instaLink ?? '' }}"
                                                            required="">
                                                            <span id="errinstaLink" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Linkedin</label>
                                                        <input type="text" class="form-control" name="linkedinlink"
                                                            id="linkedinlink"
                                                            value="{{ $CompanyInfo->linkedinlink ?? '' }}" required="">
                                                            <span id="errlinkedinlink" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-default text-uppercase mt-4"
                                                        onclick="ClearSocialMediaLinksData();">
                                                        Clear
                                                    </button>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-end">
                                                    <button type="button"
                                                        class="btn btn-success text-uppercase mt-4 mr-2" value="Submit" name="submit" id="SocialMediaLinksSubmit">
                                                        Save
                                                    </button>
                                                    <button type="submit" class="btn btn-success text-uppercase mt-4">
                                                        Save & Exit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--buttons start-->

                                        <!--/.buttons end-->
                                    </div>
                                    <!--engineer form starts-->
                                    <div class="tab-pane fade" id="engineer">
                                        <h3 class="tab-content-title">Message for Inactive User</h3>
                                        <form class="was-validated pb-3" name="frmMessageforInactiveUser"
                                            id="frmMessageforInactiveUser" action="{{route('companyinfo.MessageforInactiveUserstore')}}"
                                            method="post">
                                            @csrf
                                            <input type="hidden" name="MessageInactiveUserSave" id="MessageInactiveUserSave" value="0">
                                            <input type="hidden" name="iMessageId" id="iMessageId"
                                                value="{{ $messageMaster->iMessageId ?? '0' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Message</label>
                                                        <textarea class="form-control" rows="4" cols="50"
                                                            name="strMessage" id="strMessage"
                                                            placeholder="Your account is deactivated. Please contact admin at support@massolutions.co.uk">{{ $messageMaster->strMessage ?? ''}}</textarea>
                                                            <span id="errstrMessage" class="text-danger"></span>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" id="MessageforInactiveUserData" class="btn btn-default text-uppercase mt-4"
                                                        onclick="ClearMessageforInactiveUserData();">
                                                        Clear
                                                    </button>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-end">
                                                    <button type="button"
                                                        class="btn btn-success text-uppercase mt-4 mr-2" value="Submit" name="submit" id="MessageforInactiveUserSubmit">
                                                        Save
                                                    </button>
                                                    <button type="submit" class="btn btn-success text-uppercase mt-4" id="MessageforInactiveUserbtn">
                                                        Save & Exit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--buttons start-->

                                        <!--/.buttons end-->
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

function ClearGeneralData() {
    $("#Address1").val('');
    $("#address2").val('');
    $("#strCity").val('');
    $("#strState").val('');
    $("#strCountry").val('');
    $("#Pincode").val('');
    $("#Phone").val('');
    $("#ContactNo").val('');
    $("#EmailId").val('');
    $("#AnotherEmailId").val('');
}


function ClearSocialMediaLinksData() {
    $("#facebookLink").val('');
    $("#instaLink").val('');
    $("#twitterlink").val('');
    $("#linkedinlink").val('');
}

function ClearMessageforInactiveUserData() {
    $("#strMessage").val('');
}

$('#GeneralDataSubmit').on("click", function() {
    var isValid = validateGeneralForm();
    if (isValid == true) {
        $("#submit").attr("disabled", 'disabled');
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: "{{route('companyinfo.GeneralDataStore')}}",
            data: $('#frmGeneral').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                if (response == 1) {
                    $('#loading').css("display", "none");
                    $("#successalert").show();
                    $("#msgdata").html("<strong>Success !</strong> Company General information added Successfully.");
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
// $("#Address1").blur(function() {
//     validateGeneralForm();
// });
// $("#strCity").blur(function() {
//     validateGeneralForm();
// });
// $("#strState").blur(function() {
//     validateGeneralForm();
// });
// $("#strCountry").blur(function() {
//     validateGeneralForm();
// });
// $("#Pincode").blur(function() {
//     validateGeneralForm();
// });
// $("#ContactNo").blur(function() {
//     validateGeneralForm();
// });
// $("#EmailId").blur(function() {
//     validateGeneralForm();
// });

function validateGeneralForm() {
    //$("#EmailId").blur();
    let Address1 = document.forms["frmGeneral"]["Address1"].value;
    //let address2 = document.forms["frmGeneral"]["address2"].value;
    let strCity = document.forms["frmGeneral"]["strCity"].value;
    let strState = document.forms["frmGeneral"]["strState"].value;
    let strCountry = document.forms["frmGeneral"]["strCountry"].value;
    let Pincode = document.forms["frmGeneral"]["Pincode"].value;
    let ContactNo = document.forms["frmGeneral"]["ContactNo"].value;
    let EmailId = document.forms["frmGeneral"]["EmailId"].value;
    var isValid = true;

    if (Address1 == "") {
        $("#errAddress1").html("Please enter address 1");
        isValid = false;
    } else {
        $("#errAddress1").html("");
    }
    if (strCity == "") {
        $("#errstrCity").html("Please enter city");
        isValid = false;
    } else {
        $("#errstrCity").html("");
    }
    if (strState == "") {
        $("#errstrState").html("Please enter state");
        isValid = false;
    } else {
        $("#errstrState").html("");
    }
    if (strCountry == "") {
        $("#errstrCountry").html("Please enter country");
        isValid = false;
    } else {
        $("#errstrCountry").html("");
    }
    if (Pincode == "") {
        $("#errPincode").html("Please enter Pincode");
        isValid = false;
    } else {
        $("#errPincode").html("");
    }

    if (ContactNo == "") {
        $("#errContactNo").html("Please enter mobile number");
        isValid = false;
    } else if (ContactNo.length != 10) {
        $("#errContactNo").html("Invalid mobile number");
        isValid = false;
    } else {
        $("#errContactNo").html("");
    }
    if (EmailId == "") {
        $("#errEmailId").html("Please enter email");
        isValid = false;
    } else {
        $("#errEmailId").html("");
    }

    return isValid;
}

$('#SocialMediaLinksSubmit').on("click", function() {
    var isValid = validateSocialMediaLinksForm();
    if (isValid == true) {
        $("#submit").attr("disabled", 'disabled');
        $('#Medialoading').css("display", "block");
        $('#SocialMediaSave').val('1');
        $.ajax({
            type: 'POST',
            url: "{{route('companyinfo.SocialMediaLinkStore')}}",
            data: $('#frmSocialMediaLinks').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#Medialoading').css("display", "none");

                if (response > 0) {
                    $('#Medialoading').css("display", "none");
                    $("#successalert").show();
                    $("#msgdata").html("<strong>Success !</strong> Company Social Media links added Successfully.");
                    $('#SocialMediaSave').val('0');
                    return true;
                } else {
                    $("#erroralert").show();
                    $("#msgdata").html("<strong>Error !</strong> Somthing want wrong.");
                    $('#Medialoading').css("display", "none");
                    return false;
                }
            }
        });
    }
});
function validateSocialMediaLinksForm(){
    let facebookLink = document.forms["frmSocialMediaLinks"]["facebookLink"].value;
    let twitterlink = document.forms["frmSocialMediaLinks"]["twitterlink"].value;
    let instaLink = document.forms["frmSocialMediaLinks"]["instaLink"].value;
    let linkedinlink = document.forms["frmSocialMediaLinks"]["linkedinlink"].value;
    var isValid = true;
    if (facebookLink == "") {
        $("#errfacebookLink").html("Please enter Facebook Link");
        isValid = false;
    } else {
        $("#errfacebookLink").html("");
    }
    if (twitterlink == "") {
        $("#errtwitterlink").html("Please enter Twitter Link");
        isValid = false;
    } else {
        $("#errtwitterlink").html("");
    }
    if (instaLink == "") {
        $("#errinstaLink").html("Please enter Instagram Link");
        isValid = false;
    } else {
        $("#errinstaLink").html("");
    }
    if (linkedinlink == "") {
        $("#errlinkedinlink").html("Please enter Linkedin Link");
        isValid = false;
    } else {
        $("#errlinkedinlink").html("");
    }
    return isValid;
}
$('#MessageforInactiveUserSubmit').on("click", function() {
    var isValid = validateMessageforInactiveUserForm();
    if (isValid == true) {
        $("#MessageforInactiveUserSubmit").attr("disabled", 'disabled');
        $("#MessageforInactiveUserbtn").attr("disabled", 'disabled');
        $("#MessageforInactiveUserData").attr("disabled", 'disabled');
        $('#Inactiveloading').css("display", "block");
        $("#MessageInactiveUserSave").val('1');
        $.ajax({
            type: 'POST',
            url: "{{route('companyinfo.MessageforInactiveUserstore')}}",
            data: $('#frmMessageforInactiveUser').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#Inactiveloading').css("display", "none");
                $("#MessageforInactiveUserSubmit").attr("disabled", false);
                $("#MessageforInactiveUserbtn").attr("disabled", false);
                $("#MessageforInactiveUserData").attr("disabled", false);
                if (response > 0) {
                    $('#Inactiveloading').css("display", "none");
                    $("#successalert").show();
                    $("#msgdata").html("<strong>Success !</strong> Message for Inactive User added Successfully.");
                    $('#MessageInactiveUserSave').val('0');
                    return true;
                } else {
                    $('#Inactiveloading').css("display", "none");
                    $("#erroralert").show();
                    $("#msgdata").html("<strong>Error !</strong> Somthing want wrong.");
                    return false;
                }
            }
        });
    }
});
function validateMessageforInactiveUserForm(){
    let strMessage = document.forms["frmMessageforInactiveUser"]["strMessage"].value;
    var isValid = true;
    if (strMessage == "") {
        $("#errstrMessage").html("Please Enter Message");
        isValid = false;
    } else {
        $("#errstrMessage").html("");
    }
    return isValid;
}
</script>
@endsection
