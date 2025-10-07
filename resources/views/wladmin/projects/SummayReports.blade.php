@extends('layouts.wladmin')
@section('title', 'Dashboard')
@section('content')
<link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="main-panel">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Project Summary</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Projects</li>
                    <li class="breadcrumb-item active">Project Summary </li>
                </ol>
            </nav>
        </div>
        <!-- first row starts here -->

        <div class="row d-flex justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">search by categories</h4>
                        <form class="was-validated p-4 pb-3" action="{{ route('projects.projectsSummayReports') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select State</label>
                                        <select class="js-example-basic-single" name="search_state" id="iStateId" style="width: 100%;" onchange="getCity();">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($statemasters as $state)
                                            <option value="{{ $state->iStateId }}"
                                                @if (isset($search_state) && $search_state==$state->iStateId) {{ 'selected' }} @endif>
                                                {{ $state->strStateName }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="SM">Maharashtra</option>
                                            <option value="ka">Karnataka</option> -->
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select City</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="search_city" id="iCityId">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($citymasters as $cities)
                                            <option value="{{ $cities->iCityId }}"
                                                @if (isset($search_city) && $search_city==$cities->iCityId) {{ 'selected' }} @endif>
                                                {{ $cities->strCityName }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Date Range</label>
                                        <input type="text" class="form-control" name="daterange" value="{{ isset($search_daterange) ? $search_daterange : '' }}" />
                                    </div>
                                </div> <!-- /.col -->
                            </div>
                            <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                            <input type="button" class="btn btn-fill btn-default text-uppercase mt-3" value="Clear">
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
                            <th data-field="project" data-sortable="true">Project Name</th>
                            <th data-field="state" data-sortable="true">State</th>
                            <th data-field="city" data-sortable="true">City</th>
                            <th data-field="issue" data-sortable="true">Count of Issues</th>
                            <th data-field="action">Actions</th>
                        </thead>
                        <tbody>
                            <?php $iCount = 1; ?>
                            @foreach($firstQuery as $data)
                            <tr>
                                <td>{{ $iCount }}</td>
                                <td>{{ $data->projectName }}</td>
                                <td>{{ $data->strStateName }}</td>
                                <td>{{ $data->strCityName }}</td>
                                <td>{{ $data->TicketCount }}</td>
                                <td>
                                    <a href="{{ route('projects.projectsSummayReportsInfoview', $data->projectProfileId) }}" title="Info" class="table-action">
                                        <i class="mas-info-circle"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php $iCount++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--table end-->
    </div>

</div>
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
<?php if (in_array('48', \Session::get('menuList'))) { ?>
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
<script type="text/javascript">
    function clearData() {
        window.location.href = "";
    }

    function getCity() {
        var iStateId = $("#iStateId").val();
        $.ajax({
            type: 'POST',
            url: "{{ route('company.getCity') }}",
            data: {
                iStateId: iStateId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#iCityId").html(response);
            }
        });
    }

    function clearData() {
        window.location.href = "";
    }

    function genrateExcel() {
        var search_name = $("#search_name").val();
        var search_email = $("#search_email").val();
        var search_state = $("#iStateId").val();
        var search_city = $("#iCityId").val();
        var datepicker = $("#datepicker").val();
        var url = "{{ route('companyclient.genrateCompanyClientExcel') }}";
        window.location.href = url + "?search_name=" + search_name + "&search_email=" + search_email +
            "&search_state=" + search_state + "&search_city=" + search_city + "&daterange=" + datepicker;
    }
</script>
<!--toggle button active/inactive-->
<script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script>

<script>
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
                    .format(
                        'MM/DD/YYYY'));
            });
            $('#datepicker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#submitCheckboxes').click(function(e) {
            e.preventDefault();

            var selectedCheckboxes = $('#multiple-checkboxes').val();

            $.ajax({
                url: "{{ route('projects.reorder_column') }}", // Replace with your route
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