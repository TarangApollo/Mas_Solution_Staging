@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Add New Role</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Role</li>
                    <li class="breadcrumb-item"><a href="{{route('role.index')}}">Role List</a></li>
                    <li class="breadcrumb-item active"> Add New Role </li>
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
                                                        Role Information
                                                    </a>
                                                </h4>
                                            </div>

                                            <div id="role_information" class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                    <ul class="accordion-tab nav nav-tabs">
                                                        <li class="active" id="generalLi">
                                                            <a onClick="generalLia();" href="{{ route('role.edit',$Data->id) }}#general" @if (request()->routeIs('role.edit',$Data->id)) {{ 'data-toggle="tab" '}} @endif >General</a>
                                                        </li>
                                                        <li id="permissionsLi">
                                                            <a href="{{ route('role.edit',$Data->id) }}#permissions" @if (request()->routeIs('role.edit',$Data->id)) {{ 'data-toggle="tab" aria-expanded="true"' }} @endif >Permissions</a>
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
                                        <form class="was-validated pb-3" action="{{route('role.update')}}" method="post" name="frmparameter" id="frmparameter">
                                            <input type="hidden" name="iRoleId" id="iRoleId" value="{{ $Data->id }}" />
                                            <input type="hidden" name="save" id="save" value="0">
                                            @csrf

                                            <div class="tab-pane fade in active" id="general">
                                                <h3 class="tab-content-title">General</h3>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Role Name*</label>
                                                            <input type="text" class="form-control" id="role_name" name="role_name" value="{{$Data->name}}" required>
                                                            <span id="errstrOEMCompanyName" class="text-danger"></span>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="row">
                                                        <div class="col-md-4 d-flex">
                                                            <button type="button" href="#permissions" class="btn btn-success text-uppercase mt-4 mr-2" id="next" data-toggle="tab" aria-expanded="true" onclick="nextData();">
                                                                Next
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!--role details form starts-->
                                            <div class="tab-pane fade" id="permissions">
                                                <h3 class="tab-content-title">Permissions</h3>

                                                <div class="row permission-head">
                                                    <div class="col-md-6">
                                                        <h3>Attribute</h3>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default" id="allowList">Allow
                                                                all</button>
                                                            <button type="button" class="btn btn-default" id="denyList">Deny
                                                                all</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/. row-->
                                                @foreach ($permissionArray as $permission)

                                                <!--Component start-->
                                                <div class="mb-4">
                                                    <div class="row permission-head">
                                                        <div class="col-md-6">
                                                            <h4>Attribute - {{$permission['name']}}</h4>
                                                        </div>
                                                        <!-- <div class="col-md-6">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default">Allow
                                                                    all</button>
                                                                <button type="button" class="btn btn-default">Deny
                                                                    all</button>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                    <!--/. row-->
                                                    @if(array_key_exists('submenu',$permission))
                                                    @foreach ($permission['submenu'] as $submenulist)
                                                    <input type="hidden" name="permissionId[]" id="permissionId[]" value="{{$submenulist['id']}}" />
                                                    <input type="hidden" name="{{$submenulist['id']}}_menu" id="{{$submenulist['id']}}_menu" value="{{$submenulist['menu_id']}}" />
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="permission-label">{{$submenulist['name']}}</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="btn-group d-flex ml-4">
                                                                <div class="form-check mr-4">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="{{$submenulist['id']}}_1" id="optionsRadios1" value="1" required {{ (in_array($submenulist['id'],$menuArray)) ? 'checked':''}}> Allow <i class="input-helper"></i></label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="{{$submenulist['id']}}_1" id="optionsRadios2" value="0" required {{ (!in_array($submenulist['id'],$menuArray)) ? 'checked':''}}> Deny <i class="input-helper"></i></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach

                                                    @endif
                                                    <!--/. row-->
                                                </div>
                                                <!--/. Component END-->
                                                @endforeach

                                                <!--buttons start-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <button type="button" class="btn btn-default text-uppercase mt-4" id="add-new-option" onClick="showhide()">
                                                            Clear
                                                        </button>
                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-success text-uppercase mt-4 mr-2" value="submit" name="submit" id="submit">
                                                            Save
                                                        </button>
                                                        <button type="submit" class="btn btn-success text-uppercase mt-4">
                                                            Save & Exit
                                                        </button>
                                                    </div>
                                                </div>
                                                <!--/.buttons end-->

                                            </div>
                                            <!--/.role details form end-->
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
        <!--row-->
        <!--notify messages-->

        <!--END notify messages-->
    </div>
    <!-- content-wrapper ends -->

    <!--/. footer ends-->

<!-- main-panel ends -->


<!--toggle button active/inactive-->
<script src="../global/assets/vendors/toggle-button/bootstrap4-toggle.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function generalLia() {

        var url = "{{route('role.edit',':id')}}";
        url = url.replace(':id',  $("#iRoleId").val());
        url = url.replace('?', '/');
        window.location.href = url;

    }
    $(document).ready(function() {
        $("#role_name").focus();
        $("#general").css("display", "block");
        $("#general").attr("class", 'active');
        $("#generalLi").attr("class", 'active');

        $("#permissions").css("display", "none");
        $("#permissionsLi").removeAttr("class");
        $("#permissions").removeAttr("class");
    });
    $("#role_name").on("blur", function() {
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

    function nextData() {

        var tStatus = false;
        var strOEMCompanyName = $("#role_name").val();

        if (strOEMCompanyName != "" && strOEMCompanyName != null) {
            tStatus = true;
            $("#generalLi").removeAttr("class");
            $("#general").css("display", "none");
            $("#permissions").css("display", "block");
            $("#permissions").attr("class", 'active');
            $("#permissionsLi").attr("class", 'active');
            return tStatus;
        } else {
            $("#errstrOEMCompanyName").html("Please enter role name");

            return tStatus = false;

        }


    }
    $('#submit').on("click", function() {
        $('#save').val('1');
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: "{{route('role.update')}}",
            data: $('#frmparameter').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                console.log(response);
                if (response > 0) {
                    $('#loading').css("display", "none");
                    $('#save').val('0');
                    var url = "{{route('role.edit',':id')}}";
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
    $('#allowList').on("click", function() {
        const elements = document.querySelectorAll('[data-id="1"]');

        for (let i = 0; i < elements.length; i++) {
            elements[i].checked = true;
        }
        const elements1 = document.querySelectorAll('[data-id="2"]');

        for (let i = 0; i < elements1.length; i++) {
            elements1[i].checked = false;
        }
    });

    $('#denyList').on("click", function() {
        const elements = document.querySelectorAll('[data-id="2"]');
        for (let i = 0; i < elements.length; i++) {
            elements[i].checked = true;
        }
        const elements1 = document.querySelectorAll('[data-id="1"]');
        for (let i = 0; i < elements1.length; i++) {
            elements1[i].checked = false;
        }
    });
</script>

@endsection
