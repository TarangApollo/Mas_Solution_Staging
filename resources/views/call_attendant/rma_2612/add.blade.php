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

                                    <form action="{{ route('rma.store') }}" method="post" enctype="multipart/form-data">
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
                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Complaint ID</label>
                                                                <select class="js-example-basic-single" style="width: 100%;"
                                                                    name="iComplaintId" id="iComplaintId">
                                                                    <option label="Please Select" value="">-- Select
                                                                        --</option>
                                                                    @foreach ($ticketList as $list)
                                                                        <option value="{{ $list->iTicketId }}">
                                                                            {{ $list->strTicketUniqueID }}</option>
                                                                    @endforeach
                                                                    <option value="0">Other</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->


                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>RMA Number</label>
                                                                <input type="text" name="iRMANumber" class="form-control"
                                                                    id="iRMANumber" value="{{ $iRMANumber }}" readonly />
                                                            </div>
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Company</label>
                                                                <input type="text" name="strCustomerCompany"
                                                                    class="form-control" id="strCustomerCompany"
                                                                    value="{{ old('strCustomerCompany') }}" readonly />
                                                            </div>
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Engineer</label>
                                                                <input type="text" class="form-control"
                                                                    name="strCustomerEngineer" id="strCustomerEngineer"
                                                                    value="{{ old('strCustomerEngineer') }}" readonly />
                                                            </div>
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Distributor</label>
                                                                <select name="strDistributorDropdown" id="strDistributor" class="js-example-basic-single"
                                                                    style="width: 100%;" disabled="disabled">
                                                                    <option label="Please Select" value="">-- Select --</option>
                                                                    @foreach ($distributorList as $list)
                                                                        <option value="{{ $list->iDistributorId }}">{{ $list->Name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="strDistributor" id="hiddenDistributor" value="">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Project Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="strProjectName" id="strProjectName"
                                                                    value="{{ old('strProjectName') }}" readonly />
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
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4">
                                                            <div class="form-group">
                                                                <label>Fault description</label>
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
                                                                    <option value="Yes" >Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4" id="strMaterialReceivedDateDiv">
                                                            <div class="form-group" id="basicExample">
                                                                <label>Material Received Date</label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="date1" name="strMaterialReceivedDate"
                                                                        placeholder="dd-mm-yyyy"
                                                                        value="{{ old('strMaterialReceivedDate') }}" />
                                                                    <span class="input-group-addon"><i
                                                                            class="mas-month-calender mas-105x"></i></span>
                                                                </div>
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
                                                                    <option value="In Progress" >In Progress
                                                                    </option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4" id="strTestingCompleteDateDiv"
                                                            style="display: none;">
                                                            <div class="form-group" id="basicExample">
                                                                <label>Testing Complete Date</label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="date2" name="strTestingCompleteDate"
                                                                        placeholder="15-11-2024" />
                                                                    <span class="input-group-addon"><i
                                                                            class="mas-month-calender mas-105x"></i></span>
                                                                </div>
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
                                                                    <option value="Yes" >Yes</option>
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
                                                                    <option value="Warranty" >Warranty</option>
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
                                                                <label>Material Received</label>
                                                                <select class="js-example-basic-single"
                                                                    name="strFactory_MaterialReceived"
                                                                    id="strFactory_MaterialReceived" style="width: 100%;">
                                                                    <option label="Please Select" value="">--
                                                                        Select
                                                                        --</option>
                                                                    <option value="Yes" >Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4"
                                                            id="strFactory_MaterialReceivedDateDiv">
                                                            <div class="form-group" id="basicExample">
                                                                <label>Material Received Date</label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="date7"
                                                                        name="strFactory_MaterialReceivedDate"
                                                                        placeholder="dd-mm-yyyy" />
                                                                    <span class="input-group-addon"><i
                                                                            class="mas-month-calender mas-105x"></i></span>
                                                                </div>
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
                                                                    <option value="In Progress" >In Progress
                                                                    </option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-lg-3 col-md-4"
                                                            id="strFactory_TestingCompleteDateDiv" style="display: none;">
                                                            <div class="form-group" id="basicExample">
                                                                <label>Testing Complete Date</label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="strFactory_TestingCompleteDate"
                                                                        name="strFactory_TestingCompleteDate"
                                                                        placeholder="dd-mm-yyyy" />
                                                                    <span class="input-group-addon">
                                                                        <i class="mas-month-calender mas-105x"></i>
                                                                    </span>
                                                                </div>
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
                                                                    <option value="No" >No</option>
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
                                                                <label>Material Dispatched Date</label>
                                                                <div class="input-group date datepicker"
                                                                    data-date-format="dd-mm-yyyy">
                                                                    <input class="form-control" type="text"
                                                                        id="date3" name="strMaterialDispatchedDate" />
                                                                    <span class="input-group-addon"><i
                                                                            class="mas-month-calender mas-105x"></i></span>
                                                                </div>
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

                <!--table start-->
                <div class="row d-flex justify-content-center my-5">
                    <div class="col-md-10">
                        <div class="fresh-table toolbar-color-orange">
                            <table id="fresh-table" class="table">
                                <thead>
                                    <th data-field="id">No</th>
                                    <th data-field="no" data-sortable="true">RMA ID</th>
                                    <th data-field="customer" data-sortable="true">Customer</th>
                                    <th data-field="distributor" data-sortable="true">Distributor</th>
                                    <th data-field="item-returned" data-sortable="true">Item</th>
                                    <th data-field="qty" data-sortable="true">Quantity</th>
                                    <th data-field="warranty" data-sortable="true">Warranty</th>
                                    <th data-field="approved" data-sortable="true">Approved</th>
                                    <th data-field="customer-status" data-sortable="true">Customer Status</th>
                                    <th data-field="status" data-sortable="true">HFI Status</th>
                                    <th data-field="tat" data-sortable="true">TAT Days</th>
                                    <th data-field="actions">Actions</th>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($rmaList as $list)
                                        <tr>
                                            <td>
                                                {{ $i + $rmaList->perPage() * ($rmaList->currentPage() - 1) }}
                                            </td>
                                            <td>{{ $list->iRMANumber ?? '-' }}</td>
                                            <td>{{ $list->strCustomerEngineer ?? '-' }}</td>
                                            <td>{{ $list->distributor_name ?? '-' }}</td>
                                            <td>{{ $list->strItem ?? '-' }}</td>
                                            <td>{{ $list->strQuantity ?? '-' }}</td>
                                            <td>{{ $list->strInwarranty ?? '-' }}</td>
                                            <td>{{ $list->strReplacementApproved ?? '-' }}</td>
                                            <td>{{ $list->strStatus ?? '-' }}</td>
                                            <td>-</td>
                                            @if (isset($list->strMaterialDispatchedDate) && isset($list->strRMARegistrationDate))
                                                @php
                                                    $registrationDate = Carbon::parse($list->strRMARegistrationDate);
                                                    $dispatchedDate = Carbon::parse($list->strMaterialDispatchedDate);
                                                    $daysDifference = $dispatchedDate->diffInDays($registrationDate);
                                                @endphp

                                                <td>{{ $daysDifference }} days</td>
                                            @else
                                                <td>-</td>
                                            @endif
                                            <td>
                                                <a href="{{ route('rma.rma_info', $list->rma_id) }}" title="Info"
                                                    class="table-action">
                                                    <i class="mas-info-circle"></i>
                                                </a>
                                                @if ($list->strStatus == 'Open')
                                                    <a href="{{ route('rma.additional_rma', $list->rma_id) }}"
                                                        title="Additional RMA" class="table-action">
                                                        <i class="mas-plus"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
    <script type="text/javascript">
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
                pageSize: 8,
                pageList: [8, 10, 25, 50, 100],

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
    </script>

    <script>
        // $("#iComplaintId").on("change", function(e) {

        //     $.ajax({
        //         type: 'POST',
        //         url: "{{ route('rma.get_details') }}",
        //         data: {
        //             iTicketId: this.value
        //         },
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             console.log(response);

        //             // Ensure the response contains ProjectName
        //             if (response) {
        //                 $("#strProjectName").val(response.ProjectName);
        //                 $("#strCustomerCompany").val(response.CustomerCompany)
        //                 $("#strCustomerEngineer").val(response.CustomerName);

        //                 if ($("#strDistributor option[value='" + response.iDistributorId + "']")
        //                     .length) {
        //                     $("#strDistributor").val(response.iDistributorId).trigger('change');
        //                 }

        //                 //$("#strDistributor").val(response.iDistributorId);
        //             } else {
        //                 $("#strProjectName").val("");
        //                 $("#strDistributor").val("");
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error("Error:", error);
        //             //alert("Failed to fetch ticket details.");

        //             $("#strProjectName").val("");
        //             $("#strDistributor").val("");
        //         },
        //     });

        // });
        
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

                        if ($("#strDistributor option[value='" + response.iDistributorId + "']").length) {
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

            if (getvalue == "No") {
                $("#strMaterialReceivedDateDiv").css("display", "none");
            } else if (getvalue == "Yes") {
                $("#strMaterialReceivedDateDiv").css("display", "block");
            } else {
                $("#strMaterialReceivedDateDiv").css("display", "none");
            }

        });

        $("#strTesting").on("change", function(e) {

            let getvalue = this.value;

            if (getvalue == "In Progress") {
                $("#strTestingCompleteDateDiv").css("display", "none");
            } else if (getvalue == "Done") {
                $("#strTestingCompleteDateDiv").css("display", "block");
            } else {
                $("#strTestingCompleteDateDiv").css("display", "none");
            }

        });

        $("#strFactory_MaterialReceived").on("change", function(e) {

            let getvalue = this.value;

            if (getvalue == "No") {
                $("#strFactory_MaterialReceivedDateDiv").css("display", "none");
            } else if (getvalue == "Yes") {
                $("#strFactory_MaterialReceivedDateDiv").css("display", "block");
            } else {
                $("#strFactory_MaterialReceivedDateDiv").css("display", "none");
            }

        });

        $("#strFactory_Testing").on("change", function(e) {

            let getvalue = this.value;

            if (getvalue == "In Progress") {
                $("#strFactory_TestingCompleteDateDiv").css("display", "none");
            } else if (getvalue == "Done") {
                $("#strFactory_TestingCompleteDateDiv").css("display", "block");
            } else {
                $("#strFactory_TestingCompleteDateDiv").css("display", "none");
            }

        });

        $("#strMaterialDispatched").on("change", function(e) {

            let getvalue = this.value;

            if (getvalue == "No") {
                $("#strMaterialDispatchedDateDiv").css("display", "none");
            } else if (getvalue == "Yes") {
                $("#strMaterialDispatchedDateDiv").css("display", "block");
            } else {
                $("#strMaterialDispatchedDateDiv").css("display", "none");
            }

        });
    </script>
    <script>
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
@endsection
