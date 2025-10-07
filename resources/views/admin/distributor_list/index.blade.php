@extends('layouts.admin')

@section('title', 'List of Distributor')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>List of Distributors</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Reports</li>
                <li class="breadcrumb-item active"> Distributor List </li>
            </ol>
        </nav>
    </div>
    <!-- first row starts here -->
    @include('admin.common.alert')

    <div class="row d-flex justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body p-0">
                    <h4 class="card-title mt-0">search by categories</h4>
                    <form class="was-validated p-4 pb-3" action="{{ route('distributor_list.index') }}" method="post">
                        @csrf
                        <div class="row">
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
                                    <label>Select Distributor</label>
                                    <select class="js-example-basic-single" name="iDistributorId" id="iDistributorId"
                                        style="width: 100%;">
                                        <option label="Please Select" value="">-- Select --</option>
                                        @foreach($distributorlist as $distributor)
                                        <option value="{{ $distributor->iDistributorId }}"
                                            {{ (isset($iDistributorId) && $iDistributorId == $Company->iDistributorId) ? 'selected' : '' }}>
                                            {{ $distributor->Name }}</option>
                                        @endforeach
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select State</label>
                                    <select class="js-example-basic-single" name="search_state" style="width: 100%;"
                                        name="iStateId" id="iStateId" onchange="getCity();">
                                        <option label="Please Select" value="">--
                                            Select --</option>
                                        @foreach($statemasters as $state)
                                        <option value="{{ $state->iStateId }}" @if(isset($search_state) &&
                                            $search_state==$state->iStateId) {{ 'selected' }}
                                            @endif>{{ $state->strStateName }}</option>
                                        @endforeach

                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>City</label>
                                    <select class="js-example-basic-single" name="search_city" style="width: 100%;"
                                        name="iCityId" id="iCityId">
                                        <option label="Please Select" value="">--
                                            Select --</option>
                                        @foreach($citymasters as $cities)
                                        <option value="{{ $cities->iCityId }}" @if(isset($search_city) &&
                                            $search_city==$cities->iCityId) {{ 'selected' }}
                                            @endif>{{ $cities->strCityName }} </option>
                                        @endforeach
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4 mt-4">
                                <button type="button" class="btn btn-primary">
                                    Total Customer Companies <span class="badge badge-light ml-5">{{ count($distributors) ?? 0 }}</span>
                                </button>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                        <input type="button" class="btn btn-fill btn-default text-uppercase mt-3" onclick="clearData();"
                            value="Clear">
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
                <table id="fresh-table" class="table" >
                    <thead>
                        <th data-field="id">No</th>
                        <th data-field="oem-company" data-sortable="true">OEM Company</th>
                        <th data-field="company" data-sortable="true">Distributor</th>
                        <th data-field="email" data-sortable="true">Email</th>
                        <th data-field="state" data-sortable="true">State</th>
                        <th data-field="city" data-sortable="true">City</th>
                        <th data-field="action">Actions</th>
                    </thead>
                    <tbody>
                        <?php $iCounter = 1; ?>
                        @foreach($distributors as $distributor)
                        <tr>
                            <td>{{ $iCounter }}</td>
                            <td>{{ $distributor->strOEMCompanyName }}</td>
                            <td>{{ $distributor->Name }}</td>
                            <td>{{ $distributor->EmailId }}</td>
                            <td>{{ $distributor->strStateName }}</td>
                            <td>{{ $distributor->strCityName }}</td>
                            <td>
                                <a href="{{ route('distributor_list.info',$distributor->iDistributorId) }}" title="Info"
                                    class="table-action">
                                    <i class="mas-info-circle"></i>
                                </a>

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
<script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js">
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

function clearData() {
    window.location.href = "";
}

$("#OEMCompany").change(function() {
    getCompanyDistributor();
});

function getCompanyDistributor() {
    var OEMCompany = $("#OEMCompany").val();
    $.ajax({
        type: 'POST',
        url: "{{route('distributor_list.getCompanyDistributor')}}",
        data: {
            OEMCompany: OEMCompany
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            if (response.length > 0) {
                $("#iDistributorId").html(response);
            } else {

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

$(function() {
    $('.mas-download').click(function(e) {
        var table = $("#fresh-table");
        if (table && table.length) {
            $(table).table2excel({
                exclude: ".noExl",
                name: "Excel Document Name",
                filename: "distributor_list_" + new Date().toISOString().replace(
                        /[\-\:\.]/g, "") +
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
        var table = $("#fresh-table");
        $('.search input[class="form-control"]').val('');
        //$(table).bootstrapTable()
        $(table).bootstrapTable('refreshOptions', {
            clickToSelect: true,
            // Other options you want to override
        });
    });
});
</script>
<!--toggle button active/inactive-->
<script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script>

@endsection
