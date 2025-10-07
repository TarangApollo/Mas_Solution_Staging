@extends('layouts.callAttendant')
@section('title', 'Add Rma')
@section('content')
@php
use Carbon\Carbon;
@endphp
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('global/assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/fonts/mas-solution/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/css/vendor.bundle.base.css') }}">

    <!--bootstrap table-->
    <link href="{{ asset('global/assets/vendors/bootstrap-table/css/fresh-bootstrap-table.css') }}" rel="stylesheet" />
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <!--date picker-->
    <link href="{{ asset('global/assets/vendors/date-picker/datepicker.css') }}" rel="stylesheet" type="text/css" />


    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex justify-content-center">
                    <div class="page-header flex-wrap">
                        <div class="header-right d-flex flex-wrap mt-sm-5 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <h3 class="m-0">Return Material Authorization</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        @include('call_attendant.callattendantcommon.alert')
                        <div class="card mt-4">
                            <div class="card-body p-0">
                                <h4 class="card-title mt-0">Add New RMA</h4>
                                <!-- accordion start -->
                                <div class="accordion" id="accordionExample">

                                    <form action="{{ route('rma.store') }}" method="post" enctype="multipart/form-data" id="rmaForm">
                                        @csrf

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    Add Information
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">


                                                    <div class="row">
                                                        <!--<div class="col-lg-3 col-md-4">-->
                                                        <!--    <div class="form-group">-->
                                                        <!--        <label>Select OEM Company</label>-->
                                                        <!--        <select class="js-example-basic-single" style="width: 100%;"-->
                                                        <!--            name="OemCompannyId" id="OemCompannyId">-->
                                                        <!--            <option label="Please Select" value="">-- Select-->
                                                        <!--                --</option>-->
                                                        <!--            @foreach ($CompanyMaster as $company)-->
                                                        <!--                @if (session()->has('CompanyId') && session('CompanyId') == $company->iCompanyId)-->
                                                        <!--                    <option value="{{ $company->iCompanyId }}">-->
                                                        <!--                        {{ $company->strOEMCompanyName }}</option>-->
                                                        <!--                @else-->
                                                        <!--                    <option value="{{ $company->iCompanyId }}"-->
                                                        <!--                        {{ isset($postarray['OemCompannyId']) && $postarray['OemCompannyId'] == $company->iCompanyId ? 'selected' : '' }}>-->
                                                        <!--                        {{ $company->strOEMCompanyName }}</option>-->
                                                        <!--                @endif-->
                                                        <!--            @endforeach-->
                                                        <!--        </select>-->
                                                        <!--    </div>-->
                                                        <!--</div>-->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Select OEM Company</label>
                                                                @if(count($CompanyMaster)>1)
                                                                <select class="js-example-basic-single" style="width: 100%;" name="OemCompannyId" id="OemCompannyId">
                                                                    <option label="Please Select" value="">-- Select --</option>
                                                                    @foreach ($CompanyMaster as $company)
                                                                        <option value="{{ $company->iCompanyId }}"
                                                                            {{ (session()->has('CompanyId') && session('CompanyId') == $company->iCompanyId) ? 'selected' : '' }}>
                                                                            {{ $company->strOEMCompanyName }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @else
                                                                <input type="hidden" name="OemCompannyId" id="OemCompanny_Id" />
                                                                <select class="js-example-basic-single" style="width: 100%;" name="Oem_CompannyId" id="OemCompannyId" disabled>
                                                                    <option label="Please Select" value="">-- Select --</option>
                                                                    @foreach ($CompanyMaster as $company)
                                                                        <option value="{{ $company->iCompanyId }}"
                                                                            {{ (session()->has('CompanyId') && session('CompanyId') == $company->iCompanyId) ? 'selected' : '' }}>
                                                                            {{ $company->strOEMCompanyName }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                               <label>Complaint ID<span>*</span></label>
                                                                <select class="js-example-basic-single" style="width: 100%;"
                                                                    name="iComplaintId" id="iComplaintId" required>
                                                                    <option label="Please Select" value="">-- Select
                                                                        --</option>
                                                                    {{-- @foreach ($ticketList as $list)
                                                                        <option value="{{ $list->iTicketId }}">
                                                                            {{ $list->strTicketUniqueID }}</option>
                                                                    @endforeach --}}
                                                                    <option value="0">Other</option>
                                                                </select>
                                                                <span id="complaintIdError" class="text-danger"
                                                                    style="display:none;">Complaint ID field is
                                                                    required.</span>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>RMA Number</label>
                                                                <input type="text" name="iRMANumber" class="form-control"
                                                                    id="iRMANumber" value="" readonly/>
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Company</label>
                                                                <input type="text" name="strCustomerCompany"
                                                                    class="form-control" id="strCustomerCompany"
                                                                    value="{{ old('strCustomerCompany') }}" readonly/>
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Engineer</label>
                                                                <input type="text" class="form-control"
                                                                    name="strCustomerEngineer" id="strCustomerEngineer"
                                                                    value="{{ old('strCustomerEngineer') }}" readonly/>
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Distributor<span>*</span></label>
                                                                <select name="strDistributorDropdown" id="strDistributor"
                                                                    class="js-example-basic-single" style="width: 100%;" disabled>
                                                                    <option label="Please Select" value="">-- Select
                                                                        --</option>
                                                                    @foreach ($distributorList as $list)
                                                                        <option value="{{ $list->iDistributorId }}">
                                                                            {{ $list->Name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span id="distributorError" class="text-danger"
                                                                    style="display:none;">Distributor field is
                                                                    required.</span>
                                                                <input type="hidden" name="strDistributor"
                                                                    id="hiddenDistributor" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Project Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="strProjectName" id="strProjectName"
                                                                    value="{{ old('strProjectName') }}" readonly/>
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group" id="basicExample">
                                                                <label>RMA Registration Date</label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        name="strRMARegistrationDate" id="date1"
                                                                        value="{{ old('strRMARegistrationDate') }}" />
                                                                    <span class="input-group-addon"><i
                                                                            class="mas-month-calender mas-105x"></i></span>
                                                                </div>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Item</label>
                                                                <input type="text" name="strItem" class="form-control"
                                                                    value="{{ old('strItem') }}" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Item Description</label>
                                                                <input type="text" name="strItemDescription"
                                                                    class="form-control"
                                                                    value="{{ old('strItemDescription') }}" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Serial No</label>
                                                                <input type="text" class="form-control"
                                                                    name="strSerialNo"
                                                                    value="{{ old('strSerialNo') }}" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Date Code</label>
                                                                <input type="text" name="strDateCode"
                                                                    class="form-control"
                                                                    value="{{ old('strDateCode') }}" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>In warranty</label>
                                                                <select name="strInwarranty"
                                                                    class="js-example-basic-single" style="width: 100%;">
                                                                    <option label="Please Select" value="">-- Select
                                                                        --</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Quantity</label>
                                                                <input type="text" name="strQuantity"
                                                                    class="form-control"
                                                                    value="{{ old('strQuantity') }}" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Select System</label>
                                                                <select name="strSelectSystem"
                                                                    class="js-example-basic-single" style="width: 100%;">
                                                                    <option label="Please Select" value="">-- Select
                                                                        --</option>
                                                                    @foreach ($systemList as $list)
                                                                        <option value={{ $list->iSystemId }}>
                                                                            {{ $list->strSystem }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Model Number</label>
                                                                <input type="text" class="form-control"
                                                                    name="model_number">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Fault Description</label>
                                                                <input name="strFaultdescription" type="text"
                                                                    class="form-control"
                                                                    value="{{ old('strFaultdescription') }}" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Facts</label>
                                                                <input type="text" name="strFacts"
                                                                    class="form-control" value="{{ old('strFacts') }}" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Additional Details</label>
                                                                <input type="text" name="strAdditionalDetails"
                                                                    class="form-control"
                                                                    value="{{ old('strAdditionalDetails') }}" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                    </div> <!-- /.row -->
                                                </div><!-- /. accordion body -->
                                            </div> <!-- /. collapse One -->
                                        </div> <!-- /. accordion-item -->

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    Testing & Approval
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Material Received</label>
                                                                <select name="strMaterialReceived"
                                                                    id="strMaterialReceived"
                                                                    class="js-example-basic-single" style="width: 100%;">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4" id="strMaterialReceivedDateDiv" style="display: none;">
                                                            <div class="form-group" id="basicExample">
                                                                <label>Material Received Date<span>*</span></label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="strMaterialReceivedDate"
                                                                        name="strMaterialReceivedDate"
                                                                        placeholder="DD-MM-YYYY"
                                                                        value="{{ old('strMaterialReceivedDate') }}" />
                                                                    <span class="input-group-addon"><i
                                                                            class="mas-month-calender mas-105x"></i></span>
                                                                </div>
                                                                 <span id="materialReceivedDateError" class="text-danger"
                                                                    style="display:none;">
                                                                    Material Received Date is required.
                                                                </span>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Testing</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="strTesting"
                                                                    id="strTesting">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Done">Done</option>
                                                                    <option value="In Progress">In Progress
                                                                    </option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4" id="strTestingCompleteDateDiv"
                                                            style="display: none;">
                                                            <div class="form-group" id="basicExample">
                                                                <label>Testing Complete Date<span>*</span></label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="strTestingCompleteDate"
                                                                        name="strTestingCompleteDate"
                                                                        placeholder="DD-MM-YYYY" />
                                                                    <span class="input-group-addon"><i
                                                                            class="mas-month-calender mas-105x"></i></span>
                                                                </div>
                                                                <span id="strTestingCompleteDateError" class="text-danger"
                                                                    style="display:none;">
                                                                    Testing Complete Date is required.
                                                                </span>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Testing Result</label>
                                                                <select class="js-example-basic-single"
                                                                    name="Testing_result" style="width: 100%;">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="No Fault Found">No Fault Found</option>
                                                                    <option value="Customer Liability">Customer Liability
                                                                    </option>
                                                                    <option value="Product Failure">Product Failure
                                                                    </option>
                                                                    <option value="Programming Issue">Programming Issue
                                                                    </option>
                                                                    <option value="Repair Locally">Repair Locally
                                                                    </option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Fault Covered in Warranty</label>
                                                                <select class="js-example-basic-single"
                                                                    name="strFaultCoveredinWarranty" style="width: 100%;">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Replacement Approved</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="strReplacementApproved">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Replacement Reason</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="strReplacementReason">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Warranty">Warranty</option>
                                                                    <option value="Sales Call">Sales Call</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Upload Images</label>
                                                                <input class="form-control py-6" type="file"
                                                                    name="strImages[]" id="formFileMultiple" multiple
                                                                    accept="image/jpeg,image/gif,image/png" />
                                                            </div>
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Upload Video</label>
                                                                <input class="form-control py-6" type="file"
                                                                    name="strVideos[]" id="formFileMultiple" multiple
                                                                    accept="video/*" />
                                                            </div>
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Upload Docs</label>
                                                                <input class="form-control py-6" type="file"
                                                                    name="strDocs[]" id="formFileMultiple" multiple
                                                                    accept=".pdf,.doc,.docx,.xlsx,.xls" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Comments</label>
                                                                <textarea class="form-control" rows="4" name="Testing_Comments"></textarea>
                                                            </div>
                                                        </div>

                                                    </div> <!-- /.row -->
                                                </div><!-- /. accordion body -->
                                            </div><!-- /. collapse Two -->
                                        </div><!-- /. accordion-item -->

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                    aria-expanded="false" aria-controls="collapseThree">
                                                    Factory
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse"
                                                aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Factory RMA No</label>
                                                                <input type="text" class="form-control"
                                                                    name="Factory_rma_no">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Material Received</label>
                                                                <select class="js-example-basic-single"
                                                                    name="strFactory_MaterialReceived"
                                                                    id="strFactory_MaterialReceived" style="width: 100%;">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4"
                                                            id="strFactory_MaterialReceivedDateDiv" style="display: none;">
                                                            <div class="form-group" id="basicExample">
                                                                <label>Material Received Date<span>*</span></label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="strFactory_MaterialReceivedDate"
                                                                        name="strFactory_MaterialReceivedDate"
                                                                        placeholder="DD-MM-YYYY" />
                                                                    <span class="input-group-addon"><i
                                                                            class="mas-month-calender mas-105x"></i></span>
                                                                </div>
                                                                 <span id="strFactory_MaterialReceivedDateError"
                                                                    class="text-danger" style="display:none;">
                                                                    Material Received Date is required.
                                                                </span>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Testing</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="strFactory_Testing"
                                                                    id="strFactory_Testing">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Done">Done</option>
                                                                    <option value="In Progress">In Progress
                                                                    </option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4"
                                                            id="strFactory_TestingCompleteDateDiv" style="display: none;">
                                                            <div class="form-group" id="basicExample">
                                                                <label>Testing Complete Date<span>*</span></label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="strFactory_TestingCompleteDate"
                                                                        name="strFactory_TestingCompleteDate"
                                                                        placeholder="DD-MM-YYYY" />
                                                                    <span class="input-group-addon">
                                                                        <i class="mas-month-calender mas-105x"></i>
                                                                    </span>
                                                                </div>
                                                                <span id="strFactory_TestingCompleteDateError"
                                                                    class="text-danger" style="display:none;">
                                                                    Testing Complete Date is required.
                                                                </span>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Fault Covered in Warranty</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;"
                                                                    name="strFactory_FaultCoveredinWarranty">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Replacement Approved</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;"
                                                                    name="strFactory_ReplacementApproved">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Replacement Reason</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;"
                                                                    name="strFactory_ReplacementReason">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Warranty">Warranty</option>
                                                                    <option value="Sales Call">Sales Call
                                                                    </option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>HFI Status</label>
                                                                <select class="js-example-basic-single"
                                                                    name="Factory_Status" id=""
                                                                    style="width: 100%;">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Open">Open</option>
                                                                    <option value="Closed">Closed</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Upload Docs</label>
                                                                <input class="form-control py-6" type="file"
                                                                    name="Factory_strDocs[]" id="formFileMultiple"
                                                                    multiple accept=".pdf,.doc,.docx,.xlsx,.xls" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Comments</label>
                                                                <textarea class="form-control" rows="4" name="Factory_Comments"></textarea>
                                                            </div>
                                                        </div>
                                                    </div> <!-- /.row -->
                                                </div><!-- /. accordion body -->
                                            </div><!-- /. collapse Three -->
                                        </div><!-- /. accordion-item -->

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingFour">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                    aria-expanded="false" aria-controls="collapseFour">
                                                    Customer Status
                                                </button>
                                            </h2>
                                            <div id="collapseFour" class="accordion-collapse collapse"
                                                aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Material Dispatched</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="strMaterialDispatched"
                                                                    id="strMaterialDispatched">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4" id="strMaterialDispatchedDateDiv"
                                                            style="display: none;">
                                                            <div class="form-group" id="basicExample">
                                                                <label>Material Dispatched Date<span>*</span></label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="strMaterialDispatchedDate"
                                                                        placeholder="DD-MM-YYYY"
                                                                        name="strMaterialDispatchedDate" />
                                                                    <span class="input-group-addon"><i
                                                                            class="mas-month-calender mas-105x"></i></span>
                                                                </div>
                                                                 <span id="strMaterialDispatchedDateError"
                                                                    class="text-danger" style="display:none;">
                                                                    Material Dispatched Date is required.
                                                                </span>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="strStatus">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Open">Open</option>
                                                                    <option value="Closed">Closed</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Comments</label>
                                                                <textarea class="form-control" rows="4" name="Cus_Comments"></textarea>
                                                            </div>
                                                        </div>
                                                    </div> <!-- /.row -->

                                                </div><!-- /. accordion body -->
                                            </div><!-- /. collapse Four -->
                                        </div><!-- /. accordion-item -->

                                        <div class="row p-4">
                                            <div class="col-md-12">
                                               <input type="submit"
                                                    class="btn btn-fill btn-success text-uppercase mt-3 mr-2"
                                                    value="Submit">
                                                <input type="button" class="btn btn-fill btn-default text-uppercase mt-3"
                                                    value="Clear">
                                            </div>
                                        </div>
                                    </form>
                                </div><!-- /. accordion -->
                            </div>
                        </div><!--card end-->
                    </div>
                </div><!-- end row -->

            
                <!--table end-->
                <!--notify messages-->
                <div class="d-flex justify-content-center">
                    <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">Note for Developer</button> -->
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Note for Developer</h5>
                                </div>
                                <div class="modal-body text-dark">
                                    &bull; Reopen ticket icon should be activate for closed status <br><br>
                                    &bull; Edit icon should be disable for closed status <br><br>
                                    &bull; All company data will be displayed in the table <br><br>
                                    &bull; Display data can be download in excel. <br><br>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--END modal-->
                </div>
                <!--END notify messages-->
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022 Mas
                            Solutions. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                                href="#">
                                Excellent Computers </a> </span>
                    </div>
                </div>
            </footer>
            <!-- partial -->

        </div>
        <!-- main-panel ends -->
    </div>

    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/custom.js') }}"></script>

    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>

    <!--form validation-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.validate.min.js') }}"></script>

    <!--date picker-->
    <script src="{{ asset('global/assets/vendors/date-picker/bootstrap-datepicker.js') }}"></script>

    <!--table plugin-->
    <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
    </script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#strMaterialReceivedDate').datepicker({
                autoclose: true, // Automatically close the picker after selection
                format: 'dd-mm-yyyy', // Date format
                clearBtn: true, // Add a button to clear the date
            }).val(''); // Clear the default value

            $('#strTestingCompleteDate').datepicker({
                autoclose: true, // Automatically close the picker after selection
                format: 'dd-mm-yyyy', // Date format
                clearBtn: true, // Add a button to clear the date
            }).val(''); // Clear the default value

            $('#strFactory_MaterialReceivedDate').datepicker({
                autoclose: true, // Automatically close the picker after selection
                format: 'dd-mm-yyyy', // Date format
                clearBtn: true, // Add a button to clear the date
            }).val(''); // Clear the default value

            $('#strFactory_TestingCompleteDate').datepicker({
                autoclose: true, // Automatically close the picker after selection
                format: 'dd-mm-yyyy', // Date format
                clearBtn: true, // Add a button to clear the date
            }).val(''); // Clear the default value

            $('#strMaterialDispatchedDate').datepicker({
                autoclose: true, // Automatically close the picker after selection
                format: 'dd-mm-yyyy', // Date format
                clearBtn: true, // Add a button to clear the date
            }).val(''); // Clear the default value
        });
        var $table = $('#fresh-table'),

            full_screen = false;

        $().ready(function() {
            $table.bootstrapTable({
                toolbar: ".toolbar",

                showDownload: true,
                showRefresh: true,
                search: true,
                showColumns: true,
                pagination: true,
                striped: true,
               pageSize: 100,
                pageList: [100, 200, 300, 400, 500],

                formatShowingRows: function(pageFrom, pageTo, totalRows) {
                    //do nothing here, we don't want to show the text "showing x of y from..."
                },
                formatRecordsPerPage: function(pageNumber) {
                    return pageNumber + " rows visible";
                },
                icons: {
                    download: 'mas-download',
                    refresh: 'mas-refresh',
                    toggle: 'fa fa-th-list',
                    columns: 'mas-columns',
                    detailOpen: 'fa fa-plus-circle',
                    detailClose: 'fa fa-minus-circle'
                }
            });

            $(window).resize(function() {
                $table.bootstrapTable('resetView');
            });

            window.operateEvents = {
                'click .like': function(e, value, row, index) {
                    alert('You click like icon, row: ' + JSON.stringify(row));
                    console.log(value, row, index);
                },
                'click .edit': function(e, value, row, index) {
                    alert('You click edit icon, row: ' + JSON.stringify(row));
                    console.log(value, row, index);
                },
                'click .remove': function(e, value, row, index) {
                    $table.bootstrapTable('remove', {
                        field: 'id',
                        values: [row.id]
                    });
                }
            };
        });
        
        $(function() {
            $('.mas-download').click(function(e) {
                var table = $("#fresh-table");
                if (table && table.length) {
                    $(table).table2excel({
                        exclude: ".noExl",
                        name: "Excel Document Name",
                        filename: "RMA_" + new Date().toISOString().replace(/[\-\:\.]/g,
                            "") + ".xls",
                        fileext: ".xls",
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true,
                        preserveColors: false
                    });
                }
            });
            $('.mas-refresh').click(function(e) {
                $('#fresh-table').bootstrapTable('resetSearch');
            });
        });
    </script>

    <script>
        $("#iComplaintId").on("change", function(e) {
            $.ajax({
                type: 'POST',
                url: "{{ route('rma.get_details') }}",
                data: {
                    iTicketId: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);

                    if (response) {
                        $("#strProjectName").val(response.ProjectName);
                        $("#strCustomerCompany").val(response.CustomerCompany);
                        $("#strCustomerEngineer").val(response.CustomerName);

                        if ($("#strDistributor option[value='" + response.iDistributorId + "']")
                            .length) {
                            $("#strDistributor").val(response.iDistributorId).trigger('change');
                        }

                        // Update hidden input with the selected value
                        $("#hiddenDistributor").val(response.iDistributorId);
                    } else {
                        $("#strProjectName").val("");
                        $("#strDistributor").val("");
                        $("#hiddenDistributor").val("");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);

                    // Clear the fields on error
                    $("#strProjectName").val("");
                    $("#strDistributor").val("");
                    $("#hiddenDistributor").val("");
                },
            });
        });
        $("#strDistributor").on("change", function() {
            $("#hiddenDistributor").val($(this).val());
        });


         $("#strMaterialReceived").on("change", function(e) {
            let getvalue = this.value;
            if (getvalue === "Yes") {
                $("#strMaterialReceivedDateDiv").css("display", "block");
                $("#strMaterialReceivedDate").attr("required", true);
            } else if (getvalue === "No") {
                $("#strMaterialReceivedDateDiv").css("display", "none");
                $("#strMaterialReceivedDate").removeAttr("required");
            } else {
                $("#strMaterialReceivedDateDiv").css("display", "none");
                $("#strMaterialReceivedDate").removeAttr("required");
            }
        });
        $(document).ready(function() {
            $("#strMaterialReceived").trigger("change");
        });

        $("#strTesting").on("change", function(e) {
            let getvalue = this.value;

            if (getvalue == "Done") {
                // Show the date field and make it required
                $("#strTestingCompleteDateDiv").css("display", "block");
                $("#strTestingCompleteDate").attr("required", true);
            } else if (getvalue == "In Progress") {
                // Hide the date field
                $("#strTestingCompleteDateDiv").css("display", "none");
                $("#strTestingCompleteDate").removeAttr("required");
            } else {
                // Hide the date field and remove required
                $("#strTestingCompleteDateDiv").css("display", "none");
                $("#strTestingCompleteDate").removeAttr("required");
            }
        });


        $("#strFactory_MaterialReceived").on("change", function(e) {
            let getvalue = this.value;

            if (getvalue == "Yes") {
                // Show the date field and make it required
                $("#strFactory_MaterialReceivedDateDiv").css("display", "block");
                $("#strFactory_MaterialReceivedDate").attr("required", true);
            } else if (getvalue == "No") {
                // Hide the date field and remove the required attribute
                $("#strFactory_MaterialReceivedDateDiv").css("display", "none");
                $("#strFactory_MaterialReceivedDate").removeAttr("required");
            } else {
                // Hide the date field and remove the required attribute
                $("#strFactory_MaterialReceivedDateDiv").css("display", "none");
                $("#strFactory_MaterialReceivedDate").removeAttr("required");
            }
        });

        $("#strFactory_Testing").on("change", function(e) {
            let getvalue = this.value;

            if (getvalue == "Done") {
                // Show the Testing Complete Date field and make it required
                $("#strFactory_TestingCompleteDateDiv").css("display", "block");
                $("#strFactory_TestingCompleteDate").attr("required", true);
            } else if (getvalue == "In Progress") {
                // Hide the Testing Complete Date field and remove the required attribute
                $("#strFactory_TestingCompleteDateDiv").css("display", "none");
                $("#strFactory_TestingCompleteDate").removeAttr("required");
            } else {
                // Hide the Testing Complete Date field and remove the required attribute
                $("#strFactory_TestingCompleteDateDiv").css("display", "none");
                $("#strFactory_TestingCompleteDate").removeAttr("required");
            }
        });

        $("#strMaterialDispatched").on("change", function(e) {
            let getvalue = this.value;

            if (getvalue == "Yes") {
                // Show the Material Dispatched Date field and make it required
                $("#strMaterialDispatchedDateDiv").css("display", "block");
                $("#strMaterialDispatchedDate").attr("required", true);
            } else if (getvalue == "No") {
                // Hide the Material Dispatched Date field and remove the required attribute
                $("#strMaterialDispatchedDateDiv").css("display", "none");
                $("#strMaterialDispatchedDate").removeAttr("required");
            } else {
                // Hide the Material Dispatched Date field and remove the required attribute
                $("#strMaterialDispatchedDateDiv").css("display", "none");
                $("#strMaterialDispatchedDate").removeAttr("required");
            }
        });
    </script>
    <script>
    /*$(document).ready(function() {
        $('#iComplaintId').on('change', function() {
            if ($(this).val() === '0') {
                $('#strCustomerCompany, #strCustomerEngineer, #strProjectName')
                    .removeAttr('readonly');
                    .prop('disabled', false);

                $('#strDistributor')
                    .prop('disabled', false)
                    .attr('required', true);
            } else {
                $('#iRMANumber, #strCustomerCompany, #strCustomerEngineer, #strProjectName')
                    .attr('readonly', true)
                    .prop('disabled', true);

                $('#strDistributor')
                    .prop('disabled', true)
                    .removeAttr('required');
            }
        });
    });*/
    $(document).ready(function() {
        $('#iComplaintId').on('change', function() {
            if ($(this).val() === '0') {
                // Remove readonly from input fields
                $('#iRMANumber, #strCustomerCompany, #strCustomerEngineer, #strProjectName')
                    .removeAttr('readonly');

                $('#strDistributor').prop('disabled', false);
            } else {
                // Add readonly to input fields
                $('#iRMANumber, #strCustomerCompany, #strCustomerEngineer, #strProjectName')
                    .attr('readonly', true);
                $('#strDistributor').prop('disabled', true);
            }
        });
    });
</script>

    <script>
        $("#OemCompannyId").change(function() {
            $("#CustomerEmailCompany").val('');
            $.ajax({
                type: 'POST',
                url: "{{ route('rma.get_Complaint_id') }}",
                data: {
                    iCompanyId: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    var ticketListhtml = response[0];
                    $("#iComplaintId").html(ticketListhtml);

                    var iRMANumber = response[1];
                    $("#iRMANumber").val(iRMANumber);

                }
            });
        });
    </script>
    <script>
        // Trigger AJAX request after page load if a value is already selected
        $(document).ready(function() {
            var selectedCompanyId = $("#OemCompannyId").val();

            if (selectedCompanyId) {
                $("#OemCompanny_Id").val(selectedCompanyId);
                // Trigger the change event to load the corresponding data
                $("#OemCompannyId").change();
            }
        });

        // Handle change event for the select dropdown
        $("#OemCompannyId").change(function() {
            // Clear the customer email field
            $("#CustomerEmailCompany").val('');

            // AJAX request to fetch complaint IDs and RMA number
            $.ajax({
                type: 'POST',
                url: "{{ route('rma.get_Complaint_id') }}",
                data: {
                    iCompanyId: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Assuming response[0] contains the ticket list HTML
                    var ticketListhtml = response[0];
                    $("#iComplaintId").html(ticketListhtml);

                    // Assuming response[1] contains the RMA number
                    var iRMANumber = response[1];
                    $("#iRMANumber").val(iRMANumber);
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[type="submit"]').addEventListener('click', function(event) {
                event.preventDefault();
                //1
                var complaintId = document.getElementById('iComplaintId').value;
                var distributorId = document.getElementById('strDistributor').value;
                //2
                var materialReceived = document.getElementById('strMaterialReceived').value;
                var materialReceivedDate = document.getElementById('strMaterialReceivedDate').value;
                //3
                var strTesting = document.getElementById('strTesting').value;
                var strTestingCompleteDate = document.getElementById('strTestingCompleteDate').value;
                //4
                var strFactoryMaterialReceived = document.getElementById('strFactory_MaterialReceived')
                    .value;
                var strFactoryMaterialReceivedDate = document.getElementById(
                    'strFactory_MaterialReceivedDate').value;
                //5
                var strFactoryTesting = document.getElementById('strFactory_Testing').value;
                var strFactoryTestingCompleteDate = document.getElementById(
                    'strFactory_TestingCompleteDate').value;
                //6
                var strMaterialDispatched = document.getElementById('strMaterialDispatched').value;
                var strMaterialDispatchedDate = document.getElementById('strMaterialDispatchedDate').value;


                var complaintIdError = document.getElementById('complaintIdError');
                var distributorError = document.getElementById('distributorError');
                var materialReceivedDateError = document.getElementById('materialReceivedDateError');
                var strTestingCompleteDateError = document.getElementById('strTestingCompleteDateError');
                var strFactoryMaterialReceivedDateError = document.getElementById(
                    'strFactory_MaterialReceivedDateError');
                var strFactoryTestingCompleteDateError = document.getElementById(
                    'strFactory_TestingCompleteDateError');
                var strMaterialDispatchedDateError = document.getElementById(
                    'strMaterialDispatchedDateError');

                var isValid = true;

                // Complaint ID validation
                if (complaintId === '') {
                    complaintIdError.style.display = 'block';
                    isValid = false;
                } else {
                    complaintIdError.style.display = 'none';
                }

                // Distributor validation (only required when Complaint ID is 'Other' (0))
                if (complaintId === '0' && distributorId === '') {
                    distributorError.style.display = 'block';
                    isValid = false;
                } else {
                    distributorError.style.display = 'none';
                }

                // Material Received Date validation (only required when Material Received = "Yes")
                if (materialReceived === 'Yes' && materialReceivedDate === '') {
                    materialReceivedDateError.style.display = 'block';
                    isValid = false;
                } else {
                    materialReceivedDateError.style.display = 'none';
                }
                //
                if (strTesting === 'Done' && strTestingCompleteDate === '') {
                    strTestingCompleteDateError.style.display = 'block';
                    isValid = false;
                } else {
                    strTestingCompleteDateError.style.display = 'none';
                }
                // Factory Material Received Date validation (only required when Material Received = 'Yes')
                if (strFactoryMaterialReceived === 'Yes' && strFactoryMaterialReceivedDate === '') {
                    strFactoryMaterialReceivedDateError.style.display = 'block';
                    isValid = false;
                } else {
                    strFactoryMaterialReceivedDateError.style.display = 'none';
                }
                // Validate Factory Testing Complete Date
                if (strFactoryTesting === 'Done' && strFactoryTestingCompleteDate === '') {
                    strFactoryTestingCompleteDateError.style.display = 'block';
                    isValid = false;
                } else {
                    strFactoryTestingCompleteDateError.style.display = 'none';
                }

                if (strMaterialDispatched === 'Yes' && strMaterialDispatchedDate === '') {
                    strMaterialDispatchedDateError.style.display = 'block';
                    isValid = false;
                } else {
                    strMaterialDispatchedDateError.style.display = 'none';
                }

                // Submit form if valid
                if (isValid) {
                    document.getElementById('rmaForm').submit();
                } else {
                    alert('Please fill in the required fields.');
                }
            });

            // Enable/Disable Distributor field based on Complaint ID selection
            document.getElementById('iComplaintId').addEventListener('change', function() {
                var distributorField = document.getElementById('strDistributor');
                var hiddenDistributor = document.getElementById('hiddenDistributor');

                if (this.value === '0') {
                    distributorField.removeAttribute('disabled');
                    hiddenDistributor.value = '';
                } else {
                    distributorField.setAttribute('disabled', 'disabled');
                    hiddenDistributor.value = '';
                }
            });

            // Show/Hide Material Received Date field based on Material Received selection
            document.getElementById('strMaterialReceived').addEventListener('change', function() {
                var materialReceivedDateDiv = document.getElementById('strMaterialReceivedDateDiv');
                var materialReceivedDateInput = document.getElementById('strMaterialReceivedDate');

                if (this.value === 'Yes') {
                    materialReceivedDateDiv.style.display = 'block';
                    materialReceivedDateInput.setAttribute('required', 'required');
                } else {
                    materialReceivedDateDiv.style.display = 'none';
                    materialReceivedDateInput.removeAttribute('required');
                    materialReceivedDateInput.value = '';
                    document.getElementById('materialReceivedDateError').style.display = 'none';
                }
            });

            document.getElementById('strTesting').addEventListener('change', function() {
                var strTestingCompleteDateDiv = document.getElementById('strTestingCompleteDateDiv');
                var strTestingCompleteDateInput = document.getElementById('strTestingCompleteDate');

                if (this.value === 'Done') {
                    strTestingCompleteDateDiv.style.display = 'block';
                    strTestingCompleteDateInput.setAttribute('required', 'required');
                } else {
                    strTestingCompleteDateDiv.style.display = 'none';
                    strTestingCompleteDateInput.removeAttribute('required');
                    strTestingCompleteDateInput.value = '';
                    document.getElementById('strTestingCompleteDateError').style.display = 'none';
                }
            });

            // Show/Hide Material Received Date field based on Material Received selection for Factory
            document.getElementById('strFactory_MaterialReceived').addEventListener('change', function() {
                var strFactoryMaterialReceivedDateDiv = document.getElementById(
                    'strFactory_MaterialReceivedDateDiv');
                var strFactoryMaterialReceivedDateInput = document.getElementById(
                    'strFactory_MaterialReceivedDate');

                if (this.value === 'Yes') {
                    strFactoryMaterialReceivedDateDiv.style.display = 'block';
                    strFactoryMaterialReceivedDateInput.setAttribute('required', 'required');
                } else {
                    strFactoryMaterialReceivedDateDiv.style.display = 'none';
                    strFactoryMaterialReceivedDateInput.removeAttribute('required');
                    strFactoryMaterialReceivedDateInput.value = '';
                    document.getElementById('strFactory_MaterialReceivedDateError').style.display = 'none';
                }
            });

            // Show/Hide Factory Testing Complete Date field based on Factory Testing selection
            document.getElementById('strFactory_Testing').addEventListener('change', function() {
                var strFactoryTestingCompleteDateDiv = document.getElementById(
                    'strFactory_TestingCompleteDateDiv');
                var strFactoryTestingCompleteDateInput = document.getElementById(
                    'strFactory_TestingCompleteDate');
                if (this.value === 'Done') {
                    strFactoryTestingCompleteDateDiv.style.display = 'block';
                    strFactoryTestingCompleteDateInput.setAttribute('required', 'required');
                } else {
                    strFactoryTestingCompleteDateDiv.style.display = 'none';
                    strFactoryTestingCompleteDateInput.removeAttribute('required');
                    strFactoryTestingCompleteDateInput.value = '';
                    document.getElementById('strFactory_TestingCompleteDateError').style.display = 'none';
                }
            });

            document.getElementById('strMaterialDispatched').addEventListener('change', function() {
                var strMaterialDispatchedDateDiv = document.getElementById('strMaterialDispatchedDateDiv');
                var strMaterialDispatchedDateInput = document.getElementById('strMaterialDispatchedDate');

                if (this.value === 'Yes') {
                    strMaterialDispatchedDateDiv.style.display = 'block';
                    strMaterialDispatchedDateInput.setAttribute('required', 'required');
                } else {
                    strMaterialDispatchedDateDiv.style.display = 'none';
                    strMaterialDispatchedDateInput.removeAttribute('required');
                    strMaterialDispatchedDateInput.value = '';
                    document.getElementById('strMaterialDispatchedDateError').style.display = 'none';
                }
            });

        });
    </script>

 {{-- start new code 20-02-2025 --}}
     <!-- multi-select  -->
     <script src="{{ asset('global/assets/vendors/multi-select/js/bootstrap-multiselect.js') }}"></script>
     <script src="{{ asset('global/assets/vendors/multi-select/js/main.js') }}"></script>

     <script>
         $(document).ready(function() {
             $('#submitCheckboxes').click(function(e) {
                 e.preventDefault();
                 var selectedCheckboxes = $('#multiple-checkboxes').val();
                 $.ajax({
                     url: "{{ route('rma.reorder_column') }}",
                     type: 'POST',
                     data: {
                         checkboxes: selectedCheckboxes,
                         _token: $('meta[name="csrf-token"]').attr('content')
                     },
                     success: function(response) {
                         window.location = "";
                     },
                     error: function(xhr, status, error) {
                         console.error(error);
                     }
                 });
             });
         });
     </script>
 {{-- End new code 20-02-2025 --}}
@endsection
