@extends('layouts.admin')

@section('title', 'Call Attendant List')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>List of Call Attendants</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active"> Call Attendants </li>
            </ol>
        </nav>
    </div>
    @include('admin.common.alert')
    <div class="alert alert-success" id="successalert" role="alert" style="display:none">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <span id="msgdata"></span>
    </div>
    <div class="alert alert-danger" id="erroralert" role="alert" style="display:none">
        <strong>Error !</strong> {{ session('Error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <span id="msgdata"></span>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body p-0">
                    <h4 class="card-title mt-0">search by categories</h4>
                    <form class="was-validated p-4 pb-3" action="{{ route('call_attendant.index') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" name="search_first_name" value="{{ $search_first_name ?? '' }}" class="form-control">
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact</label>
                                    <input type="text" name="search_contact" value="{{ $search_contact ?? '' }}" class="form-control">
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email ID</label>
                                    <input type="text" name="search_email" value="{{ $search_email ?? '' }}" class="form-control">
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Status</label>
                                    <select class="js-example-basic-single" name="search_status" style="width: 100%;">
                                        <option label="Please Select" value="">-- Select --</option>
                                        <option value="1" @if(isset($search_status) && $search_status==1) {{ 'selected' }}@endif>Active</option>
                                        <option value="0" @if(isset($search_status) && $search_status==0) {{ 'selected' }}@endif>Inactive</option>
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Date Range</label>
                                    <input type="text" class="form-control" id="datepicker" value="{{ $daterange ?? '' }}" name="daterange"/>
                                </div>
                            </div> <!-- /.col -->
                        </div>
                        <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                        <input type="button" class="btn btn-fill btn-default text-uppercase mt-3" onclick="clearData();" value="Clear">
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
                        <th data-field="first" data-sortable="true">First Name</th>
                        <th data-field="last" data-sortable="true">Last Name</th>
                        <th data-field="contact" data-sortable="true">Contact</th>
                        <th data-field="mail" data-sortable="true">Email ID</th>
                        <th data-field="level">Executive Level</th>
                        <th data-field="login" data-sortable="true">Last Login</th>
                        <th data-field="date" data-sortable="true">Created Date</th>
                        <th data-field="by" data-sortable="true">Created by</th>
                        <th data-field="action">Actions</th>
                    </thead>
                    <tbody>
                        <?php $iCounter = 1; ?>
                        @foreach($CallAttendent as $CAttendent)
                        <tr>
                            <td>{{ $iCounter }}</td>
                            <td>{{ 'EN'.$CAttendent->iCallAttendentId }}</td>
                            <td>
                                @if($CAttendent->iStatus == 1)
                                <input type="checkbox" name="iStatus" id="iStatus" data-toggle="toggle" data-width="90" value="{{ $CAttendent->iStatus }}" onchange="updateStatus(<?= $CAttendent->iStatus ?>,<?= $CAttendent->iUserId ?>);" checked>
                                @else
                                <!--<input type="checkbox" name="iStatus" id="iStatus" data-toggle="toggle" data-width="90" value="{{ $CAttendent->iStatus }}" onchange="updateStatus(<?= $CAttendent->iStatus ?>,<?= $CAttendent->iUserId ?>);">-->
                                @endif
                            </td>
                            <td>{{ $CAttendent->strFirstName }}</td>
                            <td>{{ $CAttendent->strLastName }}</td>
                            <td>{{ $CAttendent->strContact }}</td>
                            <td>{{ $CAttendent->strEmailId }}</td>
                            <td>@if($CAttendent->iExecutiveLevel == 1) {{ 'Level 1' }} @else {{ 'Level 2' }} @endif</td>
                            <td>
                                <?php
                                $loginlog = \App\Models\Loginlog::select("strEntryDate")
                                    ->where(["userId" => $CAttendent->iUserId])
                                    ->orderBy('id', 'DESC')
                                    ->first();
                                ?>
                                {{ isset($loginlog->strEntryDate) ? date('d-m-Y',strtotime($loginlog->strEntryDate)) : "" }}<br>
                                <small>{{ isset($loginlog->strEntryDate) ? date('H:i:s',strtotime($loginlog->strEntryDate)) : "" }}</small>
                            </td>
                            <td>{{ date('d-m-Y',strtotime($CAttendent->strEntryDate)) }}<br>
                                <small>{{ date('H:i:s',strtotime($CAttendent->strEntryDate)) }}</small>
                            </td>

                            <td>{{ $CAttendent->first_name }} {{ $CAttendent->last_name }}</td>
                            <td>
                                                                @if($CAttendent->iStatus == 1)
                                <a href="{{ route('call_attendant.edit',$CAttendent->iUserId) }}" title="Edit" class="table-action">
                                    <i class="mas-edit"></i>
                                </a>
                                <a href="{{ route('call_attendant.info',$CAttendent->iUserId) }}" title="Info" class="table-action">
                                    <i class="mas-info-circle"></i>
                                </a>
                                  @endif
                                <form action="{{ route('call_attendant.destroy', $CAttendent->iUserId) }}" method="POST" onsubmit="return confirm('Are you Sure You wanted to Delete?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <a title="Delete" class="table-action">
                                        <button type="submit" href="#" style="border: none;background: none;">
                                            <i class="mas-trash"></i>
                                        </button>
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
            pageSize: 80,
            pageList: [80, 100, 200, 300, 400],

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
                    filename: "CallAttendant" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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

    function updateStatus(status, UserId) {
        //$("p").toggle();
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: "{{route('call_attendant.updateStatus')}}",
            data: {
                status: status,
                UserId: UserId
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
</script>
<!--toggle button active/inactive-->
<script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script>
@endsection
