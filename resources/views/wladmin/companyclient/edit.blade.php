@extends('layouts.wladmin')

@section('title', 'Add New Call Attendant')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Edit Customer Company</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Customer Company</li>
                <li class="breadcrumb-item active"> Edit Customer Company </li>
            </ol>
        </nav>
    </div>
    @include('wladmin.wlcommon.alert')
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
                                                    Basic Information
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
                                                        <a href="{{ route('companyclient.salesedit',$CompanyClients->iCompanyClientId) }}" >Sales Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('companyclient.technicaledit',$CompanyClients->iCompanyClientId) }}" >Technical Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('companyclient.userdefinededit',$CompanyClients->iCompanyClientId) }}" >User Defined</a>
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
                                        <h3 class="tab-content-title">Basic Information</h3>
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{route('companyclient.update')}}" method="post">
                                            <input type="hidden" name="iCompanyClientId" id="iCompanyClientId" value="{{ $CompanyClients->iCompanyClientId }}">
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
                                                                {{ $CompanyClients->CompanyName }}
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
                                                                                <label>Company Name*</label>
                                                                                <input type="text" name="CompanyName" id="CompanyName" value="{{ $CompanyClients->CompanyName }}" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Email ID*</label>
                                                                                <input type="email" name="email" id="email" class="form-control" value="{{ $CompanyClients->email }}" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Owner</label>
                                                                                <input type="text" name="owner" id="owner" class="form-control" value="{{ $CompanyClients->owner }}" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Owner Email</label>
                                                                                <input type="email" name="owneremail" id="owneremail" class="form-control" value="{{ $CompanyClients->owneremail }}"  >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Owner phone</label>
                                                                                <input type="text" name="ownerphone" id="ownerphone" class="form-control" value="{{ $CompanyClients->ownerphone }}"   onkeypress="return isNumber(event)" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Address*</label>
                                                                                <input type="text" name="address" id="address" class="form-control" value="{{ $CompanyClients->address }}" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>State</label>
                                                                                <select class="js-example-basic-single" name="iStateId" id="iStateId" style="width: 100%;"  onchange="getCity();">
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($statemasters as $state)
                                                                                    <option value="{{ $state->iStateId }}" @if($CompanyClients->iStateId == $state->iStateId) {{ 'selected' }} @endif>{{ $state->strStateName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>City</label>
                                                                                <select class="js-example-basic-single" name="iCityId" id="iCityId" style="width: 100%;" >
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($citymasters as $cities)
                                                                                    <option value="{{ $cities->iCityId }}" @if($CompanyClients->iCityId == $cities->iCityId) {{ 'selected' }} @endif> {{ $cities->strCityName }} </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Branch Office</label>
                                                                                <input type="text" name="branchOffice" id="branchOffice" class="form-control" value="{{ $CompanyClients->branchOffice }}"  >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Company Profile</label>
                                                                                <select class="js-example-basic-single" name="iCompanyClientProfileId" id="iCompanyClientProfileId" style="width: 100%;">
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($CompanyClientProfile as $ClientProfile)
                                                                                    <option value="{{ $ClientProfile->iCompanyClientProfileId }}" @if($CompanyClients->iCompanyClientProfileId == $ClientProfile->iCompanyClientProfileId) {{ 'selected' }} @endif>{{ $ClientProfile->strCompanyClientProfile }} </option>
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
<!--END notify messages-->


@endsection

@section('script')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
     $('#submit').on("click", function() {
        $('#save').val('1');
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: "{{route('companyclient.update')}}",
            data: $('#frmparameter').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                if (response > 0) {
                    $('#loading').css("display", "none");
                    $('#save').val('0');
                    var url = "{{route('companyclient.edit',':id')}}";
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

    function isNumber(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)  && charCode != 43)
            return false;
        return true;
    }
    $(document).ready(function(){
        getCity();
    });
    function getCity() {
        var iStateId = $("#iStateId").val();
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
                $("#iCityId").html(response);
                $("#iCityId").val(<?= $CompanyClients->iCityId ?>);
            }
        });
    }
</script>
@endsection
