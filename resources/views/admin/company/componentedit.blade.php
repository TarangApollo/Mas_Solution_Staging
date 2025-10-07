@extends('layouts.admin')

@section('title', 'Add New Company')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Edit Component / Sub Component</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Company</li>
                <li class="breadcrumb-item active"> Edit Component / Sub Component </li>
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
                                        <div id="role_information" class="panel-collapse collapse in">
    <div class="panel-body">
        <ul class="accordion-tab nav nav-tabs">
        <li class="@if (request()->routeIs('company.componentsedit')) {{ 'active' }} @endif">
                <a href="@if($components->iComponentId == 0) {{ '#' }} @else {{ route('company.componentsedit', $components->iComponentId) }} @endif">Edit
                    Component / Sub
                    Component</a>
            </li>
</ul>
</div></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8">
                            <div class="accordion-box-content">
                                <div class="tab-content clearfix">
                                    <!--Component form starts-->
                                    <div class="tab-pane fade in active" id="add-component">
                                        <h3 class="tab-content-title">Edit Component / Sub Component
                                        </h3>
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{ route('company.companycomponentupdate') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="company_id" id="company_id" value="{{ $CompanyMaster->iCompanyId }}">
                                            <input type="hidden" name="iComponentId" id="iComponentId" value="{{ $components->iComponentId }}">
                                            <div class="field_wrapper">
                                                <div class="accordion" id="accordionExample">
                                                    <div class="accordion-item delete-option">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                <span class="drag-icon pull-left">
                                                                    <i class="mas-arrow-move"></i>
                                                                </span>
                                                                {{ $CompanyMaster->strOEMCompanyName }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="new-option clearfix">
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>System</label>
                                                                                <input type="text" name="strSystem" id="strSystem" value="{{ $components->system }}" class="form-control" required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Component</label>
                                                                                <input type="text" name="strComponent" id="strComponent" class="form-control" value="{{ $components->strComponent }}" required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Wish to add Sub
                                                                                    Component?</label>
                                                                                <select class="js-example-basic-single" name="IsSubComponent" id="IsSubComponent" style="width: 100%;" required="" onchange="changeIsSubComponent();">
                                                                                    <option label="Please Select" value="">--
                                                                                        Select
                                                                                        --</option>
                                                                                    <option value="1" @if($components->IsSubComponent == 1) {{ 'selected' }} @endif>
                                                                                        Yes
                                                                                    </option>
                                                                                    <option value="2" @if($components->IsSubComponent == 2) {{ 'selected' }} @endif>
                                                                                        No
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-offset-8 col-lg-4 col-md-12">
                                                                        </div>
                                                                    </div>
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
                                                                                                <?php $iCounter = 0; ?>
                                                                                                @foreach($subcomponents as $subcomponent)
                                                                                                @if($iCounter == 0)
                                                                                                <div class="form-group d-flex">
                                                                                                    <input type="hidden" name="iSubComponentId[]" id="iSubComponentId" value="{{ $subcomponent->iSubComponentId }}">
                                                                                                    <input type="text" class="form-control" name="strSubComponent[]" id="strSubComponent" value="{{ $subcomponent->strSubComponent }}">
                                                                                                    <a href="javascript:void(0);" class="btn btn-success add_button add-more" title="Add Option"><i class="mas-plus-circle lh-normal"></i></a>
                                                                                                </div>
                                                                                                @else
                                                                                                <div class="form-group d-flex" id="NewItem">
                                                                                                    <input type="hidden" name="iSubComponentId[]" id="iSubComponentId" value="{{ $subcomponent->iSubComponentId }}">
                                                                                                    <input type="text" class="form-control" name="strSubComponent[]" id="strSubComponent" value="{{ $subcomponent->strSubComponent }}" />
                                                                                                    <a href="javascript:void(0);" class="btn btn-danger remove_button pull-right add-more" title="Remove Option"><i class="mas-minus-circle lh-normal"></i>
                                                                                                    </a>
                                                                                                </div>
                                                                                                @endif
                                                                                                <?php $iCounter++; ?>
                                                                                                @endforeach
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
                                                <div class="col-md-12 d-flex justify-content-end">
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
    $(document).ready(function() {
        changeIsSubComponent();
    });

    function changeIsSubComponent() {
        var IsSubComponent = $("#IsSubComponent").val();
        if (IsSubComponent == 1) {
            $("#divSubComponents").show();
        } else {
            $("#divSubComponents").hide();
        }
    }

    $(document).ready(function() {
        var maxField = 10;
        var addButton = $('.add_button');
        var wrapper = $('.field_wrapperNew');
        var fieldHTML =
            '<div class="form-group d-flex"><input type="hidden" name="iSubComponentId[]" id="iSubComponentId" value="0"><input type="text" class="form-control" name="strSubComponent[]" id="strSubComponent" value=""/><a href="javascript:void(0);" class="btn btn-danger remove_button pull-right add-more" title="Remove Option"><i class="mas-minus-circle lh-normal"></i></a></div>'; //New input field html
        var x = 1;

        $(addButton).click(function() {
            if (x < maxField) {
                x++;
                $(wrapper).append(fieldHTML);
            }
        });

        $(wrapper).on('click', '.remove_button', function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        });
    });
</script>
@endsection
