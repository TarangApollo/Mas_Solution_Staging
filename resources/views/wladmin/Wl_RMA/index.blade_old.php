@extends('layouts.wladmin')
@section('title', 'Dashboard')
@section('content')
    <!--<div class="container-scroller">-->
    <!--<div class="container-fluid page-body-wrapper">-->
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
                            <form class="was-validated p-4 pb-3" action="{{ route('Wl_RMA.index') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Search by Customer Name / RMA No</label>
                                            <input type="text" class="form-control" name="Customer_Name_and_RMA_No">
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
                                            <select class="js-example-basic-single" style="width: 100%;" name="HFI">
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="HFI"{{ old('HFI', $HFI) == 'HFI' ? 'selected' : '' }}>HFI
                                                </option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Financial Year</label>
                                            <select class="js-example-basic-single" style="width: 100%;" name="fYear"
                                                id="">
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
                                            <select class="js-example-basic-single" style="width: 100%;" name="fMonth"
                                                id="fMonth">
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="4" {{ $searchMonth == '04' ? 'selected' : '' }}>
                                                    April</option>
                                                <option value="5" {{ $searchMonth == '05' ? 'selected' : '' }}>
                                                    May</option>
                                                <option value="6" {{ $searchMonth == '06' ? 'selected' : '' }}>
                                                    June</option>
                                                <option value="7" {{ $searchMonth == '07' ? 'selected' : '' }}>
                                                    July</option>
                                                <option value="8" {{ $searchMonth == '08' ? 'selected' : '' }}>
                                                    August</option>
                                                <option value="9" {{ $searchMonth == '09' ? 'selected' : '' }}>
                                                    September</option>
                                                <option value="10"{{ $searchMonth == '10' ? 'selected' : '' }}>
                                                    October</option>
                                                <option value="11" {{ $searchMonth == '11' ? 'selected' : '' }}>
                                                    November</option>
                                                <option value="12" {{ $searchMonth == '12' ? 'selected' : '' }}>
                                                    December</option>
                                                <option value="1" {{ $searchMonth == '01' ? 'selected' : '' }}>
                                                    January</option>
                                                <option value="2" {{ $searchMonth == '02' ? 'selected' : '' }}>
                                                    February</option>
                                                <option value="3" {{ $searchMonth == '03' ? 'selected' : '' }}>
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

        {{-- start new code 20-02-2025 --}}

        <?php
        $userid = Auth::user()->id;
        $record = DB::table('reorder_columns')->where('strUrl', 'Wl_RMA.index')->where('iUserId', $userid)->first();

        $selectedOptions = [];
        if ($record) {
            $selectedOptions = json_decode($record->json, true);
        }
        $options = ['NO', 'COMPANY NAME', 'MONTH', 'RMA No', 'ITEM', 'QUANTITY', 'SYSTEM', 'IN WARRANTY','CUSTOMER STATUS', 'HFI STATUS','ACTIONS'];

        $optionLabels = [
            'NO' => 'No',
            'COMPANY NAME' => 'Companyname',
            'MONTH' => 'Month',
            'RMA No' => 'RmaId',
            'ITEM' => 'Item',
            'QUANTITY' => 'Quantity',
            'SYSTEM' => 'System',
            'IN WARRANTY' => 'Inwarranty',
            'CUSTOMER STATUS' => 'CustomerStatus',
            'HFI STATUS' => 'HfiStatus',
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
                                <option value="COMPANY NAME">COMPANY NAME</option>
                                <option value="MONTH">MONTH</option>
                                <option value="RMA No">RMA No</option>
                                <option value="ITEM">ITEM</option>
                                <option value="QUANTITY">QUANTITY</option>
                                <option value="SYSTEM">SYSTEM</option>
                                <option value="IN WARRANTY">IN WARRANTY</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="CUSTOMER STATUS">CUSTOMER STATUS</option>
                                <option value="HFI STATUS">HFI STATUS</option>
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
                <div class="col-md-12">
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
                                    @foreach ($groupedRmadetailList as $group)
                                        @php
                                            $details = $group['rma_details'] ?? [];
                                        @endphp
                                        <tr>
                                            @if (in_array('NO', $selectedOptions))
                                                <td>{{ $loop->iteration }}</td>
                                            @endif
                                            @if (in_array('COMPANY NAME', $selectedOptions))
                                                <td>{{ optional($group)['strCustomerCompany'] ?? '-' }}</td>
                                            @endif
                                            @if (in_array('MONTH', $selectedOptions))
                                                <td>{{ isset($group['strRMARegistrationDate']) ? \Carbon\Carbon::parse($group['strRMARegistrationDate'])->format('F') : '-' }}</td>
                                            @endif
                                            @if (in_array('RMA No', $selectedOptions))
                                                <td>{{ $group['iRMANumber'] ?? '-' }}</td>
                                            @endif
                                            @if (in_array('ITEM', $selectedOptions))
                                                <td>
                                                    <span>{{ $group['strItem'] ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strItem ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            @endif
                                            @if (in_array('QUANTITY', $selectedOptions))
                                                <td>
                                                    <span>{{ $group['strQuantity'] ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strQuantity ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            @endif
                                            @if (in_array('SYSTEM', $selectedOptions))
                                                <td>
                                                    <span>{{ $group['strSystem'] ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strSystem ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            @endif
                                            @if (in_array('IN WARRANTY', $selectedOptions))
                                                <td>
                                                    <span>{{ isset($group['strInwarranty']) ? ($group['strInwarranty'] ? 'Yes' : 'No') : '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strInwarranty ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            @endif
                                            @if (in_array('CUSTOMER STATUS', $selectedOptions))
                                                <td>
                                                    <span>{{ $group['strStatus'] ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->strStatus ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            @endif
                                            @if (in_array('HFI STATUS', $selectedOptions))
                                                <td>
                                                    <span>{{ $group['Factory_Status'] ?? '-' }}</span>
                                                    @foreach ($details as $detail)
                                                        <br><span>{{ $detail->Additional_Factory_Status ?? '-' }}</span>
                                                    @endforeach
                                                </td>
                                            @endif
                                            @if (in_array('ACTIONS', $selectedOptions))
                                                <td>
                                                    <a href="{{ route('Wl_RMA.rma_summary_info', $group['rma_id']) }}" title="Info" class="table-action">
                                                        <i class="mas-info-circle"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--table end-->
        </div>
        <!-- content-wrapper ends -->

        <!--/. footer ends-->
    </div>

    <!--form validation-->
    {{-- <script src="../global/assets/vendors/wizard/js/jquery.validate.min.js"></script>

    <!--date range picker-->
    <script type="text/javascript" src="../global/assets/vendors/date-picker/moment.min.js"></script>
    <script type="text/javascript" src="../global/assets/vendors/date-picker/daterangepicker.min.js"></script>

    <!--table plugin-->
    <script type="text/javascript" src="../global/assets/vendors/bootstrap-table/js/bootstrap-table.js"></script> --}}
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
        var $table = $('#fresh-table');
        var full_screen = false;
        $(document).ready(function() {
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

        $(document).ready(function() {
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

        $(document).ready(function() {
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
            $('.mas-refresh').click(function(e) {
                $('#fresh-table').bootstrapTable('resetSearch');
            });
        });
    </script>
    <?php } ?>
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
                     url: "{{ route('Wl_RMA.rma_detail_reorder_column') }}",
                     type: 'POST',
                     data: {
                         checkboxes: selectedCheckboxes,
                         "_token": "{{ csrf_token() }}"
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
