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
                                    <h3 class="m-0">List of Return Material Authorization</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <!--<div class="card mt-5">
                            <div class="card-body p-0">-->
                            <div class="wizard-container">
                            @include('call_attendant.callattendantcommon.alert')
                            
                            <div class="card wizard-card" data-color="orange" id="wizardProfile">
                                <h4 class="card-title mt-0">search by categories</h4>

                                <form class="was-validated p-4 pb-3" action="{{ route('rma.list') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Search by RMA ID</label>
                                                <input type="text" class="form-control" name="searchText" id="searchText"
                                                    value="{{ (isset($postarray['searchText']) && $postarray['searchText']) != null ? $postarray['searchText'] : '' }}" />
                                            </div>
                                        </div> <!-- /.col -->
                                        
                                        
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select Distributor</label>
                                                <select name="searchDistributor" id="searchDistributor"
                                                    class="js-example-basic-single" style="width: 100%;">
                                                    <option label="Please Select" value="">-- Select --</option>
                                                    @foreach ($distributorList as $list)
                                                        <option value="{{ $list->iDistributorId }}" {{ (isset($postarray['searchDistributor']) && $postarray['searchDistributor']) == $list->iDistributorId ? 'selected' : '' }}>
                                                            {{ $list->Name }}</option>
                                                    @endforeach
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select Month</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="searchMonth" id="searchMonth">
                                                    <option label="Please Select" value="">-- Select --</option>
                                                    <option value="4" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '04' ? 'selected' : '' }}>April</option>
                                                    <option value="5" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '05' ? 'selected' : '' }}>May</option>
                                                    <option value="6" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '06' ? 'selected' : '' }}>June</option>
                                                    <option value="7" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '07' ? 'selected' : '' }}>July</option>
                                                    <option value="8" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '08' ? 'selected' : '' }}>August</option>
                                                    <option value="9" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '09' ? 'selected' : '' }}>September</option>
                                                    <option value="10" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '10' ? 'selected' : '' }}>October</option>
                                                    <option value="11" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '11' ? 'selected' : '' }}>November</option>
                                                    <option value="12" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '12' ? 'selected' : '' }}>December</option>
                                                    <option value="1" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '01' ? 'selected' : '' }}>January</option>
                                                    <option value="2" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '02' ? 'selected' : '' }}>February</option>
                                                    <option value="3" {{ (isset($postarray['searchMonth']) && $postarray['searchMonth']) == '03' ? 'selected' : '' }}>March</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Search by Model No</label>
                                                <input type="text" class="form-control" name="searchModelNo" id="searchModelNo"
                                                    value="{{ (isset($postarray['searchModelNo']) && $postarray['searchModelNo']) != null ? $postarray['searchModelNo'] : '' }}" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select System</label>
                                                <select name="searchSystem"
                                                    class="js-example-basic-single" style="width: 100%;">
                                                    <option label="Please Select" value="">-- Select --</option>
                                                    @foreach ($systemList as $list)
                                                        <option value={{ $list->iSystemId }} {{ (isset($postarray['searchSystem']) && $postarray['searchSystem']) == $list->iSystemId ? 'selected' : '' }}>
                                                            {{ $list->strSystem }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Customer Status</label>
                                                <select class="js-example-basic-single"
                                                    style="width: 100%;" name="searchStatus">
                                                    <option label="Please Select" value="">--Select--</option>
                                                    <option value="Open" {{ (isset($postarray['searchStatus']) && $postarray['searchStatus']) == "Open" ? 'selected' : '' }}>Open</option>
                                                    <option value="Closed" {{ (isset($postarray['searchStatus']) && $postarray['searchStatus']) == "Closed" ? 'selected' : '' }}>Closed</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Factory RMA No</label>
                                                <input type="text" class="form-control"
                                                    name="searchFactoryRmaNo" value="{{ (isset($postarray['searchFactoryRmaNo']) && $postarray['searchFactoryRmaNo']) != null ? $postarray['searchFactoryRmaNo'] : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Testing Result</label>
                                                <select class="js-example-basic-single"
                                                    name="searchTestingResult" style="width: 100%;">
                                                    <option label="Please Select" value="">--Select--</option>
                                                    <option value="No Fault Found" {{ (isset($postarray['searchTestingResult']) && $postarray['searchTestingResult']) == "No Fault Found" ? 'selected' : '' }}>No Fault Found</option>
                                                    <option value="Customer Liability" {{ (isset($postarray['searchTestingResult']) && $postarray['searchTestingResult']) == "Customer Liability" ? 'selected' : '' }}>Customer Liability</option>
                                                    <option value="Product Failure" {{ (isset($postarray['searchTestingResult']) && $postarray['searchTestingResult']) == "Product Failure" ? 'selected' : '' }}>Product Failure</option>
                                                    <option value="Programming Issue" {{ (isset($postarray['searchTestingResult']) && $postarray['searchTestingResult']) == "Programming Issue" ? 'selected' : '' }}>Programming Issue</option>
                                                    <option value="Repair Locally" {{ (isset($postarray['searchTestingResult']) && $postarray['searchTestingResult']) == "Repair Locally" ? 'selected' : '' }}>Repair Locally</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                    </div>
                                    <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3"
                                        value="Search">
                                    <input type="button" onclick="clearData();"
                                        class="btn btn-fill btn-default text-uppercase mt-3" value="Clear">
                                </form>
                            </div>
                        </div>
                        <!--card end-->
                    </div>
                </div><!-- end row -->

            <?php
                $userid = Auth::user()->id;
                $record = DB::table('reorder_columns')->where('strUrl', 'rma.index')->where('iUserId', $userid)->first();

                $selectedOptions = [];
                if ($record) {
                    $selectedOptions = json_decode($record->json, true);
                }
                $options = ['NO', 'RMA ID', 'CUSTOMER', 'DISTRIBUTOR', 'ITEM', 'QUANTITY', 'SYSTEM', 'WARRANTY', 'APPROVED', 'CUSTOMER STATUS', 'HFI STATUS', 'TAT DAYS', 'ACTIONS'];

                $optionLabels = [
                    'NO' => 'No',
                    'RMA ID' => 'RmaId',
                    'CUSTOMER' => 'Customer',
                    'DISTRIBUTOR' => 'Distributor',
                    'ITEM' => 'Item',
                    'QUANTITY' => 'Quantity',
                    'SYSTEM' => 'System',
                    'WARRANTY' => 'Warranty',
                    'APPROVED' => 'Approved',
                    'CUSTOMER STATUS' => 'CustomerStatus',
                    'HFI STATUS' => 'HfiStatus',
                    'TAT DAYS' => 'TatDays',
                    'ACTIONS' => 'Actions',
                ];

                // Use default options if no user-specific data is available
                if (empty($selectedOptions)) {
                    $selectedOptions = $options;
                }
                ?>

                <!-- multi-select row -->
                <div class="row d-flex justify-content-center mt-5">
                    <div class="col-md-10">
                        <div class="row justify-content-center">
                            <div class="col-md-9 d-flex justify-content-center align-items-center">
                                <div class="d-flex text-left align-items-center w-100">
                                    <strong class="sl">Reorder Columns:</strong>

                                    <?php if(!$record){ ?>
                                    <select id="multiple-checkboxes" multiple="multiple" required>
                                        <option value="NO">NO</option>
                                        <option value="RMA ID">RMA ID</option>
                                        <option value="CUSTOMER">CUSTOMER</option>
                                        <option value="DISTRIBUTOR">DISTRIBUTOR</option>
                                        <option value="ITEM">ITEM</option>
                                        <option value="QUANTITY">QUANTITY</option>
                                        <option value="SYSTEM">SYSTEM</option>
                                        <option value="WARRANTY">WARRANTY</option>
                                        <option value="APPROVED">APPROVED</option>
                                        <option value="CUSTOMER STATUS">CUSTOMER STATUS</option>
                                        <option value="HFI STATUS">HFI STATUS</option>
                                        <option value="TAT DAYS">TAT DAYS</option>
                                        <option value="ACTIONS">ACTIONS</option>
                                    </select>
                                    <?php }else{ ?>
                                    <select id="multiple-checkboxes" multiple="multiple" required>
                                        <?php
                                        foreach ($options as $option) {
                                            $selected = in_array($option, $selectedOptions) ? 'selected' : '';
                                            echo "<option value=\"$option\" $selected>$option</option>";
                                        }
                                        ?>
                                    </select>
                                    <?php } ?>

                                    <input type="button" id="submitCheckboxes"
                                        class="btn btn-fill btn-success text-uppercase ml-3 d-flex justify-content-center align-items-center"
                                        value="Save">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- multi-select row END -->
                <!--table start-->
                <div class="row d-flex justify-content-center my-5">
                    <div class="col-md-10">
                        <div class="fresh-table toolbar-color-orange">
                            <table id="fresh-table" class="table">
                                <thead>
                                    <?php
                                    foreach ($selectedOptions as $option) {
                                        $label = $optionLabels[$option];
                                        echo "<th data-field=\"{$option}\">{$label}</th>";
                                    }
                                    ?>
                                </thead>
                                <tbody>
                                    @foreach ($groupedRmaList as $rmaId => $group)
                                        @php

                                            $rma = $group['rma'];
                                            $details = $group['rma_details'];

                                        @endphp
                                        <tr>
                                            <?php if (in_array('NO', $selectedOptions)) { ?>
                                                <td>{{ $loop->iteration }}</td>
                                            <?php } ?>
                                            <?php if (in_array('RMA ID', $selectedOptions)) { ?>
                                                <td>{{ $rma->iRMANumber ?? '-' }}</td>
                                            <?php } ?>
                                            <?php if (in_array('CUSTOMER', $selectedOptions)) { ?>
                                                <td>{{ $rma->strCustomerCompany ?? '-' }}</td>
                                            <?php } ?>
                                            <?php if (in_array('DISTRIBUTOR', $selectedOptions)) { ?>
                                                <td>{{ $rma->distributor_name ?? '-' }}</td>
                                            <?php } ?>
                                            <?php if (in_array('ITEM', $selectedOptions)) { ?>
                                                <td>
                                                    <span>{{ $rma->strItem ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strItem ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            <?php } ?>
                                            <?php if (in_array('QUANTITY', $selectedOptions)) { ?>
                                                <td>
                                                    <span>{{ $rma->strQuantity ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strQuantity ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            <?php } ?>
                                            <?php if (in_array('SYSTEM', $selectedOptions)) { ?>
                                                <td>
                                                    <span>{{ $rma->strSystem ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{$detail->strSystem ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            <?php } ?>
                                            <?php if (in_array('WARRANTY', $selectedOptions)) { ?>
                                                <td>
                                                    <span>{{ $rma->strInwarranty ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strInwarranty ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            <?php } ?>
                                            <?php if (in_array('APPROVED', $selectedOptions)) { ?>
                                                <td>
                                                    <span>{{ $rma->strReplacementApproved ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strReplacementApproved ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            <?php } ?>
                                            <?php if (in_array('CUSTOMER STATUS', $selectedOptions)) { ?>
                                                <td>
                                                    <span>{{ $rma->strStatus ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strStatus ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            <?php } ?>
                                            <?php if (in_array('HFI STATUS', $selectedOptions)) { ?>
                                                <td>
                                                    <span>{{ $rma->Factory_Status ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{$detail->Additional_Factory_Status ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            <?php } ?>
                                            @if (in_array('TAT DAYS', $selectedOptions))
                                                <td>
                                                    @php
                                                        $tatDays = '-';
                                                        if (!empty($rma->strMaterialDispatchedDate) && !empty($rma->strRMARegistrationDate)) {
                                                            $registrationDate = Carbon::parse($rma->strRMARegistrationDate);
                                                            $dispatchedDate = Carbon::parse($rma->strMaterialDispatchedDate);
                                                            $tatDays = $dispatchedDate->diffInDays($registrationDate) . ' days';
                                                        }
                                                    @endphp
                                                    <span>{{ $tatDays }}</span>

                                                    @foreach ($details as $detail)
                                                        @php
                                                            $detailTatDays = '-';
                                                            if (!empty($detail->strMaterialDispatchedDate) && !empty($detail->strRMARegistrationDate)) {
                                                                $detailRegistrationDate = Carbon::parse($detail->strRMARegistrationDate);
                                                                $detailDispatchedDate = Carbon::parse($detail->strMaterialDispatchedDate);
                                                                $detailTatDays = $detailDispatchedDate->diffInDays($detailRegistrationDate) . ' days';
                                                            }
                                                        @endphp
                                                        <br><span>{{ $detailTatDays }}</span>
                                                    @endforeach
                                                </td>
                                            @endif
                                            <?php if (in_array('ACTIONS', $selectedOptions)) { ?>
                                                <td>
                                                    <a href="{{ route('rma.rma_info', $rma->rma_id) }}" title="Info" class="table-action">
                                                        <i class="mas-info-circle"></i>
                                                    </a>
                                                    @if ($rma->strStatus != 'Closed')
                                                        <a href="{{ route('rma.additional_rma', $rma->rma_id) }}" title="Additional RMA" class="table-action">
                                                            <i class="mas-plus"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            <?php } ?>
                                        </tr>
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
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
    <script type="text/javascript">
        /*$(document).ready(function() {
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
        }); */
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
        /*$("#iComplaintId").on("change", function(e) {
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
        });*/
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
    /*$(document).ready(function() {
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
    });*/
</script>

    <script>
    function clearData() {
        window.location.href = "";
    }
        /*$("#OemCompannyId").change(function() {
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
        });*/
    </script>
    <script>
    
        /*// Trigger AJAX request after page load if a value is already selected
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
        });*/
    </script>
    <script>
        /*document.addEventListener('DOMContentLoaded', function() {
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

        });*/
    </script>

 {{-- start new code 20-02-2025 --}}
     <!-- multi-select  -->
     <script src="{{ asset('global/assets/vendors/multi-select/js/bootstrap-multiselect.js') }}"></script>
     <script src="{{ asset('global/assets/vendors/multi-select/js/main.js') }}"></script>

     <script>
         /*$(document).ready(function() {
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
         });*/
     </script>
 {{-- End new code 20-02-2025 --}}
@endsection
