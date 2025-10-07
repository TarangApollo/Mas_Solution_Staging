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
                <div class="d-flex flex-row-reverse">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-md-2 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <p class="m-0 pr-8">List of Complaints</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <div class="card mt-5">
                            <div class="card-body p-0">
                                <h4 class="card-title mt-0">search by categories</h4>
                                <form class="was-validated p-4 pb-3" action="{{route('my-tickets.index' )}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Search by Contact No / Complaint ID</label>
                                            <input type="text" class="form-control" name="searchText" id="searchText" value="{{ (isset($postarray['searchText']) && $postarray['searchText']) != null ? $postarray['searchText'] : '' }}" />
                                        </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select OEM Company</label>
                                            <select class="js-example-basic-single" style="width: 100%;"  name="OemCompannyId" id="OemCompannyId">
                                                <option label="Please Select" value="">-- Select --</option>
                                                @foreach ($CompanyMaster as $company)
                                                <option value="{{ $company->iCompanyId }}" {{ (isset($postarray['OemCompannyId']) && $postarray["OemCompannyId"] == $company->iCompanyId) ? 'selected' : '' }}>{{ $company->strOEMCompanyName }}</option>
                                                @endforeach
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select System</label>
                                            <select class="js-example-basic-single" style="width: 100%;"  name="iSystemId" id="iSystemId">
                                                <option label="Please Select" value="">-- Select --</option>
                                                @foreach ($systemLists as $system)
                                                    <option value="{{ $system->iSystemId }}"
                                                        {{ isset($postarray['iSystemId']) && $postarray['iSystemId'] == $system->iSystemId ? 'selected' : '111' }}>
                                                        {{ $system->strSystem }}</option>
                                                @endforeach
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Component</label>
                                            <select class="js-example-basic-single" style="width: 100%;"  name="iComponentId" id="iComponentId">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Sub Component</label>
                                            <select class="js-example-basic-single" style="width: 100%;"  name="iSubComponentId" id="iSubComponentId">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Status</label>
                                            <select class="js-example-basic-single" style="width: 100%;" name="level"  >
                                                <option label="Please Select" value="">-- Select --</option>
                                                <option value="0" {{ (isset($postarray['level']) && $postarray["level"] == 0) ? 'selected' : '' }}>Open</option>
                                                <option value="3" {{ (isset($postarray['level']) && $postarray["level"] == 3) ? 'selected' : '' }}>Reopen</option>
                                                <option value="1" {{ (isset($postarray['level']) && $postarray["level"] == 1) ? 'selected' : '' }}>Closed</option>
                                                <option value="5" {{ (isset($postarray['level']) && $postarray["level"] == 5) ? 'selected' : '' }}>Customer Feedback Awaited </option>
                                                <option value="4" {{ (isset($postarray['level']) && $postarray["level"] == 4) ? 'selected' : '' }}>Closed with RMA</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Date Range</label>
                                            <input type="text" class="form-control" name="daterange" id="datepicker" value="{{ isset($postarray['daterange']) ? $postarray['daterange'] : '' }}"/>
                                        </div>
                                    </div> <!-- /.col -->
                                </div>
                                <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3" value="Search">
                                <input type="button" onclick="clearData();" class="btn btn-fill btn-default text-uppercase mt-3" value="Clear">
                                </form>
                            </div>
                        </div>
                        <!--card end-->
                    </div>
                </div><!-- end row -->

                <!--table start-->
                <div class="row d-flex justify-content-center my-5">
                    <div class="col-md-10">

                        <div class="fresh-table toolbar-color-orange">
                            <table id="fresh-table" class="table">
                                <thead>
                                    <th data-field="id">No</th>
                                    <th data-field="no" data-sortable="true">ID</th>
                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th data-field="contactname" data-sortable="true">Contact Name</th>
                                    <th data-field="contact" data-sortable="true">Contact No</th>
                                    <th data-field="company" data-sortable="true">OEM Company</th>
                                    <th data-field="companyname" data-sortable="true">Company Name</th>
                                    <th data-field="system" data-sortable="true">System</th>
                                    <th data-field="component" data-sortable="true">Component</th>
                                    <th data-field="subComponent" data-sortable="true">Sub Component</th>
                                    <th data-field="complaint">Solution Type</th>
                                    <th data-field="date" data-sortable="true">Complaint Date</th>
                                    <th data-field="resolvedDate" data-sortable="true">Resolved Date</th>
                                    <th data-field="issue">Issue</th>
                                    <th data-field="attendant" data-sortable="true">Attendant</th>
                                    <th data-field="actions">Actions</th>
                                </thead>
                                <tbody>
                                <?php $iCounter = 1; ?>
                                @if(count($ticketList)>0)
                                @foreach($ticketList as $ticket)
                                <tr>
                                    <td>{{$iCounter}}</td>
                                    <td>{{str_pad($ticket->iTicketId, 4, "0", STR_PAD_LEFT)}}</td>
                                    <td>{{$ticket->ticketName}}</td>
                                    <td>{{$ticket->CustomerName}}</td>
                                    <td>{{$ticket->CustomerMobile}}</td>
                                    <td>{{$ticket->strOEMCompanyName}}</td>
                                    <td>{{$ticket->CompanyName}}</td>
                                    <td>{{$ticket->strSystem}}</td>
                                    <td>{{$ticket->strComponent}}</td>
                                    <td>{{$ticket->strSubComponent}}</td>
                                    <td>{{$ticket->strResolutionCategory}}</td>
                                    <td>{{date('d-m-Y',strtotime($ticket->ComplainDate))}}<br>
                                        <small>{{date('h:i:s', strtotime($ticket->ComplainDate))}}</small>
                                    </td>
                                    @if($ticket->ResolutionDate != NULL && in_array($ticket->finalStatus,array(1,2,4)))
                                    <td>{{date('d-m-Y',strtotime($ticket->ResolutionDate))}}<br>
                                        <small>{{date('h:i:s', strtotime($ticket->ResolutionDate))}}</small>
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    <td>{{$ticket->strIssueType}}</td>
                                    <td>{{$ticket->first_name . ' ' . $ticket->last_name}}</td>
                                    <td>
                                        <a href="{{route('complaint.info',$ticket->iTicketId)}}" title="Info" class="table-action">
                                            <i class="mas-info-circle"></i>
                                        </a>
                                        @if(in_array($ticket->finalStatus,array(1,2,4)))
                                        <a href="{{route('complaint.edit',$ticket->iTicketId)}}" title="Reopen" class="table-action">
                                            <i class="mas-reopen"></i>
                                        </a>
                                        @else
                                        <a href="{{route('complaint.edit',$ticket->iTicketId)}}" title="Edit" class="table-action">
                                            <i class="mas-edit"></i>
                                        </a>
                                        @endif
                                    </td>
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
                                href="#"> Excellent Computers </a> </span>
                    </div>
                </div>
            </footer>
            <!-- partial -->

        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->

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
    <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>

    <script type="text/javascript">
        $(function(){
            $(function(){
                $( "#datepicker" ).daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $('#datepicker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
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

    $(function() {
        $('.mas-download').click(function(e) {
            var table = $("#fresh-table");
            if (table && table.length) {
                $(table).table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "My_Ticket_" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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
            url: "{{route('company.getCompany')}}",
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
            url: "{{route('company.getcomponent')}}",
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
            url: "{{route('faq.getsubcomponent')}}",
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
@endsection
