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

                        <div class="col-lg-9 col-md-8">
                            <div class="accordion-box-content">
                                <div class="tab-content clearfix">
                                    <!--Component form starts-->
                                    <div class="tab-pane fade in active" id="add-component">
                                        <h3 class="tab-content-title">Add Component / Sub Component
                                        </h3>
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{ route('company.companycomponentstore') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="company_id" id="company_id" value="{{ $id }}">
                                            <input type="hidden" name="save" id="save" value="0">
                                            <div class="field_wrapper">
                                                <div class="accordion" id="accordionExample">
                                                    <div class="accordion-item delete-option">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                <span class="drag-icon pull-left">
                                                                    <i class="mas-arrow-move"></i>
                                                                </span>
                                                                New Option
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>System</label>
                                                                                <input type="text" name="strSystem[]" id="strSystem" class="form-control" required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Component</label>
                                                                                <input type="text" name="strComponent[]" id="strComponent" class="form-control" required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Wish to add Sub
                                                                                    Component?</label>
                                                                                <select class="js-example-basic-single" name="IsSubComponent[]" id="IsSubComponent_0" style="width: 100%;" required="" onchange="changeIsSubComponent(0);">
                                                                                    <option label="Please Select" value="">--
                                                                                        Select
                                                                                        --</option>
                                                                                    <option value="1">
                                                                                        Yes
                                                                                    </option>
                                                                                    <option value="2">
                                                                                        No
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-offset-8 col-lg-4 col-md-12">
                                                                            <!-- <button type="button" class="btn delete-option pull-right deleteDep" title="Delete Option">
                                                                                    <i class="mas-trash"></i>
                                                                                </button> -->
                                                                            <!--/.delete icon-->
                                                                        </div>
                                                                        <!--/.col-->
                                                                    </div>
                                                                    <div class="col-md-12" id="divSubComponents0" style="display: none;">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Add Sub Components
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="col-md-9 field_wrapperNew">
                                                                                                <div class="form-group d-flex">
                                                                                                    <input type="text" class="form-control" name="strSubComponent[0][]" id="strSubComponent">
                                                                                                    <a href="javascript:void(0);" class="btn btn-success add_button_new add-more" title="Add Option"><i class="mas-plus-circle lh-normal"></i></a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-default text-uppercase mt-4" id="add-new-option">
                                                        Add New Option
                                                    </button>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
    function changeIsSubComponent(id) {

        if (id == 0) {
            var IsSubComponent = $("#IsSubComponent_0").val();
            if (IsSubComponent == 1) {
                $("#divSubComponents0").show();
            } else {
                $("#divSubComponents0").hide();
            }
        } else {
            var IsSubComponent = $("#IsSubComponent_" + id).val();
            if (IsSubComponent == 1) {
                $("#divSubComponents" + id).show();
            } else {
                $("#divSubComponents" + id).hide();
            }
        }
    }

    $(document).ready(function() {
        var maxField = 15; //Input fields increment limitation
        var addButton = $('.add_button_new'); //Add button selector
        var wrapper = $('.field_wrapperNew'); //Input field wrapper

        var x = 1; //Initial field counter is 1

        //Once add button is clicked
        $(addButton).click(function() {
            //Check maximum number of input fields
            if (x < maxField) {
                var fieldHTML =
                    `<div class="form-group d-flex">
                    <input type="text" class="form-control" name="strSubComponent[0][]" value=""/>
                    <a href="javascript:void(0);" class="btn btn-danger remove_buttonNew pull-right add-more" title="Remove Option"><i class="mas-minus-circle lh-normal"></i>
                    </a>
                </div>`; //New input field html
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            }
        });

        //Once remove button is clicked
        $(wrapper).on('click', '.remove_buttonNew', function(e) {
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });

    $(document).ready(function() {

        var maxField = 15; //Input fields increment limitation
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
                                                                            <div class="col-lg-4 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>System</label>
                                                                                    <input type="text" name="strSystem[]" id="strSystem" class="form-control" required="">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-4 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>Component</label>
                                                                                    <input type="text" name="strComponent[]" id="strComponent" class="form-control" required="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>Wish to add Sub
                                                                                        Component?</label>
                                                                                    <select class="js-example-basic-single" name="IsSubComponent[]" id="IsSubComponent_` + x + `" style="width: 100%;" required="" onchange="changeIsSubComponent(` + x + `);">
                                                                                        <option label="Please Select" value="">--
                                                                                            Select
                                                                                            --</option>
                                                                                        <option value="1">
                                                                                            Yes
                                                                                        </option>
                                                                                        <option value="2">
                                                                                            No
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-offset-8 col-lg-4 col-md-12">
                                                                                <button type="button" class="btn delete-option pull-right deleteDep" onclick="deleteDep(` + x + `);" title="Delete Option">
                                                                                    <i class="mas-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    <div class="col-md-12" id="divSubComponents` + x + `" style="display: none;">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Add Sub Components
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="col-md-9 field_wrapperNew` + x + `">
                                                                                                <div class="form-group d-flex">
                                                                                                    <input type="text" class="form-control" name="strSubComponent[` + x + `][]" id="strSubComponent">
                                                                                                    <a href="javascript:void(0);" class="btn btn-success add-more" onclick="add_button_new(` + x + `);" title="Add Option"><i class="mas-plus-circle lh-normal"></i></a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
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

    function add_button_new(parentVal) {
        var maxField = 10;
        var wrapper = $('.field_wrapperNew' + parentVal);
        var x = 1;
        if (x < maxField) {
            var fieldHTML =
                `<div class="form-group d-flex" id="NewItem` + parentVal + `">
                <input type="text" class="form-control" name="strSubComponent[` + parentVal + `][]" value=""/>
                <a href="javascript:void(0);" class="btn btn-danger pull-right add-more" onclick="remove_buttonNew(` + parentVal + `,` + x + `)" title="Remove Option"><i class="mas-minus-circle lh-normal"></i>
                </a>
            </div>`; //New input field html
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    }

    function remove_buttonNew(parentVal, childVal, e) {
        $("#NewItem" + parentVal).remove();
        childVal--; //Decrement field counter
    }

    function deleteDep(parentVal) {
        $(".accordion" + parentVal).remove(); //Remove field html
        parentVal--; //Decrement field counter
    }

    $('#submit').on("click", function() {
        $('#save').val('1');
        // var isValid = validateForm();
        // if (isValid == true) {
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: "{{route('company.companycomponentstore')}}",
            data: $('#frmparameter').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");

                if (response > 0) {
                    $('#loading').css("display", "none");
                    $("#successalert").show();
                    $("#msgdata").html("<strong>Success !</strong> Company Component Created Successfully.");
                    $('#company_id').val(response);
                    var company_id = response;
                    $('#save').val('0');
                    var url = "{{route('company.componetcreate',':company_id')}}";
                    url = url.replace(':company_id', company_id);
                    url = url.replace('?', '/');

                    window.location.href = url;
                    return true;
                } else {
                    $("#erroralert").show();
                    $("#msgdata").html("<strong>Error !</strong> Somthing want wrong.");
                    $('#loading').css("display", "none");
                    return false;
                }
            }
        });
        // }
    });
</script>
@endsection
