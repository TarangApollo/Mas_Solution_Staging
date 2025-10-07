@extends('layouts.admin')

@section('title', 'Call List')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>List of Customer</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active"> Customer List </li>
                </ol>
            </nav>
        </div>
        <!-- first row starts here -->

        <div class="row d-flex justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">search by categories</h4>
                        <form class="was-validated p-4 pb-3" action="{{ route('customer_list.index') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control" name="searchText" id="searchText"
                                            value="{{ (isset($searchCustomer) && $searchCustomer) != null ? $searchCustomer : '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select OEM Company</label>
                                        <select class="js-example-basic-single" name="OEMCompany" id="OEMCompany"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach($CompanyMaster as $Company)
                                            <option value="{{ $Company->iCompanyId }}"
                                                {{ (isset($OEMCompany) && $OEMCompany == $Company->iCompanyId) ? 'selected' : '' }}>
                                                {{ $Company->strOEMCompanyName }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Company</label>
                                        <select class="js-example-basic-single" name="iCompanyClientId" id="iCompanyClientId"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach($companyClients as $company)
                                            <option value="{{ $company->iCompanyClientId }}"
                                                {{ (isset($search_Company) && $search_Company == $company->iCompanyClientId) ? "selected" : "" }}>
                                                {{ $company->CompanyName }}</option>
                                            @endforeach

                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Date Range</label>
                                        <input type="text" class="form-control" id="datepicker"
                                            value="{{ $search_daterange ?? '' }}" name="daterange" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-4 mt-4">
                                    <button type="button" class="btn btn-primary">
                                        Total Customer Companies <span class="badge badge-light ml-5">{{ count($ticketList) ?? 0 }}</span>
                                    </button>
                                </div>
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

        <!--table start-->
        <div class="row d-flex justify-content-center my-5">
            <div class="col-md-12">
                <div class="fresh-table toolbar-color-orange">
                    <table id="fresh-table" class="table">
                        <thead>
                            <th data-field="id">No</th>
                            <th data-field="customer" data-sortable="true">Customer</th>
                            <th data-field="company" data-sortable="true">Company</th>
                            <th data-field="oem-company" data-sortable="true">OEM Company</th>
                            <th data-field="call" data-sortable="true">Call Count</th>
                            <th data-field="issue" data-sortable="true">Count of Issues</th>
                            <th data-field="action">Actions</th>
                        </thead>
                        <tbody>
                            <?php $iCounter = 1; ?>
                            @foreach($ticketList as $ticket)
                            <tr>
                                <td>{{ $iCounter }}</td>
                                <td>{{ $ticket->strCustomerName }}</td>
                                <td>{{ $ticket->CompanyName }}</td>
                                <td>{{ $ticket->strOEMCompanyName }}</td>
                                <td>{{ $ticket->CallCount }}</td>
                                <td>{{ $ticket->issueCount }}</td>
                                <td>
                                    <!-- <a href="customer-info.html" title="Info" class="table-action">
                                        <i class="mas-info-circle"></i>
                                    </a> -->
                                    <form action="{{ route('customer_list.info') }}" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="CustomerMobile" value="{{ $ticket->CustomerMobile }}">
                                        <input type="hidden" name="OemCompannyId" value="{{ $ticket->OemCompannyId }}">
                                        <input type="hidden" name="daterange" id="strdaterange" value="">
                                        <a href="#" title="Info" class="table-action">
                                            <button type="submit" onclick="return setSelectedDateRange();" style="border: none;background: none;"><i class="mas-info-circle"></i></button>
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

</div>
<!-- main-panel ends -->

@endsection
@section('script')

<script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
</script>
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
<!-- <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js">
</script> -->

<script>
// $(function(){
//     getsystem();
//     getCallAttendant();
// });


function setSelectedDateRange() {
    $("#strdaterange").val($("#datepicker").val());
}
$("#OEMCompany").change(function() {
    getsystem();
    getCallAttendant();
});

function getsystem() {
    var OEMCompany = $("#OEMCompany").val();
    $.ajax({
        type: 'POST',
        url: "{{route('call_report.getsystem')}}",
        data: {
            OEMCompany: OEMCompany
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            if (response.length > 0) {
                $("#iSystemId").html(response);
            } else {

            }
        }
    });
}

function getCallAttendant() {
    var OEMCompany = $("#OEMCompany").val();
    $.ajax({
        type: 'POST',
        url: "{{route('call_report.getCallAttendant')}}",
        data: {
            OEMCompany: OEMCompany
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            if (response.length > 0) {
                $("#exeId").html(response);
            } else {

            }
        }
    });
}

</script>

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
    $('.mas-download').click(function(e) {
        var table = $("#fresh-table");
        if (table && table.length) {
            $(table).table2excel({
                exclude: ".noExl",
                name: "Excel Document Name",
                filename: "customer_list_" + new Date().toISOString().replace(/[\-\:\.]/g, "") +
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

$(function() {
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
</script>

@endsection
