@extends('layouts.callAttendant')

@section('title', 'My Ticket')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- css for this page -->
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <!--bootstrap table-->
    <link href="{{ asset('global/assets/vendors/bootstrap-table/css/fresh-bootstrap-table.css') }}" rel="stylesheet" />
    <!--date range picker-->
    <link rel="stylesheet" type="text/css" href="{{ asset('global/assets/vendors/date-picker/daterangepicker.css') }}" />
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex justify-content-center">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-sm-5 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <h3 class="m-0">List of Complaints</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <!--<div class="card mt-5">-->
                        <!--    <div class="card-body p-0">-->
                        <div class="wizard-container">
                            @include('call_attendant.callattendantcommon.alert')
                            <div class="card wizard-card" data-color="orange" id="wizardProfile">
                                <h4 class="card-title mt-0">search by categories</h4>
                                <form class="was-validated p-4 pb-3" action="{{ route('my-tickets.index') }}"
                                    method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Search by Contact No / Complaint ID</label>
                                                <input type="text" class="form-control" name="searchText" id="searchText"
                                                    value="{{ (isset($postarray['searchText']) && $postarray['searchText']) != null ? $postarray['searchText'] : '' }}" />
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select OEM Company</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="OemCompannyId" id="OemCompannyId">
                                                    <option label="Please Select" value="">-- Select --</option>
                                                    @foreach ($CompanyMaster as $company)
                                                        @if (session()->has('CompanyId') && session('CompanyId') == $company->iCompanyId)
                                                            <option value="{{ $company->iCompanyId }}" {{ 'selected' }}>
                                                                {{ $company->strOEMCompanyName }}</option>
                                                        @else
                                                            <option value="{{ $company->iCompanyId }}"
                                                                {{ isset($postarray['OemCompannyId']) && $postarray['OemCompannyId'] == $company->iCompanyId ? 'selected' : '' }}>
                                                                {{ $company->strOEMCompanyName }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select System</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="iSystemId" id="iSystemId">
                                                    <option label="Please Select" value="">-- Select --</option>
                                                    @foreach ($systemLists as $system)
                                                        <option value="{{ $system->iSystemId }}"
                                                            {{ isset($postarray['iSystemId']) && $postarray['iSystemId'] == $system->iSystemId ? 'selected' : '111' }}>
                                                            {{ $system->strSystem }}</option>
                                                    @endforeach
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select Component</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="iComponentId" id="iComponentId">
                                                    <option label="Please Select" value="">-- Select --</option>
                                                    @if (isset($postarray['iComponentId']))
                                                        @foreach ($componentLists as $compo)
                                                            <option value="{{ $compo->iComponentId }}"
                                                                {{ $postarray['iComponentId'] == $compo->iComponentId ? 'selected' : '' }}>
                                                                {{ $compo->strComponent }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select Sub Component</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="iSubComponentId" id="iSubComponentId">
                                                    <option label="Please Select" value="">-- Select --</option>
                                                    @if (isset($postarray['iSubComponentId']))
                                                        @foreach ($subcomponents as $subcompo)
                                                            <option value="{{ $subcompo->iSubComponentId }}"
                                                                {{ $postarray['iSubComponentId'] == $subcompo->iSubComponentId ? 'selected' : '' }}>
                                                                {{ $subcompo->strSubComponent }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select Status</label>
                                                <select class="js-example-basic-single" style="width: 100%;" name="level">
                                                    <option label="Please Select" value="">-- Select --</option>
                                                    <option value="0"
                                                        {{ isset($postarray['level']) && $postarray['level'] == 0 ? 'selected' : '' }}>
                                                        Open</option>
                                                    <option value="3"
                                                        {{ isset($postarray['level']) && $postarray['level'] == 3 ? 'selected' : '' }}>
                                                        Reopen</option>
                                                    <option value="1"
                                                        {{ isset($postarray['level']) && $postarray['level'] == 1 ? 'selected' : '' }}>
                                                        Closed</option>
                                                    <option value="5"
                                                        {{ isset($postarray['level']) && $postarray['level'] == 5 ? 'selected' : '' }}>
                                                        Customer Feedback Awaited </option>
                                                    <option value="4"
                                                        {{ isset($postarray['level']) && $postarray['level'] == 4 ? 'selected' : '' }}>
                                                        Closed with RMA</option>
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select Date Range</label>
                                                <input type="text" class="form-control" name="daterange" id="datepicker"
                                                    value="{{ isset($postarray['daterange']) ? $postarray['daterange'] : '' }}" />
                                            </div>
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
                $record = DB::table('reorder_columns')->where('strUrl', 'my-tickets.index')->where('iUserId', $userid)->first();
                
                $selectedOptions = [];
                if ($record) {
                    $selectedOptions = json_decode($record->json, true); // Decode JSON as an associative array
                }
                
                $options = ['NO', 'ID', 'STATUS', 'CONTACT NAME', 'CONTACT NO', 'OEM COMPANY', 'COMPANY NAME','PROJECT', 'SYSTEM', 'COMPONENT', 'SUB COMPONENT', 'SOLUTION TYPE', 'COMPLAINT DATE', 'RESOLVED DATE', 'ISSUE', 'ATTENDANT', 'ACTIONS'];
                $optionLabels = [
                    'NO' => 'No',
                    'ID' => 'ID',
                    'STATUS' => 'Status',
                    'CONTACT NAME' => 'Contact Name',
                    'CONTACT NO' => 'Contact No',
                    'OEM COMPANY' => 'OEM Company',
                    'COMPANY NAME' => 'Company Name',
                    'PROJECT' => 'Project',
                    'SYSTEM' => 'System',
                    'COMPONENT' => 'Component',
                    'SUB COMPONENT' => 'Sub Component',
                    'SOLUTION TYPE' => 'Solution type',
                    'COMPLAINT DATE' => 'Complaint Date',
                    'RESOLVED DATE' => 'Resolved Date',
                    'ISSUE' => 'Issue',
                    'ATTENDANT' => 'Attendant',
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
                                        <option value="ID">ID</option>
                                        <option value="STATUS">STATUS</option>
                                        <option value="CONTACT NAME">CONTACT NAME</option>
                                        <option value="CONTACT NO">CONTACT NO</option>
                                        <option value="OEM COMPANY">OEM COMPANY</option>
                                        <option value="COMPANY NAME">COMPANY NAME</option>
                                        <option value="PROJECT">PROJECT</option>
                                        <option value="SYSTEM">SYSTEM</option>
                                        <option value="COMPONENT">COMPONENT</option>
                                        <option value="SUB COMPONENT">SUB COMPONENT</option>
                                        <option value="SUPPORT TYPE">SUPPORT TYPE</option>
                                        <option value="COMPLAINT DATE">COMPLAINT DATE</option>
                                        <option value="RESOLVED DATE">RESOLVED DATE</option>
                                        <option value="ISSUE">ISSUE</option>
                                        <option value="ATTENDANT">ATTENDANT</option>
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
                            </div> <!-- /. col-md-7 -->
                        </div> <!-- /. row justify content -->
                    </div> <!-- /. col-md-10 -->
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
                                    <?php $iCounter = 1; ?>
                                    @if (count($ticketList) > 0)
                                        @foreach ($ticketList as $ticket)
                                            <tr>

                                                <?php if (in_array('NO', $selectedOptions)) { ?>
                                                <td><?= $iCounter ?></td>
                                                <?php } ?>

                                                <?php if (in_array('ID', $selectedOptions)) { ?>
                                                <td><?= isset($ticket->strTicketUniqueID) && $ticket->strTicketUniqueID != "" ? $ticket->strTicketUniqueID : str_pad($ticket->iTicketId, 4, '0', STR_PAD_LEFT) ?>
                                                </td>
                                                <?php } ?>

                                                <?php if (in_array('STATUS', $selectedOptions)) { ?>
                                                <td><?= $ticket->ticketName ?? '-' ?></td>
                                                <?php } ?>

                                                <?php if (in_array('CONTACT NAME', $selectedOptions)) { ?>
                                                <td><?= $ticket->CustomerName ?? '-' ?></td>
                                                <?php } ?>

                                                <?php if (in_array('CONTACT NO', $selectedOptions)) { ?>
                                                <td><?= $ticket->CustomerMobile ?? '-' ?></td>
                                                <?php } ?>

                                                <?php if (in_array('OEM COMPANY', $selectedOptions)) { ?>
                                                <td><?= $ticket->strOEMCompanyName ?? '-' ?></td>
                                                <?php } ?>
                                                <?php if (in_array('COMPANY NAME', $selectedOptions)) { ?>
                                                <td><?= $ticket->CompanyName ?? '-' ?></td>
                                                <?php } ?>
                                                <?php if (in_array('PROJECT', $selectedOptions)) {
                                                    echo "<td>{$ticket->ProjectName}</td>";
                                                } ?>

                                                <?php if (in_array('SYSTEM', $selectedOptions)) { ?>
                                                <td><?= $ticket->strSystem ?? '-' ?></td>
                                                <?php } ?>

                                                <?php if (in_array('COMPONENT', $selectedOptions)) { ?>
                                                <td><?= $ticket->strComponent ?? '-' ?></td>
                                                <?php } ?>

                                                <?php if (in_array('SUB COMPONENT', $selectedOptions)) { ?>
                                                <td><?= $ticket->strSubComponent ?? '-' ?></td>
                                                <?php } ?>

                                                <?php if (in_array('SOLUTION TYPE', $selectedOptions)) { ?>
                                                <td><?= $ticket->strResolutionCategory ?? '-' ?></td>
                                                <?php } ?>

                                                <?php if (in_array('COMPLAINT DATE', $selectedOptions)) { ?>
                                                <td>
                                                    <?= date('d-m-Y', strtotime($ticket->ComplainDate)) ?><br><small><?= date('H:i:s', strtotime($ticket->ComplainDate)) ?></small>
                                                </td>
                                                <?php }  ?>

                                                <?php if (in_array('COMPLAINT DATE', $selectedOptions)) { ?>
                                                @if ($ticket->ResolutionDate != null && in_array($ticket->finalStatus, [1, 2, 4]))
                                                    <td>{{ date('d-m-Y', strtotime($ticket->ResolutionDate)) }}<br>
                                                        <small>{{ date('H:i:s', strtotime($ticket->ResolutionDate)) }}</small>
                                                    </td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                                <?php }  ?>

                                                <?php if (in_array('ISSUE', $selectedOptions)) { ?>
                                                <td><?= $ticket->strIssueType ?? '-' ?></td>
                                                <?php } ?>

                                                <?php if (in_array('ATTENDANT', $selectedOptions)) { ?>
                                                <td><?= $ticket->first_name . ' ' . $ticket->last_name ?></td>
                                                <?php } ?>

                                                <?php if (in_array('ACTIONS', $selectedOptions)) { ?>
                                                <td>
                                                    <a href="{{ route('complaint.info', $ticket->iTicketId) }}"
                                                        title="Info" class="table-action">
                                                        <i class="mas-info-circle"></i>
                                                    </a>
                                                    @if (in_array($ticket->finalStatus, [1, 2, 4]))
                                                        <a href="{{ route('complaint.edit', $ticket->iTicketId) }}"
                                                            title="Reopen" class="table-action">
                                                            <i class="mas-reopen"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('complaint.edit', $ticket->iTicketId) }}"
                                                            title="Edit" class="table-action">
                                                            <i class="mas-edit"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <?php } ?>
                                            </tr>
                                            <?php $iCounter++; ?>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--table end-->
                <!--notify messages-->

                <!--END modal-->
            </div>
            <!--END notify messages-->
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="container">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                        2022 Mas Solutions. All rights reserved.</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                            href="https://www.excellentcomputers.co.in/" target="_blank"> Excellent Computers </a> </span>
                </div>
            </div>
        </footer>
        <!-- partial -->

    </div>
    <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    <!-- back to top button -->
    <a id="button"></a>
    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/custom.js') }}"></script>

    <!--Plugin js for this page -->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>

    <!--form validation-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.validate.min.js') }}"></script>

    <!--date range picker-->
    <script type="text/javascript" src="{{ asset('global/assets/vendors/date-picker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('global/assets/vendors/date-picker/daterangepicker.min.js') }}"></script>

    <!--table plugin-->
    <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
    </script>


    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js">
    </script>

    <script type="text/javascript">
        $(function() {
            $(function() {
                $("#datepicker").daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });
                $('#datepicker').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate
                        .format('MM/DD/YYYY'));
                });
                $('#datepicker').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
            });
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
                        filename: "My_Ticket_" + new Date().toISOString().replace(/[\-\:\.]/g, "") +
                            ".xls",
                        fileext: ".xls",
                        exclude_img: true,
                        exclude_links: false,
                        exclude_inputs: false,
                        preserveColors: false
                    });
                }
            });
            $('.mas-refresh').click(function(e) {
                $('#fresh-table').bootstrapTable('resetSearch');
            });
        });

        function clearData() {
            window.location.href = "";
        }
        $("#OemCompannyId").change(function() {
            $("#CustomerEmailCompany").val('');
            $.ajax({
                type: 'POST',
                url: "{{ route('company.getCompany') }}",
                data: {
                    iCompanyId: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    let dataItems = JSON.parse(response);
                    $("#iSystemId").html(dataItems.system);
                    $("#iComponentId").html(dataItems.component);

                }

            });
        });
        $("#iSystemId").change(function() {
            $.ajax({
                type: 'POST',
                url: "{{ route('company.getcomponent') }}",
                data: {
                    search_system: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (response.length > 0) {
                        $("#iComponentId").html(response);
                    } else {

                    }
                }
            });
        });
        $("#iComponentId").change(function() {

            $.ajax({
                type: 'POST',
                url: "{{ route('faq.getsubcomponent') }}",
                data: {
                    iComponentId: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    $("#iSubComponentId").html(response);
                }
            });

        });
    </script>

    <!-- multi-select  -->
    <script src="{{ asset('global/assets/vendors/multi-select/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/multi-select/js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#submitCheckboxes').click(function(e) {
                e.preventDefault();

                var selectedCheckboxes = $('#multiple-checkboxes').val();

                $.ajax({
                    url: "{{ route('my-tickets.reorder_column') }}", // Replace with your route
                    type: 'POST',
                    data: {
                        checkboxes: selectedCheckboxes,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        window.location = "";
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Handle error
                    }
                });
            });
        });
    </script>
@endsection
