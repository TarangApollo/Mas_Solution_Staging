@extends('layouts.wladmin')

@section('title', 'List of Calls')

@section('content')
    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

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
                        <form class="was-validated p-4 pb-3" action="{{ route('callList.index') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Search by Complaint ID</label>
                                        <input type="text" class="form-control" name="searchText" id="searchText"
                                            value="{{ (isset($postarray['searchText']) && $postarray['searchText']) != null ? $postarray['searchText'] : '' }}" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Customer Company</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="CompanyClientId"
                                            id="CompanyClientId">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($CompanyClients as $CompanyClient)
                                                <option value="{{ $CompanyClient->iCompanyClientId }}"
                                                    {{ isset($postarray['CompanyClientId']) && $postarray['CompanyClientId'] == $CompanyClient->iCompanyClientId ? 'selected' : '' }}>
                                                    {{ $CompanyClient->CompanyName }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select System</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iSystemId"
                                            id="iSystemId">
                                            <option label="Please Select" value="">-- Select -- </option>
                                            @if (!empty($systems))
                                                @foreach ($systems as $system)
                                                    <option value="{{ $system->iSystemId }}"
                                                        {{ isset($postarray['iSystemId']) && $postarray['iSystemId'] == $system->iSystemId ? 'selected' : '' }}>
                                                        {{ $system->strSystem }}</option>
                                                @endforeach
                                            @else
                                                <option label="Please Select" value="">No Record Found</option>
                                            @endif
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Component</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iComponentId"
                                            id="iComponentId">
                                            <option label="Please Select" value="">-- Select -- </option>
                                            @if (!empty($componentLists))
                                                @foreach ($componentLists as $component)
                                                    <option value="{{ $component->iComponentId }}"
                                                        {{ isset($postarray['iComponentId']) && $postarray['iComponentId'] == $component->iComponentId ? 'selected' : '' }}>
                                                        {{ $component->strComponent }}</option>
                                                @endforeach
                                            @else
                                                <option label="Please Select" value="">No Record Found</option>
                                            @endif
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Ticket Status</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="level">
                                            <option label="Please Select" value="">-- Select --</option>
                                            <option value="0"
                                                {{ isset($postarray['level']) && $postarray['level'] == 0 ? 'selected' : '' }}>
                                                Open</option>
                                            <option value="3"
                                                {{ isset($postarray['level']) && $postarray['level'] == 3 ? 'selected' : '' }}>
                                                Reopen</option>
                                            <option value="1"
                                                {{ isset($postarray['level']) && $postarray['level'] == 1 ? 'selected' : '' }}>
                                                Closed</option>
                                            <option value="5"
                                                {{ isset($postarray['level']) && $postarray['level'] == 5 ? 'selected' : '' }}>
                                                Customer Feedback Awaited </option>
                                            <option value="4"
                                                {{ isset($postarray['level']) && $postarray['level'] == 4 ? 'selected' : '' }}>
                                                Closed with RMA</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Date Range</label>
                                        <input type="text" class="form-control" id="datepicker"
                                            value="{{ $postarray['daterange'] }}" name="daterange" />
                                    </div>
                                </div> <!-- /.col -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Executive Level</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="LevelId"
                                            id="LevelId">
                                            <option label="Please Select" value="">-- Select -- </option>
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

        <?php
        $userid = Auth::user()->id;
        $record = DB::table('reorder_columns')->where('strUrl', 'callList.index')->where('iUserId', $userid)->first();
        
        $selectedOptions = [];
        if ($record) {
            $selectedOptions = json_decode($record->json, true); // Decode JSON as an associative array
        }
        
        $options = ['NO', 'ID', 'LEVEL', 'STATUS', 'DATE', 'SYSTEM', 'COMPONENT', 'SUPPORT TYPE', 'CUSTOMER', 'COMPANY', 'ACTIONS'];
        $optionLabels = [
            'NO' => 'No',
            'ID' => 'ID',
            'LEVEL' => 'Level',
            'STATUS' => 'Status',
            'DATE' => 'Date',
            'SYSTEM' => 'System',
            'COMPONENT' => 'Component',
            'SUPPORT TYPE' => 'Support type',
            'CUSTOMER' => 'Customer',
            'COMPANY' => 'Company',
            'ACTIONS' => 'Actions',
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

                            <?php if(!$record){ ?>
                            <select id="multiple-checkboxes" multiple="multiple" required>
                                <option value="NO">NO</option>
                                <option value="ID">ID</option>
                                <option value="LEVEL">LEVEL</option>
                                <option value="STATUS">STATUS</option>
                                <option value="DATE">DATE</option>
                                <option value="SYSTEM">SYSTEM</option>
                                <option value="COMPONENT">COMPONENT</option>
                                <option value="SUPPORT TYPE">SUPPORT TYPE</option>
                                <option value="CUSTOMER">CUSTOMER</option>
                                <option value="COMPANY">COMPANY</option>
                                <option value="ACTIONS">ACTIONS</option>
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
                    <table id="fresh-table" class="table" data-show-export="true">


                        <thead>
                            <?php
                            foreach ($selectedOptions as $option) {
                                $label = $optionLabels[$option];
                                echo "<th data-field=\"{$option}\">{$label}</th>";
                            }
                            ?>
                        </thead>

                        <tbody>
                            <?php $iCounter = 1;
                            $TotalMinutes = 0; ?>
                            @foreach ($ticketList as $ticket)
                                <tr>

                                    <?php if (in_array('NO', $selectedOptions)) {
                                        echo '<td>' . $iCounter . '</td>';
                                    } ?>
                                    <?php if (in_array('ID', $selectedOptions)) {
                                        echo '<td>' . $ticket->strTicketUniqueID ?? str_pad($ticket->iTicketId, 4, '0', STR_PAD_LEFT) . '</td>';
                                    } ?>
                                    <?php if (in_array('LEVEL', $selectedOptions)) {
                                        echo '<td>Level ' . ($ticket->closelevel ?: $ticket->openLevel) . '</td>';
                                    } ?>
                                    <?php if (in_array('STATUS', $selectedOptions)) {
                                        echo "<td>{$ticket->ticketName}</td>";
                                    } ?>
                                    <?php if (in_array('DATE', $selectedOptions)) {
                                        echo '<td>' . date('d-m-Y', strtotime($ticket->ComplainDate)) . '<br><small>' . date('H:i:s', strtotime($ticket->ComplainDate)) . '</small></td>';
                                    } ?>
                                    <?php if (in_array('SYSTEM', $selectedOptions)) {
                                        echo "<td>{$ticket->strSystem}</td>";
                                    } ?>
                                    <?php if (in_array('COMPONENT', $selectedOptions)) {
                                        echo "<td>{$ticket->strComponent}</td>";
                                    } ?>
                                    <?php if (in_array('SUPPORT TYPE', $selectedOptions)) {
                                        echo "<td>{$ticket->strSupportType}</td>";
                                    } ?>
                                    <?php if (in_array('CUSTOMER', $selectedOptions)) {
                                        echo "<td>{$ticket->CustomerName}</td>";
                                    } ?>
                                    <?php if (in_array('COMPANY', $selectedOptions)) {
                                        echo "<td>{$ticket->CompanyName}</td>";
                                    } ?>
                                    <?php if (in_array('ACTIONS', $selectedOptions)) {
                                        echo "<td><a href=\"" . route('callList.info', $ticket->iTicketId) . "\" title=\"Info\" class=\"table-action\"><i class=\"mas-info-circle\"></i></a></td>";
                                    } ?>
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

    <!-- multi-select  -->
    <script src="{{ asset('global/assets/vendors/multi-select/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/multi-select/js/main.js') }}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/tableExport.js') }}"></script>
    <?php  if (in_array('46', \Session::get('menuList')) ) { ?>
    <script>
        var $table = $('#fresh-table');

        $(document).ready(function() {
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
                exportDataType: 'all',
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
        var seeAll = true; // Boolean to veridy if we're exporting
        var myLimit = 50;
        var previousLimit = 50;

        function queryParams(params) {
            // if we are exporting
            if (seeAll) {
                //sets the limit parameter
                params.limit = myLimit;
            }
            return params;
        }

        $(function() {
            $('.mas-download').click(function(e) {
                // $('#fresh-table').tableExport({
                //     type: 'excel',
                //     fileName: "Call_List" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
                //     exportDataType: 'all',
                // });
                var newLimit = $table.bootstrapTable('getOptions').totalRows;
                // $table.tableExport({
                //     exportDataType: 'all',
                //     type: 'excel',
                // });
                //now sets the new limit, we use the total of rows since we want to show everything
                var newLimit = $table.bootstrapTable('getOptions').totalRows;
                //Get the actual limit before extracting (page size)
                previousLimit = $table.bootstrapTable('getOptions').pageSize;
                //sets the limit we want to send in pramater to the new one
                myLimit = newLimit;
                //refresh the table, triggering the queryParams ( our table's dataQueryParams )
                $table.bootstrapTable('refresh');
                //your export of the table, in any way you'd like
                $table.tableExport({
                    type: 'excel',
                });
                // sets the new limit parameter to the previous one before showing all rows, in our case '10'
                myLimit = previousLimit;
                //refresh the table with new limit parameter
                $table.bootstrapTable('refresh');
            });

            $('.mas-refresh').click(function(e) {
                $('#fresh-table').bootstrapTable('resetSearch');
            });
        });

        function clearData() {
            window.location.href = "";
        }
    </script>
    <?php } else { ?>
    <script>
        var $table = $('#fresh-table');

        $(document).ready(function() {
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
                exportDataType: 'all',
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
    <script>
        function setSelectedDateRange() {
            $("#strdaterange").val($("#datepicker").val());
        }

        $("#iSystemId").change(function() {
            $.ajax({
                type: 'POST',
                url: "{{ route('company.getcomponent') }}",
                data: {
                    search_system: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (response.length > 0) {
                        $("#iComponentId").html(response);
                    } else {

                    }
                }
            });
        });
        $("#iComponentId").change(function() {

            $.ajax({
                type: 'POST',
                url: "{{ route('faq.getsubcomponent') }}",
                data: {
                    iComponentId: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    $("#iSubComponentId").html(response);
                }
            });

        });

        function clearData() {
            window.location.href = "";
        }

        //on the event of the successful loading of the table, needed otherwise the table wont be done
        // generating before the export goes on


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

    <script>
        $(document).ready(function() {
            $('#submitCheckboxes').click(function(e) {
                e.preventDefault();

                var selectedCheckboxes = $('#multiple-checkboxes').val();

                $.ajax({
                    url: "{{ route('callList.reorder_column') }}", // Replace with your route
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
