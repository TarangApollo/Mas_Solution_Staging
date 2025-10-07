@extends('layouts.wladmin')

@section('title', 'Add New Call Attendant')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Edit Distributor</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Distributor</li>
                <li class="breadcrumb-item active"> Edit Distributor </li>
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
                                                    Distributor Information
                                                </a>
                                            </h4>
                                        </div>

                                        <div id="role_information" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <ul class="accordion-tab nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#account" data-toggle="tab">Basic Information</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('distributor.salesedit',$Distributor->iDistributorId) }}" >Sales Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('distributor.technicaledit',$Distributor->iDistributorId) }}" >Technical Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('distributor.userdefinededit',$Distributor->iDistributorId) }}">User Defined</a>
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
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{route('distributor.update')}}" method="post">
                                            <input type="hidden" name="iDistributorId" id="iDistributorId" value="{{ $Distributor->iDistributorId }}">
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
                                                                {{ $Distributor->Name }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapse0" class="accordion-collapse collapse show" aria-labelledby="heading0" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Name*</label>
                                                                                <input type="text" name="Name" id="Name" value="{{ $Distributor->Name }}"  class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Distributor Email ID*</label>
                                                                                <input type="email" name="EmailId" id="EmailId" class="form-control"  value="{{ $Distributor->EmailId }}" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Distributor Phone</label>
                                                                                <input type="text" name="distributorPhone" id="distributorPhone" class="form-control"  value="{{ $Distributor->distributorPhone }}" onkeypress="return isNumber(event)">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Address</label>
                                                                                <input type="text" name="Address" id="Address" value="{{ $Distributor->Address }}"  class="form-control" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>State</label>
                                                                                <select class="js-example-basic-single" style="width: 100%;" name="iStateId" id="iStateId"  onchange="getCity();" >
                                                                                    <option label="Please Select" value="">
                                                                                        --
                                                                                        Select --</option>
                                                                                    @foreach($statemasters as $state)
                                                                                    <option value="{{ $state->iStateId }}" @if($Distributor->iStateId == $state->iStateId) {{ 'selected' }} @endif>{{ $state->strStateName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>City</label>
                                                                                <select class="js-example-basic-single" style="width: 100%;" name="iCityId" id="iCityId"  >
                                                                                    <option label="Please Select" value="">
                                                                                        --
                                                                                        Select --</option>
                                                                                    @foreach($citymasters as $cities)
                                                                                    <option value="{{ $cities->iCityId }}" @if($Distributor->iCityId == $cities->iCityId) {{ 'selected' }} @endif>{{ $cities->strCityName }} </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Branch Office</label>
                                                                                <input type="text" name="branchOffice" id="branchOffice" class="form-control" value="{{ $Distributor->branchOffice }}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Company Profile</label>
                                                                                <select class="js-example-basic-single" name="iCompanyClientProfileId" id="iCompanyClientProfileId" style="width: 100%;">
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($CompanyClientProfile as $ClientProfile)
                                                                                    <option value="{{ $ClientProfile->iCompanyClientProfileId }}" @if($Distributor->iProfileId == $ClientProfile->iCompanyClientProfileId) {{ 'selected' }} @endif>{{ $ClientProfile->strCompanyClientProfile }} </option>
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
            url: "{{route('distributor.update')}}",
            data: $('#frmparameter').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                if (response > 0) {
                    $('#loading').css("display", "none");
                    $('#save').val('0');
                    var url = "{{route('distributor.edit',':id')}}";
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
    $(document).ready(function() {
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
                $("#iCityId").val(<?= $Distributor->iCityId ?>);
            }
        });
    }
</script>
@endsection
