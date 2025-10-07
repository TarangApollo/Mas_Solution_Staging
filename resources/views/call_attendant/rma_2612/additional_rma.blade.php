@extends('layouts.callAttendant')

@section('title', 'Additional Rma ')

@section('content')

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
                                    <h3 class="m-0">Additional RMA</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @include('call_attendant.callattendantcommon.alert')

                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <div class="card mt-4">
                            <div class="card-body p-0">
                                <h4 class="card-title mt-0">RMA Number: {{ $rmaList->iRMANumber }}</h4>
                                <div class="container">
                                    <div class="row bg-orange bg-opacity-25 p-4">
                                        <div class="col-md-6 font-15">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2"><strong>RMA
                                                        Number:</strong></div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    <strong>{{ $rmaList->iRMANumber }}</strong>
                                                </div>
                                                 <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Complaint ID:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">{{ $rmaList->strTicketUniqueID == 0 ? 'Other' : $rmaList->strTicketUniqueID ?? '-' }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Customer Company:
                                                </div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">{{ $rmaList->strCustomerCompany ?? '-' }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Customer Engineer:
                                                </div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">{{ $rmaList->strCustomerEngineer ?? '-' }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Distributor:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $rmaList->distributor_name }}
                                                </div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Project Name:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $rmaList->strProjectName }}</div>
                                            </div>
                                        </div> <!-- /.col -->
                                    </div> <!-- /.row level 1 details -->

                                    <div class="row my-4">
                                        <h4 class="info-text"> Would you like to Add Additional RMA?</h4>
                                        <div class="col-sm-12 p-0">
                                            <div class="accordion" id="accordionExample">

                                                <form action="{{ route('rma.additional_rma_store') }}" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="rma_id" value="{{ $rmaList->rma_id }}">

                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                                aria-expanded="true" aria-controls="collapseOne">
                                                                Add Additional RMA Information
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">

                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group" id="basicExample">
                                                                            <label>RMA Registration Date</label>
                                                                            <div class="input-group date datepicker"
                                                                                data-date-format="dd-mm-yyyy">
                                                                                <input class="form-control" type="text"
                                                                                    id="date1"
                                                                                    name="strRMARegistrationDate"
                                                                                    value="21-04-2024" />
                                                                                <span class="input-group-addon"><i
                                                                                        class="mas-month-calender mas-105x"></i></span>
                                                                            </div>
                                                                        </div> <!-- /.form-group -->
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Item</label>
                                                                            <input type="text" class="form-control"
                                                                                name="strItem"
                                                                                value="{{ $rmaList->strItem }}" />
                                                                        </div>
                                                                    </div> <!-- /.col -->
                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Item Description</label>
                                                                            <input type="text" class="form-control"
                                                                                name="strItemDescription"
                                                                                value="{{ $rmaList->strItemDescription }}" />
                                                                        </div>
                                                                    </div> <!-- /.col -->
                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Serial No</label>
                                                                            <input type="text" class="form-control"
                                                                                name="strSerialNo"
                                                                                value="{{ $rmaList->strSerialNo }}" />
                                                                        </div>
                                                                    </div> <!-- /.col -->
                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Date Code</label>
                                                                            <input type="text" class="form-control"
                                                                                name="strDateCode"
                                                                                value="{{ $rmaList->strDateCode }}" />
                                                                        </div>
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>In warranty</label>
                                                                            <select name="strInwarranty"
                                                                                class="js-example-basic-single"
                                                                                style="width: 100%;">
                                                                                <option label="Please Select"
                                                                                    value="">-- Select
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
                                                                                value="{{ $rmaList->strQuantity }}" />
                                                                        </div>
                                                                    </div> <!-- /.col -->
                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Select System</label>
                                                                            <select name="strSelectSystem"
                                                                                class="js-example-basic-single"
                                                                                style="width: 100%;">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    -- Select
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
                                                                            <input name="strFaultdescription"
                                                                                type="text" class="form-control"
                                                                                value="{{ old('strFaultdescription') }}" />
                                                                        </div>
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Facts</label>
                                                                            <input type="text" name="strFacts"
                                                                                class="form-control"
                                                                                value="{{ old('strFacts') }}" />
                                                                        </div>
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Additional Details</label>
                                                                            <input type="text"
                                                                                name="strAdditionalDetails"
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
                                                            aria-labelledby="headingTwo"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">

                                                                <div class="row">

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Material Received</label>
                                                                            <select name="strMaterialReceived"
                                                                                id="strMaterialReceived"
                                                                                class="js-example-basic-single"
                                                                                style="width: 100%;">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
                                                                                    Select
                                                                                    --</option>
                                                                                <option value="Yes" selected>Yes
                                                                                </option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div> <!-- /.form-group -->
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4"
                                                                        id="strMaterialReceivedDateDiv">
                                                                        <div class="form-group" id="basicExample">
                                                                            <label>Material Received Date</label>
                                                                            <div class="input-group date datepicker"
                                                                                data-date-format="dd-mm-yyyy">
                                                                                <input class="form-control" type="text"
                                                                                    id="date1"
                                                                                    name="strMaterialReceivedDate"
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
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
                                                                                    Select
                                                                                    --</option>
                                                                                <option value="Done">Done</option>
                                                                                <option value="In Progress" selected>In
                                                                                    Progress
                                                                                </option>
                                                                            </select>
                                                                        </div> <!-- /.form-group -->
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4"
                                                                        id="strTestingCompleteDateDiv"
                                                                        style="display: none;">
                                                                        <div class="form-group" id="basicExample">
                                                                            <label>Testing Complete Date</label>
                                                                            <div class="input-group date datepicker"
                                                                                data-date-format="dd-mm-yyyy">
                                                                                <input class="form-control" type="text"
                                                                                    id="date2"
                                                                                    name="strTestingCompleteDate"
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
                                                                                name="strFaultCoveredinWarranty"
                                                                                style="width: 100%;">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
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
                                                                                name="strReplacementApproved">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
                                                                                    Select
                                                                                    --</option>
                                                                                <option value="Yes" selected>Yes
                                                                                </option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div> <!-- /.form-group -->
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Replacement Reason</label>
                                                                            <select class="js-example-basic-single"
                                                                                style="width: 100%;"
                                                                                name="strReplacementReason">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
                                                                                    Select
                                                                                    --</option>
                                                                                <option value="Warranty" selected>Warranty
                                                                                </option>
                                                                                <option value="Sales Call">Sales Call
                                                                                </option>
                                                                            </select>
                                                                        </div> <!-- /.form-group -->
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Upload Images</label>
                                                                            <input class="form-control py-6"
                                                                                type="file" name="strImages[]"
                                                                                id="formFileMultiple" multiple
                                                                                accept="image/jpeg,image/gif,image/png" />
                                                                        </div>
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Upload Video</label>
                                                                            <input class="form-control py-6"
                                                                                type="file" name="strVideos[]"
                                                                                id="formFileMultiple" multiple
                                                                                accept="video/*" />
                                                                        </div>
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Upload Docs</label>
                                                                            <input class="form-control py-6"
                                                                                type="file" name="strDocs[]"
                                                                                id="formFileMultiple" multiple
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
                                                            aria-labelledby="headingThree"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">

                                                                <div class="row">

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Material Received</label>
                                                                            <select class="js-example-basic-single"
                                                                                name="strFactory_MaterialReceived"
                                                                                id="strFactory_MaterialReceived"
                                                                                style="width: 100%;">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
                                                                                    Select
                                                                                    --</option>
                                                                                <option value="Yes" selected>Yes
                                                                                </option>
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
                                                                                style="width: 100%;"
                                                                                name="strFactory_Testing"
                                                                                id="strFactory_Testing">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
                                                                                    Select
                                                                                    --</option>
                                                                                <option value="Done">Done</option>
                                                                                <option value="In Progress" selected>In
                                                                                    Progress
                                                                                </option>
                                                                            </select>
                                                                        </div> <!-- /.form-group -->
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4"
                                                                        id="strFactory_TestingCompleteDateDiv"
                                                                        style="display: none;">
                                                                        <div class="form-group" id="basicExample">
                                                                            <label>Testing Complete Date</label>
                                                                            <div class="input-group date datepicker"
                                                                                data-date-format="dd-mm-yyyy">
                                                                                <input class="form-control" type="text"
                                                                                    id="strFactory_TestingCompleteDate"
                                                                                    name="strFactory_TestingCompleteDate"
                                                                                    placeholder="dd-mm-yyyy" />
                                                                                <span class="input-group-addon"><i
                                                                                        class="mas-month-calender mas-105x"></i></span>
                                                                            </div>
                                                                        </div> <!-- /.form-group -->
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Fault Covered in Warranty</label>
                                                                            <select class="js-example-basic-single"
                                                                                style="width: 100%;"
                                                                                name="strFactory_FaultCoveredinWarranty">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
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
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
                                                                                    Select
                                                                                    --</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No" selected>No</option>
                                                                            </select>
                                                                        </div> <!-- /.form-group -->
                                                                    </div> <!-- /.col -->
                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Replacement Reason</label>
                                                                            <select class="js-example-basic-single"
                                                                                style="width: 100%;"
                                                                                name="strFactory_ReplacementReason">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
                                                                                    Select
                                                                                    --</option>
                                                                                <option value="Warranty">Warranty</option>
                                                                                <option value="Sales Call" selected>Sales
                                                                                    Call
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
                                                            aria-labelledby="headingFour"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">

                                                                <div class="row">

                                                                    <div class="col-lg-3 col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Material Dispatched</label>
                                                                            <select class="js-example-basic-single"
                                                                                style="width: 100%;"
                                                                                name="strMaterialDispatched"
                                                                                id="strMaterialDispatched">
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
                                                                                    Select
                                                                                    --</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div> <!-- /.form-group -->
                                                                    </div> <!-- /.col -->

                                                                    <div class="col-lg-3 col-md-4"
                                                                        id="strMaterialDispatchedDateDiv"
                                                                        style="display: none;">
                                                                        <div class="form-group" id="basicExample">
                                                                            <label>Material Dispatched Date</label>
                                                                            <div class="input-group date datepicker"
                                                                                data-date-format="dd-mm-yyyy">
                                                                                <input class="form-control" type="text"
                                                                                    id="date3"
                                                                                    name="strMaterialDispatchedDate" />
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
                                                                                <option label="Please Select"
                                                                                    value="">
                                                                                    --
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
                                                            <input type="button"
                                                                class="btn btn-fill btn-default text-uppercase mt-3"
                                                                value="Clear">
                                                        </div>
                                                    </div>

                                                </form>

                                            </div><!-- /. accordion -->
                                        </div>
                                    </div> <!-- /.row -->
                                </div><!--container end-->
                            </div>
                        </div><!--card end-->
                    </div>
                </div><!-- end row -->
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022
                            Mas Solutions. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                                href="#"> Excellent Computers </a> </span>
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

                    // Ensure the response contains ProjectName
                    if (response) {
                        $("#strProjectName").val(response.ProjectName);
                        $("#strDistributor").val(response.iDistributorId);
                    } else {
                        $("#strProjectName").val();
                        $("#strDistributor").val();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert("Failed to fetch ticket details.");
                },
            });

        });
    </script>



    <script>
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



@endsection
