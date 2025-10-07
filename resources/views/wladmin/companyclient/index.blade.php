@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>List of Customer Companies</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Customer Company</li>
                <li class="breadcrumb-item active"> Customer Company List </li>
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
                    <form class="was-validated p-4 pb-3" action="{{ route('companyclient.index') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" name="search_name" id="search_name" value="{{ $search_name ?? '' }}" >
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" name="search_email" id="search_email" value="{{ $search_email ?? '' }}" >
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State</label>
                                    <select class="js-example-basic-single" name="search_state" id="iStateId" style="width: 100%;" onchange="getCity();">
                                        <option label="Please Select" value="">-- Select --</option>
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
                                    <select class="js-example-basic-single" name="search_city" id="iCityId" style="width: 100%;" >
                                        <option label="Please Select" value="">-- Select --</option>
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

                            <div class="col-md-4 mt-4">
                                <button type="button" class="btn btn-primary">
                                    Total Customer Companies <span class="badge badge-light ml-5">{{ count($CompanyClients) ?? 0 }}</span>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                                <input type="button" class="btn btn-fill btn-default text-uppercase mt-3" onclick="clearData();" value="Clear">
                            </div>
                            <?php  if (in_array('46', \Session::get('menuList')) ) { ?>
                            <div class="col-md-6">
                                <input type="button" onclick="genrateExcel();" class="btn btn-fill btn-success text-uppercase mt-3 pull-right-md" value="Download Full Report">
                            </div>
                            <?php } ?>
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
                        <th data-field="name" data-sortable="true">COMPANY NAME</th>
                        <th data-field="mail" data-sortable="true">Email ID</th>
                        <th data-field="state" data-sortable="true">State</th>
                        <th data-field="city" data-sortable="true">City</th>
                        <th data-field="date" data-sortable="true">Created Date</th>
                        <th data-field="by" data-sortable="true">Created by</th>
                        <th data-field="action">Actions</th>
                    </thead>
                    <tbody>
                        <?php $iCounter = 1; ?>
                        @foreach($CompanyClients as $CompanyClient)
                        <tr>
                            <td>{{ $iCounter }}</td>
                            <td>EN{{ $CompanyClient->iCompanyClientId }}</td>
                            <td>
                                @if($CompanyClient->iStatus == 1)
                                <input type="checkbox" name="iStatus" id="iStatus" data-toggle="toggle" data-width="90" value="{{ $CompanyClient->iStatus }}" onchange="updateStatus(<?= $CompanyClient->iStatus ?>,<?= $CompanyClient->iCompanyClientId ?>);" checked>
                                @else
                                <input type="checkbox" name="iStatus" id="iStatus" data-toggle="toggle" data-width="90" value="{{ $CompanyClient->iStatus }}" onchange="updateStatus(<?= $CompanyClient->iStatus ?>,<?= $CompanyClient->iCompanyClientId ?>);">
                                @endif
                            </td>
                            <td>{{ $CompanyClient->CompanyName }}</td>
                            <td>{{ $CompanyClient->email }}</td>

                            <td>{{ $CompanyClient->strStateName }}</td>
                            <td>{{ $CompanyClient->strCityName }}</td>
                            <td>{{ date('d-m-Y',strtotime($CompanyClient->strEntryDate)) }}<br>
                                <small>{{ date('H:i:s',strtotime($CompanyClient->strEntryDate)) }}</small>
                            </td>
                            <td>{{ $CompanyClient->first_name }} {{ $CompanyClient->last_name }}</td>
                            <td>
                                <a href="{{ route('companyclient.edit',$CompanyClient->iCompanyClientId) }}" title="Edit" class="table-action">
                                    <i class="mas-edit"></i>
                                </a>
                                <a href="{{ route('companyclient.info',$CompanyClient->iCompanyClientId) }}" title="Info" class="table-action">
                                          <i class="mas-info-circle"></i>
                                        </a>
                                <form action="{{ route('companyclient.delete', $CompanyClient->iCompanyClientId) }}" method="POST" onsubmit="return confirm('Are you Sure You wanted to Delete?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <a href="#" title="Delete" class="table-action">
                                        <button type="submit" class="p-0 border-0 bg-none"><i class="mas-trash"></i></button>
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
<script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>
@endsection
@section('script')

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
                // pageSize: 50,
                // //pageList: [8, 10, 25, 50, 100],
                // pageList: [50, 100, 200, 300, 400],
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
                        filename: "companyclients_" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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
<script type="text/javascript">
    function clearData() {
        window.location.href = "";
    }


    $("#search_system").change(function() {
        var search_system = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{route('component.getcomponent')}}",
            data: {
                search_system: search_system
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#search_component").html(response);
            }
        });
    });
    function updateStatus(status, iCompanyClientId) {
        //$("p").toggle();
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: "{{route('companyclient.updateStatus')}}",
            data: {
                status: status,
                iCompanyClientId: iCompanyClientId
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
    function clearData() {
        window.location.href = "";
    }

    function genrateExcel(){
        var search_name = $("#search_name").val();
        var search_email = $("#search_email").val();
        var search_state = $("#iStateId").val();
        var search_city = $("#iCityId").val();
        var datepicker = $("#datepicker").val();
        var url ="{{route('companyclient.genrateCompanyClientExcel')}}";
        window.location.href = url + "?search_name=" + search_name + "&search_email=" + search_email + "&search_state=" + search_state + "&search_city=" + search_city + "&daterange=" + datepicker;
    }
</script>
<!--toggle button active/inactive-->
<script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script>
@endsection
