@extends('layouts.wladmin')

@section('title', 'Top Analytic Report')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Top Analytic Report</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Reports</li>
                <li class="breadcrumb-item active"> Top Analytic Report </li>
            </ol>
        </nav>
    </div>
    @include('wladmin.wlcommon.alert')
    <div class="row">
        <div class="col-xl-12 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="accordion-content clearfix">
                        <div class="col-lg-3 col-md-4">
                            <div class="accordion-box">
                                <div class="panel-group" id="RoleTabs">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a>
                                                    Top Analytic 
                                                </a>
                                            </h4>
                                        </div>
                                        @include('wladmin.topAnalytic.topAnalyticsidebar')
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8">
                            <div class="accordion-box-content">
                                <div class="tab-content clearfix">
                                    <!--Component form starts-->
                                    <div class="tab-pane fade in active" id="add-component">
                                        <h3 class="tab-content-title">Top Cities</h3>
                                            <div class="row">    
                                                <!--table div start-->
                                                <div class="col-md-12">                                
                                                    <div class="fresh-table toolbar-color-orange">
                                                        <table id="fresh-table" class="table">
                                                            <thead>
                                                                <th data-field="id">No</th>
                                                                <th data-field="city" data-sortable="true">City Name</th>
                                                                <th data-field="issue" data-sortable="true">Count of Issues</th>
                                                            </thead>
                                                            <tbody>
                                                                <?php $iCounter=1; ?>
                                                                @foreach($ticketList as $ticket)
                                                                <tr>
                                                                    <td>{{ $iCounter }}</td>
                                                                    <td>{{ $ticket->strCityName }}</td>
                                                                    <td>{{ $ticket->issueCount }}</td>                                                               
                                                                </tr>
                                                                <?php $iCounter++; ?>
                                                                @endforeach                        
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!--card body end-->
            </div>
            <!--card end-->
        </div>
    </div>

</div>
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
                    filename: "Top_Cities_Report_" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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
