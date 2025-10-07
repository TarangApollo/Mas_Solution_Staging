@extends('layouts.wladmin')

@section('title', 'Add New Component')

@section('content')

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Add New Component</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">My Components</li>
                    <li class="breadcrumb-item active"> Add Component </li>
                </ol>
            </nav>
        </div>
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
                                                        Company Information
                                                    </a>
                                                </h4>
                                            </div>

                                            <div id="role_information" class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                    <ul class="accordion-tab nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#general" data-toggle="tab">General</a>
                                                        </li>
                                                        <li>
                                                            <a href="#permissions" data-toggle="tab"
                                                                aria-expanded="true">Advance Information</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('component.create') }}">Add Component /
                                                                Sub Component</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('component.resolution-category') }}">Resolution
                                                                Category</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('component.issue') }}">Issue Type</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('component.CallCompetency') }}">Call
                                                                Competency</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('component.Support-Type') }}">Support
                                                                Type</a>
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
                                        <div class="tab-pane fade in active" id="general">
                                            <h3 class="tab-content-title">General</h3>
                                            <form class="was-validated pb-3" action="" method="post">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>OEM Company Prefix*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->strCompanyPrefix }}"
                                                                disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>OEM Company Name*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->strOEMCompanyName }}"
                                                                disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>OEM Company ID*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->strOEMCompanyId }}"
                                                                disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                </div>
                                            </form>
                                        </div>
                                        <!--Advance information form start-->
                                        <div class="tab-pane fade" id="permissions">
                                            <h3 class="tab-content-title">Company Details</h3>
                                            <form class="was-validated pb-3" action="" method="post">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Contact Person*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->ContactPerson }}" disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Mail ID*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->EmailId }}" disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Contact No*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->ContactNo }}" disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Address 1*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->Address1 }}" disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Address 2*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->Address2 }}" disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Address 3</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->Address3 ?? '' }}"
                                                                disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Postcode*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->Pincode }}" disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Select State*</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                disabled="">
                                                                @foreach ($statemasters as $state)
                                                                    <option value="{{ $state->iStateId }}"
                                                                        @if (isset($CompanyMaster->iStateId) && $CompanyMaster->iStateId == $state->iStateId) {{ 'selected' }} @endif>
                                                                        {{ $state->strStateName }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Select City*</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                disabled="">
                                                                @foreach ($citymasters as $cities)
                                                                    <option value="{{ $cities->iCityId }}"
                                                                        @if (isset($CompanyMaster->iCityId) && $CompanyMaster->iCityId == $cities->iCityId) {{ 'selected' }} @endif>
                                                                        {{ $cities->strCityName }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>GST Number*</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $CompanyMaster->strGSTNo }}" disabled="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                </div>
                                            </form>
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
