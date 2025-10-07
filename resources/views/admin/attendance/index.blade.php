@extends('layouts.admin')

@section('title', 'Attendance List')

@section('content')

<div class="main-panel">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>User Attendance</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active"> Attendance </li>
                </ol>
            </nav>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">search by categories</h4>
                        <form class="was-validated p-4 pb-3" action="{{ route('attendance.index') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Date Range</label>
                                        <input type="text" class="form-control" id="datepicker"
                                            value="{{ $date_range ?? '' }}" name="daterange" />
                                    </div>
                                </div> <!-- /.col -->
                            </div>
                            <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2"
                                value="Search">
                            <input type="button" onclick="clearData();"
                                class="btn btn-fill btn-default text-uppercase mt-3" value="Clear">
                        </form>

                    </div>
                </div>
                <!--card end-->
            </div>
        </div><!-- end row -->


        <div class="row">
            <div class="col-md-12 d-flex justify-content-end mt-3">
                <!-- <button type="button" class="btn btn-success text-uppercase pt-1 mb-3 mr-2">
                    <i class="mas-download btn-icon"></i>
                    Previous Week
                </button>-->
                <!-- <button type="button" class="btn btn-success text-uppercase pt-1 mb-3">
                    <i class="mas-download btn-icon"></i>
                    Previous Month
                </button>  -->
                <form action="{{ route('attendance.download') }}" method="POST" style="display: inline-block;">
                    <input type="hidden" name="_method" value="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="daterange" id="str_date_range" value="">
                    <!-- <a href="#" title="Info" class="table-action"> -->
                        <button type="submit" onclick="return setSelected_DateRange();"
                            class="btn btn-success text-uppercase pt-1 mb-3"><i
                                class="mas-download btn-icon"></i>Download</button>
                    <!-- </a> -->
                </form>
            </div>
        </div>
        <!--table start-->
        <div class="row d-flex justify-content-center mb-5">
            <div class="col-md-12">
                <div class="fresh-table toolbar-color-orange">
                    <table id="fresh-table" class="table">
                        <thead>
                            <th data-field="id">No</th>
                            <th data-field="no" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="true">Name</th>
                            <th data-field="contact" data-sortable="true">Contact</th>
                            <th data-field="mail" data-sortable="true">Email ID</th>
                            <th data-field="level">Executive Level</th>
                            <!-- <th data-field="date" data-sortable="true">Days Count</th> -->
                            <th data-field="date" data-sortable="true">DATE</th>
                            <th data-field="status" data-sortable="true">Status</th>
                            <th data-field="login" data-sortable="true">Last Login</th>
                            <th data-field="action">Actions</th>
                        </thead>
                        <tbody>
                            <?php $iCounter = 1; ?>
                            @foreach($callAttendent as $Attendent)
                            <?php
                                // $loginCount = \App\Models\Loginlog::select(DB::raw("count(DISTINCT(DATE_format(strEntryDate,'%Y-%m-%d'))) as cnt"))
                                //     ->where('strEntryDate','>=',$formDate)->where('strEntryDate','<=',$toDate)
                                //     ->where(["userId" => $Attendent->iUserId,"action" => 'Login'])
                                //     ->first();
                                if(isset($date_range) && $date_range != ""){
                                    $to_Date = $toDate;
                                } else {
                                    $to_Date = date("Y-m-d");
                                }
                                $loginCount = \App\Models\Loginlog::where(DB::raw("DATE_format(strEntryDate,'%Y-%m-%d')"),'=',DB::raw("DATE_format('".$to_Date."','%Y-%m-%d')"))
                                    ->where(["userId" => $Attendent->iUserId,"action" => 'Login'])
                                    ->orderBy('id','desc')
                                    ->take(1)
                                    ->first();
                            ?>
                            <tr>
                                <td>{{ $iCounter }}</td>
                                <td>{{ 'EN'.$Attendent->iCallAttendentId }}</td>
                                <td>{{ $Attendent->strFirstName ." ". $Attendent->strLastName }}</td>
                                <td>{{ $Attendent->strContact }}</td>
                                <td>{{ $Attendent->strEmailId }}</td>
                                <td>{{ $Attendent->iExecutiveLevel == 1 ? 'Level 1' : 'Level 2' }}</td>
                                
                                <td>{{ date('d-m-Y',strtotime($to_Date)) }}</td>
                                <td>@if(!empty($loginCount)) {{ 'Present' }} @else {{ 'Absent' }}@endif</td>
                                <td>@if(!empty($loginCount))
                                    {{ date('d-m-Y',strtotime($loginCount->strEntryDate)) }}<br>
                                        <small>{{ date('H:i:s',strtotime($loginCount->strEntryDate)) }}</small>
                                    @endif
                                    </td>
                                <!-- <td>
                                    <a href="{{ route('attendance.info',$Attendent->iCallAttendentId) }}" title="Info" class="table-action">
                                        <i class="mas-info-circle"></i>
                                    </a>
                                </td> -->
                                <td>
                                    <form action="{{ route('attendance.info') }}" method="POST"
                                        style="display: inline-block;">
                                        <input type="hidden" name="_method" value="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="iCallAttendentId"
                                            value="{{ $Attendent->iCallAttendentId }}">
                                        <input type="hidden" name="daterange" id="strdaterange">
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
        <!--notify messages-->


        <!--END notify messages-->
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
</script>
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
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

    // $alertBtn.click(function() {
    //     alert("You pressed on Alert");
    // });

});

$(function() {
    $("#datepicker").daterangepicker({
        autoUpdateInput: false,
        dateLimit: {
            'months': 1,
            //'days': 31
        },
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

function setSelected_DateRange(){
    $("#str_date_range").val($("#datepicker").val());
}

$(function() {
    $('.mas-download').click(function(e) {
        var table = $("#fresh-table");
        if (table && table.length) {
            $(table).table2excel({
                exclude: ".noExl",
                name: "Excel Document Name",
                filename: "Attendant_" + new Date().toISOString().replace(/[\-\:\.]/g, "") +
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

function clearData() {
    window.location.href = "";
}
</script>

<!--toggle button active/inactive-->
<script src="../global/assets/vendors/toggle-button/bootstrap4-toggle.min.js"></script>

@endsection