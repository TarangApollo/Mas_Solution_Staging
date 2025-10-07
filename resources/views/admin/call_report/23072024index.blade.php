@extends('layouts.admin')

@section('title', 'Call List')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper pb-0">
            <div class="page-header">
                <h3>List of Calls</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item active"> Call List </li>
                    </ol>
                </nav>
            </div>
            <!-- first row starts here -->

            <div class="row d-flex justify-content-center">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <h4 class="card-title mt-0">search by categories</h4>
                            <form class="was-validated p-4 pb-3" action="{{ route('call_report.index') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Search by Complaint ID</label>
                                            <input type="text" class="form-control" name="searchText" id="searchText"
                                                value="{{ (isset($postarray['searchText']) && $postarray['searchText']) != null ? $postarray['searchText'] : '' }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select OEM Company</label>
                                            <select class="js-example-basic-single" name="OEMCompany" id="OEMCompany"
                                                style="width: 100%;">
                                                <option label="Please Select" value="">-- Select --</option>
                                                @foreach ($CompanyMaster as $Company)
                                                    <option value="{{ $Company->iCompanyId }}"
                                                        {{ isset($postarray['OEMCompany']) && $postarray['OEMCompany'] == $Company->iCompanyId ? 'selected' : '' }}>
                                                        {{ $Company->strOEMCompanyName }}</option>
                                                @endforeach
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select System</label>
                                            <select class="js-example-basic-single" name="iSystemId" id="iSystemId"
                                                style="width: 100%;">
                                                <option label="Please Select" value="">-- Select --</option>
                                                @foreach ($systems as $system)
                                                    <option value="{{ $system->iSystemId }}"
                                                        {{ isset($postarray['iSystemId']) && $postarray['iSystemId'] == $system->iSystemId ? 'selected' : '' }}>
                                                        {{ $system->strSystem }}</option>
                                                @endforeach

                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Ticket Status</label>
                                            <select class="js-example-basic-single" style="width: 100%;"
                                                name="TicketStatus">
                                                <option label="Please Select" value="">-- Select --</option>
                                                <option value="0"
                                                    {{ isset($postarray['TicketStatus']) && $postarray['TicketStatus'] == 0 ? 'selected' : '' }}>
                                                    Open</option>
                                                <option value="3"
                                                    {{ isset($postarray['TicketStatus']) && $postarray['TicketStatus'] == 3 ? 'selected' : '' }}>
                                                    Reopen</option>
                                                <option value="1"
                                                    {{ isset($postarray['TicketStatus']) && $postarray['TicketStatus'] == 1 ? 'selected' : '' }}>
                                                    Closed</option>

                                                <option value="5"
                                                    {{ isset($postarray['TicketStatus']) && $postarray['TicketStatus'] == 5 ? 'selected' : '' }}>
                                                    Customer Feedback Awaited </option>
                                                <option value="4"
                                                    {{ isset($postarray['TicketStatus']) && $postarray['TicketStatus'] == 4 ? 'selected' : '' }}>
                                                    Closed with RMA</option>
                                                <option value="6"
                                                    {{ isset($postarray['TicketStatus']) && $postarray['TicketStatus'] == 6 ? 'selected' : '' }}>
                                                    Open < 24</option>
                                                <option value="7"
                                                    {{ isset($postarray['TicketStatus']) && $postarray['TicketStatus'] == 7 ? 'selected' : '' }}>
                                                    Open > 24</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Executive Level</label>
                                            <select class="js-example-basic-single" style="width: 100%;" name="LevelId"
                                                id="LevelId">
                                                <option label="Please Select" value="">-- Select --</option>
                                                <option value="1"
                                                    {{ isset($postarray['LevelId']) && $postarray['LevelId'] == 1 ? 'selected' : '' }}>
                                                    Level 1</option>
                                                <option value="2"
                                                    {{ isset($postarray['LevelId']) && $postarray['LevelId'] == 2 ? 'selected' : '' }}>
                                                    Level 2</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Call Outcome</label>
                                            <select class="js-example-basic-single" style="width: 100%;" name="CallOutcome"
                                                id="CallOutcome">
                                                <option label="Please Select" value="">-- Select --</option>
                                                <option value="answered"
                                                    {{ isset($postarray['CallOutcome']) && $postarray['CallOutcome'] == 'answered' ? 'selected' : '' }}>
                                                    Answered</option>
                                                <option value="missed"
                                                    {{ isset($postarray['CallOutcome']) && $postarray['CallOutcome'] == 'missed' ? 'selected' : '' }}>
                                                    Missed</option>
                                                <!-- <option value="OP">No Answer</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Date Range</label>
                                            <input type="text" class="form-control" id="datepicker"
                                                value="{{ $postarray['daterange'] }}" name="daterange" />
                                        </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Call Attendant</label>
                                            <select class="js-example-basic-single" style="width: 100%;" name="exeId"
                                                id="exeId">
                                                <option label="Please Select" value="">-- Select --</option>
                                                @foreach ($executiveList as $user)
                                                    <option value="{{ $user->iUserId }}"
                                                        {{ isset($postarray['exeId']) && $postarray['exeId'] == $user->iUserId ? 'selected' : '' }}>
                                                        {{ $user->strFirstName . ' ' . $user->strLastName }}</option>
                                                @endforeach
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Support type</label>
                                            <select class="js-example-basic-single" name="iSupportType" id="iSupportType"
                                                style="width: 100%;">
                                                <option label="Please Select" value="">-- Select --</option>
                                                @foreach ($supporttypes as $supporttype)
                                                    <option value="{{ $supporttype->iSuppotTypeId }}"
                                                        {{ isset($postarray['iSupportType']) && $postarray['iSupportType'] == $supporttype->iSuppotTypeId ? 'selected' : '' }}>
                                                        {{ $supporttype->strSupportType }}</option>
                                                @endforeach

                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4 mt-4">
                                        <button type="button" class="btn btn-primary">
                                            Total Calls <span
                                                class="badge badge-light ml-5">{{ count($ticketList) ?? 0 }}</span>
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
                                <th data-field="no" data-sortable="true">ID</th>
                                <th data-field="outcome" data-sortable="true">Outcome</th>
                                <th data-field="status" data-sortable="true">Status</th>
                                <th data-field="date" data-sortable="true">Date</th>
                                <th data-field="audio">Audio</th>
                                <th data-field="service" data-sortable="true">OEM Company</th>
                                <th data-field="system" data-sortable="true">System</th>
                                <th data-field="Projecttype" data-sortable="true">Support type</th>
                                <th data-field="openlevel" data-sortable="true">Open Level</th>
                                <th data-field="closelevel" data-sortable="true">Close Level</th>
                                <th data-field="duration" data-sortable="true">Duration</th>
                                <th data-field="attendant" data-sortable="true">Attendant</th>
                                <th data-field="action">Actions</th>
                            </thead>
                            <tbody>
                                <?php $iCounter = 1;
                                $TotalMinutes = 0; ?>
                                @foreach ($ticketList as $ticket)
                                    <tr>
                                        <td>{{ $iCounter }}</td>
                                        <td>{{ str_pad($ticket->iTicketId, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ ucfirst($ticket->call_state) }}</td>
                                        <td>{{ $ticket->ticketName }}</td>
                                        <td>{{ date('d-m-Y', strtotime($ticket->ComplainDate)) }}<br>
                                            <small>{{ date('H:i:s', strtotime($ticket->ComplainDate)) }}</small>
                                        </td>
                                        <td>
                                            @if ($ticket->recordUrl != '')
                                                <audio controls>
                                                    <source src="{{ $ticket->recordUrl }}" type="audio/mpeg">
                                                    {{ $ticket->recordUrl }}
                                                </audio>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->strOEMCompanyName }} </td>
                                        <td>{{ $ticket->strSystem }}</td>
                                        <td>{{ $ticket->strSupportType }}</td>
                                        <td>{{ $ticket->openLevel == 1 ? 'Level 1' : 'Level 2' }}</td>
                                        <td><?php
                                        $subjects = \App\Models\TicketLog::where('iticketId', $ticket->iTicketId)
                                            ->whereIn('iStatus', [1, 4, 5])
                                            ->orderBy('iTicketLogId', 'DESC')
                                            ->first();
                                            if($subjects)
                                                echo 'Level '.$subjects->LevelId  ;
                                            else if($ticket->closelevel)
                                                 echo 'Level '.$ticket->closelevel ;
                                                else
                                                echo 'Level '.$ticket->openLevel;
                                        ?>
                                        </td>
                                        <td><?php
                                        $seconds = $ticket->callDuration;
                                        $H = floor($seconds / 3600);
                                        $i = ($seconds / 60) % 60;
                                        $s = $seconds % 60;
                                        echo sprintf('%02d:%02d:%02d', $H, $i, $s);
                                        $TotalMinutes = $TotalMinutes + $ticket->callDuration;
                                        ?>
                                        </td>
                                        <td>{{ $ticket->first_name . ' ' . $ticket->last_name }}</td>
                                        <td>
                                            <a href="{{ route('call_report.info', $ticket->iTicketId) }}" title="Info"
                                                class="table-action">
                                                <i class="mas-info-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $iCounter++; ?>
                                @endforeach

                                <tr class="font-weight-bold">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">Total Minutes</td>
                                    <td><?php
                                    $seconds = $TotalMinutes;
                                    $H = floor($seconds / 3600);
                                    $i = ($seconds / 60) % 60;
                                    $s = $seconds % 60;
                                    echo sprintf('%02d:%02d:%02d', $H, $i, $s);
                                    ?></td>
                                    <td></td>
                                </tr>
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
            getSupportType();
        });

        function getsystem() {
            var OEMCompany = $("#OEMCompany").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('call_report.getsystem') }}",
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
                url: "{{ route('call_report.getCallAttendant') }}",
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

        function getSupportType(){
            var OEMCompany = $("#OEMCompany").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('call_report.getSupportType') }}",
                data: {
                    OEMCompany: OEMCompany
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (response.length > 0) {
                        $("#iSupportType").html(response);
                    } else {

                    }
                }
            });
        }

        // $("#iSystemId").change(function() {
        //     $.ajax({
        //         type: 'POST',
        //         url: "{{ route('company.getcomponent') }}",
        //         data: {
        //             search_system: this.value
        //         },
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             console.log(response);
        //             if (response.length > 0) {
        //                 $("#iComponentId").html(response);
        //             } else {

        //             }
        //         }
        //     });
        // });
        // $("#iComponentId").change(function() {

        //     $.ajax({
        //         type: 'POST',
        //         url: "{{ route('faq.getsubcomponent') }}",
        //         data: {
        //             iComponentId: this.value
        //         },
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             console.log(response);
        //             $("#iSubComponentId").html(response);
        //         }
        //     });

        // });
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
                        filename: "Call_List" + new Date().toISOString().replace(/[\-\:\.]/g, "") +
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
