@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
@include('wladmin.wlcommon.alert')
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

    <!--/. page header ends-->

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
                                                Customer Company Information
                                                </a>
                                            </h4>
                                        </div>

                                        <div id="role_information" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <ul class="accordion-tab nav nav-tabs">
                                                    <!-- <li>
                                                        <a href="{{ route('companyclient.create') }}">Company Profile</a>
                                                    </li> -->
                                                    <li>
                                                        <a href="{{ route('companyclient.edit',$id) }}">Basic Information</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('companyclient.salesedit',$id) }}">Sales Person</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('companyclient.technicaledit',$id) }}">Technical Person</a>
                                                    </li>
                                                    <li class="active">
                                                        <a href="#user-defined">User Defined</a>
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
                                        <h3 class="tab-content-title">Add Users</h3>
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{ route('companyclient.userdefinedstore') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="company_id" id="company_id" value="{{ $CompanyMaster->iCompanyId }}">
                                            <input type="hidden" name="companyclient_id" id="companyclient_id" value="{{ $id }}">
                                            @if($userdefined)
                                            <input type="hidden" name="iUserDefineId" id="iUserDefineId" value="{{$userdefined->iUserDefineId }}">
                                            @else
                                            <input type="hidden" name="iUserDefineId" id="iUserDefineId" value="0">
                                            @endif
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
                                                                New user
                                                            </button>
                                                        </h2>
                                                        <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>User Defined 1</label>
                                                                                <input type="text" name="userdefine1" id="userdefine1" class="form-control" value="{{($userdefined)?$userdefined->userDefine1:''}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>User Defined 2</label>
                                                                                <input type="text" name="userdefine2" id="userdefine2" class="form-control" value="{{($userdefined)?$userdefined->userDefine2:''}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>User Defined 3 </label>
                                                                                <input type="type" name="userdefine3" id="userdefine3" class="form-control" value="{{($userdefined)?$userdefined->userDefine3:''}}">
                                                                            </div> <!-- /.form-group -->
                                                                        </div> <!-- /.col -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($id!=0)
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
                                            @endif
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
            url: "{{route('companyclient.userdefinedstore')}}",
            data: $('#frmparameter').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                if (response > 0) {
                    $('#loading').css("display", "none");
                    $('#save').val('0');
                    window.location.href = "";
                    return true;
                } else {
                    $('#loading').css("display", "none");
                    return false;
                }
            }
        });
    });
</script>

@endsection
