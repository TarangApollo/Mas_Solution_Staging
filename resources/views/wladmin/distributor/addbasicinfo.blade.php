@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Add New Customer Company</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Customer Company</li>
                <li class="breadcrumb-item active"> Add New Company </li>
            </ol>
        </nav>
    </div>
    <!--/. page header ends-->
    <div class="row">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" class="btn btn-success text-uppercase pt-1 mb-3 mr-2">
                <i class="mas-upload btn-icon"></i>
                Upload Company List
            </button>
        </div>
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
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a>
                                                    Company Information
                                                </a>
                                            </h4>
                                        </div>

                                        <div id="role_information" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <ul class="accordion-tab nav nav-tabs">
                                                    <!-- <li>
                                                        <a href="{{ route('companyclient.create') }}">Company Profile</a>
                                                    </li> -->
                                                    <li class="active">
                                                        <a href="#account" data-toggle="tab">Basic Information</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('companyclient.salescreate',$id) }}">Sales Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('companyclient.technicalcreate',$id) }}">Technical Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('companyclient.userdefinedcreate',$id) }}">User Defined</a>
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
                                    <!-- account basic information starts -->
                                    <div class="" id="account">
                                        <h3 class="tab-content-title">Basic Information</h3>
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{ route('companyclient.basicinfostore') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="company_id" id="company_id" value="{{ $CompanyMaster->iCompanyId }}">
                                            <input type="hidden" name="save" id="save" value="0">
                                            <div class="field_wrapper">
                                                <div class="accordion" id="accordionExample">
                                                    <!--display non Add new option-->
                                                    <div id="add-option" class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFour">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                                                <span class="drag-icon pull-left">
                                                                    <i class="mas-arrow-move"></i>
                                                                </span>
                                                                New Option
                                                            </button>
                                                        </h2>
                                                        <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Company Name*</label>
                                                                                <input type="text" name="CompanyName[]" id="CompanyName" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Company Email ID* </label>
                                                                                <input type="email" name="email[]" id="email" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Owner</label>
                                                                                <input type="text" name="owner[]" id="Owner" class="form-control">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Owner Email ID* </label>
                                                                                <input type="email" name="owneremail[]" id="owneremail" class="form-control">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Owner Phone</label>
                                                                                <input type="text" name="ownerphone[]" id="contact" class="form-control" onkeypress="return isNumber(event)">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Address</label>
                                                                                <input type="text" name="address[]" id="address" class="form-control">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>State</label>
                                                                                <select class="js-example-basic-single" name="iStateId[]" id="iStateId0" style="width: 100%;" onchange="getCity(0);">
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($statemasters as $state)
                                                                                    <option value="{{ $state->iStateId }}">{{ $state->strStateName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>City</label>
                                                                                <select class="js-example-basic-single" name="iCityId[]" id="iCityId0" style="width: 100%;">
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($citymasters as $cities)
                                                                                    <option value="{{ $cities->iCityId }}">{{ $cities->strCityName }} </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Branch Office</label>
                                                                                <input type="text" name="branchOffice[]" id="branchOffice" class="form-control">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Company Profile</label>
                                                                                <select class="js-example-basic-single" name="iCompanyClientProfileId[]" id="iCompanyClientProfileId" style="width: 100%;">
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($CompanyClientProfile as $ClientProfile)
                                                                                    <option value="{{ $ClientProfile->iCompanyClientProfileId }}">{{ $ClientProfile->strCompanyClientProfile }} </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <!-- <div class="col-md-offset-4 col-md-4">
                                                                            <button type="button" class="btn delete-option pull-right" title="Delete Option">
                                                                                <i class="mas-trash"></i>
                                                                            </button>
                                                                        </div> -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- <button type="button" class="btn btn-default text-uppercase mt-4" id="add-new-option" onClick="showhide()">
                                                        Add New User
                                                    </button> -->
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-end">
                                                    <button type="button" class="btn btn-success text-uppercase mt-4 mr-2" value="Submit" name="submit" id="submit">
                                                        Save
                                                    </button>
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
@endsection

@section('script')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $('#submit').on("click", function() {
        $('#save').val('1');
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: "{{route('companyclient.basicinfostore')}}",
            data: $('#frmparameter').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                console.log(response);
                if (response > 0) {
                    $('#loading').css("display", "none");
                    $('#company_id').val(response);
                    var company_id = response;
                    $('#save').val('0');
                    var url = "{{route('companyclient.basicinfocreate',':id')}}";
                    url = url.replace(':id', response);
                    url = url.replace('?', '/');

                    window.location.href = url;
                    return true;
                } else {
                    $('#loading').css("display", "none");
                    return false;
                }
            }
        });
    });
</script>
<script>
    function isNumber(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)  && charCode != 43)
            return false;
        return true;
    }

    $(document).ready(function() {

        var maxField = 10; //Input fields increment limitation
        var addButton = $('#add-new-option'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper

        var x = 1; //Initial field counter is 1

        //Once add button is clicked
        $(addButton).click(function() {
            //Check maximum number of input fields
            if (x < maxField) {
                var fieldHTML = `<div class="accordion` + x + `" id="accordionExample">
                                    <div class="accordion-item delete-option">
                                        <h2 class="accordion-header" id="heading` + x + `">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse` + x + `" aria-expanded="true" aria-controls="collapseOne">
                                                <span class="drag-icon pull-left">
                                                    <i class="mas-arrow-move"></i>
                                                </span>
                                                New Option
                                            </button>
                                        </h2>
                                        <div id="collapse` + x + `" class="accordion-collapse collapse show" aria-labelledby="heading` + x + `" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="new-option clearfix">
                                                    <div class="row">
                                                    <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Company Name*</label>
                                                                                <input type="text" name="CompanyName[]" id="CompanyName" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Company Email ID* </label>
                                                                                <input type="email" name="email[]" id="email" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Owner</label>
                                                                                <input type="text" name="owner[]" id="Owner" class="form-control" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Owner Email ID* </label>
                                                                                <input type="email" name="owneremail[]" id="owneremail" class="form-control" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Owner Phone</label>
                                                                                <input type="text" name="contact[]" id="contact" class="form-control"  onkeypress="return isNumber(event)" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Address</label>
                                                                                <input type="text" name="address[]" id="address" class="form-control" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>State</label>
                                                                                <select class="js-example-basic-single" name="iStateId[]" id="iStateId` + x + `"  style="width: 100%;" onchange="getCity(0);">
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($statemasters as $state)
                                                                                    <option value="{{ $state->iStateId }}">{{ $state->strStateName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>City</label>
                                                                                <select class="js-example-basic-single" name="iCityId[]" id="iCityId` + x + `" style="width: 100%;" >
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($citymasters as $cities)
                                                                                    <option value="{{ $cities->iCityId }}">{{ $cities->strCityName }} </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Branch Office</label>
                                                                                <input type="text" name="branchOffice[]" id="branchOffice" class="form-control" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Company Profile</label>
                                                                                <select class="js-example-basic-single" name="iCompanyClientProfileId[]" id="iCompanyClientProfileId" style="width: 100%;">
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($CompanyClientProfile as $ClientProfile)
                                                                                    <option value="{{ $ClientProfile->iCompanyClientProfileId }}">{{ $ClientProfile->strCompanyClientProfile }} </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                        <div class="col-md-offset-8 col-lg-4 col-md-12">
                                                            <button type="button" class="btn delete-option pull-right deleteDep" onclick="deleteDep(` + x + `);" title="Delete Option">
                                                                <i class="mas-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
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

    function deleteDep(parentVal) {
        $(".accordion" + parentVal).remove(); //Remove field html
        parentVal--; //Decrement field counter
    }

    function getCity(id) {
        var iStateId = $("#iStateId" + id).val();
        $.ajax({
            type: 'POST',
            url: "{{route('company.getCity')}}",
            data: {
                iStateId: iStateId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#iCityId" + id).html(response);
            }
        });
    }
</script>
@endsection
