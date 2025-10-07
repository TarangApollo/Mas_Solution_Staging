@extends('layouts.wladmin')

@section('title', 'Customer Summary')

@section('content')
<link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Customer Summary</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active"> Customer Summary </li>
                </ol>
            </nav>
        </div>
        <!-- first row starts here -->
        <div class="row d-flex justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">search by categories</h4>
                        <form class="was-validated p-4 pb-3" action="{{route('customersummary.index' )}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control" name="searchCustomer" id="searchCustomer" value="{{ $searchCustomer ?? '' }}"/>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <select class="js-example-basic-single" name="search_Company" value="{{ $search_Company ?? '' }}" style="width: 100%;" name="search_Company" id="search_Company" >
                                            <option label="Please Select" value="">--
                                                Select --</option>
                                            @foreach($companyClients as $companyClient)
                                                <option value="{{ $companyClient->iCompanyClientId }}" @if(isset($search_Company) &&
                                                $search_Company == $companyClient->iCompanyClientId) {{ 'selected' }}
                                                @endif>{{ $companyClient->CompanyName }} </option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Date Range</label>
                                        <input type="text" class="form-control" id="datepicker" value="{{ $search_daterange ?? '' }}" name="daterange"/>
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="js-example-basic-single" name="status" style="width: 100%;" id="status" >
                                            <option label="Please Select" value="">--Select --</option>
                                            <option value="Total" @if(isset($status) && $status == "Total") {{ 'selected' }} @endif>{{ 'Total' }} </option>
                                            <option value="TotalTD" @if(isset($status) && $status == "TotalTD") {{ 'selected' }} @endif>{{ 'TD' }} </option>
                                            <option value="new" @if(isset($status) && $status == "new") {{ 'selected' }} @endif>{{ 'New' }} </option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                            </div>
                            <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                            <input type="button" onclick="clearData();" class="btn btn-fill btn-default text-uppercase mt-3" value="Clear">
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
                            <th data-field="Customer" data-sortable="true">Customer</th>
                            <th data-field="Company" data-sortable="true">Company</th>
                            <th data-field="CallCount" data-sortable="true">Call Count</th>
                            <th data-field="issues" data-sortable="true"> Count of Issues</th>
                            <th data-field="Competency" data-sortable="true"> Caller Competency </th>
                            <th data-field="action">Actions</th>
                        </thead>
                        <tbody>
                            <?php $iCounter = 1;
                            $TotalMinutes = 0; ?>
                            @foreach($ticketList as $ticket)
                            <tr>
                                <td>{{ $iCounter }}</td>
                                <td>{{ $ticket->strCustomerName }}</td>
                                <td>{{ $ticket->CompanyName }}</td>
                                <td>{{ $ticket->CallCount }}</td>
                                <td>{{ $ticket->issueCount }}</td>
                                <td>{{ $ticket->CallerCompetencyId }}</td>
                                <td>
                                    <form action="{{ route('customersummary.info') }}" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="CustomerMobile" value="{{ $ticket->CustomerMobile }}">
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


@endsection
@section('script')


<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>
<script>
    // $(function(){
    //     $("#datepicker").daterangepicker();
    // });
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

    function setSelectedDateRange(){
        $("#strdaterange").val($("#datepicker").val());
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

    function clearData() {
        window.location.href = "";
    }

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
              //  pageSize: 8,
                //pageList: [ 25, 50, 100],
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
                        filename: "Customer_summary_" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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
            //    pageSize: 8,
             //   pageList: [8, 10, 25, 50, 100],
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
