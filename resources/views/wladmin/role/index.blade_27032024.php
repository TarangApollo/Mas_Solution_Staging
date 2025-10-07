@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')

<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>List of Roles</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Role</li>
                <li class="breadcrumb-item active"> Role List </li>
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
                    <form class="was-validated p-4 pb-3" action="" method="post">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Role Name</label>
                                    <select class="js-example-basic-single" style="width: 100%;">
                                        <option label="Please Select" value="">-- Select --
                                        </option>
                                        <option value="BJ">Admin</option>
                                        <option value="SM">Operation</option>
                                        <option value="MR">Support Team</option>
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Status</label>
                                    <select class="js-example-basic-single" style="width: 100%;">
                                        <option label="Please Select" value="">-- Select --
                                        </option>
                                        <option value="CL">Active</option>
                                        <option value="OP">Inactive</option>
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Date Range</label>
                                    <input type="text" class="form-control" name="daterange" id="datepicker" />
                                </div>
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <!-- <label>Select User</label>
                                            <select class="js-example-basic-single" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="CA">Admin</option>
                                                <option value="CB">Employee 2</option>
                                                <option value="CC">Employee 3</option>
                                            </select> -->
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                        </div>
                        <input type="button" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                        <input type="button" class="btn btn-fill btn-default text-uppercase mt-3" value="Clear">
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
                        <!-- <th data-field="status">Status</th> -->
                        <th data-field="role" data-sortable="true">Role Name</th>
                        <th data-field="date" data-sortable="true">Created Date</th>
                        <th data-field="by" data-sortable="true">Created by</th>
                        <th data-field="action">Actions</th>
                    </thead>
                    <tbody>
                        {{!$icounter=1}}
                        @if(count($Roles)>0)
                        @foreach ($Roles as $role)
                        <tr>
                            <td>{{$icounter}}</td>
                            <td>{{'RL'.$role->id}}</td>
                            <!-- <td>
                                <input type="checkbox" data-toggle="toggle" data-width="90" checked>
                            </td> -->
                            <td>{{$role->name}}</td>
                            <td>{{ date('d-m-Y',strtotime($role->strEntryDate)) }}<br>
                                <small>{{ date('H:i:s',strtotime($role->strEntryDate)) }}</small>
                            </td>
                            <td>{{$role->first_name.' '.$role->last_name}}</td>
                            <td>
                                <!-- <a href="{{ route('role.user-list') }}" title="Assigned Users" class="table-action">
                                        <i class="mas-users"></i>
                                    </a> -->
                                <a href="{{route('role.edit',$role->id)}}" title="Edit" class="table-action">
                                    <i class="mas-edit"></i>
                                </a>
                                <a href="{{ route('role.info',$role->id) }}" title="Info" class="table-action">
                                    <i class="mas-info-circle"></i>
                                </a>

                                <form action="{{ route('role.delete',$role->id) }}" method="POST" onsubmit="return confirm('Are you Sure You wanted to Delete?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <a href="#" title="Delete" class="table-action">
                                        <button type="submit" class="p-0 border-0 bg-none"><i class="mas-trash"></i></button>
                                    </a>
                                </form>
                            </td>
                        </tr>
                        {{!$icounter++}}
                        @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--table end-->
    <!--notify messages-->

    <!--END notify messages-->
</div>
<!-- content-wrapper ends -->

<!--/. footer ends-->


<!-- plugins:js -->
<!--toggle button active/inactive-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
</script>
<script src="../global/assets/vendors/toggle-button/bootstrap4-toggle.min.js"></script>

@endsection
@section('script')

<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
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
                    filename: "role" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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

</script>
<!--toggle button active/inactive-->
<script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script>
@endsection
