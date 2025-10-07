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
                                                    <li >
                                                        <a href="{{ route('companyclient.edit',$CompanyClientProfile->iCompanyClientId) }}">Basic Information</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('companyclient.salesedit',$CompanyClientProfile->iCompanyClientId) }}">Sales Person</a>
                                                    </li>
                                                    <li class="active">
                                                        <a href="#technical" data-toggle="tab">Technical Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('companyclient.userdefinededit',$CompanyClientProfile->iCompanyClientId) }}">User Defined</a>
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
                                        <h3 class="tab-content-title">Technical Person</h3>
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{route('companyclient.technicalupdate')}}" method="post">
                                            <input type="hidden" name="iCompanyClientId" id="iCompanyClientId" value="{{ $CompanyClientProfile->iCompanyClientId}}">
                                            <input type="hidden" name="company_id" id="company_id" value="{{ $CompanyClientProfile->iCompanyId }}">
                                            <input type="hidden" name="save" id="save" value="0">
                                            @csrf
                                            <div class="field_wrapper">
                                                <div class="accordion" id="accordionExample">
                                                    {{! $icounter=1 }}
                                                    @if(count($salesPersonList))
                                                    @foreach ($salesPersonList as $salesList)


                                                    <div id="add-option" class="accordion-item">
                                                        <h2 class="accordion-header" id="heading{{$icounter}}">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$icounter}}" aria-expanded="true" aria-controls="collapse{{$icounter}}">
                                                                <span class="drag-icon pull-left">
                                                                    <i class="mas-arrow-move"></i>
                                                                </span>
                                                                {{ $salesList->technicalPersonName }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapse{{$icounter}}" class="accordion-collapse collapse" aria-labelledby="heading{{$icounter}}" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">



                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Name*</label>
                                                                                <input type="text" name="name[]" id="name_{{$icounter}}" class="form-control" required="" value="{{$salesList->technicalPersonName}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Phone*</label>
                                                                                <input type="text" name="phone[]" id="phone_{{$icounter}}" class="form-control" required onkeypress="return isNumber(event)" value="{{$salesList->technicalPersonNumber}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Email ID* </label>
                                                                                <input type="email" name="email[]" id="email_{{$icounter}}" class="form-control" required="" value="{{$salesList->technicalPersonEmail}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-offset-8 col-lg-4 col-md-12">
                                                                            <input type="hidden" name="iTechnicalId[]" id="salesId_{{$icounter}}" value="{{$salesList->iTechnicalId}}" />

                                                                            <button type="button" class="btn delete-option pull-right" title="Edit Option" onclick="updateSales('{{$icounter}}')">
                                                                                <i class="mas-edit"></i></button><!--/.edit icon-->
                                                                            <button type="button" class="btn delete-option pull-right" title="Delete Option" onclick="deletesales('{{$icounter}}')">
                                                                                <i class="mas-trash"></i></button><!--/.delete icon-->
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <!--new option-->
                                                            </div>
                                                            <!--accordion body-->
                                                        </div>
                                                    </div>
                                                    {{!$icounter++}}
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-default text-uppercase mt-4" id="add-new-option" onClick="showhide()">
                                                        Add New User
                                                    </button>
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
<!--END notify messages-->


@endsection

@section('script')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function isNumber(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)   && charCode != 43)
            return false;
        return true;
    }
    $('#submit').on("click", function() {
        $('#save').val('1');
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: "{{route('companyclient.technicalupdate')}}",
            data: $('#frmparameter').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                if (response > 0) {
                    $('#loading').css("display", "none");
                    $('#save').val('0');
                    var url = "{{route('companyclient.technicaledit',':id')}}";
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

    function deletesales(id) {
        var salesId = $('#salesId_' + id).val();
        var iCompanyClientId = $('#iCompanyClientId').val();
        var url = "{{route('companyclient.technicaldelete',':id')}}";
        url = url.replace(':id', salesId);
        url = url.replace('?', '/');
        $.ajax({
            type: 'DELETE',
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                if (response > 0) {
                    $('#loading').css("display", "none");
                    $('#save').val('0');
                    url = "{{route('companyclient.technicaledit',':id')}}";
                    url = url.replace(':id', iCompanyClientId);
                    url = url.replace('?', '/');
                    window.location.href = url;

                    return true;
                } else {
                    $('#loading').css("display", "none");
                    return false;
                }
            }
        });
    }

    function updateSales(id) {
        $('#loading').css("display", "block");
        var name = $('#name_' + id).val();
        var phone = $('#phone_' + id).val();
        var email = $('#email_' + id).val();
        var salesId = $('#salesId_' + id).val();
        var iCompanyClientId = $('#iCompanyClientId').val();
        $.ajax({
            type: 'POST',
            url: "{{route('companyclient.technicalupdate')}}",
            data: {
                "name": name,
                "phone": phone,
                "email": email,
                "salesId": salesId,
                "iCompanyClientId": iCompanyClientId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                if (response > 0) {
                    $('#loading').css("display", "none");
                    $('#save').val('0');
                    var url = "{{route('companyclient.technicaledit',':id')}}";
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
    }
    $(document).ready(function() {


        var addButton = $('#add-new-option'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper

        var x = '{{$icounter}}'; //Initial field counter is 1
        var maxField = x + 10; //Input fields increment limitation
        //Once add button is clicked
        $(addButton).click(function() {
            //Check maximum number of input fields
            if (x < maxField) {
                var fieldHTML = `<div class="accordion` + x + `" id="accordionExample">
                            <div class="accordion-item delete-option">
                                <h2 class="accordion-header" id="heading` + x + `">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse` + x + `" aria-expanded="true" aria-controls="collapse` + x + `">
                                        <span class="drag-icon pull-left">
                                            <i class="mas-arrow-move"></i>
                                        </span>
                                        New Technical Person
                                    </button>
                                </h2>
                                <div id="collapse` + x + `" class="accordion-collapse collapse show" aria-labelledby="heading` + x + `" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="new-option clearfix">
                                            <div class="row">
                                            <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Name*</label>
                                                                        <input type="text" name="name[]" id="name" class="form-control" required="">
                                                                    </div> <!-- /.form-group -->
                                                                </div> <!-- /.col -->
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Phone</label>
                                                                        <input type="text" name="phone[]" id="phone" class="form-control"  required onkeypress="return isNumber(event)" >
                                                                    </div> <!-- /.form-group -->
                                                                </div> <!-- /.col -->
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Email ID* </label>
                                                                        <input type="email" name="email[]" id="email" class="form-control" required="">
                                                                    </div> <!-- /.form-group -->
                                                                </div> <!-- /.col -->

                                                <div class="col-md-offset-8 col-lg-4 col-md-12">
                                                    <button type="button" class="btn delete-option pull-right deleteDep" onclick="deleteDep(` + x + `);" title="Delete Option">
                                                        <i class="mas-trash"></i>
                                                    </button>
                                                    <input type="hidden" name="iTechnicalId[]" id="iTechnicalId_` + x + `" value="0" />
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
</script>
@endsection
