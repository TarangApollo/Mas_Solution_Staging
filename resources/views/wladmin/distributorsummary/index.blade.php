@extends('layouts.wladmin')

@section('title', 'Distributor Summary')

@section('content')
    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Distributor Summary</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active"> Distributor Summary </li>
                </ol>
            </nav>
        </div>
        <!-- first row starts here -->
        <div class="row d-flex justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">SEARCH BY CATEGORIES</h4>
                        <form class="was-validated p-4 pb-3" action="{{ route('distributorsummary.index') }}"
                            method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Distributor Name </label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iDistributorId"
                                            id="iDistributorId">
                                            <option label="Please Select" value="">-- Select -- </option>
                                            @if (!empty($distributorMaster))
                                                @foreach ($distributorMaster as $distributor)
                                                    <option value="{{ $distributor->iDistributorId }}"
                                                        {{ isset($postarray['iDistributorId']) && $postarray['iDistributorId'] == $distributor->iDistributorId ? 'selected' : '' }}>
                                                        {{ $distributor->Name }}</option>
                                                @endforeach
                                            @else
                                                <option label="Please Select" value="">No Record Found</option>
                                            @endif
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Company</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iCompanyId"
                                            id="iCompanyId">
                                            <option label="Please Select" value="">-- Select -- </option>
                                            @if (!empty($CompanyMaster))
                                                @foreach ($CompanyMaster as $Company)
                                                    <option value="{{ $Company->iCompanyId }}"
                                                        {{ isset($postarray['iCompanyId']) && $postarray['iCompanyId'] == $Company->iCompanyId ? 'selected' : '' }}>
                                                        {{ $Company->strOEMCompanyName }}</option>
                                                @endforeach
                                            @else
                                                <option label="Please Select" value="">No Record Found</option>
                                            @endif
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Date Range</label>
                                        <input type="text" class="form-control"
                                            value="{{ $postarray['daterange'] ?? '' }}" id="datepicker" name="daterange" />
                                    </div>
                                </div> <!-- /.col -->
                            </div>
                            <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                            <input type="button" onclick="clearData();"
                                class="btn btn-fill btn-default text-uppercase mt-3" value="Clear">
                        </form>
                    </div>
                </div>
                <!--card end-->
            </div>
        </div><!-- end row -->
        <!--table start-->
        <div class="row d-flex justify-content-center my-5">
            <div class="col-md-12">
                <div class="fresh-table toolbar-color-orange">
                    <table id="fresh-table" class="table">
                        <thead>
                            <th data-field="id">No</th>
                            <th data-field="system" data-sortable="true">DISTRIBUTOR</th>
                            <th data-field="component" data-sortable="true">COUNT OF INSTALLER </th>
                            <th data-field="action">Actions</th>
                        </thead>
                        <tbody>
                            <?php $iCounter = 1;
                            $TotalMinutes = 0; ?>
                            @foreach ($ticketList as $ticket)
                                <tr>
                                    <td>{{ $iCounter }}</td>
                                    <td>{{ $ticket->Name }}</td>
                                    <td>{{ $ticket->count }}</td>
                                    <td>

                                        <form action="{{ route('distributorsummary.info') }}" method="POST"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="post">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="iDistributorId"
                                                value="{{ $ticket->iDistributorId }}">
                                            <input type="hidden" name="daterange" id="strdaterange" value="">
                                            <a href="#" title="Info" class="table-action">
                                                <button type="submit" onclick="return setSelectedDateRange();"
                                                    style="border: none;background: none;"><i
                                                        class="mas-info-circle"></i></button>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                                <?php $iCounter++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--table end-->
    </div>


@endsection
@section('script')


    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js">
    </script>
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
                        filename: "System_Summary_" + new Date().toISOString().replace(/[\-\:\.]/g,
                            "") + ".xls",
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
    </script>
    <?php } else { ?>
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
    <script>
        //$("#datepicker").daterangepicker();
        $(function() {
            //$("#datepicker").daterangepicker();
            $("#datepicker").daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $('#datepicker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
            });
            $('#datepicker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });

        function setSelectedDateRange() {
            $("#strdaterange").val($("#datepicker").val());
        }
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

        function clearData() {
            window.location.href = "";
        }
    </script>


@endsection
