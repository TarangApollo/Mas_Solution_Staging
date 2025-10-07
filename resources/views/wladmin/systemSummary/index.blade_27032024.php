@extends('layouts.wladmin')

@section('title', 'System Summary')

@section('content')
<link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>System Summary</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active"> System Summary </li>
                </ol>
            </nav>
        </div>
        <!-- first row starts here -->
        <div class="row d-flex justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">SEARCH BY CATEGORIES</h4>
                        <form class="was-validated p-4 pb-3" action="{{route('systemsummary.index' )}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select System</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iSystemId" id="iSystemId">
                                            <option label="Please Select" value="">-- Select -- </option>
                                            @if(!empty($systems))
                                            @foreach($systems as $system)
                                            <option value="{{ $system->iSystemId }}" {{ (isset($postarray['iSystemId']) && $postarray['iSystemId'] == $system->iSystemId) ? "selected" : "" }} >{{ $system->strSystem }}</option>
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
                                        <select class="js-example-basic-single" style="width: 100%;" name="iComponentId" id="iComponentId">
                                            <option label="Please Select" value="">-- Select -- </option>
                                            @if(!empty($componentLists))
                                            @foreach($componentLists as $component)
                                            <option value="{{ $component->iComponentId }}" {{ (isset($postarray['iComponentId']) && $postarray['iComponentId'] == $component->iComponentId) ? "selected" : "" }} >{{ $component->strComponent }}</option>
                                            @endforeach
                                            @else
                                            <option label="Please Select" value="">No Record Found</option>
                                            @endif
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Sub Component</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iSubComponentId" id="iSubComponentId">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @if(!empty($subcomponents))
                                            @foreach($subcomponents as $subcomponent)
                                            <option value="{{ $subcomponent->iSubComponentId }}" {{ (isset($postarray['iSubComponentId']) && $postarray['iSubComponentId'] == $subcomponent->iSubComponentId) ? "selected" : "" }} >{{ $subcomponent->strSubComponent }}</option>
                                            @endforeach
                                            @else
                                            <option label="Please Select" value="">No Record Found</option>
                                            @endif
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Date Range</label>
                                        <input type="text" class="form-control" value="{{ $postarray['daterange'] ?? '' }}" id="datepicker" name="daterange"/>
                                    </div>
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
                            <th data-field="system" data-sortable="true">System</th>
                            <th data-field="component" data-sortable="true">Component</th>
                            <th data-field="sub" data-sortable="true">Sub Component</th>
                            <th data-field="issues">Count of issues</th>
                            <th data-field="action">Actions</th>
                        </thead>
                        <tbody>
                            <?php $iCounter = 1;
                            $TotalMinutes = 0; ?>
                            @foreach($ticketList as $ticket)
                            <tr>
                                <td>{{ $iCounter }}</td>
                                <td>{{$ticket->strSystem}}</td>
                                <td>{{$ticket->strComponent}}</td>
                                <td>{{$ticket->strSubComponent}}</td>
                                <td>{{$ticket->issue}}</td>
                                <td>

                                    <form action="{{ route('systemsummary.info') }}" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="iSystemId" value="{{ $ticket->iSystemId }}">
                                        <input type="hidden" name="iComnentId" value="{{ $ticket->iComnentId }}">
                                        <input type="hidden" name="iSubComponentId" value="{{ $ticket->iSubComponentId }}">
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

    //$("#datepicker").daterangepicker();
    $(function(){
    //$("#datepicker").daterangepicker();
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
    function setSelectedDateRange(){
        $("#strdaterange").val($("#datepicker").val());
    }
    $("#iSystemId").change(function() {
        $.ajax({
            type: 'POST',
            url: "{{route('company.getcomponent')}}",
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
            url: "{{route('faq.getsubcomponent')}}",
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
                    filename: "System_Summary_" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: false,
                    exclude_inputs: false,
                    preserveColors: false
                });
            }
        });
        // $('.mas-refresh').click(function(e) {
        //     var table = $("#fresh-table");
        //     $('.search input[class="form-control"]').val(' ');
        //     $(table).bootstrapTable('resetView');
        // });
        $('.mas-refresh').click(function(e) {
            $('#fresh-table').bootstrapTable('resetSearch');
        });
    });
    function clearData() {
        window.location.href = "";
    }
</script>

@endsection
