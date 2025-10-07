@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>List of Projects</h3>
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
                        <form class="was-validated p-4 pb-3" action="{{ route('projects.index') }}" method="post">
                            @csrf
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Project Name</label>
                                        <select class="js-example-basic-single" name="search_project" id="iProjectId"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($ticketmasters as $ticket)
                                                <option value="{{ $ticket->ProjectName }}"
                                                    @if (isset($search_project) && $search_project == $ticket->ProjectName) {{ 'selected' }} @endif>
                                                    {{ $ticket->ProjectName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select class="js-example-basic-single" name="search_state" id="iStateId"
                                            style="width: 100%;" onchange="getCity();">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($statemasters as $state)
                                                <option value="{{ $state->iStateId }}"
                                                    @if (isset($search_state) && $search_state == $state->iStateId) {{ 'selected' }} @endif>
                                                    {{ $state->strStateName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City</label>
                                        <select class="js-example-basic-single" name="search_city" id="iCityId"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($citymasters as $cities)
                                                <option value="{{ $cities->iCityId }}"
                                                    @if (isset($search_city) && $search_city == $cities->iCityId) {{ 'selected' }} @endif>
                                                    {{ $cities->strCityName }} </option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Date Range</label>
                                        <input type="text" class="form-control" name="daterange" id="datepicker"
                                            value="{{ isset($search_daterange) ? $search_daterange : '' }}" />
                                    </div>
                                </div> <!-- /.col -->

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2"
                                        value="Search">
                                    <input type="button" class="btn btn-fill btn-default text-uppercase mt-3"
                                        onclick="clearData();" value="Clear">
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <!--card end-->
            </div>
        </div><!-- end row -->
        <?php
            $userid = Auth::user()->id;
            $record = DB::table('reorder_columns')->where('strUrl', 'projects.index')->where('iUserId', $userid)->first();
            //dd($record);
            $selectedOptions = [];
            if ($record) {
                $selectedOptions = json_decode($record->json, true); // Decode JSON as an associative array
            }
    
            $options = ['No','PROJECT NAME','STATE','CITY','VERTICAL','SUB-VERTICAL','SI','ENGINEER','COMMISSIONED IN','SYSTEM','PANEL','PANEL QTY','DEVICES','DEVICE QTY','OTHER COMPONENTS','BOQ','AMC','Actions'];
            $optionLabels = [
                'No' => 'No',
                'PROJECT NAME' => 'PROJECT NAME',
                'LEVEL' => 'Level',
                'STATE' => 'STATE',
                'CITY' => 'CITY',
                'VERTICAL' => 'VERTICAL',
                'SUB-VERTICAL' => 'SUB-VERTICAL',
                'SI' => 'SI',
                'ENGINEER' => 'ENGINEER',
                'COMMISSIONED IN' => 'COMMISSIONED IN',
                'SYSTEM' => 'SYSTEM',
                'PANEL' => 'PANEL',
                'PANEL QTY' => 'PANEL QTY',
                'DEVICES' => 'DEVICES',
                'DEVICE QTY' => 'DEVICE QTY',
                'OTHER COMPONENTS' => 'OTHER COMPONENTS',
                'BOQ' => 'BOQ',
                'AMC' => 'AMC',
                'Actions' => 'Actions',
            ];
    
            // Use default options if no user-specific data is available
            if (empty($selectedOptions)) {
                $selectedOptions = $options;
            }
        
        ?>
        <!-- multi-select row -->
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-10">
                <div class="row justify-content-center">
                    <div class="col-md-9 d-flex justify-content-center align-items-center">
                        <div class="d-flex text-left align-items-center w-100">
                            <strong class="sl">Reorder Columns:</strong>
                            <?php  if(!$record){ ?>
                            <select id="multiple-checkboxes" multiple="multiple" required>
                                <option value="No">No</option>
                                <option value="PROJECT NAME">PROJECT NAME</option>
                                <option value="STATE">STATE</option>
                                <option value="CITY">CITY</option>
                                <option value="VERTICAL">VERTICAL</option>
                                <option value="SUB-VERTICAL">SUB-VERTICAL</option>
                                <option value="ENGINEER">ENGINEER</option>
                                <option value="COMMISSIONED IN">COMMISSIONED IN</option>
                                <option value="SYSTEM">SYSTEM</option>
                                <option value="PANEL">PANEL</option>
                                <option value="PANEL QTY">PANEL QTY</option>
                                <option value="DEVICES">DEVICES</option>
                                <option value="DEVICE QTY">DEVICE QTY</option>
                                <option value="OTHER COMPONENTS">OTHER COMPONENTS</option>
                                <option value="BOQ">BOQ</option>
                                <option value="AMC">AMC</option>
                                <option value="Actions">Actions</option>
                            </select>
                            <?php }else{ ?>
                            <select id="multiple-checkboxes" multiple="multiple" required>
                                <?php
                                foreach ($options as $option) {
                                    $selected = in_array($option, $selectedOptions) ? 'selected' : '';
                                    echo "<option value=\"$option\" $selected>$option</option>";
                                }
                                ?>
                            </select>
                            <?php } ?>

                            <input type="button" id="submitCheckboxes"
                                class="btn btn-fill btn-success text-uppercase ml-3 d-flex justify-content-center align-items-center"
                                value="Save">

                        </div>
                    </div> <!-- /. col-md-7 -->
                </div> <!-- /. row justify content -->
            </div> <!-- /. col-md-10 -->
        </div>
        <!-- multi-select row END -->
        
        <!--table start-->
        <div class="row d-flex justify-content-center my-5">
            <div class="col-md-12">
                <div class="fresh-table toolbar-color-orange">
                    <table id="fresh-table" class="table">
                        <!--<thead>
                            <th data-field="id">No</th>
                            <th data-field="PROJECT NAME" data-sortable="true">PROJECT NAME</th>
                            <th data-field="STATE">STATE</th>
                            <th data-field="CITY" data-sortable="true">CITY</th>
                            <th data-field="VERTICAL" data-sortable="true">VERTICAL</th>
                            <th data-field="SUB-VERTICAL" data-sortable="true">SUB-VERTICAL</th>
                            <th data-field="SI" data-sortable="true">SI</th>
                            <th data-field="ENGINEER" data-sortable="true">ENGINEER</th>
                            <th data-field="COMMISSIONED IN" data-sortable="true">COMMISSIONED IN</th>
                            <th data-field="SYSTEM" data-sortable="true">SYSTEM</th>
                            <th data-field="PANEL" data-sortable="true">PANEL</th>
                            <th data-field="PANEL QTY" data-sortable="true">PANEL QTY</th>
                            <th data-field="DEVICES" data-sortable="true">DEVICES</th>
                            <th data-field="DEVICE QTY" data-sortable="true">DEVICE QTY</th>
                            <th data-field="OTHER COMPONENTS" data-sortable="true">OTHER COMPONENTS</th>
                            <th data-field="BOQ" data-sortable="true">BOQ</th>
                            <th data-field="AMC" data-sortable="true">AMC</th>
                            <th data-field="action">Actions</th>
                        </thead>-->
                        <thead>
                            <?php
                            foreach ($selectedOptions as $option) {
                                $label = $optionLabels[$option];
                                echo "<th data-field=\"{$option}\">{$label}</th>";
                            }
                            ?>
                        </thead>
                        <tbody>
                            <?php $iCounter = 1; ?>
                            @foreach ($Projects as $projects)
                                <tr>
                                    <?php if (in_array('No', $selectedOptions)) { ?>
                                        <td>{{ $iCounter }}</td>
                                    <?php } ?>
                                    <?php if (in_array('PROJECT NAME', $selectedOptions)) { ?>
                                        <td>{{ $projects->projectName }}</td>
                                    <?php } ?>
                                    <?php if (in_array('STATE', $selectedOptions)) { ?>
                                        <td>{{ $projects->strStateName }}</td>
                                    <?php } ?>
                                    <?php if (in_array('CITY', $selectedOptions)) { ?>
                                        <td>{{ $projects->strCityName }}</td>
                                    <?php } ?>
                                    <?php if (in_array('VERTICAL', $selectedOptions)) { ?>
                                        <td>{{ $projects->strVertical }}</td>
                                    <?php } ?>
                                    <?php if (in_array('SUB-VERTICAL', $selectedOptions)) { ?>
                                        <td>{{ $projects->strSubVertical }}</td>
                                    <?php } ?>
                                    <?php if (in_array('SI', $selectedOptions)) { ?>
                                        <td>{{ $projects->strSI }}</td>
                                    <?php } ?>
                                    <?php if (in_array('ENGINEER', $selectedOptions)) { ?>
                                        <td>{{ $projects->strEngineer }}</td>
                                    <?php } ?>
                                    <?php if (in_array('COMMISSIONED IN', $selectedOptions)) { ?>
                                        <td>{{ $projects->strCommissionedIn }}</td>
                                    <?php } ?>
                                    <?php if (in_array('SYSTEM', $selectedOptions)) { ?>
                                        <td>{{ $projects->strSystem }}</td>
                                    <?php } ?>
                                    <?php if (in_array('PANEL', $selectedOptions)) { ?>
                                        <td>{{ $projects->strPanel }}</td>
                                    <?php } ?>
                                    <?php if (in_array('PANEL QTY', $selectedOptions)) { ?>
                                        <td>{{ $projects->strPanelQuantity }}</td>
                                    <?php } ?>
                                    <?php if (in_array('DEVICES', $selectedOptions)) { ?>
                                        <td>{{ $projects->strDevices }}</td>
                                    <?php } ?>
                                    <?php if (in_array('DEVICE QTY', $selectedOptions)) { ?>
                                        <td>{{ $projects->strDeviceQuantity }}</td>
                                    <?php } ?>
                                    <?php if (in_array('OTHER COMPONENTS', $selectedOptions)) { ?>
                                        <td>{{ $projects->strOtherComponents }}</td>
                                    <?php } ?>
                                    <?php if (in_array('BOQ', $selectedOptions)) { ?>
                                        <td>{{ $projects->strBOQ }}</td>
                                    <?php } ?>
                                    <?php if (in_array('AMC', $selectedOptions)) { ?>
                                        <td>{{ $projects->strAMC }}</td>
                                    <?php } ?>
                                    <?php if (in_array('Actions', $selectedOptions)) { ?>
                                        <td>
                                            <a href="{{ route('projects.edit', $projects->iTicketId) }}" title="Edit"
                                                class="table-action">
                                                <i class="mas-edit"></i>
                                            </a>
                                            <a href="{{ route('projects.info', $projects->iTicketId) }}" title="Info"
                                                class="table-action">
                                                <i class="mas-info-circle"></i>
                                            </a>
                                            <form action="{{ route('projects.delete', $projects->projectProfileId) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <a href="#" title="Delete" class="table-action">
                                                    <button type="submit" class="p-0 border-0 bg-none"><i
                                                            class="mas-trash"></i></button>
                                                </a>
                                            </form>
                                        </td>
                                    <?php } ?>
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
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js">
    </script>
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
                url: "{{ route('component.getcomponent') }}",
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
                url: "{{ route('companyclient.updateStatus') }}",
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
