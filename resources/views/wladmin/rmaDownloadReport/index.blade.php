@extends('layouts.wladmin')

@section('title', 'Download RMA Report')

@section('content')


    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Download RMA </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active"> Download RMA </li>
                </ol>
            </nav>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">search by categories</h4>
                        <form class="was-validated p-4 pb-3" action="{{ route('rmaDownloadReport.download') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Date Range</label>
                                        <input type="text" class="form-control" id="datepicker" name="daterange" />
                                    </div>
                                </div> <!-- /.col -->
                            </div>
                            <button type="submit" class="btn btn-success text-uppercase pt-1 mb-3 mr-2"><i
                                class="mas-download btn-icon"></i>Download</button>
                            <button type="button" onclick="clearData();"
                                class="btn btn-fill btn-default text-uppercase mb-3">Clear</button>
                        </form>

                    </div>
                </div>
                <!--card end-->
            </div>
        </div><!-- end row -->


        <!--END notify messages-->
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
    $("#datepicker").daterangepicker({
        autoUpdateInput: false,
        dateLimit: {
            'months': 3,
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
