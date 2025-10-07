@extends('layouts.wladmin')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="container-scroller">

            <div class="container-fluid page-body-wrapper">

                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper pb-0">
                        <div class="page-header">
                            <h3>RMA Summary</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">RMA</li>
                                    <li class="breadcrumb-item active"> RMA Summary </li>
                                </ol>
                            </nav>
                        </div>
                        <!-- first row starts here -->

                        <div class="row d-flex justify-content-center">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <h4 class="card-title mt-0">search by categories</h4>
                                        <form class="was-validated p-4 pb-3" action="{{ route('Wl_RMA.index') }}"
                                            method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Search by Customer Name / RMA No</label>
                                                        <input type="text" class="form-control"
                                                            name="Customer_Name_and_RMA_No">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Select Customer Status</label>
                                                        <select class="js-example-basic-single" style="width: 100%;"
                                                            name="Customer_Status">
                                                            <option label="Please Select" value="">-- Select --
                                                            </option>
                                                            <option
                                                                value="Open"{{ old('Customer_Status', $search_Customer_Status) == 'Open' ? 'selected' : '' }}>
                                                                Open</option>
                                                            <option
                                                                value="Closed"{{ old('Customer_Status', $search_Customer_Status) == 'Closed' ? 'selected' : '' }}>
                                                                Closed</option>
                                                        </select>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Select HFI Status</label>
                                                        <select class="js-example-basic-single" style="width: 100%;"
                                                            name="HFI_Status">
                                                            <option label="Please Select" value="">-- Select --
                                                            </option>
                                                            <option value="BJ">Status 1</option>
                                                            <option value="SM">Status 2</option>
                                                        </select>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Select Financial Year</label>
                                                        <select class="js-example-basic-single" style="width: 100%;"
                                                            name="fYear" id="">
                                                            @foreach ($yearList as $year)
                                                                <option value="{{ $year->iYearId }}">{{ $year->yearName }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Select Month</label>
                                                        <?php $month = 0; ?>
                                                        <select class="js-example-basic-single" style="width: 100%;"
                                                            required name="fMonth" id="fMonth">
                                                            <option label="Please Select" value="">-- Select --
                                                            </option>
                                                            <option value="4"
                                                                {{ $searchMonth == '04' ? 'selected' : '' }}>
                                                                April</option>
                                                            <option value="5"
                                                                {{ $searchMonth == '05' ? 'selected' : '' }}>
                                                                May</option>
                                                            <option value="6"
                                                                {{ $searchMonth == '06' ? 'selected' : '' }}>
                                                                June</option>
                                                            <option value="7"
                                                                {{ $searchMonth == '07' ? 'selected' : '' }}>
                                                                July</option>
                                                            <option value="8"
                                                                {{ $searchMonth == '08' ? 'selected' : '' }}>
                                                                August</option>
                                                            <option value="9"
                                                                {{ $searchMonth == '09' ? 'selected' : '' }}>
                                                                September</option>
                                                            <option
                                                                value="10"{{ $searchMonth == '10' ? 'selected' : '' }}>
                                                                October</option>
                                                            <option value="11"
                                                                {{ $searchMonth == '11' ? 'selected' : '' }}>
                                                                November</option>
                                                            <option value="12"
                                                                {{ $searchMonth == '12' ? 'selected' : '' }}>
                                                                December</option>
                                                            <option value="1"
                                                                {{ $searchMonth == '01' ? 'selected' : '' }}>
                                                                January</option>
                                                            <option value="2"
                                                                {{ $searchMonth == '02' ? 'selected' : '' }}>
                                                                February</option>
                                                            <option value="3"
                                                                {{ $searchMonth == '03' ? 'selected' : '' }}>
                                                                March</option>
                                                        </select>
                                                    </div> <!-- /.form-group -->
                                                </div> <!-- /.col -->
                                            </div>
                                            <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2"
                                                value="Search">
                                            <input type="button" class="btn btn-fill btn-default text-uppercase mt-3"
                                                onclick="clearData();" value="Clear">
                                        </form>
                                    </div>
                                </div><!--card end-->
                            </div>
                        </div><!-- end row -->
                        <!--table start-->

                        <div class="row d-flex justify-content-center my-5">
                            <div class="col-md-12">
                                <div class="fresh-table toolbar-color-orange">
                                    <table id="fresh-table" class="table">
                                        <thead>
                                            <th data-field="id">No</th>
                                            <th data-field="customer" data-sortable="true">Customer</th>
                                            <th data-field="month" data-sortable="true">Month</th>
                                            <th data-field="rma-no" data-sortable="true">RMA No</th>
                                            <th data-field="item" data-sortable="true">Item</th>
                                            <th data-field="quantity" data-sortable="true">Quantity</th>
                                            <th data-field="warranty" data-sortable="true">In Warranty</th>
                                            <th data-field="cus-status" data-sortable="true">Customer Status</th>
                                            <th data-field="hfi-status" data-sortable="true">HFI Status</th>
                                            <th data-field="action">Actions</th>
                                        </thead>

                                        <tbody>
                                            @foreach ($rmsData as $key => $row)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $row->CustomerName ?? '-' }}</td>
                                                    <td>{{ isset($row->created_at) ? \Carbon\Carbon::parse($row->created_at)->format('F') : '-' }}
                                                    </td>
                                                    <td>{{ $row->iRMANumber ?? '-' }}</td>
                                                    <td>{{ $row->strItem ?? '-' }}</td>
                                                    <td>{{ $row->strQuantity ?? '-' }}</td>
                                                    <td>{{ $row->strInwarranty ? 'Yes' : 'No' }}</td>
                                                    <td>{{ $row->strStatus ?? '-' }}</td>
                                                    <td>{{ $row->hfi_status ?? '-' }}</td>
                                                    <td>
                                                        <a href="{{ route('Wl_RMA.rma_summary_info', $row->rma_id) }}"
                                                            title="Info" class="table-action">
                                                            <i class="mas-info-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        {{-- <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Newage Fire Protection Pvt Ltd</td>
                                                <td>June</td>
                                                <td>RN0015</td>
                                                <td>1</td>
                                                <td>25</td>
                                                <td>Yes</td>
                                                <td>Open</td>
                                                <td>HFI Status</td>
                                                <td>
                                                    <a href="rma-summary-info.html" title="Info" class="table-action">
                                                        <i class="mas-info-circle"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                        </tbody> --}}
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--table end-->
                    </div>
                    <!-- content-wrapper ends -->

                    <!--/. footer ends-->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        <script src="../global/assets/vendors/js/vendor.bundle.base.js"></script>
        <script src="../global/assets/js/jquery.cookie.js" type="text/javascript"></script>
        <script src="../global/assets/js/settings.js"></script>
        <script src="../global/assets/js/custom.js"></script>
        <script src="../global/assets/js/off-canvas.js"></script>
        <script src="../global/assets/js/hoverable-collapse.js"></script>
        <script src="../global/assets/vendors/wizard/js/bootstrap.min.js" type="text/javascript"></script>

        <!-- Plugin js for this page -->
        <!--select 2 form-->


        <!--form validation-->
        <script src="../global/assets/vendors/wizard/js/jquery.validate.min.js"></script>

        <!--date range picker-->
        <script type="text/javascript" src="../global/assets/vendors/date-picker/moment.min.js"></script>
        <script type="text/javascript" src="../global/assets/vendors/date-picker/daterangepicker.min.js"></script>

        <!--table plugin-->
        <script type="text/javascript" src="../global/assets/vendors/bootstrap-table/js/bootstrap-table.js"></script>
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

        <!--toggle button active/inactive-->
        <script src="../global/assets/vendors/toggle-button/bootstrap4-toggle.min.js"></script>
    </body>
    <script>
        function clearData() {
            window.location.href = "";
        }
    </script>



@endsection
@section('script')

    <!-- multi-select  -->
    <script src="{{ asset('global/assets/vendors/multi-select/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/multi-select/js/main.js') }}"></script>

    <!--table plugin-->
    <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
    </script>

    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <?php  if (in_array('46', \Session::get('menuList')) ) { ?>
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
                // pageSize: 8,
                // pageList: [8, 10, 25, 50, 100],
                pageSize: 50,
                pageList: [50, 100, 200, 300, 400],

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
                        filename: "companyclients_" + new Date().toISOString().replace(/[\-\:\.]/g,
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
    <?php } else {?>
    <script type="text/javascript">
        var $table = $('#fresh-table'),

            full_screen = false;

        $().ready(function() {
            $table.bootstrapTable({
                toolbar: ".toolbar",
                showRefresh: true,
                search: true,
                showColumns: true,
                pagination: true,
                striped: true,
                // pageSize: 8,
                // pageList: [8, 10, 25, 50, 100],
                pageSize: 50,
                pageList: [50, 100, 200, 300, 400],

                formatShowingRows: function(pageFrom, pageTo, totalRows) {
                    //do nothing here, we don't want to show the text "showing x of y from..."
                },
                formatRecordsPerPage: function(pageNumber) {
                    return pageNumber + " rows visible";
                },
                icons: {
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
            $('.mas-refresh').click(function(e) {
                $('#fresh-table').bootstrapTable('resetSearch');
            });
        });
    </script>
    <?php } ?>
@endsection
