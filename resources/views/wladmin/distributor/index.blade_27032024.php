@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>List of Distributors</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Distributors</li>
                <li class="breadcrumb-item active"> Distributor List </li>
            </ol>
        </nav>
    </div>
    <!-- first row starts here -->
    @include('wladmin.wlcommon.alert')

    <div class="row d-flex justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body p-0">
                    <h4 class="card-title mt-0">search by categories</h4>
                    <form class="was-validated p-4 pb-3" id="searchfrm" action="{{ route('distributor.index') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Distributor Name</label>
                                    <input type="text" class="form-control" value="{{ $search_name ?? '' }}" name="search_name" id="search_name" >
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Distributor Contact</label>
                                    <input type="text" class="form-control" value="{{ $search_contact ?? '' }}" name="search_contact" id="search_contact">
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Distributor Email ID</label>
                                    <input type="email" class="form-control" value="{{ $search_email ?? '' }}" name="search_email" id="search_email">
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select State</label>
                                    <select class="js-example-basic-single" name="search_state" style="width: 100%;" name="iStateId" id="iStateId" onchange="getCity();" >
                                        <option label="Please Select" value="">--
                                            Select --</option>
                                        @foreach($statemasters as $state)
                                        <option value="{{ $state->iStateId }}" @if(isset($search_state) &&
                                            $search_state == $state->iStateId) {{ 'selected' }}
                                            @endif>{{ $state->strStateName }}</option>
                                        @endforeach

                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>City</label>
                                    <select class="js-example-basic-single" name="search_city" style="width: 100%;" name="iCityId" id="iCityId" >
                                        <option label="Please Select" value="">--
                                            Select --</option>
                                        @foreach($citymasters as $cities)
                                        <option value="{{ $cities->iCityId }}" @if(isset($search_city) &&
                                            $search_city == $cities->iCityId) {{ 'selected' }}
                                            @endif>{{ $cities->strCityName }} </option>
                                        @endforeach
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Date Range</label>
                                    <input type="text" class="form-control" name="daterange" id="datepicker" value="{{ isset($search_daterange) ? $search_daterange : '' }}"/>
                                </div>
                            </div> <!-- /.col -->
                            <div class="col-md-4 my-4">
                                <button type="button" class="btn btn-primary">
                                    Total Distributors <span class="badge badge-light ml-5">{{ count($distributors) ?? 0 }}</span>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                                <input type="button" class="btn btn-fill btn-default text-uppercase mt-3" onclick="clearData();" value="Clear">
                            </div>
                            <div class="col-md-6">
                                <input type="button" onclick="genrateExcel();" class="btn btn-fill btn-success text-uppercase mt-3 pull-right-md" value="Download Full Report">
                            </div>
                        </div>
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
                        <th data-field="no" data-sortable="true">ID</th>
                        <th data-field="status">Status</th>
                        <th data-field="name" data-sortable="true">DISTRIBUTOR NAME</th>
                        <th data-field="mail" data-sortable="true">Email ID</th>
                        <th data-field="contact" data-sortable="true">Contact</th>
                        <th data-field="state" data-sortable="true">State</th>
                        <th data-field="city" data-sortable="true">City</th>
                        <th data-field="date" data-sortable="true">Created Date</th>
                        <th data-field="by" data-sortable="true">Created by</th>
                        <th data-field="action">Actions</th>
                    </thead>
                    <tbody>
                        <?php $iCounter = 1; ?>
                        @foreach($distributors as $distributor)
                        <tr>
                            <td>{{ $iCounter }}</td>
                            <td>EN{{ $distributor->iDistributorId }}</td>
                            <td>
                                @if($distributor->iStatus == 1)
                                <input type="checkbox" name="iStatus" id="iStatus" data-toggle="toggle" data-width="90" value="{{ $distributor->iStatus }}" onchange="updateStatus(<?= $distributor->iStatus ?>,<?= $distributor->iDistributorId ?>);" checked>
                                @else
                                <input type="checkbox" name="iStatus" id="iStatus" data-toggle="toggle" data-width="90" value="{{ $distributor->iStatus }}" onchange="updateStatus(<?= $distributor->iStatus ?>,<?= $distributor->iDistributorId ?>);">
                                @endif
                            </td>
                            <td>{{ $distributor->Name }}</td>
                            <td>{{ $distributor->EmailId }}</td>
                            <td>{{ $distributor->distributorPhone }}</td>
                            <td>{{ $distributor->strStateName }}</td>
                            <td>{{ $distributor->strCityName }}</td>
                            <td>{{ date('d-m-Y',strtotime($distributor->strEntryDate)) }}<br>
                                <small>{{ date('H:i:s',strtotime($distributor->strEntryDate)) }}</small>
                            </td>
                            <td>{{ $distributor->first_name }} {{ $distributor->last_name }}</td>
                            <td>
                                <a href="{{ route('distributor.edit', $distributor->iDistributorId) }}" title="Edit" class="table-action">
                                    <i class="mas-edit"></i>
                                </a>
                                <a href="{{ route('distributor.info',$distributor->iDistributorId) }}" title="Info" class="table-action">
                                          <i class="mas-info-circle"></i>
                                        </a>
                                <form action="{{ route('distributor.delete', $distributor->iDistributorId) }}" method="POST" onsubmit="return confirm('Are you Sure You wanted to Delete?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <a href="#" title="Delete" class="table-action">
                                        <button type="submit" class="p-0 border-0 bg-none" ><i class="mas-trash"></i></button>
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

<!--table plugin-->
<script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
</script>
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>
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

        // $alertBtn.click(function() {
        //     alert("You pressed on Alert");
        // });
    });
    $(function() {
        $('.mas-download').click(function(e) {
            var table = $("#fresh-table");
            if (table && table.length) {
                $(table).table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "Distributors_" + new Date().toISOString().replace(/[\-\:\.]/g, "") +
                        ".xls",
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
    $(function(){
        $("#datepicker").daterangepicker({
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
    function clearData() {
        window.location.href = "";
    }

    function updateStatus(status, iDistributorId) {
        //$("p").toggle();
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: "{{route('distributor.updateStatus')}}",
            data: {
                status: status,
                iDistributorId: iDistributorId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");

                if (response == 1) {
                    $('#loading').css("display", "none");
                    $("#msgdata").html("<strong>Success !</strong> Company Created Successfully.");
                    window.location.href = "";
                    return true;
                } else {
                    $('#loading').css("display", "none");
                    return false;
                }
            }
        });
    }


    function getCity() {
        var iStateId = $("#iStateId").val();
        $.ajax({
            type: 'POST',
            url: "{{route('company.getCity')}}",
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

    function genrateExcel(){
        // $.ajax({
        //     type: 'POST',
        //     url: "{{route('distributor.genrateDistributorExcel')}}",
        //     data: $('#searchfrm').serialize(),
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     success: function(response) {
        //         $("#iCityId").html(response);
        //     }
        // });
        var search_name = $("#search_name").val();
        var search_contact = $("#search_contact").val();
        var search_email = $("#search_email").val();
        var search_state = $("#iStateId").val();
        var search_city = $("#iCityId").val();
        var datepicker = $("#datepicker").val();
        var url ="{{route('distributor.genrateDistributorExcel')}}";
        window.location.href = url + "?search_name=" + search_name + "&search_contact=" + search_contact + "&search_email=" + search_email + "&search_state=" + search_state + "&search_city=" + search_city + "&daterange=" + datepicker;
    }
</script>
<!--toggle button active/inactive-->
<script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script>

@endsection
