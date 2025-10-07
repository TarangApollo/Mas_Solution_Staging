@extends('layouts.admin')

@section('title', 'Add New Company')

@section('content')
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
                                        <form class="was-validated pb-3" action="" method="post">
                                            <input type="hidden" name="company_id" id="company_id" value="{{ $id }}">
                                            <input type="hidden" name="save" id="save" value="0">
                                            <div class="field_wrapper">
                                                <div class="accordion" id="accordionExample">
                                                    @if($components->isEmpty())
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
                                                                        <!--/.col-->

                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Component</label>
                                                                                <input type="text" name="strComponent[]" id="strComponent" class="form-control" required="">
                                                                            </div>
                                                                        </div>
                                                                        <!--/.col-->

                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Wish to add Sub
                                                                                    Component?</label>
                                                                                <select class="js-example-basic-single" name="IsSubComponent[]" id="IsSubComponent" style="width: 100%;" required="" onchange="changeIsSubComponent(0);">
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
                                                                            </div> <!-- /.form-group -->
                                                                        </div>
                                                                        <!--/.col-->
                                                                        <div class="col-md-offset-8 col-lg-4 col-md-12">
                                                                            <button type="button" class="btn delete-option pull-right deleteDep" title="Delete Option">
                                                                                <i class="mas-trash"></i>
                                                                            </button>
                                                                            <!--/.delete icon-->
                                                                        </div>
                                                                        <!--/.col-->
                                                                    </div>
                                                                    <!--/.row-->
                                                                    <!--sub Component list div start-->
                                                                    <div class="col-md-12" id="divSubComponents" style="display: none;">
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
                                                                                                    <input type="hidden" name="iSubComponentId[]" value="0">
                                                                                                    <input type="text" name="strSubComponent[]" id="strSubComponent" class="form-control">
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
                                                    @else
                                                    <?php $Counter = 1; ?>
                                                    @foreach($components as $component)
                                                    <div class="accordion-item delete-option">
                                                        <h2 class="accordion-header" id="heading0000<?= $Counter ?>">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne0000<?= $Counter ?>" aria-expanded="true" aria-controls="collapseOne">
                                                                <span class="drag-icon pull-left">
                                                                    <i class="mas-arrow-move"></i>
                                                                </span>
                                                                {{ $component->strComponent }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne0000<?= $Counter ?>" class="accordion-collapse collapse show" aria-labelledby="headingheading0000<?= $Counter ?>" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">

                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>System</label>
                                                                                <input type="text" name="strSystem[]" id="strSystem" value="{{ $component->strSystem ?? '' }}" class="form-control" required="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Component</label>
                                                                                <input type="text" class="form-control" name="strComponent[]" id="strComponent" value="{{ $component->strComponent ?? '' }}" required="">
                                                                            </div>
                                                                        </div>
                                                                        <!--/.col-->

                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Wish to add Sub
                                                                                    Component?</label>
                                                                                <select class="js-example-basic-single" name="IsSubComponent[]" id="IsSubComponent_<?= $component->iComponentId; ?>" style="width: 100%;" required="" onchange="changeIsSubComponent(<?= $component->iComponentId; ?>);">
                                                                                    <option label="Please Select" value="">--
                                                                                        Select
                                                                                        --</option>
                                                                                    <option value="1" @if($component->IsSubComponent == 1) {{ 'selected' }} @endif>
                                                                                        Yes
                                                                                    </option>
                                                                                    <option value="2" @if($component->IsSubComponent == 2) {{ 'selected' }} @endif>
                                                                                        No
                                                                                    </option>
                                                                                </select>
                                                                            </div> <!-- /.form-group -->
                                                                        </div>
                                                                        <!--/.col-->
                                                                        <div class="col-md-offset-8 col-lg-4 col-md-12">
                                                                            <button type="button" class="btn delete-option pull-right deleteDep" title="Delete Option">
                                                                                <i class="mas-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                        <!--/.col-->
                                                                    </div>
                                                                    <!--/.row-->

                                                                    <!--sub Component list div start-->
                                                                    @if(!$subcomponents->isEmpty())
                                                                    <div class="col-md-12" id="divSubComponents<?= $component->iComponentId ?>">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Add Sub Components
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php $jCounter = 1; ?>
                                                                                    @foreach($subcomponents as $subcomponent)
                                                                                    @if($subcomponent->iComponentId == $component->iComponentId)
                                                                                    @if($jCounter == 1)
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="col-md-9 field_wrapperNew">
                                                                                                <div class="form-group d-flex">
                                                                                                <input type="hidden" name="iSubComponentId[]" value="{{ $subcomponent->iSubComponentId }}">
                                                                                                    <input type="text" name="strSubComponent[]" id="strSubComponent" value="{{ $subcomponent->strSubComponent }}" class="form-control">
                                                                                                    <a href="javascript:void(0);" class="btn btn-success add_button_new add-more" title="Add Option"><i class="mas-plus-circle lh-normal"></i></a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    @else
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="col-md-9 field_wrapperNew">
                                                                                                <div class="form-group d-flex">
                                                                                                    <input type="hidden" name="iSubComponentId[]" value="{{ $subcomponent->iSubComponentId }}">
                                                                                                    <input type="text" name="strSubComponent[]" id="strSubComponent" value="{{ $subcomponent->strSubComponent }}" class="form-control">
                                                                                                    <a href="javascript:void(0);" class="btn btn-danger remove_buttonNew pull-right add-more" title="Remove Option"><i class="mas-minus-circle lh-normal"></i></a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    @endif
                                                                                    <?php $jCounter++; ?>
                                                                                    @endif
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                    <!--/.col-md-12 sub Componentlist-->
                                                                </div>
                                                                <!--new option-->
                                                            </div>
                                                            <!--accordion body-->
                                                        </div>
                                                        <!--/.collapse ONE-->
                                                    </div>
                                                    <?php $Counter++; ?>
                                                    @endforeach
                                                    @endif
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
                                                        <button type="button" class="btn btn-success text-uppercase mt-4" id="savesubmit">
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
<!-- add more inputs -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">

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
                                                        <div id="collapse` + x + `" class="accordion-collapse collapse show" aria-labelledby="heading` + x + `e" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">
                                                                        <div class="row">
                                                                            <div class="col-lg-4 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>System</label>
                                                                                    <input type="text" name="strSystem[]" id="strSystem" class="form-control" required="">
                                                                                </div>
                                                                            </div>
                                                                            <!--/.col-->

                                                                            <div class="col-lg-4 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>Component</label>
                                                                                    <input type="text" name="strComponent[]" id="strComponent" class="form-control" required="">
                                                                                </div>
                                                                            </div>
                                                                            <!--/.col-->

                                                                            <div class="col-lg-4 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>Wish to add Sub
                                                                                        Component?</label>
                                                                                    <select class="js-example-basic-single" name="IsSubComponent[]" id="IsSubComponent_`+x+`" style="width: 100%;" required="" onchange="changeIsSubComponent(`+x+`);">
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
                                                                                </div> <!-- /.form-group -->
                                                                            </div>
                                                                            <!--/.col-->
                                                                            <div class="col-md-offset-8 col-lg-4 col-md-12">
                                                                                <button type="button" class="btn delete-option pull-right deleteDep" title="Delete Option">
                                                                                    <i class="mas-trash"></i>
                                                                                </button>
                                                                                <!--/.delete icon-->
                                                                            </div>
                                                                            <!--/.col-->
                                                                        </div>
                                                                        <!--/.row-->
                                                                    <!--sub Component list div start-->
                                                                    <div class="col-md-12" id="divSubComponents`+x+`" style="display: none;">
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
                                                                                                <input type="hidden" name="iSubComponentId[]" value="0">
                                                                                                <input type="text" class="form-control" name="strSubComponent[]" value=""/>
                                                                                                <a href="javascript:void(0);" class="btn btn-danger remove_buttonNew`+x+` pull-right add-more" title="Remove Option"><i class="mas-minus-circle lh-normal"></i>
                                                                                                </a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <!--/.col-md-12 sub Componentlist-->
                                                                </div>
                                                                <!--new option-->
                                                            </div>
                                                            <!--accordion body-->
                                                        </div>
                                                        <!--/.collapse ONE-->
                                                    </div></div>`;
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

    function changeIsSubComponent(id) {
        if (id == 0) {
            var IsSubComponent = $("#IsSubComponent").val();
            if (IsSubComponent == 1) {
                $("#divSubComponents").show();
            } else {
                $("#divSubComponents").hide();
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
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button_new'); //Add button selector
        var wrapper = $('.field_wrapperNew'); //Input field wrapper

        var x = 1; //Initial field counter is 1

        //Once add button is clicked
        $(addButton).click(function() {
            //Check maximum number of input fields
            if (x < maxField) {
                var fieldHTML =
                `<div class="form-group d-flex">
                    <input type="hidden" name="iSubComponentId[]" value="0">
                    <input type="text" class="form-control" name="strSubComponent[]" value=""/>
                    <a href="javascript:void(0);" class="btn btn-danger remove_buttonNew`+x+` pull-right add-more" title="Remove Option"><i class="mas-minus-circle lh-normal"></i>
                    </a>
                    </div>`; //New input field html
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            }
        });

        //Once remove button is clicked
        $(wrapper).on('click', '.remove_buttonNew'+x, function(e) {
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
</script>
@endsection
