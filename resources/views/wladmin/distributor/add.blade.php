@extends('layouts.wladmin')

@section('title', 'Add New Distributor')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Add New Distributor</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Distributors</li>
                <li class="breadcrumb-item active"> Add New Distributor </li>
            </ol>
        </nav>
    </div>
    <!--/. page header ends-->
    {{-- <div class="row">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" class="btn btn-success text-uppercase pt-1 mb-3 mr-2">
                <i class="mas-upload btn-icon"></i>
                Upload Distributor List
            </button>
        </div>
    </div> --}}
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
                                                        <a href="{{ route('distributor.salescreate',$id) }}">Sales Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('distributor.technicalcreate',$id) }}">Technical Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('distributor.userdefinedcreate',$id) }}">User Defined</a>
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

                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{route('distributor.store')}}" method="post">
                                            <input type="hidden" name="save" id="save" value="0">
                                            <input type="hidden" name="iCompanyId" id="iCompanyId" value="{{ $CompanyMaster->iCompanyId }}">

                                            @csrf
                                            <div class="field_wrapper">
                                                <div class="accordion" id="accordionExample">
                                                    <div id="add-option" class="accordion-item">
                                                        <h2 class="accordion-header" id="heading0">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse0" aria-expanded="true" aria-controls="collapse0">
                                                                <span class="drag-icon pull-left">
                                                                    <i class="mas-arrow-move"></i>
                                                                </span>
                                                                New Option
                                                            </button>
                                                        </h2>
                                                        <div id="collapse0" class="accordion-collapse collapse show" aria-labelledby="heading0" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Distributor Name*</label>
                                                                                <input type="text" name="Name[]" id="Name" class="form-control" required="" value="{{$id!=0? $Distributor->Name:''}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Distributor Email ID*</label>
                                                                                <input type="email" name="EmailId[]" id="EmailId" class="form-control" required="" value="{{$id!=0? $Distributor->EmailId:''}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Distributor Phone</label>
                                                                                <input type="text" name="distributorPhone[]" id="distributorPhone" class="form-control"  onkeypress="return isNumber(event)" value="{{$id!=0? $Distributor->distributorPhone:''}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Address*</label>
                                                                                <input type="text" name="Address[]" id="Address" class="form-control" value="{{$id!=0? $Distributor->Address:''}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>State*</label>
                                                                                <select class="js-example-basic-single" style="width: 100%;" name="iStateId[]" id="iStateId0" onchange="getCity(0);">
                                                                                    <option label="Please Select" value="">
                                                                                        --
                                                                                        Select --</option>
                                                                                    @foreach($statemasters as $state)
                                                                                    <option value="{{ $state->iStateId }}" {{($id!=0 && $Distributor->iStateId == $state->iStateId )? 'selected':''}}>{{ $state->strStateName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>City*</label>
                                                                                <select class="js-example-basic-single" style="width: 100%;" name="iCityId[]" id="iCityId0">
                                                                                    <option label="Please Select" value="">
                                                                                        --
                                                                                        Select --</option>
                                                                                    @foreach($citymasters as $cities)
                                                                                    <option value="{{ $cities->iCityId }}" {{( $id!=0 && $Distributor->iCityId == $cities->iCityId)? 'selected':''}}>{{ $cities->strCityName }} </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Branch Office</label>
                                                                                <input type="text" name="branchOffice[]" id="branchOffice" class="form-control" value="{{$id!=0? $Distributor->branchOffice:''}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Company Profile</label>
                                                                                <select class="js-example-basic-single" name="iCompanyClientProfileId[]" id="iCompanyClientProfileId" style="width: 100%;">
                                                                                    <option label="Please Select" value="">-- Select --
                                                                                    </option>
                                                                                    @foreach($CompanyClientProfile as $ClientProfile)
                                                                                    <option value="{{ $ClientProfile->iCompanyClientProfileId }}" {{($id!=0 && $Distributor->iProfileId == $ClientProfile->iCompanyClientProfileId )? 'selected':''}}>{{ $ClientProfile->strCompanyClientProfile }} </option>
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

                                                <div class="col-md-6">
                                                    <!-- <button type="button" class="btn btn-default text-uppercase mt-4 add_button" id="add-new-option">
                                                        Add New User
                                                    </button> -->
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-end">
                                                    <button type="button" class="btn btn-success text-uppercase mt-4 mr-2" name="submit" id="submit">
                                                        Save
                                                    </button>
                                                    <button type="submit" class="btn btn-success text-uppercase mt-4" id="savesubmit">
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


</div>
@endsection
@section('script')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function isNumber(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43)
            return false;
        return true;
    }

    $('#submit').on("click", function() {
        $('#save').val('1');
        var isValid = validateForm();
        if (isValid == true) {
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: "{{route('distributor.store')}}",
                data: $('#frmparameter').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#loading').css("display", "none");
                    console.log(response);
                    if (response > 0) {

                        var url = "{{route('distributor.create',':id')}}";
                        url = url.replace(':id', response);
                        url = url.replace('?', '/');

                        window.location.href = url;

                        return true;
                    } else {

                        return false;
                    }
                }
            });
        }
    });

    function validateForm() {

        var isValid = true;

        return isValid;
    }


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
                    <h2 class="accordion-header" id="heading` + x + `">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse` + x + `" aria-expanded="true" aria-controls="collapse` + x + `">
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
                                                                                <label>Distributor Name*</label>
                                                                                <input type="text" name="Name[]" id="Name" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Distributor Email ID*</label>
                                                                                <input type="email" name="EmailId[]" id="EmailId" class="form-control" required="">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Distributor Phone</label>
                                                                                <input type="text" name="distributorPhone[]" id="distributorPhone" class="form-control"  onkeypress="return isNumber(event)">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Address*</label>
                                                                                <input type="text" name="Address[]" id="Address" class="form-control" >
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>State*</label>
                                                                                <select class="js-example-basic-single" style="width: 100%;" name="iStateId[]" id="iStateId0" onchange="getCity(0);" >
                                                                                    <option label="Please Select" value="">
                                                                                        --
                                                                                        Select --</option>
                                                                                    @foreach($statemasters as $state)
                                                                                    <option value="{{ $state->iStateId }}">{{ $state->strStateName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>City*</label>
                                                                                <select class="js-example-basic-single" style="width: 100%;" name="iCityId[]" id="iCityId0" >
                                                                                    <option label="Please Select" value="">
                                                                                        --
                                                                                        Select --</option>
                                                                                    @foreach($citymasters as $cities)
                                                                                    <option value="{{ $cities->iCityId }}" >{{ $cities->strCityName }} </option>
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
