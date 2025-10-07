@extends('layouts.callAttendant')
@section('title', 'Add Rma')
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
                                    <h3 class="m-0">Return Details</h3>
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
                                <h4 class="card-title mt-0">RMA Details for: {{ $rmaList->iRMANumber }} </h4>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped" data-role="content" data-plugin="selectable"
                                                data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Complaint ID</td>
                                                        <td>{{ $rmaList->strTicketUniqueID == 0 ? 'Other' : $rmaList->strTicketUniqueID ?? '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Customer Company</td>
                                                        <td>{{ $rmaList->strCustomerCompany ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Customer Engineer</td>
                                                        <td>{{ $rmaList->strCustomerEngineer ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Distributor</td>
                                                        <td>{{ $rmaList->distributor_name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Project Name</td>
                                                        <td>{{ $rmaList->strProjectName ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!--/. responsive table 1 div-->
                                        <hr>
                                        <!-- other information table div-->
                                        <div class="table-responsive">
                                            <div class="row mx-1">
                                                <div class="col-md-6 col-xs-8">
                                                    <h4>Other Information</h4>
                                                    @if (isset($rmaList->first_name))
                                                        <p>Latest Updated by:
                                                            {{ $rmaList->first_name . ' ' . $rmaList->last_name ?? 'not updated' }}
                                                        </p>
                                                    @endif
                                                </div>
                                           
                                                    <div class="col-md-6 col-xs-4 text-right mt-2">
                                                        <button type="button" class="btn btn-success text-uppercase"
                                                            data-toggle="modal" data-target="#edit_{{ $rmaList->rma_id }}"
                                                            id="editinfo">
                                                            Edit Info
                                                        </button>
                                                    </div>
                                           
                                            </div>

                                            <!-- modal one edit info start -->
                                            <div class="modal fade bd-example-modal-lg" id="edit_{{ $rmaList->rma_id }}"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Other
                                                                Information for
                                                                {{ str_pad($rmaList->rma_id, 4, '0', STR_PAD_LEFT) }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form class="was-validated" action="{{ route('rma.rma_update') }}"
                                                            method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="rma_id" id="rma_id">
                                                            <div class="modal-body">
                                                                <div class="accordion" id="accordionExample">
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="headingOne">
                                                                            <button class="accordion-button" type="button"
                                                                                data-bs-toggle="collapse"
                                                                                data-bs-target="#collapseOne"
                                                                                aria-expanded="true"
                                                                                aria-controls="collapseOne">
                                                                                Other Information
                                                                            </button>
                                                                        </h2>
                                                                        <div id="collapseOne"
                                                                            class="accordion-collapse collapse show"
                                                                            aria-labelledby="headingOne"
                                                                            data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body">

                                                                                <div class="row">
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group"
                                                                                            id="basicExample1">
                                                                                            <label>RMA Registration
                                                                                                Date</label>
                                                                                            <div class="input-group date datepicker"
                                                                                                data-date-format="dd-mm-yyyy">
                                                                                                <input class="form-control"
                                                                                                    type="text"
                                                                                                    id="date1"
                                                                                                    name="strRMARegistrationDate"
                                                                                                    value="21-04-2024" />
                                                                                                <span
                                                                                                    class="input-group-addon"><i
                                                                                                        class="mas-month-calender mas-105x"></i></span>
                                                                                            </div>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Item</label>
                                                                                            <input type="text"
                                                                                                id="editstrItem"
                                                                                                class="form-control"
                                                                                                name="Item" />
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Item Description</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                value=""
                                                                                                name="strItemDescription"
                                                                                                id="editstrItemDescription" />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Serial No</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                name="strSerialNo"
                                                                                                id="editstrSerialNo"
                                                                                                value="" />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Date Code</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                name="strDateCode"
                                                                                                id="editstrDateCode" />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>In warranty</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                name="strInwarranty"
                                                                                                style="width: 100%;"id="EditstrInwarranty">

                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Quantity</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                value=""
                                                                                                name="strQuantity"
                                                                                                id="editstrQuantity" />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Select System</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                name="strSelectSystem"
                                                                                                id="editstrSelectSystem">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Model Number</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="editmodel_number"
                                                                                                name="model_number">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Fault Description</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                name="strFaultdescription"
                                                                                                id="ditstrFaultdescription" />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Facts</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                name="strFacts"
                                                                                                id="editstrFacts" />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Additional
                                                                                                Details</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                name="strAdditionalDetails"
                                                                                                id="editstrAdditionalDetails" />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->
                                                                                </div> <!-- /.row -->

                                                                            </div><!-- /. accordion body -->
                                                                        </div> <!-- /. collapse One -->
                                                                    </div> <!-- /. accordion-item -->

                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="headingTwo">
                                                                            <button class="accordion-button collapsed"
                                                                                type="button" data-bs-toggle="collapse"
                                                                                data-bs-target="#collapseTwo"
                                                                                aria-expanded="false"
                                                                                aria-controls="collapseTwo">
                                                                                Testing & Approval
                                                                            </button>
                                                                        </h2>
                                                                        <div id="collapseTwo"
                                                                            class="accordion-collapse collapse"
                                                                            aria-labelledby="headingTwo"
                                                                            data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body">

                                                                                <div class="row">
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Material Received</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                id="EditstrMaterialReceived"
                                                                                                class="form-control"
                                                                                                style="width: 100%;"
                                                                                                name="strMaterialReceived">
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-3 col-md-4"
                                                                                        id="strMaterialReceivedDateDiv"
                                                                                        style="display: none;">
                                                                                        <div class="form-group"
                                                                                            id="basicExample">
                                                                                            <label>Material Received
                                                                                                Date<span>*</span></label>
                                                                                            <div class="input-group date datepicker"
                                                                                                data-date-format="dd-mm-yyyy">
                                                                                                <input class="form-control"
                                                                                                    type="text"
                                                                                                    id="Material_Received_Date"
                                                                                                    name="Material_Received_Date"
                                                                                                    placeholder="DD-MM-YYYY" />
                                                                                                <span
                                                                                                    class="input-group-addon"><i
                                                                                                        class="mas-month-calender mas-105x"></i></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Testing</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                id="EditstrTesting"
                                                                                                class="form-control"
                                                                                                style="width: 100%;"
                                                                                                name="strTesting">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4"
                                                                                        id="strTestingCompleteDateDiv"
                                                                                        style="display: none;">
                                                                                        <div class="form-group"
                                                                                            id="basicExample">
                                                                                            <label>Testing Complete
                                                                                                Date<span>*</span></label>
                                                                                            <div class="input-group date datepicker"
                                                                                                data-date-format="dd-mm-yyyy">
                                                                                                <input class="form-control"
                                                                                                    type="text"
                                                                                                    id="strTestingCompleteDate"
                                                                                                    name="strTestingCompleteDate"
                                                                                                    placeholder="" />
                                                                                                <span
                                                                                                    class="input-group-addon"><i
                                                                                                        class="mas-month-calender mas-105x"></i></span>
                                                                                            </div>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Testing Result</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                name="Testing_result"
                                                                                                id="editTesting_result"
                                                                                                style="width: 100%;">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Fault Covered in
                                                                                                Warranty</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                name="strFaultCoveredinWarranty"
                                                                                                id="EditFault_Covereds"
                                                                                                style="width: 100%;">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Replacement
                                                                                                Approved</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                id="Replacement_Approveds"
                                                                                                name="strReplacementApproved">

                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Replacement
                                                                                                Reason</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                id="EditReplacement_Reasons"
                                                                                                name="strReplacementReason">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Upload Images</label>
                                                                                            <input
                                                                                                class="form-control py-6"
                                                                                                type="file"
                                                                                                name="strImages[]"
                                                                                                id="formFileMultiple"
                                                                                                multiple />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Upload Video</label>
                                                                                            <input
                                                                                                class="form-control py-6"
                                                                                                type="file"
                                                                                                name="strVideos[]"
                                                                                                id="formFileMultiple"
                                                                                                multiple />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Upload Docs</label>
                                                                                            <input
                                                                                                class="form-control py-6"
                                                                                                type="file"
                                                                                                name="strDocs[]"
                                                                                                id="formFileMultiple"
                                                                                                multiple />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Comments</label>
                                                                                            <textarea class="form-control" rows="4" name="Testing_Comments" id="editTesting_Comments"></textarea>
                                                                                        </div>
                                                                                    </div>

                                                                                </div> <!-- /.row -->

                                                                            </div><!-- /. accordion body -->
                                                                        </div><!-- /. collapse Two -->
                                                                    </div>

                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="headingThree">
                                                                            <button class="accordion-button collapsed"
                                                                                type="button" data-bs-toggle="collapse"
                                                                                data-bs-target="#collapseThree"
                                                                                aria-expanded="false"
                                                                                aria-controls="collapseThree">
                                                                                Factory
                                                                            </button>
                                                                        </h2>
                                                                        <div id="collapseThree"
                                                                            class="accordion-collapse collapse"
                                                                            aria-labelledby="headingThree"
                                                                            data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body">

                                                                                <div class="row">
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Factory RMA No</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                name="Factory_rma_no"
                                                                                                id="edit_Factory_rma_no">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Material Received</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                name="strFactory_MaterialReceived"
                                                                                                id="editMaterial_Received">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4"
                                                                                        id="strFactory_MaterialReceivedDateDiv"
                                                                                        style="display: none;">
                                                                                        <div class="form-group"
                                                                                            id="basicExample">
                                                                                            <label>Material Received
                                                                                                Date<span>*</span></label>
                                                                                            <div class="input-group date datepicker"
                                                                                                data-date-format="dd-mm-yyyy">
                                                                                                <input class="form-control"
                                                                                                    type="text"
                                                                                                    id="Factory_Material_Received_Date"
                                                                                                    name="Factory_Material_Received_Date"
                                                                                                    placeholder="DD-MM-YYYY" />
                                                                                                <span
                                                                                                    class="input-group-addon"><i
                                                                                                        class="mas-month-calender mas-105x"></i></span>
                                                                                            </div>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Testing</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                id="EditFactory_Testing"
                                                                                                name="strFactory_Testing">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4"
                                                                                        id="strFactory_TestingCompleteDateDiv"
                                                                                        style="display: none;">
                                                                                        <div class="form-group"
                                                                                            id="basicExample">
                                                                                            <label>Testing Complete
                                                                                                Date<span>*</span></label>
                                                                                            <div class="input-group date datepicker"
                                                                                                data-date-format="dd-mm-yyyy">
                                                                                                <input class="form-control"
                                                                                                    type="text"
                                                                                                    id="strFactory_TestingCompleteDate"
                                                                                                    name="strFactory_TestingCompleteDate"
                                                                                                    placeholder="DD-MM-YYYY" />
                                                                                                <span
                                                                                                    class="input-group-addon"><i
                                                                                                        class="mas-month-calender mas-105x"></i></span>
                                                                                            </div>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Fault Covered in
                                                                                                Warranty</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                id="EditFault_Covered_in_Warrantys"
                                                                                                name="strFactory_FaultCoveredinWarranty">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Replacement
                                                                                                Approved</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                id="EditFactory_Replacement_Approveds"
                                                                                                name="strFactory_ReplacementApproved">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Replacement
                                                                                                Reason</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                id="EditFactory_Replacement_Reasons"
                                                                                                name="strFactory_ReplacementReason">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>HFI Status</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                name="Factory_Status"
                                                                                                id="editFactory_Status"
                                                                                                style="width: 100%;">
                                                                                                </option>
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Upload Docs</label>
                                                                                            <input
                                                                                                class="form-control py-6"
                                                                                                type="file"
                                                                                                name="Factory_strDocs[]"
                                                                                                id="formFileMultiple"
                                                                                                multiple
                                                                                                accept=".pdf,.doc,.docx,.xlsx,.xls" />
                                                                                        </div>
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Comments</label>
                                                                                            <textarea class="form-control" rows="4" name="Factory_Comments" id="editFactory_Comments"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> <!-- /.row -->

                                                                            </div><!-- /. accordion body -->
                                                                        </div><!-- /. collapse Three -->
                                                                    </div><!-- /. accordion-item -->

                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="headingFour">
                                                                            <button class="accordion-button collapsed"
                                                                                type="button" data-bs-toggle="collapse"
                                                                                data-bs-target="#collapseFour"
                                                                                aria-expanded="false"
                                                                                aria-controls="collapseFour">
                                                                                Customer Status
                                                                            </button>
                                                                        </h2>
                                                                        <div id="collapseFour"
                                                                            class="accordion-collapse collapse"
                                                                            aria-labelledby="headingFour"
                                                                            data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body">

                                                                                <div class="row">
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Material
                                                                                                Dispatched</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                id="EditMaterial_Dispatched"
                                                                                                name="strMaterialDispatched">

                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4"
                                                                                        id="strMaterialDispatchedDateDiv"
                                                                                        style="display: none;">
                                                                                        <div class="form-group"
                                                                                            id="basicExample">
                                                                                            <label>Material Dispatched
                                                                                                Date<span>*</span></label>
                                                                                            <div class="input-group date datepicker"
                                                                                                data-date-format="dd-mm-yyyy">
                                                                                                <input class="form-control"
                                                                                                    type="text"
                                                                                                    id="strMaterialDispatchedDate"
                                                                                                    name="strMaterialDispatchedDate" placeholder="DD-MM-YYYY" />
                                                                                                <span
                                                                                                    class="input-group-addon"><i
                                                                                                        class="mas-month-calender mas-105x"></i></span>
                                                                                            </div>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->

                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Status</label>
                                                                                            <select
                                                                                                class="js-example-basic-single"
                                                                                                style="width: 100%;"
                                                                                                id="Editcus_Status_"
                                                                                                name="strStatus">
                                                                                            </select>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div> <!-- /.col -->
                                                                                    <div class="col-lg-3 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label>Comments</label>
                                                                                            <textarea class="form-control" rows="4" name="Cus_Comments" id="editCus_Comments"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> <!-- /.row -->

                                                                            </div><!-- /. accordion body -->
                                                                        </div><!-- /. collapse Four -->
                                                                    </div><!-- /. accordion-item -->

                                                                </div><!-- /. accordion -->
                                                            </div><!--main body-->
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                    class="btn btn-secondary">Cancle</button>
                                                                <button type="submit"
                                                                    class="btn btn-fill btn-success">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /. modal one edit info End -->

                                            <table class="table table-striped" data-role="content"
                                                data-plugin="selectable" data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>RMA Registration Date</td>
                                                        <td>
                                                            {{ $rmaList->strRMARegistrationDate  && $rmaList->strRMARegistrationDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strRMARegistrationDate))
                                                                : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Item</td>
                                                        <td> {{ $rmaList->strItem ?? '-' }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Item Description</td>
                                                        <td> {{ $rmaList->strItemDescription ?? '-' }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Serial No</td>
                                                        <td>{{ $rmaList->strSerialNo ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Date Code</td>
                                                        <td>{{ $rmaList->strDateCode ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>In warranty</td>
                                                        <td>{{ $rmaList->strInwarranty ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7</td>
                                                        <td>Quantity</td>
                                                        <td>{{ $rmaList->strQuantity ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>8</td>
                                                        <td>Select System</td>
                                                        <td>{{ $rmaList->strSystem ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>9</td>
                                                        <td>Model Number</td>
                                                        <td>{{ $rmaList->model_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>10</td>
                                                        <td>Fault Description</td>
                                                        <td>{{ $rmaList->strFaultdescription ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>11</td>
                                                        <td>Facts</td>
                                                        <td>{{ $rmaList->strFacts ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>12</td>
                                                        <td>Additional Details</td>
                                                        <td>{{ $rmaList->strAdditionalDetails ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!--/. other information table div-->
                                        <!-- Testing & Approval table div-->
                                        <div class="table-responsive">
                                            <div class="row mx-1">
                                                <div class="col-md-12">
                                                    <h4>Testing & Approval</h4>
                                                </div>
                                            </div>

                                            <table class="table table-striped" data-role="content"
                                                data-plugin="selectable" data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <input type="hidden" name="rma_id" id="put_rma_id"
                                                    value="{{ $rmaList->rma_id }}">
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Material Received</td>
                                                        <td>{{ $rmaList->strMaterialReceived ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Material Received Date</td>
                                                        @if ($rmaList->strMaterialReceived == 'Yes')
                                                            <td>
                                                                {{ $rmaList->strMaterialReceivedDate && $rmaList->strMaterialReceivedDate != '0000-00-00'
                                                                    ? date('d-m-Y', strtotime($rmaList->strMaterialReceivedDate))
                                                                    : '-' }}
                                                            </td>
                                                        @else
                                                            <td>-</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Testing</td>
                                                        <td>{{ $rmaList->strTesting ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Testing Complete Date</td>
                                                        @if ($rmaList->strTesting == 'Done')
                                                            <td>
                                                                {{ $rmaList->strTestingCompleteDate && $rmaList->strTestingCompleteDate != '0000-00-00'
                                                                    ? date('d-m-Y', strtotime($rmaList->strTestingCompleteDate))
                                                                    : '-' }}
                                                            </td>
                                                        @else
                                                            <td>-</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Testing Result</td>
                                                        <td>{{ $rmaList->Testing_result ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>Fault Covered in Warranty</td>
                                                        <td>{{ $rmaList->strFaultCoveredinWarranty ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7</td>
                                                        <td>Replacement Approved</td>
                                                        <td>{{ $rmaList->strReplacementApproved ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>8</td>
                                                        <td>Replacement Reason</td>
                                                        <td>{{ $rmaList->strReplacementReason ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>9</td>
                                                        <td>Comments</td>
                                                        <td class="ws-break">{{ $rmaList->Testing_Comments ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!-- image / video row rma--->
                                            
                                            @if (count($Documents) > 0)
                                                <div class="row mx-1">
                                                    <div class="col-md-12">
                                                        <h4>Testing Images, Videos & Docs</h4>
                                                        <div class="row mt-4">
                                                            @foreach ($Documents as $index => $item)
                                                                @if ($item->strImages || $item->strVideos || $item->strDocs)
                                                                    <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                        @if ($item->strImages)
                                                                            <div class="modal-trigger rma_model"
                                                                                data-toggle="modal"
                                                                                data-target="#documentModal"
                                                                                data-type="image"
                                                                                data-index="{{ $index }}">
                                                                                {{-- data-url="{{ asset('/RMADOC/images') . '/' . $item->strImages }}" --}}
                                                                                <img src="{{ asset('/RMADOC/images') . '/' . $item->strImages }}"
                                                                                    alt="Image">
                                                                            </div>
                                                                        @elseif ($item->strVideos)
                                                                            <div class="modal-trigger rma_model"
                                                                                data-toggle="modal"
                                                                                data-target="#documentModal"
                                                                                data-type="video"
                                                                                data-index="{{ $index }}">
                                                                                {{-- data-url="{{ asset('/RMADOC/videos') . '/' . $item->strVideos }}" --}}
                                                                                <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                                                    alt="Video">
                                                                            </div>
                                                                        @elseif ($item->strDocs)
                                                                            @php
                                                                                $extension = pathinfo(
                                                                                    $item->strDocs,
                                                                                    PATHINFO_EXTENSION,
                                                                                );
                                                                            @endphp
                                                                            @if ($extension == 'xls' || $extension == 'xlsx')
                                                                                <a
                                                                                    onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                    <img 
                                                                                        src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                                                        alt="{{ $item->strDocs }}">
                                                                                </a>
                                                                            @elseif ($extension == 'doc' || $extension == 'docx')
                                                                                <a
                                                                                    onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                    <img 
                                                                                        src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                                                        alt="{{ $item->strDocs }}">
                                                                                </a>
                                                                            @else
                                                                                <a
                                                                                    onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                    <img 
                                                                                        src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                                                        alt="{{ $item->strDocs }}">
                                                                                </a>
                                                                            @endif
                                                                        @endif

                                                                        <div class="text-center del-img mt-2">
                                                                            <form
                                                                                action="{{ route('rma.rma_delete', $item->rma_docs_id) }}"
                                                                                method="POST"
                                                                                onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                style="display: inline-block;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="p-0 border-0 bg-none">
                                                                                    <i class="mas-trash mas-1x"
                                                                                        title="Delete"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @foreach ($Documents_files as $index => $doc)
                                                            @if($doc->strDocs)
                                                                @php
                                                                    $extension = pathinfo($doc->strDocs, PATHINFO_EXTENSION);
                                                                @endphp
                                                                <!-- Document Display -->
                                                                <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                    <a onclick="openDoc('{{ $doc->rma_docs_id }}')">
                                                                        @if (in_array($extension, ['xls', 'xlsx']))
                                                                            <img  src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}" alt="{{ $doc->strDocs }}">
                                                                        @elseif (in_array($extension, ['doc', 'docx']))
                                                                            <img  src="{{ asset('global/assets/images/reference/photo/word.jpg') }}" alt="{{ $doc->strDocs }}">
                                                                        @else
                                                                            <img  src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}" alt="{{ $doc->strDocs }}">
                                                                        @endif
                                                                    </a>

                                                                    <!-- Delete Button -->
                                                                    <div class="text-center del-img mt-2">
                                                                        <form action="{{ route('rma.rma_delete', $doc->rma_docs_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete?');" style="display: inline-block;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="p-0 border-0 bg-none">
                                                                                <i class="mas-trash mas-1x" title="Delete"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach

                                                        </div>
                                                        <div class="modal fade" id="documentModal" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <div id="modalCarousel" class="carousel slide"
                                                                            data-ride="carousel">
                                                                            <ol class="carousel-indicators"
                                                                                id="carouselIndicators">
                                                                            </ol>
                                                                            <div class="carousel-inner"
                                                                                id="carouselContent">
                                                                            </div>
                                                                            <a class="carousel-control-prev"
                                                                                role="button"
                                                                                onclick="navigateDocument('prev')">
                                                                                <span class="carousel-control-prev-icon"
                                                                                    aria-hidden="true"></span>
                                                                                <span class="sr-only">Previous</span>
                                                                            </a>
                                                                            <a class="carousel-control-next"
                                                                                role="button"
                                                                                onclick="navigateDocument('next')">
                                                                                <span class="carousel-control-next-icon"
                                                                                    aria-hidden="true"></span>
                                                                                <span class="sr-only">Next</span>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endif
                                            <!-- /. image / video row -->
                                        </div><!--/. Testing & Approval table div-->
                                        <!-- Factory table div-->
                                        <div class="table-responsive">
                                            <div class="row mx-1">
                                                <div class="col-md-12">
                                                    <h4>Factory</h4>
                                                </div>
                                            </div>

                                            <table class="table table-striped" data-role="content"
                                                data-plugin="selectable" data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Factory RMA No</td>
                                                        <td>{{ $rmaList->Factory_rma_no ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Material Received</td>
                                                        <td>{{ $rmaList->strFactory_MaterialReceived ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Material Received Date</td>
                                                        <td> {{ $rmaList->strFactory_MaterialReceivedDate && $rmaList->strFactory_MaterialReceivedDate != '0000-00-00'
                                                            ? date('d-m-Y', strtotime($rmaList->strFactory_MaterialReceivedDate))
                                                            : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Testing</td>
                                                        <td>{{ $rmaList->strFactory_Testing ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Testing Complete Date</td>
                                                        <td>{{ $rmaList->strFactory_TestingCompleteDate && $rmaList->strFactory_TestingCompleteDate != '0000-00-00'
                                                            ? date('d-m-Y', strtotime($rmaList->strFactory_TestingCompleteDate))
                                                            : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>Fault Covered in Warranty</td>
                                                        <td>{{ $rmaList->strFactory_FaultCoveredinWarranty ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7</td>
                                                        <td>Replacement Approved</td>
                                                        <td>{{ $rmaList->strFactory_ReplacementApproved ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>8</td>
                                                        <td>Replacement Reason</td>
                                                        <td>{{ $rmaList->strFactory_ReplacementReason ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>9</td>
                                                        <td>HFI Status</td>
                                                        <td>{{ $rmaList->Factory_Status ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>10</td>
                                                        <td>Comments</td>
                                                        <td class="ws-break">{{ $rmaList->Factory_Comments ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @if (count($Documents_files) > 0)
                                                <div class="row mx-1">
                                                    <div class="col-md-12">
                                                        <h4>Documents</h4>
                                                        <div class="row mt-4">
                                                            @foreach ($Documents_files as $index => $item)
                                                                @if ($item->Factory_strDocs)
                                                                    <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                        @if ($item->Factory_strDocs)
                                                                            @php
                                                                                $extension = pathinfo(
                                                                                    $item->Factory_strDocs,
                                                                                    PATHINFO_EXTENSION,
                                                                                );
                                                                            @endphp
                                                                            @if ($extension == 'xls' || $extension == 'xlsx')
                                                                                <a onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                    <img 
                                                                                        src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                                                        alt="{{ $item->Factory_strDocs }}">
                                                                                </a>
                                                                            @elseif ($extension == 'doc' || $extension == 'docx')
                                                                                <a onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                    <img 
                                                                                        src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                                                        alt="{{ $item->Factory_strDocs }}">
                                                                                </a>
                                                                            @else
                                                                                <a onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                    <img 
                                                                                        src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                                                        alt="{{ $item->Factory_strDocs }}">
                                                                                </a>
                                                                            @endif
                                                                            <div class="text-center del-img mt-2">
                                                                                <form
                                                                                    action="{{ route('rma.rma_delete', $item->rma_docs_id) }}"
                                                                                    method="POST"
                                                                                    onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                    style="display: inline-block;">
                                                                                    <input type="hidden" name="_method"
                                                                                        value="DELETE">
                                                                                    <input type="hidden" name="_token"
                                                                                        value="{{ csrf_token() }}">
                                                                                    <button type="submit"
                                                                                        class="p-0 border-0 bg-none">
                                                                                        <i class="mas-trash mas-1x"
                                                                                            title="Delete"></i>
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                        @endif



                                                                    </div><!-- /.col -->
                                                                @endif
                                                            @endforeach
                                                        </div> <!-- /.row -->
                                                        <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="myModal" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div> <!-- /.header -->
                                                                    <div class="modal-body">
                                                                        <!-- Carousel -->
                                                                        <div id="myCarousel" class="carousel slide">
                                                                            <?php $i = 1; ?>
                                                                            <ol class="carousel-indicators">
                                                                                @foreach ($Documents as $index => $item)
                                                                                    <li data-target="#myCarousel"
                                                                                        data-slide-to="{{ $index }}"
                                                                                        class="{{ $loop->first ? 'active' : '' }}">
                                                                                    </li>
                                                                                    <?php $i++; ?>
                                                                                @endforeach
                                                                            </ol>
                                                                            <div class="carousel-inner">
                                                                                <?php $i = 1; ?>
                                                                                @foreach ($Documents as $index => $item)
                                                                                    @if ($item->strImages)
                                                                                        <div
                                                                                            class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                            <img src="{{ asset('/RMADOC/images') . '/' . $item->strImages }}"
                                                                                                class="d-block w-100"
                                                                                                alt="Image">
                                                                                        </div>
                                                                                    @elseif($item->strVideos)
                                                                                        <div
                                                                                            class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                            <div
                                                                                                class="embed-responsive embed-responsive-16by9">
                                                                                                <video controls>
                                                                                                    <source
                                                                                                        src="{{ asset('RMADOC/videos/') . '/' . $item->strVideos }}"
                                                                                                        type="video/mp4">
                                                                                                </video>
                                                                                            </div>
                                                                                        </div>
                                                                                    @else
                                                                                        {{-- <div
                                                                                            class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                            <div class="text-center">
                                                                                                <a href="{{ asset('/RMADOC/docs') . '/' . $item->strDocs }}"
                                                                                                    target="_blank"
                                                                                                    class="btn btn-primary">Download
                                                                                                    Document</a>
                                                                                            </div>
                                                                                        </div> --}}
                                                                                    @endif
                                                                                    <?php $i++; ?>
                                                                                @endforeach
                                                                            </div> <!-- /.carousel inner -->

                                                                            <a class="carousel-control-prev"
                                                                                href="#myCarousel" role="button"
                                                                                data-slide="prev">
                                                                                <span class="carousel-control-prev-icon"
                                                                                    aria-hidden="true"></span>
                                                                                <span class="sr-only">Previous</span>
                                                                            </a>
                                                                            <a class="carousel-control-next"
                                                                                href="#myCarousel" role="button"
                                                                                data-slide="next">
                                                                                <span class="carousel-control-next-icon"
                                                                                    aria-hidden="true"></span>
                                                                                <span class="sr-only">Next</span>
                                                                            </a>
                                                                        </div> <!-- /.carousel slide -->

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                        class="btn btn-secondary" data-dismiss="modal">
                                                                        Close</button>
                                                                    </div> <!-- /.footer -->
                                                                </div>
                                                            </div>
                                                        </div> <!-- /.modal -->
                                                    </div>
                                                    <!-- /.col 12 level 1 images -->
                                                </div>
                                            @endif
                                        </div><!--/. Factory table div-->


                                        <!-- Customer Status table div-->
                                        <div class="table-responsive">
                                            <div class="row mx-1">
                                                <div class="col-md-12">
                                                    <h4>Customer Status</h4>
                                                </div>
                                            </div>

                                            <table class="table table-striped" data-role="content"
                                                data-plugin="selectable" data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Material Dispatched</td>
                                                        <td>{{ $rmaList->strMaterialDispatched ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Material Dispatched Date</td>
                                                        @if ($rmaList->strMaterialDispatched == 'Yes')
                                                            <td>
                                                                {{ $rmaList->strMaterialDispatchedDate && $rmaList->strMaterialDispatchedDate != '0000-00-00'
                                                                    ? date('d-m-Y', strtotime($rmaList->strMaterialDispatchedDate))
                                                                    : '-' }}

                                                            </td>
                                                        @else
                                                            <td>-</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Status</td>
                                                        <td>{{ $rmaList->strStatus ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Comments</td>
                                                        <td class="ws-break">{{ $rmaList->Cus_Comments ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!--/. Customer Status table div-->
                                    </div><!--/. col 6-->
                                    <div class="col-lg-6 col-md-12">
                                        <!-- status table -->
                                        <div class="table-responsive">
                                            <table class="table table-striped" data-role="content"
                                                data-plugin="selectable" data-row-selectable="true">
                                                <thead class="bg-grey-100">
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Status</th>
                                                        <th>Date of Updates</th>
                                                        <th>Submitted by</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                    @foreach ($datalog as $index => $rma_info)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                @if($rma_info->tableName != "rma")
                                                                    {{ 'Additional RMA' }}
                                                                @endif
                                                                {{ $rma_info->strStatus }}
                                                            </td>
                                                            @if($rma_info->strStatus == 'Open')
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($rma_info->strRMARegistrationDate)->format('d-m-Y') }}
                                                                <br />
                                                                <small class="position-static">{{ $rma_info->strRMARegistrationDate }}</small>
                                                                <!--<br>-->
                                                                <!--<small-->
                                                                <!--    class="position-static">{{ \Carbon\Carbon::parse($rma_info->strEntryDate)->format('H:i:s') }}</small>-->
                                                            </td>
                                                            @elseif($rma_info->strStatus == 'Closed')
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($rma_info->strMaterialDispatchedDate)->format('d-m-Y') }}
                                                                <!--<br>-->
                                                                <br />
                                                                <small class="position-static">{{ $rma_info->strMaterialDispatchedDate }}</small>
                                                                <!--<small-->
                                                                <!--    class="position-static">{{ \Carbon\Carbon::parse($rma_info->strEntryDate)->format('H:i:s') }}</small>-->
                                                            </td>
                                                            @endif
                                                            <td>{{ $rma_info->actionBy }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div><!--/. table 1 resonsive div-->

                                        <!-- Additional RMA Details 1 -->
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($rmadetailList as $item)
                                            @php
                                                $documents = DB::table('rma_docs')
                                                    ->orderBy('rma_docs_id', 'desc')
                                                    ->where([
                                                        'iStatus' => 1,
                                                        'isDelete' => 0,
                                                        'rma_id' => $item->rma_id,
                                                        'rma_detail_id' => $item->rma_detail_id,
                                                    ])
                                                    ->where(function ($query) {
                                                        $query->whereNotNull('strImages')->orWhereNotNull('strVideos');
                                                    })
                                                    ->get();

                                                $documentFiles = DB::table('rma_docs')
                                                    ->orderBy('rma_docs_id', 'desc')
                                                    ->where([
                                                        'iStatus' => 1,
                                                        'isDelete' => 0,
                                                        'rma_id' => $item->rma_id,
                                                        'rma_detail_id' => $item->rma_detail_id,
                                                    ])
                                                    ->where(function ($query) {
                                                        $query
                                                            ->whereNotNull('strDocs')
                                                            ->orWhereNotNull('Factory_strDocs');
                                                    })
                                                    ->get();
                                                   
                                            @endphp


                                            <div class="row mx-1">
                                                <div class="col-md-9 col-xs-8">
                                                    <h4>Additional RMA Details {{ $counter }}</h4>
                                                    @if (isset($item->first_name))
                                                        <p>Latest Updated by:
                                                            {{ $item->first_name . ' ' . $item->last_name ?? 'not updated' }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 col-xs-4 text-right mt-2">
                                            <!-- @if ($item->strStatus != 'Closed')    
                                                    <button type="button"
                                                        class="btn btn-success text-uppercase edit-info-button"
                                                        data-toggle="modal"
                                                        data-target="#edit_info_{{ $item->rma_detail_id }}"
                                                        data-rma_id="{{ $item->rma_id }}"
                                                        data-rma-detail-id="{{ $item->rma_detail_id }}">
                                                        Edit info
                                                    </button>
                                            @endif    -->    
                                             <button type="button"
                                                        class="btn btn-success text-uppercase edit-info-button"
                                                        data-toggle="modal"
                                                        data-target="#edit_info_{{ $item->rma_detail_id }}"
                                                        data-rma_id="{{ $item->rma_id }}"
                                                        data-rma-detail-id="{{ $item->rma_detail_id }}" onclick="openModel({{ $item->rma_detail_id }});">
                                                        Edit info
                                                    </button>
                                                </div>
                                                <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>-->
                                                @include('call_attendant.rma.rma_detail_model')


                                                <div class="accordion px-0" id="accordionExample_{{$item->rma_detail_id}}">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFive_{{$item->rma_detail_id}}">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseFive_{{$item->rma_detail_id}}"
                                                                aria-expanded="false" aria-controls="collapseFive_{{$item->rma_detail_id}}">
                                                                <span class="text-orange">View Details</span>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseFive_{{$item->rma_detail_id}}" class="accordion-collapse collapse"
                                                            aria-labelledby="headingFive_{{$item->rma_detail_id}}"
                                                            data-bs-parent="#accordionExample_{{$item->rma_detail_id}}">
                                                            <div class="accordion-body px-0">
                                                                <!-- Additional RMA ravi1 other information table div -->
                                                                <div class="table-responsive">
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Other Information</h4>
                                                                        </div>
                                                                    </div>

                                                                    <table class="table table-striped" data-role="content"
                                                                        data-plugin="selectable"
                                                                        data-row-selectable="true">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr. No</th>
                                                                                <th>Label</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>RMA Registration Date</td>
                                                                                <td>{{ $item->strRMARegistrationDate && $item->strRMARegistrationDate != '0000-00-00'
                                                                                    ? date('d-m-Y', strtotime($item->strRMARegistrationDate))
                                                                                    : '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Item</td>
                                                                                <td> {{ $item->strItem ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Item Description</td>
                                                                                <td>{{ $item->strItemDescription ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Serial No</td>
                                                                                <td>{{ $item->strSerialNo ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Date Code</td>
                                                                                <td>{{ $item->strDateCode ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>6</td>
                                                                                <td>In warranty</td>
                                                                                <td>{{ $item->strInwarranty ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>7</td>
                                                                                <td>Quantity</td>
                                                                                <td>{{ $item->strQuantity ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>8</td>
                                                                                <td>Select System</td>
                                                                                <td>{{ $item->system_name ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>9</td>
                                                                                <td>Model Number</td>
                                                                                <td>{{ $item->Additional_rma_model_number ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>10</td>
                                                                                <td>Fault Description</td>
                                                                                <td>{{ $item->strFaultdescription ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>11</td>
                                                                                <td>Facts</td>
                                                                                <td>{{ $item->strFacts ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>12</td>
                                                                                <td>Additional Details</td>
                                                                                <td>{{ $item->strAdditionalDetails ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>

                                                                </div>

                                                                <!--/. other information table div-->

                                                                <!-- Testing & Approval table div-->
                                                                <div class="table-responsive">
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Testing & Approval</h4>
                                                                        </div>
                                                                    </div>

                                                                    <table class="table table-striped" data-role="content"
                                                                        data-plugin="selectable"
                                                                        data-row-selectable="true">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr. No</th>
                                                                                <th>Label</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Material Received</td>
                                                                                <td>{{ $item->strMaterialReceived ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Material Received Date</td>
                                                                                @if ($item->strMaterialReceived == 'Yes')
                                                                                    <td> {{ $item->strMaterialReceivedDate && $item->strMaterialReceivedDate != '0000-00-00'
                                                                                        ? date('d-m-Y', strtotime($item->strMaterialReceivedDate))
                                                                                        : '-' }}
                                                                                    </td>
                                                                                @else
                                                                                    <td>-</td>
                                                                                @endif
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Testing</td>
                                                                                <td>{{ $item->strTesting ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Testing Complete Date</td>
                                                                                @if ($item->strTesting == 'Done')
                                                                                    <td>{{ $item->strTestingCompleteDate && $item->strTestingCompleteDate != '0000-00-00'
                                                                                        ? date('d-m-Y', strtotime($item->strTestingCompleteDate))
                                                                                        : '-' }}
                                                                                    </td>
                                                                                @else
                                                                                    <td>-</td>
                                                                                @endif
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Testing Result</td>
                                                                                <td>{{ $item->Additional_Testing_result ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Fault Covered in Warranty</td>
                                                                                <td>{{ $item->strFaultCoveredinWarranty ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Replacement Approved</td>
                                                                                <td>{{ $item->strReplacementApproved ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>6</td>
                                                                                <td>Replacement Reason</td>
                                                                                <td>{{ $item->strReplacementReason ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>7</td>
                                                                                <td>Comments</td>
                                                                                <td class="ws-break">{{ $item->Additional_Testing_Comments ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>


                                                                    <!-- image / video row img2 -->
                                                                   @if (count($documents) > 0)
                                                                        <div class="row mx-1">
                                                                            <div class="col-md-12">
                                                                                <h4>Testing images, videos & Docs</h4>
                                                                                <div class="row mt-4">
                                                                                    @foreach ($documents as $index => $data)
                                                                                        @php
                                                                                            $extension = pathinfo(
                                                                                                $data->strDocs,
                                                                                                PATHINFO_EXTENSION,
                                                                                            );
                                                                                            $modalId =
                                                                                                'myModal_' .
                                                                                                $data->rma_detail_id;
                                                                                            $carouselId =
                                                                                                'myCarousel_' .
                                                                                                $data->rma_detail_id;
                                                                                        @endphp
                                                                                        @if ($data->strImages || $data->strVideos || $data->strDocs)
                                                                                            <div
                                                                                                class="col-md-3 col-xs-6 gallery-box text-center">
                                                                                                @if ($data->strImages)
                                                                                                    <div data-toggle="modal"
                                                                                                        data-target="#{{ $modalId }}"
                                                                                                        data-index="{{ $index }}"
                                                                                                        class="open-image-modal">
                                                                                                        <img src="{{ asset('/RMADOC/images') . '/' . $data->strImages }}"
                                                                                                            alt="Image 1">
                                                                                                    </div>
                                                                                                @elseif($data->strVideos)
                                                                                                    <div data-toggle="modal"
                                                                                                        data-target="#{{ $modalId }}"
                                                                                                        data-index="{{ $index }}"
                                                                                                        class="open-video-modal">
                                                                                                        <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                                                                            alt="Video">
                                                                                                    </div>
                                                                                                @else
                                                                                                    <a
                                                                                                        onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                        <img 
                                                                                                            src="{{ asset('global/assets/images/reference/photo/' . ($extension == 'xls' || $extension == 'xlsx' ? 'excel.jpg' : ($extension == 'doc' || $extension == 'docx' ? 'word.jpg' : 'pdf.jpg'))) }}"
                                                                                                            alt="{{ $data->strDocs }}">
                                                                                                    </a>
                                                                                                @endif
                                                                                                <div
                                                                                                    class="text-center del-img mt-2">
                                                                                                    <form
                                                                                                        action="{{ route('rma.rma_detail_delete', $data->rma_docs_id) }}"
                                                                                                        method="POST"
                                                                                                        onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                                        style="display: inline-block;">
                                                                                                        @csrf
                                                                                                        @method('DELETE')
                                                                                                        <button
                                                                                                            type="submit"
                                                                                                            class="p-0 border-0 bg-none">
                                                                                                            <i class="mas-trash mas-1x"
                                                                                                                title="Delete"></i>
                                                                                                        </button>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @foreach ($documentFiles as $index => $data)
                                                                                        @php
                                                                                            $extension = pathinfo(
                                                                                                $data->strDocs,
                                                                                                PATHINFO_EXTENSION,
                                                                                            );
                                                                                            $modalId =
                                                                                                'myModal_' .
                                                                                                $data->rma_detail_id;
                                                                                            $carouselId =
                                                                                                'myCarousel_' .
                                                                                                $data->rma_detail_id;
                                                                                        @endphp
                                                                                        @if ($data->strDocs)
                                                                                            <div
                                                                                                class="col-md-3 col-xs-6 gallery-box text-center">

                                                                                                <a
                                                                                                    onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                    <img 
                                                                                                        src="{{ asset('global/assets/images/reference/photo/' . ($extension == 'xls' || $extension == 'xlsx' ? 'excel.jpg' : ($extension == 'doc' || $extension == 'docx' ? 'word.jpg' : 'pdf.jpg'))) }}"
                                                                                                        alt="{{ $data->strDocs }}">
                                                                                                </a>

                                                                                                <div
                                                                                                    class="text-center del-img mt-2">
                                                                                                    <form
                                                                                                        action="{{ route('rma.rma_detail_delete', $data->rma_docs_id) }}"
                                                                                                        method="POST"
                                                                                                        onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                                        style="display: inline-block;">
                                                                                                        @csrf
                                                                                                        @method('DELETE')
                                                                                                        <button
                                                                                                            type="submit"
                                                                                                            class="p-0 border-0 bg-none">
                                                                                                            <i class="mas-trash mas-1x"
                                                                                                                title="Delete"></i>
                                                                                                        </button>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>


                                                                                @foreach ($documents as $index => $data)
                                                                                    @php
                                                                                        $modalId =
                                                                                            'myModal_' .
                                                                                            $data->rma_detail_id;
                                                                                        $carouselId =
                                                                                            'myCarousel_' .
                                                                                            $data->rma_detail_id;
                                                                                    @endphp
                                                                                    <div class="modal fade rma_additional_model"
                                                                                        id="{{ $modalId }}"
                                                                                        tabindex="-1" aria-hidden="true">
                                                                                        <div class="modal-dialog modal-lg">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <button type="button"
                                                                                                        class="close"
                                                                                                        data-dismiss="modal"
                                                                                                        aria-label="Close">
                                                                                                        <span
                                                                                                            aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <div id="{{ $carouselId }}"
                                                                                                        class="carousel slide"
                                                                                                        data-ride="carousel">
                                                                                                        <ol
                                                                                                            class="carousel-indicators">
                                                                                                            @foreach ($documents as $carouselIndex => $modeldata)
                                                                                                                @if ($modeldata->strImages || $modeldata->strVideos)
                                                                                                                    <li data-target="#{{ $carouselId }}"
                                                                                                                        data-slide-to="{{ $carouselIndex }}"
                                                                                                                        class="{{ $carouselIndex == $index ? 'active' : '' }}">
                                                                                                                    </li>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                        </ol>
                                                                                                        <div
                                                                                                            class="carousel-inner">
                                                                                                            @foreach ($documents as $carouselIndex => $itemdetail)
                                                                                                                @if ($itemdetail->strImages || $itemdetail->strVideos)
                                                                                                                    <div
                                                                                                                        class="carousel-item {{ $carouselIndex == $index ? 'active' : '' }}">
                                                                                                                        @if ($itemdetail->strImages)
                                                                                                                            <img src="{{ asset('/RMADOC/images') . '/' . $itemdetail->strImages }}"
                                                                                                                                class="d-block w-100"
                                                                                                                                alt="Image">
                                                                                                                        @elseif($itemdetail->strVideos)
                                                                                                                            <div
                                                                                                                                class="embed-responsive embed-responsive-16by9">
                                                                                                                                <video
                                                                                                                                    controls>
                                                                                                                                    <source
                                                                                                                                        src="{{ asset('RMADOC/videos/') . '/' . $itemdetail->strVideos }}"
                                                                                                                                        type="video/mp4">
                                                                                                                                </video>
                                                                                                                            </div>
                                                                                                                        @else
                                                                                                                        @endif
                                                                                                                    </div>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                        <a class="carousel-control-prev"
                                                                                                            href="javascript:void(0)"
                                                                                                            role="button"
                                                                                                            onclick="navigate_detail('prev', '{{ $carouselId }}')">
                                                                                                            <span
                                                                                                                class="carousel-control-prev-icon"
                                                                                                                aria-hidden="true"></span>
                                                                                                            <span
                                                                                                                class="sr-only">Previous</span>
                                                                                                        </a>
                                                                                                        <a class="carousel-control-next"
                                                                                                            href="javascript:void(0)"
                                                                                                            role="button"
                                                                                                            onclick="navigate_detail('next', '{{ $carouselId }}')">
                                                                                                            <span
                                                                                                                class="carousel-control-next-icon"
                                                                                                                aria-hidden="true"></span>
                                                                                                            <span
                                                                                                                class="sr-only">Next</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button"
                                                                                                        class="btn btn-secondary"
                                                                                                        data-dismiss="modal">Close</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    <!-- /. image / video row -->
                                                                </div><!--/. Testing & Approval table div-->

                                                                <!-- Factory table div-->
                                                                <div class="table-responsive">
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Factory</h4>
                                                                        </div>
                                                                    </div>

                                                                    <table class="table table-striped" data-role="content"
                                                                        data-plugin="selectable"
                                                                        data-row-selectable="true">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr. No</th>
                                                                                <th>Label</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Factory RMA No</td>
                                                                                <td>{{ $item->Additional_Factory_rma_no ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Material Received</td>
                                                                                <td>{{ $item->strFactory_MaterialReceived ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Material Received Date</td>
                                                                                @if ($item->strFactory_MaterialReceived == 'Yes')
                                                                                    <td>{{ $item->strFactory_MaterialReceivedDate && $item->strFactory_MaterialReceivedDate != '0000-00-00'
                                                                                        ? date('d-m-Y', strtotime($item->strFactory_MaterialReceivedDate))
                                                                                        : '-' }}
                                                                                    </td>
                                                                                @else
                                                                                    <td>-</td>
                                                                                @endif
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Testing</td>
                                                                                <td>{{ $item->strFactory_Testing ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Testing Complete Date</td>
                                                                                @if ($item->strFactory_Testing == 'Done')
                                                                                    <td>{{ $item->strFactory_TestingCompleteDate && $item->strFactory_TestingCompleteDate != '0000-00-00'
                                                                                        ? date('d-m-Y', strtotime($item->strFactory_TestingCompleteDate))
                                                                                        : '-' }}
                                                                                    </td>
                                                                                @else
                                                                                    <td>-</td>
                                                                                @endif
                                                                            </tr>
                                                                            <tr>
                                                                                <td>6</td>
                                                                                <td>Fault Covered in Warranty</td>
                                                                                <td>{{ $item->strFactory_FaultCoveredinWarranty ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>7</td>
                                                                                <td>Replacement Approved</td>
                                                                                <td>{{ $item->strFactory_ReplacementApproved ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>8</td>
                                                                                <td>Replacement Reason</td>
                                                                                <td>{{ $item->strFactory_ReplacementReason ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>9</td>
                                                                                <td>HFI Status</td>
                                                                                <td>{{ $item->Additional_Factory_Status ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>10</td>
                                                                                <td>Comments</td>
                                                                                <td class="ws-break">{{ $item->Additional_Factory_Comments ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    @if (count($documentFiles) > 0)
                                                                        <div class="row mx-1">
                                                                            <div class="col-md-12">
                                                                                <h4>Documents</h4>
                                                                                <div class="row mt-4">
                                                                                    @foreach ($documentFiles as $index => $data)
                                                                                        @if ($data->Factory_strDocs)
                                                                                            @php
                                                                                                $extension = pathinfo(
                                                                                                    $data->Factory_strDocs,
                                                                                                    PATHINFO_EXTENSION,
                                                                                                );
                                                                                            @endphp
                                                                                            <div
                                                                                                class="col-md-3 col-xs-6 gallery-box text-center">

                                                                                                @if ($extension == 'xls' || $extension == 'xlsx')
                                                                                                    <a onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                        <img 
                                                                                                            src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                                                                            alt="{{ $data->Factory_strDocs }}">
                                                                                                    </a>
                                                                                                @elseif($extension == 'doc' || $extension == 'docx')
                                                                                                    <a onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                        <img 
                                                                                                            src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                                                                            alt="{{ $data->Factory_strDocs }}">
                                                                                                    </a>
                                                                                                @else
                                                                                                    <a onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                        <img 
                                                                                                            src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                                                                            alt="{{ $data->Factory_strDocs }}">
                                                                                                    </a>
                                                                                                @endif

                                                                                                <div
                                                                                                    class="text-center del-img mt-2">
                                                                                                    <form
                                                                                                        action="{{ route('rma.rma_detail_delete', $data->rma_docs_id) }}"
                                                                                                        method="POST"
                                                                                                        onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                                        style="display: inline-block;">
                                                                                                        <input
                                                                                                            type="hidden"
                                                                                                            name="_method"
                                                                                                            value="DELETE">
                                                                                                        <input
                                                                                                            type="hidden"
                                                                                                            name="_token"
                                                                                                            value="{{ csrf_token() }}">
                                                                                                        <button
                                                                                                            type="submit"
                                                                                                            class="p-0 border-0 bg-none">
                                                                                                            <i class="mas-trash mas-1x"
                                                                                                                title="Delete"></i>
                                                                                                        </button>
                                                                                                    </form>
                                                                                                </div>

                                                                                            </div><!-- /.col -->
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div> <!-- /.row -->
                                                                                <!-- Lightbox (made with Bootstrap modal and carousel) -->

                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                </div><!--/. Factory table div-->

                                                                <!-- Customer Status table div-->
                                                                <div class="table-responsive">
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Customer Status</h4>
                                                                        </div>
                                                                    </div>

                                                                    <table class="table table-striped" data-role="content"
                                                                        data-plugin="selectable"
                                                                        data-row-selectable="true">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr. No</th>
                                                                                <th>Label</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Material Dispatched</td>
                                                                                <td>{{ $item->strMaterialDispatched ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Material Dispatched Date</td>
                                                                                @if ($item->strMaterialDispatched == 'Yes')
                                                                                    <td>{{ $item->strMaterialDispatchedDate && $item->strMaterialDispatchedDate != '0000-00-00'
                                                                                        ? date('d-m-Y', strtotime($item->strMaterialDispatchedDate))
                                                                                        : '-' }}
                                                                                    </td>
                                                                                @else
                                                                                    <td>-</td>
                                                                                @endif
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Status</td>
                                                                                <td>{{ $item->strStatus ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Comments</td>
                                                                                <td class="ws-break">{{ $item->Additional_Cus_Comments ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div><!--/. Customer Status table div-->

                                                            </div><!-- /. accordion body -->
                                                        </div> <!-- /. collapse One -->
                                                    </div> <!-- /. accordion-item -->
                                                </div><!-- /. accordion -->
                                            </div>
                                            @php
                                                $counter++;
                                            @endphp
                                        @endforeach

                                    </div><!--/. col 6-->
                                </div>
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
        $(document).ready(function () {
            $('#Material_Received_Date').datepicker({
                autoclose: true, // Automatically close the picker after selection
                format: 'dd-mm-yyyy', // Date format
                clearBtn: true, // Add a button to clear the date
            }).val(''); // Clear the default value
            
            $('#strTestingCompleteDate').datepicker({
                autoclose: true, // Automatically close the picker after selection
                format: 'dd-mm-yyyy', // Date format
                clearBtn: true, // Add a button to clear the date
            }).val(''); // Clear the default value
            
            $('#Factory_Material_Received_Date').datepicker({
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

                        if ($("#strDistributor option[value='" + response.iDistributorId + "']")
                            .length) {
                            $("#strDistributor").val(response.iDistributorId).trigger('change');
                        }

                        //$("#strDistributor").val(response.iDistributorId);
                    } else {
                        $("#strProjectName").val("");
                        $("#strDistributor").val("");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    //alert("Failed to fetch ticket details.");

                    $("#strProjectName").val("");
                    $("#strDistributor").val("");
                },
            });

        });

        $("#EditstrMaterialReceived").on("change", function() {
            let getvalue = this.value;
            if (getvalue === "Yes") {
                $("#strMaterialReceivedDateDiv").show();
                $("#Material_Received_Date").attr("required", true);
            } else if (getvalue === "No") {
                $("#strMaterialReceivedDateDiv").hide();
                $("#Material_Received_Date").removeAttr("required");
            } else {
                $("#strMaterialReceivedDateDiv").hide();
                $("#Material_Received_Date").removeAttr("required");
            }
        });
        $(document).ready(function() {
            $("#EditstrMaterialReceived").trigger("change");
        });

       $("#EditstrTesting").on("change", function() {
            let getvalue = this.value;
            if (getvalue === "Done") {
                $("#strTestingCompleteDateDiv").show();
                $("#strTestingCompleteDate").attr("required", true);
            } else if (getvalue === "In Progress") {
                $("#strTestingCompleteDateDiv").hide();
                $("#strTestingCompleteDate").removeAttr("required");
            } else {
                $("#strTestingCompleteDateDiv").hide();
                $("#strTestingCompleteDate").removeAttr("required");
            }
        });
        $(document).ready(function() {
            $("#EditstrTesting").trigger("change");
        });

        $("#editMaterial_Received").on("change", function() {
            let getvalue = this.value;
            if (getvalue === "Yes") {
                $("#strFactory_MaterialReceivedDateDiv").show();
                $("#Factory_Material_Received_Date").attr("required", true);
            } else if (getvalue === "No") {
                $("#strFactory_MaterialReceivedDateDiv").hide();
                $("#Factory_Material_Received_Date").removeAttr("required");
            } else {
                $("#strFactory_MaterialReceivedDateDiv").hide();
                $("#Factory_Material_Received_Date").removeAttr("required");
            }
        });
        $(document).ready(function() {
            $("#editMaterial_Received").trigger("change");
        });

       $("#EditFactory_Testing").on("change", function() {
            let getvalue = this.value;
            if (getvalue === "Done") {
                $("#strFactory_TestingCompleteDateDiv").show();
                $("#strFactory_TestingCompleteDate").attr("required", true);
            } else if (getvalue === "In Progress" || getvalue === "") {
                $("#strFactory_TestingCompleteDateDiv").hide();
                $("#strFactory_TestingCompleteDate").removeAttr("required");
            } else {
                $("#strFactory_TestingCompleteDateDiv").hide();
                $("#strFactory_TestingCompleteDate").removeAttr("required");
            }
        });
        $(document).ready(function() {
            $("#EditFactory_Testing").trigger("change");
        });

        $("#EditMaterial_Dispatched").on("change", function() {
            let getvalue = this.value;
            if (getvalue === "Yes") {
                $("#strMaterialDispatchedDateDiv").show();
                $("#strMaterialDispatchedDate").attr("required", true);
            } else if (getvalue === "No" || getvalue === "") {
                $("#strMaterialDispatchedDateDiv").hide();
                $("#strMaterialDispatchedDate").removeAttr("required");
            }
        });
        $(document).ready(function() {
            $("#EditMaterial_Dispatched").trigger("change");
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- first model --}}
    <script>
        $(document).ready(function() {
            $("#editinfo").click(function() {
                var rma_id = $("#put_rma_id").val();

                $.ajax({
                    type: 'GET',
                    url: "{{ route('rma.rma_edit') }}",
                    data: {
                        rma_id: rma_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        var obj = response[0];
                        $("#rma_id").val(obj.rma_id);
                        $("#basicExample1").val(obj.strRMARegistrationDate);
                        $("#editstrItem").val(obj.strItem);
                        $("#editstrItemDescription").val(obj.strItemDescription);
                        $("#editstrSerialNo").val(obj.strSerialNo);
                        $("#editstrDateCode").val(obj.strDateCode);
                        $("#editstrQuantity").val(obj.strQuantity);
                        $("#ditstrFaultdescription").val(obj.strFaultdescription);
                        $("#editstrFacts").val(obj.strFacts);
                        $("#editstrAdditionalDetails").val(obj.strAdditionalDetails);
                        $("#editmodel_number").val(obj.model_number);
                        $("#editTesting_Comments").val(obj.Testing_Comments);
                        $("#edit_Factory_rma_no").val(obj.Factory_rma_no);
                        $("#editFactory_Comments").val(obj.Factory_Comments);
                        $("#editCus_Comments").val(obj.Cus_Comments);
                        //date set first from start
                        var Rma_registration_date = obj.strRMARegistrationDate;
                        if (Rma_registration_date) {
                            var dateParts = Rma_registration_date.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date1").val(
                                formattedDate);
                        } else {
                            $("#date1").val('');
                        }
                        //
                        var materialReceivedDate = obj.strMaterialReceivedDate;
                        if (materialReceivedDate) {
                            var dateParts = materialReceivedDate.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#Material_Received_Date").val(
                                formattedDate);
                        } else {
                            $("#Material_Received_Date").val('');
                        }
                        //
                        var Testing_Complete_Date = obj.strTestingCompleteDate;
                        if (Testing_Complete_Date) {
                            var dateParts = Testing_Complete_Date.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#strTestingCompleteDate").val(
                                formattedDate);
                        } else {
                            $("#strTestingCompleteDate").val('');
                        }
                        //date set second from start
                        //
                        var Factory_Received_Date = obj.strFactory_MaterialReceivedDate;
                        if (Factory_Received_Date) {
                            var dateParts = Factory_Received_Date.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#Factory_Material_Received_Date").val(
                                formattedDate);
                        } else {
                            $("#Factory_Material_Received_Date").val('');
                        }
                        //
                        //
                        var Factory_MaterialReceivedDate = obj.strFactory_TestingCompleteDate;
                        if (Factory_MaterialReceivedDate) {
                            var dateParts = Factory_MaterialReceivedDate.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#strFactory_TestingCompleteDate").val(
                                formattedDate);
                        } else {
                            $("#strFactory_TestingCompleteDate").val('');
                        }
                        //
                        //
                        var strMaterialDispatchedDate = obj.strMaterialDispatchedDate;
                        if (strMaterialDispatchedDate) {
                            var dateParts = strMaterialDispatchedDate.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#strMaterialDispatchedDate").val(
                                formattedDate);
                        } else {
                            $("#strMaterialDispatchedDate").val('');
                        }
                        //

                        var warrantyValue = response[1];
                        $("#EditstrInwarranty").html(warrantyValue);

                        var Materialhtml = response[2];
                        if (obj.strMaterialReceived == "Yes") {
                            $("#strMaterialReceivedDateDiv").show();
                        } else if (obj.strMaterialReceived == "No") {
                            $("#strMaterialReceivedDateDiv").hide();
                        } else {
                            $("#strMaterialReceivedDateDiv").hide();
                        }
                        $("#EditstrMaterialReceived").html(Materialhtml);


                        var systemhtml = response[3];

                        $("#editstrSelectSystem").html(systemhtml);

                        var Testinghtml = response[4];
                        if (obj.strTesting == 'Done') {
                            $("#strTestingCompleteDateDiv").show();
                        } else if (obj.strTesting == "In Progress") {
                            $("#strTestingCompleteDateDiv").hide();
                        } else {
                            $("#strTestingCompleteDateDiv").hide();
                        }
                        $("#EditstrTesting").html(Testinghtml);

                        var Fault_Covered_html = response[5];
                        $("#EditFault_Covereds").html(Fault_Covered_html);

                        var Replacement_Approved_html = response[6];
                        $("#Replacement_Approveds").html(Replacement_Approved_html);

                        var Replacement_Reason_html = response[7];
                        $("#EditReplacement_Reasons").html(Replacement_Reason_html);

                        var Material_Received_html = response[8];
                        if (obj.strFactory_MaterialReceived == 'Yes') {
                            $("#strFactory_MaterialReceivedDateDiv").show();
                        } else if (obj.strFactory_MaterialReceived == "No") {
                            $("#strFactory_MaterialReceivedDateDiv").hide();
                        } else {
                            $("#strFactory_MaterialReceivedDateDiv").hide();
                        }
                        $("#editMaterial_Received").html(Material_Received_html);

                        var Factory_Testing_html = response[9];
                        if (obj.strFactory_Testing == 'Done') {
                            $("#strFactory_TestingCompleteDateDiv").show();
                        } else if (obj.strFactory_Testing == "In Progress") {
                            $("#strFactory_TestingCompleteDateDiv").hide();
                        } else {
                            $("#strFactory_TestingCompleteDateDiv").hide();
                        }
                        $("#EditFactory_Testing").html(Factory_Testing_html);

                        var Fault_Covered_in_Warranty = response[10];
                        $("#EditFault_Covered_in_Warrantys").html(Fault_Covered_in_Warranty);

                        var Factory_Replacement_Approved = response[11];
                        $("#EditFactory_Replacement_Approveds").html(
                            Factory_Replacement_Approved);

                        var Factory_Replacement_Reason_html = response[12];
                        $("#EditFactory_Replacement_Reasons").html(
                            Factory_Replacement_Reason_html);

                        var Material_Dispatched = response[13];
                        if (obj.strMaterialDispatched == "Yes") {
                            $("#strMaterialDispatchedDateDiv").show();
                        } else if (obj.strMaterialDispatched == "No") {
                            $("#strMaterialDispatchedDateDiv").hide();
                        } else {
                            $("#strMaterialDispatchedDateDiv").hide();
                        }
                        $("#EditMaterial_Dispatched").html(Material_Dispatched);

                        var cus_Status = response[14];
                        $("#Editcus_Status_").html(cus_Status);

                        var Testing_Result_html = response[15];
                        $("#editTesting_result").html(Testing_Result_html);

                        var HFI_Status_html = response[16];
                        $("#editFactory_Status").html(HFI_Status_html);
                    },
                    error: function(xhr) {
                        console.error("Error: ", xhr.responseText);
                    }
                });
            });
        });
    </script>
    {{-- first model --}}
    <script>
        $(document).ready(function() {
            //setTestMaterial();
            // setTestTesting();
            // setFactoryMaterial();
            // setFactoryTesting();
            // setCustomerMaterialDispatched();
            $('.modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var index = button.data('index');
                var modal = $(this);
                var carousel = modal.find('.carousel');

                carousel.find('.carousel-item').removeClass('active').eq(index).addClass('active');
                carousel.find('.carousel-indicators li').removeClass('active').eq(index).addClass('active');
                carousel.carousel(index);
            });
        });
    </script>
    
    
   <script>
        let currentIndex = 0;
        let documents = @json($Documents);

        // Filter out documents with no images or videos
        let validDocuments = documents.filter(item => item.strImages || item.strVideos);

        $(document).on('click', '.modal-trigger', function() {
            currentIndex = $(this).data('index');
            // Ensure currentIndex is valid after filtering
            if (validDocuments[currentIndex] === undefined) {
                currentIndex = 0;
            }
            loadCarouselContent();
        });

        function loadCarouselContent() {
            let carouselItems = '';
            let carouselIndicators = '';

            validDocuments.forEach((item, index) => {
                console.log('Current Index:', item);
                const type = item.strImages ? 'image' : item.strVideos ? 'video' : 'doc';
                let url = '';

                if (item.strImages) {
                    url = "{{ asset('/RMADOC/images') }}" + '/' + item.strImages;
                } else if (item.strVideos) {
                    url = "{{ asset('/RMADOC/videos') }}" + '/' + item.strVideos;
                }

                if (type === 'image') {
                    carouselItems += `
                    <div class="carousel-item ${index === currentIndex ? 'active' : ''}">
                        <img src="${url}" alt="Image" class="d-block w-100" oncontextmenu="return false;">
                    </div>`;
                } else if (type === 'video') {
                    carouselItems += `
                    <div class="carousel-item ${index === currentIndex ? 'active' : ''}">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video class="embed-responsive-item" controls oncontextmenu="return false;">
                                <source src="${url}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>`;
                } else {
                    carouselItems += `
                    <div class="carousel-item ${index === currentIndex ? 'active' : ''}">
                        <div class="alert alert-info">No content available for this document.</div>
                    </div>`;
                }

                carouselIndicators +=
                    `
                <li data-target="#modalCarousel" data-slide-to="${index}" class="${index === currentIndex ? 'active' : ''}"></li>`;
            });

            $('#carouselContent').html(carouselItems);
            $('#carouselIndicators').html(carouselIndicators);
        }

        function navigateDocument(direction) {
            if (direction === 'prev') {
                currentIndex = currentIndex > 0 ? currentIndex - 1 : validDocuments.length - 1;
            } else if (direction === 'next') {
                currentIndex = currentIndex < validDocuments.length - 1 ? currentIndex + 1 : 0;
            }

            loadCarouselContent();
        }

        function openDoc(url) {
            var newurl = "{{ route('rma.openDocument', ':id') }}";
            newurl = newurl.replace(':id', url);
            newurl = newurl.replace('?', '/');
            window.open(newurl, '_blank');
        }
    </script>

<script>
    $(document).on('click', '.open-image-modal, .open-video-modal', function () {
    const index = $(this).data('index');
    const modalId = $(this).data('target');
    const $carousel = $(modalId).find('.carousel');
    $carousel.find('.carousel-item').removeClass('active').eq(index).addClass('active');
    $carousel.find('.carousel-indicators li').removeClass('active').eq(index).addClass('active');
});

function navigate_detail(directiondetail, carouselId) {
    const $carousel = $(`#${carouselId}`);
    const $items = $carousel.find('.carousel-item');
    const totalItems = $items.length;

    // Find the current active index
    let currentIndex = $items.index($carousel.find('.carousel-item.active'));
    if (directiondetail === 'prev') {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalItems - 1;
    } else if (directiondetail === 'next') {
        currentIndex = (currentIndex < totalItems - 1) ? currentIndex + 1 : 0;
    }

    // Update the active state for items and indicators
    $items.removeClass('active').eq(currentIndex).addClass('active');
    $carousel.find('.carousel-indicators li').removeClass('active').eq(currentIndex).addClass('active');
}
    </script>
 
   <script>
   $(document).ready(function() {
    $(".edit-info-button").click(function() {
        
        var rma_detail_id = $(this).data("rma-detail-id");  
        $.ajax({
            type: 'GET',
            url: "{{ route('rma.rmadetail_edit') }}",
            data: {
                rma_detail_id:rma_detail_id,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#Add_date_strRMARegistrationDate").val(response.strRMARegistrationDate);
                $("#Material_Received_Dates_detail").val(response.strMaterialReceivedDate);
                $("#edit_detail_strTestingCompleteDate").val(response.strTestingCompleteDate);
                $("#Factory_Material_Received_Date_rmadetail").val(response.strFactory_MaterialReceivedDate);
                $("#strFactory_TestingCompleteDate_detail").val(response.strFactory_TestingCompleteDate);
                $("#strMaterialDispatchedDate_detail").val(response.strMaterialDispatchedDate);
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });
});

function openModel(rma_detail_id){
    setTestMaterial(rma_detail_id);
    setTestTesting(rma_detail_id);
    setFactoryMaterial(rma_detail_id);
    setFactoryTesting(rma_detail_id);
    setCustomerMaterialDispatched(rma_detail_id);
}
 </script>
    
@endsection
