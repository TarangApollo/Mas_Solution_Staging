<?php

foreach($rmadetailList as $item){ ?>

<div class="modal fade bd-example-modal-lg" id="edit_info_{{ $item->rma_detail_id }}" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Other
                    Information for
                    {{ str_pad($rmaList->rma_id, 4, '0', STR_PAD_LEFT) }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="was-validated" action="{{ route('rma.rma_detail_update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="rma_detail_id" id="" value="{{ $item->rma_detail_id }}">
                <div class="modal-body">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Other Information
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group" id="basicExample1">
                                                <label>RMA Registration
                                                    Date</label>
                                                <div class="input-group date datepicker" data-date-format="dd-mm-yyyy">
                                                    <input class="form-control" type="text" id="date1"
                                                        name="strRMARegistrationDate"
                                                        value="{{ $item->strRMARegistrationDate && $item->strRMARegistrationDate != '0000-00-00'
                                                            ? date('d-m-Y', strtotime($item->strRMARegistrationDate))
                                                            : '-' }}" />
                                                    <span class="input-group-addon"><i
                                                            class="mas-month-calender mas-105x"></i></span>
                                                </div>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Item</label>
                                                <input type="text" id="editstrItem" class="form-control"
                                                    name="Item"value="{{ $item->strItem ?? '-' }}" />
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Item
                                                    Description</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $item->strItemDescription ?? '-' }}"
                                                    name="strItemDescription" id="editstrItemDescription" />
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Serial No</label>
                                                <input type="text" class="form-control" name="strSerialNo"
                                                    id="editstrSerialNo" value="{{ $item->strSerialNo ?? '-' }}" />
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Date Code</label>
                                                <input type="text" class="form-control" name="strDateCode"
                                                    id="editstrDateCode" value="{{ $item->strDateCode ?? '-' }}" />
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>In warranty</label>
                                                <select name="strInwarranty" class="js-example-basic-single"
                                                    style="width: 100%;">
                                                    <option label="Please Select" value="">-- Select
                                                        --</option>
                                                    <option
                                                        value="Yes"{{ isset($item->strInwarranty) && $item->strInwarranty === 'Yes' ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option
                                                        value="No"{{ isset($item->strInwarranty) && $item->strInwarranty === 'No' ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $item->strQuantity ?? '-' }}" name="strQuantity"
                                                    id="editstrQuantity" />
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select System</label>
                                                <select name="strSelectSystem" class="js-example-basic-single"
                                                    style="width: 100%;">
                                                    <option label="Please Select" value="">-- Select
                                                        --</option>
                                                    @foreach ($systemList as $list)
                                                        <option value="{{ $list->iSystemId }}"
                                                            {{ isset($item->iSystemId) && $item->iSystemId == $list->iSystemId ? 'selected' : '' }}>
                                                            {{ $list->strSystem }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Fault
                                                    description</label>
                                                <input type="text" class="form-control" name="strFaultdescription"
                                                    id="ditstrFaultdescription"
                                                    value="{{ $item->strFaultdescription ?? '-' }}" />
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Facts</label>
                                                <input type="text" class="form-control" name="strFacts"
                                                    value="{{ $item->strFacts ?? '-' }}" id="editstrFacts" />
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Additional
                                                    Details</label>
                                                <input type="text" class="form-control"
                                                    name="strAdditionalDetails" id="editstrAdditionalDetails"
                                                    value="{{ $item->strAdditionalDetails ?? '-' }}" />
                                            </div>
                                        </div> <!-- /.col -->
                                    </div> <!-- /.row -->

                                </div><!-- /. accordion body -->
                            </div> <!-- /. collapse One -->
                        </div> <!-- /. accordion-item -->

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Testing & Approval
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Material
                                                    Received</label>
                                                <select class="js-example-basic-single"
                                                    id="Edit_strMaterialReceived_{{ $item->rma_detail_id }}"
                                                    class="form-control" style="width: 100%;"
                                                    name="strMaterialReceived" onchange="setTestMaterial();">
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Yes"
                                                        {{ isset($item->strMaterialReceived) && $item->strMaterialReceived === 'Yes' ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="No"
                                                        {{ isset($item->strMaterialReceived) && $item->strMaterialReceived === 'No' ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-4"
                                            id="strMaterialReceivedDateDiv_{{ $item->rma_detail_id }}"
                                            style="display: none;">
                                            <div class="form-group" id="basicExample">
                                                <label>Material Received
                                                    Date</label>
                                                <div class="input-group date datepicker"
                                                    data-date-format="dd-mm-yyyy">
                                                    <input class="form-control" type="text" id="date2"
                                                        name="Material_Received_Date" placeholder="dd-mm-yyyy"
                                                        value="{{ $item->strMaterialReceivedDate && $item->strMaterialReceivedDate != '0000-00-00'
                                                            ? date('d-m-Y', strtotime($item->strMaterialReceivedDate))
                                                            : '-' }}" />
                                                    <span class="input-group-addon"><i
                                                            class="mas-month-calender mas-105x"></i></span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Testing</label>
                                                <select class="js-example-basic-single"
                                                    id="Edit_strTesting_{{ $item->rma_detail_id }}"
                                                    class="form-control" style="width: 100%;" name="strTesting"
                                                    onchange="setTestTesting();">
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Done"
                                                        {{ isset($item->strTesting) && $item->strTesting === 'Done' ? 'selected' : '' }}>
                                                        Done</option>
                                                    <option
                                                        value="In Progress"{{ isset($item->strTesting) && $item->strTesting === 'In Progress' ? 'selected' : '' }}>
                                                        In Progress
                                                    </option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4"
                                            id="strTestingCompleteDateDiv_{{ $item->rma_detail_id }}"
                                            style="display: none;">
                                            <div class="form-group" id="basicExample">
                                                <label>Testing Complete
                                                    Date</label>
                                                <div class="input-group date datepicker"
                                                    data-date-format="dd-mm-yyyy">
                                                    <input class="form-control" type="text" id="date3"
                                                        name="strTestingCompleteDate" placeholder=""
                                                        value="{{ $item->strTestingCompleteDate && $item->strTestingCompleteDate != '0000-00-00'
                                                            ? date('d-m-Y', strtotime($item->strTestingCompleteDate))
                                                            : '-' }}" />
                                                    <span class="input-group-addon"><i
                                                            class="mas-month-calender mas-105x"></i></span>
                                                </div>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Fault Covered in
                                                    Warranty</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    id="EditFault_Covered" name="strFaultCoveredinWarranty">
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option
                                                        value="Yes"{{ isset($item->strFaultCoveredinWarranty) && $item->strFaultCoveredinWarranty === 'Yes' ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option
                                                        value="No"{{ isset($item->strFaultCoveredinWarranty) && $item->strFaultCoveredinWarranty === 'No' ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Replacement
                                                    Approved</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    id="Replacement_Approved" name="strReplacementApproved" >
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Yes"
                                                        {{ isset($item->strReplacementApproved) && $item->strReplacementApproved === 'Yes' ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="No"
                                                        {{ isset($item->strReplacementApproved) && $item->strReplacementApproved === 'No' ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Replacement
                                                    Reason</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    id="EditReplacement_Reason" name="strReplacementReason" >
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Warranty"
                                                        {{ isset($item->strReplacementReason) && $item->strReplacementReason === 'Warranty' ? 'selected' : '' }}>
                                                        Warranty</option>
                                                    <option value="Sales Call"
                                                        {{ isset($item->strReplacementReason) && $item->strReplacementReason === 'Sales Call' ? 'selected' : '' }}>
                                                        Sales Call</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Upload Images</label>
                                                <input class="form-control py-6" type="file" name="strImages[]"
                                                    id="formFileMultiple" multiple />
                                            </div>
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Upload Video</label>
                                                <input class="form-control py-6" type="file" name="strVideos[]"
                                                    id="formFileMultiple" multiple />
                                            </div>
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Upload Docs</label>
                                                <input class="form-control py-6" type="file" name="strDocs[]"
                                                    id="formFileMultiple" multiple />
                                            </div>
                                        </div> <!-- /.col -->

                                    </div> <!-- /.row -->

                                </div><!-- /. accordion body -->
                            </div><!-- /. collapse Two -->
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                    Factory
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Material
                                                    Received</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="strFactory_MaterialReceived"
                                                    id="edit_Material_Received_{{ $item->rma_detail_id }}" 
                                                    onchange="setFactoryMaterial()">
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Yes"
                                                        {{ isset($item->strFactory_MaterialReceived) && $item->strFactory_MaterialReceived === 'Yes' ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="No"
                                                        {{ isset($item->strFactory_MaterialReceived) && $item->strFactory_MaterialReceived === 'No' ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4"
                                            id="strFactory_MaterialReceivedDateDiv_{{ $item->rma_detail_id }}"
                                            style="display: none;">
                                            <div class="form-group" id="basicExample">
                                                <label>Material Received
                                                    Date</label>
                                                <div class="input-group date datepicker"
                                                    data-date-format="dd-mm-yyyy">
                                                    <input class="form-control" type="text" id="date7"
                                                        name="Factory_Material_Received_Date" placeholder="dd-mm-yyyy"
                                                        value="{{ $item->strFactory_MaterialReceivedDate && $item->strFactory_MaterialReceivedDate != '0000-00-00'
                                                            ? date('d-m-Y', strtotime($item->strFactory_MaterialReceivedDate))
                                                            : '-' }}" />
                                                    <span class="input-group-addon"><i
                                                            class="mas-month-calender mas-105x"></i></span>
                                                </div>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Testing</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    id="EditFactory_Testing_{{ $item->rma_detail_id }}"
                                                    name="strFactory_Testing"  onchange="setFactoryTesting()">
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Done"
                                                        {{ isset($item->strFactory_Testing) && $item->strFactory_Testing === 'Done' ? 'selected' : '' }}>
                                                        Done</option>
                                                    <option value="In Progress"
                                                        {{ isset($item->strFactory_Testing) && $item->strFactory_Testing === 'In Progress' ? 'selected' : '' }}>
                                                        In Progress
                                                    </option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4"
                                            id="strFactory_TestingCompleteDateDiv_{{ $item->rma_detail_id }}"
                                            style="display: none;">
                                            <div class="form-group" id="basicExample">
                                                <label>Testing Complete
                                                    Date</label>
                                                <div class="input-group date datepicker"
                                                    data-date-format="dd-mm-yyyy">
                                                    <input class="form-control" type="text" id="date8"
                                                        name="strFactory_TestingCompleteDate" placeholder="dd-mm-yyyy"
                                                        value="{{ $item->strFactory_TestingCompleteDate && $item->strFactory_TestingCompleteDate != '0000-00-00'
                                                            ? date('d-m-Y', strtotime($item->strFactory_TestingCompleteDate))
                                                            : '-' }}" />
                                                    <span class="input-group-addon"><i
                                                            class="mas-month-calender mas-105x"></i></span>
                                                </div>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Fault Covered in
                                                    Warranty</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    id="EditFault_Covered_in_Warranty"
                                                    name="strFactory_FaultCoveredinWarranty" >
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option
                                                        value="Yes"{{ isset($item->strFactory_FaultCoveredinWarranty) && $item->strFactory_FaultCoveredinWarranty === 'Yes' ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option
                                                        value="No"{{ isset($item->strFactory_FaultCoveredinWarranty) && $item->strFactory_FaultCoveredinWarranty === 'No' ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Replacement
                                                    Approved</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    id="EditFactory_Replacement_Approved"
                                                    name="strFactory_ReplacementApproved" >
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Yes"
                                                        {{ isset($item->strFactory_ReplacementApproved) && $item->strFactory_ReplacementApproved === 'Yes' ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="No"
                                                        {{ isset($item->strFactory_ReplacementApproved) && $item->strFactory_ReplacementApproved === 'No' ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Replacement
                                                    Reason</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    id="EditFactory_Replacement_Reason"
                                                    name="strFactory_ReplacementReason" >
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Warranty"
                                                        {{ isset($item->strFactory_ReplacementReason) && $item->strFactory_ReplacementReason === 'Warranty' ? 'selected' : '' }}>
                                                        Warranty</option>
                                                    <option value="Sales Call"
                                                        {{ isset($item->strFactory_ReplacementReason) && $item->strFactory_ReplacementReason === 'Sales Call' ? 'selected' : '' }}>
                                                        Sales Call
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
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour" aria-expanded="false"
                                    aria-controls="collapseFour">
                                    Customer Status
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Material
                                                    Dispatched</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    id="EditMaterial_Dispatched_{{ $item->rma_detail_id }}"
                                                    onchange="setCustomerMaterialDispatched();"
                                                    name="strMaterialDispatched" >
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Yes"
                                                        {{ isset($item->strMaterialDispatched) && $item->strMaterialDispatched === 'Yes' ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="No"
                                                        {{ isset($item->strMaterialDispatched) && $item->strMaterialDispatched === 'No' ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4"
                                            id="strMaterialDispatchedDateDiv_{{ $item->rma_detail_id }}"
                                            style="display: none;">
                                            <div class="form-group" id="basicExample">
                                                <label>Material Dispatched
                                                    Date</label>
                                                <div class="input-group date datepicker"
                                                    data-date-format="dd-mm-yyyy">
                                                    <input class="form-control" type="text" id="date9"
                                                        name="strMaterialDispatchedDate"
                                                        value="{{ $item->strMaterialDispatchedDate && $item->strMaterialDispatchedDate != '0000-00-00'
                                                            ? date('d-m-Y', strtotime($item->strMaterialDispatchedDate))
                                                            : '-' }}" />
                                                    <span class="input-group-addon"><i
                                                            class="mas-month-calender mas-105x"></i></span>
                                                </div>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    id="Editcus_Status" name="strStatus" >
                                                    <option label="Please Select" value="">--
                                                        Select
                                                        --</option>
                                                    <option value="Open"
                                                        {{ isset($item->strStatus) && $item->strStatus === 'Open' ? 'selected' : '' }}>
                                                        Open</option>
                                                    <option value="Closed"
                                                        {{ isset($item->strStatus) && $item->strStatus === 'Closed' ? 'selected' : '' }}>
                                                        Closed</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                    </div> <!-- /.row -->

                                </div><!-- /. accordion body -->
                            </div><!-- /. collapse Four -->
                        </div><!-- /. accordion-item -->

                    </div><!-- /. accordion -->
                </div><!--main body-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary">Cancle</button>
                    <button type="submit" class="btn btn-fill btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setTestMaterial() {
        let strMaterialReceived = $("#Edit_strMaterialReceived_{{ $item->rma_detail_id }}").val();
        if (strMaterialReceived == "Yes") {
            $("#strMaterialReceivedDateDiv_{{ $item->rma_detail_id }}").show();
        } else if (strMaterialReceived == "No") {
            $("#strMaterialReceivedDateDiv_{{ $item->rma_detail_id }}").hide();
        } else {
            $("#strMaterialReceivedDateDiv_{{ $item->rma_detail_id }}").hide();
        }
    }

    function setTestTesting() {
        let strTesting = $("#Edit_strTesting_{{ $item->rma_detail_id }}").val();
        if (strTesting == 'Done') {
            $("#strTestingCompleteDateDiv_{{ $item->rma_detail_id }}").show();
        } else if (strTesting == "In Progress") {
            $("#strTestingCompleteDateDiv_{{ $item->rma_detail_id }}").hide();
        } else {
            $("#strTestingCompleteDateDiv_{{ $item->rma_detail_id }}").hide();
        }
    }

    function setFactoryMaterial() {
        let strMaterialReceived = $("#edit_Material_Received_{{ $item->rma_detail_id }}").val();
        if (strMaterialReceived == "Yes") {
            $("#strFactory_MaterialReceivedDateDiv_{{ $item->rma_detail_id }}").show();
        } else if (strMaterialReceived == "No") {
            $("#strFactory_MaterialReceivedDateDiv_{{ $item->rma_detail_id }}").hide();
        } else {
            $("#strFactory_MaterialReceivedDateDiv_{{ $item->rma_detail_id }}").hide();
        }
    }

    function setFactoryTesting() {
        let strTesting = $("#EditFactory_Testing_{{ $item->rma_detail_id }}").val();
        if (strTesting == 'Done') {
            $("#strFactory_TestingCompleteDateDiv_{{ $item->rma_detail_id }}").show();
        } else if (strTesting == "In Progress") {
            $("#strFactory_TestingCompleteDateDiv_{{ $item->rma_detail_id }}").hide();
        } else {
            $("#strFactory_TestingCompleteDateDiv_{{ $item->rma_detail_id }}").hide();
        }
    }

    function setCustomerMaterialDispatched() {
        let strMaterialDispatched = $("#EditMaterial_Dispatched_{{ $item->rma_detail_id }}").val();
        if (strMaterialDispatched == "Yes") {
            $("#strMaterialDispatchedDateDiv_{{ $item->rma_detail_id }}").show();
        } else if (strMaterialDispatched == "No") {
            $("#strMaterialDispatchedDateDiv_{{ $item->rma_detail_id }}").hide();
        } else {
            $("#strMaterialDispatchedDateDiv_{{ $item->rma_detail_id }}").hide();
        }
    }
</script>

<?php } ?>
