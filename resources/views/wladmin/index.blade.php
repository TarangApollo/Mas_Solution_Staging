@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')

    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!--<div class="main-panel">-->
    <div class="content-wrapper pb-0">
        <?php if (in_array('13', \Session::get('menuList'))) { ?>
        <div class="page-header">
            <h3>Dashboard</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
        <!-- first row starts here -->

        <div class="row">
            <form class="was-validated pb-3" action="" method="post">
                @csrf
                <div class="col-md-3 pl-lg-0">
                    <div class="form-group">
                        <label>Select Financial Year</label>
                        <select class="js-example-basic-single" style="width: 100%;" required name="fYear" id="fYear">
                            @foreach ($yearList as $year)
                                <option value="{{ $year->iYearId }}">{{ $year->yearName }}</option>
                            @endforeach
                        </select>
                    </div> <!-- /.form-group -->
                </div> <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Select Month</label>
                        <?php $month = 0; ?>
                        <select class="js-example-basic-single" style="width: 100%;" required name="fMonth" id="fMonth">
                            <option label="Please Select" value="">-- Select --</option>
                            <option value="4" {{ $month == '04' ? 'selected' : '' }}>April</option>
                            <option value="5" {{ $month == '05' ? 'selected' : '' }}>May</option>
                            <option value="6" {{ $month == '06' ? 'selected' : '' }}>June</option>
                            <option value="7" {{ $month == '07' ? 'selected' : '' }}>July</option>
                            <option value="8" {{ $month == '08' ? 'selected' : '' }}>August</option>
                            <option value="9" {{ $month == '09' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $month == '10' ? 'selected' : '' }}>October</option>
                            <option value="11" {{ $month == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $month == '12' ? 'selected' : '' }}>December</option>
                            <option value="1" {{ $month == '01' ? 'selected' : '' }}>January</option>
                            <option value="2" {{ $month == '02' ? 'selected' : '' }}>February</option>
                            <option value="3" {{ $month == '03' ? 'selected' : '' }}>March</option>
                        </select>
                    </div> <!-- /.form-group -->
                </div> <!-- /.col -->
            </form>
        </div>
        
         {{-- Total count Box start --}}
        <div class="row">
            <div class="col-lg-3 col-md-6 pr-lg-0">
                <div class="card stretch-card mb-3 zoom">
                    <div class="card-body analytic d-flex flex-wrap justify-content-between p-3">
                        <div class="row mb-1 w-115">
                            <div class="head">
                                <heading>Calls</heading>
                            </div>
                            <div class="col-4">
                                <img class="pull-right d-img" src="{{ asset('/global/assets/images/dashboard/calls.png') }}"
                                    alt="calls">
                            </div>
                        </div>
                        <div class="text-left">
                            <h4 class="w-100 text-black-50">Total</h4>
                            <h3 class="text-success font-weight-bold d-flex align-items-center" id="TotalCalls">0</h3>
                        </div>
                    </div>
                </div>
            </div><!--./col-->

            <div class="col-lg-3 col-md-6 pr-lg-0">
                <div class="card stretch-card mb-3 zoom">
                    <div class="card-body analytic d-flex flex-wrap justify-content-between p-3">
                        <div class="row mb-1 w-115">
                            <div class="head">
                                <heading>Tickets</heading>
                            </div>
                            <div class="col-4">
                                <img class="pull-right" src="{{ asset('/global/assets/images/dashboard/ticket-closed.png') }}"
                                    alt="ticket">
                            </div>
                        </div>
                        <!-- Total Tickets Form -->
                        <form action="{{ route('callList.index') }}" method="POST" id="totalTicketsForm"
                            style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="date_range">
                            <input type="hidden" name="iTicketYearId" id="iTicketYearId">
                            <input type="hidden" name="iTicketMonth" id="iTicketMonth">
                            <div class="text-left">
                                <h4 class="w-100 text-black-50">Total</h4>
                                <a href="#" class="text-decoration-none"
                                    onclick="document.getElementById('totalTicketsForm').submit();">
                                    <h3 class="font-weight-bold d-flex align-items-center" id="TotalTickets">0</h3>
                                </a>
                            </div>
                        </form>
                        <!-- Open Tickets Form -->
                        <form action="{{ route('callList.index') }}" method="POST" id="openTicketsForm"
                            style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="open_ticket_daterange">
                            <input type="hidden" name="level" value="0">
                            <input type="hidden" name="iTicketYearId" id="iTicketOpenYearId">
                            <input type="hidden" name="iTicketMonth" id="iTicketOpenMonth">
                            <div class="text-right">
                                <h4 class="w-100 text-black-50 pull-right">Open</h4>
                                <a href="#" onclick="document.getElementById('openTicketsForm').submit();">
                                    <h3 class="pull-right font-weight-bold d-flex align-items-center" id="OpenTickets">0
                                    </h3>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
<?php if (in_array('50', \Session::get('menuList')) || in_array('51', \Session::get('menuList')) || in_array('52', \Session::get('menuList'))) { ?>
            <div class="col-lg-3 col-md-6 pr-lg-0">
                <div class="card stretch-card mb-3 zoom">
                    <div class="card-body analytic d-flex flex-wrap justify-content-between p-3">
                        <div class="row mb-1 w-115">
                            <div class="head">
                                <heading>RMA</heading>
                            </div>
                            <div class="col-4">
                                <img class="pull-right" src="{{ asset('/global/assets/images/dashboard/products.png') }}"
                                    alt="products">
                            </div>
                        </div>

                        <div class="text-left">
                            <h4 class="w-100 text-black-50">Total</h4>
                            <a href="{{ route('Wl_RMA.index') }}">
                                <h3 class="font-weight-bold d-flex align-items-center" id="Total_rma">0</h3>
                            </a>
                        </div>
                        <div class="text-right">
                            <h4 class="w-100 text-black-50 pull-right">Open</h4>
                            <a href="{{ route('Wl_RMA.index') }}">
                                <h3 class="pull-right font-weight-bold d-flex align-items-center" id="Total_rma_open">0</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div><!--./col-->
<?php }?>
            <div class="col-lg-3 col-md-6">
                <div class="card stretch-card mb-3 zoom">
                    <div class="card-body analytic d-flex flex-wrap justify-content-between p-3">
                        <div class="row mb-1 w-115">
                            <div class="head">
                                <heading>Company</heading>
                            </div>
                            <div class="col-4">
                                <img class="pull-right" src="{{ asset('/global/assets/images/dashboard/company.png') }}" alt="company">
                            </div>
                        </div>
                        <!-- Total Companies Form -->
                        <form action="{{ route('companysummary.index') }}" method="POST" id="totalAllCompanyForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="Total_Company_daterange">
                            <input type="hidden" name="status" value="TotalAll">
                            <input type="hidden" name="iCompnayYearID" id="Total_CompnayYearID_daterange">
                            <div class="text-left">
                                <h4 class="w-100 text-black-50">Total</h4>
                                <a href="javascript:void(0);" onclick="document.getElementById('totalAllCompanyForm').submit();">
                                    <h3 class="font-weight-bold d-flex align-items-center" id="totalCustCompany">0</h3>
                                </a>
                            </div>
                        </form>
                        <form action="{{ route('companysummary.index') }}" method="POST" id="totalTDCompanyForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="Total_Company_daterange">
                            <input type="hidden" name="New_Company_Month" id="New_TD_Company_Month">
                            <input type="hidden" name="iCompnayYearID" id="Total_TDiCompnayYearID_daterange">
                            <input type="hidden" name="status" value="TotalTD">
                            <div class="text-center">
                              <h4 class="w-100 text-black-50">TD</h4>
                              <a href="javascript:void(0);" onclick="document.getElementById('totalTDCompanyForm').submit();">
                                <h3 class="font-weight-bold d-flex align-items-center" id="TotalTDCompany">0</h3> 
                              </a>
                            </div>
                        </form>
                        <!-- New Companies Form -->
                        <form action="{{ route('companysummary.index') }}" method="POST" id="newCompanyForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="Total_Company_daterange">
                            <input type="hidden" name="iCompnayYearID" id="Total_iCompnayYearID_daterange">
                            
                            <input type="hidden" name="New_Company_Month" id="New_Company_Month">
                            <input type="hidden" name="status" value="new">
                            <div class="text-right">
                                <h4 class="w-100 text-black-50 pull-right">New</h4>
                                <a href="javascript:void(0);" onclick="document.getElementById('newCompanyForm').submit();">
                                    <h3 class="pull-right font-weight-bold d-flex align-items-center" id="NewCompany">0</h3>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 pr-lg-0">
                <div class="card stretch-card grid-margin zoom">
                    <div class="card-body analytic d-flex flex-wrap justify-content-between p-3">
                        <div class="row mb-1 w-115">
                            <div class="head">
                                <heading>Customer</heading>
                            </div>
                            <div class="col-4">
                                <img class="pull-right" src="{{ asset('/global/assets/images/dashboard/users.png') }}" alt="Customers">
                            </div>
                        </div>
                        <!-- Total Customers Form -->
                        <form action="{{ route('customersummary.index') }}" method="POST" id="totalAllCustomerForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="Total_Customer_daterange">
                            <input type="hidden" name="isDashboardList" value="1">
                            <input type="hidden" name="status" value="TotalAll">
                            <input type="hidden" name="iCustomerYearID" id="Total_CustomerYearID_daterange">
                            <div class="text-left">
                                <h4 class="w-100 text-black-50">Total</h4>
                                <a href="javascript:void(0);" onclick="document.getElementById('totalAllCustomerForm').submit();">
                                    <h3 class="font-weight-bold d-flex align-items-center" id="TotalAllCustomer">0</h3>
                                </a>
                            </div>
                        </form>
                        
                        <form action="{{ route('customersummary.index') }}" method="POST" id="totalCustomerForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="Total_Customer_daterange">
                            <input type="hidden" name="New_Customer_Month" id="New_CustomerTD_Month">
                            <input type="hidden" name="iCustomerYearID" id="Total_TDiCustomerYearID_daterange">
                            <input type="hidden" name="isDashboardList" value="1">
                            <input type="hidden" name="status" value="TotalTD">
                            <div class="text-center">
                              <h4 class="w-100 text-black-50">TD</h4>
                              <a href="javascript:void(0);" onclick="document.getElementById('totalCustomerForm').submit();">
                                <h3 class="font-weight-bold d-flex align-items-center" id="TotalTDCustomer">0</h3> 
                              </a>
                            </div>
                        </form>
                        <!-- New Customers Form -->
                        <form action="{{ route('customersummary.index') }}" method="POST" id="newCustomerForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="Total_Customer_daterange">
                            <input type="hidden" name="isDashboardList" value="1">
                            <input type="hidden" name="New_Customer_Month" id="New_Customer_Month">
                            <input type="hidden" name="iCustomerYearID" id="Total_iCustomerYearID_daterange">
                            <input type="hidden" name="status" value="new">
                            <div class="text-right">
                                <h4 class="w-100 text-black-50 pull-right">New</h4>
                                <a href="javascript:void(0);" onclick="document.getElementById('newCustomerForm').submit();">
                                    <h3 class="pull-right font-weight-bold d-flex align-items-center" id="NewCustomer">0</h3>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        {{-- Total count Box End --}}

        <!--<div class="row">--><!--</div>-->
        <!--./row-->
        <!-- year & month selector -->

        <!-- /. year & month selector -->
        <!-- second row starts here -->
        <div class="row">
            <!--chart start-->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card zoom">
                     <div class="card">
                        <div class="card-body">
                            <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">Tickets Analytics
                                <br><small class="text-black-50 text-lowercase">Data displayed in numbers</small>
                            </h4>
                            <div id="barChart-graph-container"> <canvas id="barChart" style="height: 230px;"></canvas></div>
                        </div>
                    </div>
                </div>
               
            </div>
            <!--/.chart end-->
            <!--line chart start-->
            <div class="col-md-6 stretch-card grid-margin">
                <div class="card zoom">
                     <div class="card">
                        <div class="card-body">
                            <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">Resolution Analytics
                                <br><small class="text-black-50 text-lowercase">data displayed in % value</small>
                            </h4>
                            <!-- <canvas id="lineChart"></canvas> -->
                            <div id="lineChart-graph-container"><canvas id="lineChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.line chart end-->
        </div>
        <!--./row-->

        <!-- third row starts here -->
        <div class="row">
            <!--call list start-->
            <!--chart start-->
            <div class="col-md-6 stretch-card grid-margin">
                 <div class="card zoom">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">System Tickets Analytics
                                <br><small class="text-black-50 text-lowercase">Data displayed in numbers</small>
                            </h4>
                            <div id="graph-container"><canvas id="doughnutChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.chart end-->

            <!--line chart start-->
            <div class="col-md-6 stretch-card grid-margin">
                <div class="card zoom">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">Resolution Analytics
                                <br><small class="text-black-50 text-lowercase">data displayed in numbers</small>
                                </h4>
                                <!-- <canvas id="lineChart"></canvas> -->
                            <div id="lineAnalyticsChart-graph-container"><canvas id="lineChartAnalytics"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.line chart end-->
            <?php if (in_array('50', \Session::get('menuList')) || in_array('51', \Session::get('menuList')) || in_array('52', \Session::get('menuList'))) { ?>
            <!--line chart start-->
            <div class="col-md-6 stretch-card grid-margin">
                <div class="card zoom">
                    <div class="card-body">
                        <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">RMA System Analytics</h4>
                        <div id="RMAlineChart"><canvas id="doughnutChart2"></canvas></div>
                    </div>
                </div>
            </div>
            <!--/.line chart end-->

            <!-- fourth row starts here -->

            <!--pie chart start-->
            <div class="col-md-6 stretch-card grid-margin">
                <div class="card zoom">
                    <div class="card-body">
                        <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">
                            RMA Analytics <br>
                            <small class="text-black-50 text-lowercase">Data displayed in numbers</small>
                        </h4>
                        <div id="lineChart-graph-container-rma"><canvas id="lineChartrma"></canvas></div>
                    </div>
                </div>
            </div>
            <!--/.pie chart end-->
<?php } ?>
        </div>
        <!--./row-->
        <?php }else { ?>
        <div class="page-header">
            <h3>Welcome to Dashboard</h3>
        </div>
        <?php } ?>
    </div>
    <!-- content-wrapper ends -->

    <!-- partial -->
    <!--</div>-->
    <!-- main-panel ends -->


@endsection

@section('script')
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        function getdata() {
            var yearID = $("#fYear").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('profile.getCallChart') }}",
                data: {
                    yearId: yearID
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#barChart").remove();
                    if (jQuery.isEmptyObject(JSON.parse(response)) == false) {
                        var e = $('<canvas id="barChart" style="height: 230px;"></canvas>');
                        $('#barChart-graph-container').append(e);
                        var options = {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            },
                            legend: {
                                display: false
                            },
                            elements: {
                                point: {
                                    radius: 0
                                }
                            },
                            plugins: {
                                colors: {
                                    forceOverride: true,
                                    enabled: true
                                }
                            }
                        };
                        var barChartCanvas = $("#barChart").get(0).getContext("2d");
                        var barChart = new Chart(barChartCanvas, {
                            type: 'bar',
                            data: {
                                labels: [],
                                datasets: [{
                                    label: '#Tickets',
                                    data: [],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)',
                                        'rgba(255, 159, 64, 0.2)',
                                        'rgba(21, 42, 237, 0.2)',
                                        'rgba(224, 1, 200, 0.2)',
                                        'rgba(13, 191, 189, 0.2)',
                                        'rgba(191, 71, 13, 0.2)',
                                        'rgba(63, 191, 13, 0.2)',
                                        'rgba(157, 22, 22, 0.2)',

                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)',
                                        'rgba(21, 42, 237, 1)',
                                        'rgba(224, 1, 200, 1)',
                                        'rgba(13, 191, 189, 1)',
                                        'rgba(191, 71, 13, 1)',
                                        'rgba(63, 191, 13, 1)',
                                        'rgba(157, 22, 22, 1)',

                                    ],
                                    borderWidth: 1,
                                    fill: false
                                }]
                            },
                            options: options
                        });
                        $.each(JSON.parse(response), function(key, value) {
                            barChart.data.labels.push(key);
                            barChart.data.datasets[0].data.push(value);
                        });
                        barChart.update();
                    } else {
                        var e = $('<div id="barChart"><strong>No Data Found!</strong></div>');
                        $('#barChart-graph-container').append(e);
                    }
                }
            });
        }

        function getLineChart() {
            var yearID = $("#fYear").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('profile.getLineChart') }}",
                data: {
                    yearId: yearID
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // $.each(JSON.parse(response), function(key, value) {
                    //     myLineChart.data.datasets[0].data.push(value);
                    // });
                    // myLineChart.update();
                    $("#lineChart").remove();
                    if (jQuery.isEmptyObject(JSON.parse(response)) == false) {
                        var e = $('<canvas id="lineChart" style="height: 230px;"></canvas>');
                        $('#lineChart-graph-container').append(e);
                        var ctxL = document.getElementById("lineChart").getContext('2d');
                        var myLineChart = new Chart(ctxL, {
                            type: 'line',
                            data: {
                                labels: [],
                                datasets: [{
                                        label: "Resolution at L1",
                                        data: [],
                                        backgroundColor: [
                                            'rgba(105, 0, 132, .2)',
                                        ],
                                        borderColor: [
                                            'rgba(200, 99, 132, .7)',
                                        ],
                                        borderWidth: 2
                                    },
                                    {
                                        label: "Resolution in < 24 hrs",
                                        data: [],
                                        backgroundColor: [
                                            'rgba(0, 137, 132, .2)',
                                        ],
                                        borderColor: [
                                            'rgba(0, 10, 130, .7)',
                                        ],
                                        borderWidth: 2
                                    }
                                ]
                            },
                            options: {
                                tooltips: {
                                    callbacks: {
                                        label: function(tooltipItem, data) {
                                            //alert("Tooltip : " + data);
                                            return data.datasets[tooltipItem.datasetIndex].label +
                                                " : " + Number(tooltipItem.yLabel).toFixed(0)
                                                .replace(
                                                    /./g,
                                                    function(c, i, a) {
                                                        return i > 0 && c !== "." && (a.length -
                                                                i) %
                                                            3 === 0 ? "," + c : c;
                                                    }) + "%";
                                        }
                                    }
                                },
                                responsive: true
                            }
                        });
                        $.each(JSON.parse(response), function(key, value) {
                            var keys = Object.keys(value);
                            if (key == "levelOne") {
                                $.each(value, function(key, val) {
                                    myLineChart.data.labels.push(val.key);
                                    myLineChart.data.datasets[0].data.push(val.Count);

                                });
                            }
                            if (key == "samedaysolution") {
                                $.each(value, function(key, val) {
                                    myLineChart.data.datasets[1].data.push(val);
                                });

                            }
                        });
                        myLineChart.update();
                    } else {
                        var e = $('<div id="lineChart"><strong>No Data Found!</strong></div>');
                        $('#lineChart-graph-container').append(e);
                    }
                }
            });
        }

        // function getrmaLineChart()
        // {
        //     var yearID = $("#fYear").val();

        //     $.ajax({
        //         type: 'POST',
        //         url: "{{ route('profile.get_rmaLineChart') }}",
        //         data: {
        //             yearId: yearID
        //         },
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             console.log(response);
        //             // $.each(JSON.parse(response), function(key, value) {
        //             //     myLineChart.data.datasets[0].data.push(value);
        //             // });
        //             // myLineChart.update();
        //             $("#lineChartrma").remove();
        //             if (jQuery.isEmptyObject(JSON.parse(response)) == false) {
        //                 var e = $('<canvas id="lineChartrma" style="height: 230px;"></canvas>');
        //                 $('#lineChart-graph-container-rma').append(e);
        //                 var ctxL = document.getElementById("lineChartrma").getContext('2d');
        //                 var myLineChartrma = new Chart(ctxL, {
        //                     type: 'line',
        //                     data: {
        //                         labels: [],
        //                         datasets: [{
        //                                 label: "Registered RMA",
        //                                 data: [],
        //                                 backgroundColor: [
        //                                     'rgba(105, 0, 132, .2)',
        //                                 ],
        //                                 borderColor: [
        //                                     'rgba(200, 99, 132, .7)',
        //                                 ],
        //                                 borderWidth: 2
        //                             },
        //                             {
        //                                 label: "Closed RMA",
        //                                 data: [],
        //                                 backgroundColor: [
        //                                     'rgba(0, 137, 132, .2)',
        //                                 ],
        //                                 borderColor: [
        //                                     'rgba(0, 10, 130, .7)',
        //                                 ],
        //                                 borderWidth: 2
        //                             }
        //                         ]
        //                     },
        //                     options: {
        //                         tooltips: {
        //                             callbacks: {
        //                                 label: function(tooltipItem, data) {
        //                                     //alert("Tooltip : " + data);
        //                                     return data.datasets[tooltipItem.datasetIndex].label +
        //                                         " : " + Number(tooltipItem.yLabel).toFixed(0)
        //                                         .replace(
        //                                             /./g,
        //                                             function(c, i, a) {
        //                                                 return i > 0 && c !== "." && (a.length -
        //                                                         i) %
        //                                                     3 === 0 ? "," + c : c;
        //                                             }) + "%";
        //                                 }
        //                             }
        //                         },
        //                         responsive: true
        //                     }
        //                 });
        //                 console.log((response));
        //                 $.each(JSON.parse(response), function(key, value) {
        //                     var keys = Object.keys(value);
        //                     //console.log(keys);
        //                     if (key == "levelOne") {
        //                         $.each(value, function(key, val) {
        //                             myLineChartrma.data.labels.push(val.key);
        //                             myLineChartrma.data.datasets[0].data.push(val.Count);

        //                         });
        //                     }
        //                     if (key == "samedaysolution") {
        //                         $.each(value, function(key, val) {
        //                             console.log(val);
        //                             myLineChartrma.data.datasets[1].data.push(val);
        //                         });

        //                     }
        //                 });
        //                 myLineChartrma.update();
        //             } else {
        //                 var e = $('<div id="lineChartrma"><strong>No Data Found!</strong></div>');
        //                 $('#lineChart-graph-container-rma').append(e);
        //             }
        //         }
        //     });
        // }
        
         function getrmaLineChart() {
            var yearID = $("#fYear").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('profile.get_rmaLineChart') }}",
                data: {
                    yearId: yearID
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#lineChartrma").remove();
                    if (jQuery.isEmptyObject(response) == false) {
                        var e = $('<canvas id="lineChartrma" style="height: 230px;"></canvas>');
                        $('#lineChart-graph-container-rma').append(e);
                        var ctxL = document.getElementById("lineChartrma").getContext('2d');
                        var myLineChartrma = new Chart(ctxL, {
                            type: 'line',
                            data: {
                                labels: [],
                                datasets: [{
                                        label: "Registered RMA",
                                        data: [],
                                        backgroundColor: ['rgba(105, 0, 132, .2)'],
                                        borderColor: ['rgba(200, 99, 132, .7)'],
                                        borderWidth: 2
                                    },
                                    {
                                        label: "Closed RMA",
                                        data: [],
                                        backgroundColor: ['rgba(0, 137, 132, .2)'],
                                        borderColor: ['rgba(0, 10, 130, .7)'],
                                        borderWidth: 2
                                    }
                                ]
                            },
                            options: {
                                tooltips: {
                                    callbacks: {
                                        label: function(tooltipItem, data) {
                                            return data.datasets[tooltipItem.datasetIndex].label +
                                                " : " + tooltipItem.yLabel;
                                        }
                                    }
                                },
                                responsive: true
                            }
                        });

                        $.each(response, function(key, value) {
                            if (key == "levelOne") {
                                $.each(value, function(i, val) {
                                    // Ensure months are displayed even if the count is 0
                                    myLineChartrma.data.labels.push(val.key);
                                    myLineChartrma.data.datasets[0].data.push(val
                                    .Count); // Registered RMA count
                                });
                            }
                            if (key == "samedaysolution") {
                                $.each(value, function(i, val) {
                                    myLineChartrma.data.datasets[1].data.push(
                                    val); 
                                });
                            }
                        });

                        myLineChartrma.update();
                    } else {
                        var e = $('<div id="lineChartrma"><strong>No Data Found!</strong></div>');
                        $('#lineChart-graph-container-rma').append(e);
                    }
                }
            });
        }

        function getPiaChart() {
            var yearID = $("#fYear").val();
            var fMonth = $("#fMonth").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('profile.getPiaChart') }}",
                data: {
                    yearId: yearID,
                    fMonth: fMonth
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#doughnutChart").remove();
                    if (jQuery.isEmptyObject(JSON.parse(response)) == false) {
                        var e = $('<canvas id="doughnutChart"></canvas>');
                        $('#graph-container').append(e);
                        var ctxD = document.getElementById("doughnutChart").getContext('2d');
                        var piaChart = new Chart(ctxD, {
                            type: 'doughnut',
                            data: {
                                //labels: ["Advanced", "Apollo", "Argus", "FFE", "Esprit"],
                                datasets: [{
                                    data: [],
                                    backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C",
                                        "#949FB1",
                                        "#4D5360",
                                        "#ff355e", "#fd5b78", "#ff6037", "#ff9966",
                                        "#ff9933",
                                        "#ffcc33", "#ffff66", "#ccff00", "#66ff66",
                                        "#aaf0d1",
                                        "#16d0cb", "#50bfe6", "#9c27b0", "#ee34d2",
                                        "#ff00cc"
                                    ],
                                    hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870",
                                        "#A8B3C5", "#616774",
                                        "#ff355e", "#fd5b78", "#ff6037", "#ff9966",
                                        "#ff9933",
                                        "#ffcc33", "#ffff66", "#ccff00", "#66ff66",
                                        "#aaf0d1",
                                        "#16d0cb", "#50bfe6", "#9c27b0", "#ee34d2",
                                        "#ff00cc"
                                    ]
                                }]
                            },
                            options: {
                                responsive: true
                            }
                        });
                        $.each(JSON.parse(response), function(key, value) {
                            piaChart.data.labels.push(value.strSystem);
                            piaChart.data.datasets[0].data.push(value.issueCount);
                        });
                        piaChart.update();
                    } else {
                        var e = $('<div id="doughnutChart"><strong>No Data Found!</strong></div>');
                        $('#graph-container').append(e);
                    }
                }
            });
        }

        function getRMAPiaChart() {
            var yearID = $("#fYear").val();
            var fMonth = $("#fMonth").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('profile.get_rmaPiaChart') }}",
                data: {
                    yearId: yearID,
                    fMonth: fMonth
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#doughnutChart2").remove();
                    if (jQuery.isEmptyObject(JSON.parse(response)) == false) {
                        var e = $('<canvas id="doughnutChart2"></canvas>');
                        $('#RMAlineChart').append(e);
                        
                        
                        
                        
                        var ctxD = document.getElementById("doughnutChart2").getContext('2d');
                        var piaChartRMA = new Chart(ctxD, {
                            type: 'pie',
                            data: {
                                //labels: ["Advanced", "Apollo", "Argus", "FFE", "Esprit"],
                                datasets: [{
                                    data: [],
                                    backgroundColor: ["#00b8bf", "#59cefe", "#436cb1", "#f75789", "#ff925e",
                                        "#00b8bf", "#59cefe", "#436cb1", "#f75789", "#ff925e",
                                        "#00b8bf", "#59cefe", "#436cb1", "#f75789", "#ff925e"
                                    ],
                                    hoverBackgroundColor: ["#0fd5dd", "#92dbf9", "#5988d7", "#f8779f", "#fdb28f",
                                        "#0fd5dd", "#92dbf9", "#5988d7", "#f8779f", "#fdb28f",
                                        "#0fd5dd", "#92dbf9", "#5988d7", "#f8779f", "#fdb28f"
                                    ]
                                }]
                            },
                            options: {
                                responsive: true
                            }
                        });
                        $.each(JSON.parse(response), function(key, value) {
                            piaChartRMA.data.labels.push(value.SystemName);
                            piaChartRMA.data.datasets[0].data.push(value.Count);
                        });
                        piaChartRMA.update();
                    } else {
                        var e = $('<div id="doughnutChart2"><strong>No Data Found!</strong></div>');
                        $('#RMAlineChart').append(e);
                    }
                }
            });
        }

        function getDashboardCount() {
            var yearID = $("#fYear").val();
            var fMonth = $("#fMonth").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('profile.getDashboardCount') }}",
                data: {
                    yearId: yearID,
                    fMonth: fMonth
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    $("#TotalCalls").html(obj.call);
                    $("#TotalTickets").html(obj.ticket);
                    $("#OpenTickets").html(obj.oTicket);
                    $("#TotalTDCompany").html(obj.custTDCompany);
                    $("#totalCustCompany").html(obj.totalCustCompany);
                    $("#NewCompany").html(obj.NewCompany);
                    $("#TotalTDCustomer").html(obj.TDCustomer);
                    $("#TotalAllCustomer").html(obj.Totalcustomer);
                    $("#NewCustomer").html(obj.NewCustomer);
                    $("#Total_rma").html(obj.Total_Rma);
                    $("#Total_rma_open").html(obj.TotalRma_Open);
                }
            });
        }

        $(document).ready(function() {
            getdata();
            getPiaChart();
            getRMAPiaChart();
            getLineChart();
            getrmaLineChart();
            getDashboardCount();
            //getLineChartSameDaySolution();
            getAnalyticsLineChart();
            var year = $("#fYear option:selected").text();
            var yearID = $("#fYear").val();
            var month = $('#fMonth').val();
            if (month != "") {
                var myArray = year.split("-");
                var CurrYear = "";
                if (month > 3) {
                    CurrYear = myArray[0];
                } else {
                    CurrYear = myArray[1];
                }
                var daterange = getselectedDateRange(CurrYear, month);
                $("#date_range").val(daterange);
                $("#open_ticket_daterange").val(daterange);
                $("#Total_Company_daterange").val(daterange);
                $("#Total_CompnayYearID_daterange").val(yearID);
                $("#Total_TDiCompnayYearID_daterange").val(yearID);
                $("#Total_iCompnayYearID_daterange").val(yearID);
                $("#New_Company_Month").val(month);
                $("#New_TD_Company_Month").val(month);
                $("#New_CustomerTD_Month").val(month);
                $("#Total_Customer_daterange").val(daterange);
                $("#New_Customer_Month").val(month);
                
                $("#Total_CustomerYearID_daterange").val(yearID);
                $("#Total_TDiCustomerYearID_daterange").val(yearID);
                $("#Total_iCustomerYearID_daterange").val(yearID);
                $("#iTicketYearId").val(yearID);
                $("#iTicketMonth").val(month);
                $("#iTicketOpenYearId").val(yearID);
                $("#iTicketOpenMonth").val(month);
            } else {
                const currentDate = new Date();
                const currentMonth = currentDate.getMonth();
                const monthNumber = currentMonth + 1;
                $("#date_range").val('');
                $("#open_ticket_daterange").val('');
                $("#Total_Company_daterange").val('');
                $("#Total_CompnayYearID_daterange").val(yearID);
                $("#Total_TDiCompnayYearID_daterange").val(yearID);
                $("#Total_iCompnayYearID_daterange").val(yearID);
                $("#New_Company_Month").val('');
                $("#New_CustomerTD_Month").val(monthNumber);
                $("#New_TD_Company_Month").val(monthNumber);
                $("#Total_Customer_daterange").val('');
                $("#New_Customer_Month").val('');
                $("#Total_CustomerYearID_daterange").val(yearID);
                $("#Total_TDiCustomerYearID_daterange").val(yearID);
                $("#Total_iCustomerYearID_daterange").val(yearID);
                $("#iTicketYearId").val(yearID);
                $("#iTicketMonth").val(month);
                $("#iTicketOpenYearId").val(yearID);
                $("#iTicketOpenMonth").val(month);
            }
        });

        $("#fMonth").change(function() {
            getdata();
            getPiaChart();
            getRMAPiaChart();
            getLineChart();
            getrmaLineChart();
            getDashboardCount();
            getAnalyticsLineChart();

            var year = $("#fYear option:selected").text();
            var yearID = $("#fYear").val();
            var month = $(this).val();
            if (month != "") {
                var myArray = year.split("-");
                var CurrYear = "";
                if (month > 3) {
                    CurrYear = myArray[0];
                } else {
                    CurrYear = myArray[1];
                }
                var daterange = getselectedDateRange(CurrYear, month);
                console.log(daterange);
                $("#date_range").val(daterange);
                $("#open_ticket_daterange").val(daterange);
                $("#Total_Company_daterange").val(daterange);
                $("#New_Company_Month").val(month);
                $("#New_TD_Company_Month").val(month);
                $("#New_CustomerTD_Month").val(month);
                $("#Total_Customer_daterange").val(daterange);
                $("#New_Customer_Month").val(month);
                
                $("#Total_CompnayYearID_daterange").val(yearID);
                $("#Total_TDiCompnayYearID_daterange").val(yearID);
                $("#Total_iCompnayYearID_daterange").val(yearID);
                
                $("#Total_CustomerYearID_daterange").val(yearID);
                $("#Total_TDiCustomerYearID_daterange").val(yearID);
                $("#Total_iCustomerYearID_daterange").val(yearID);
                $("#iTicketYearId").val(yearID);
                $("#iTicketMonth").val(month);
                $("#iTicketOpenYearId").val(yearID);
                $("#iTicketOpenMonth").val(month);
            } else {
                const currentDate = new Date();
                const currentMonth = currentDate.getMonth();
                const monthNumber = currentMonth + 1;

                $("#date_range").val('');
                $("#open_ticket_daterange").val('');
                $("#Total_Company_daterange").val('');
                $("#New_Company_Month").val('');
                $("#New_TD_Company_Month").val(monthNumber);
                $("#New_CustomerTD_Month").val(monthNumber);
                $("#Total_Customer_daterange").val('');
                $("#New_Customer_Month").val('');
                
                $("#Total_CompnayYearID_daterange").val(yearID);
                $("#Total_TDiCompnayYearID_daterange").val(yearID);
                $("#Total_iCompnayYearID_daterange").val(yearID);
                
                $("#Total_CustomerYearID_daterange").val(yearID);
                $("#Total_TDiCustomerYearID_daterange").val(yearID);
                $("#Total_iCustomerYearID_daterange").val(yearID);
                $("#iTicketYearId").val(yearID);
                $("#iTicketMonth").val(month);
                $("#iTicketOpenYearId").val(yearID);
                $("#iTicketOpenMonth").val(month);
            }
        });
        $("#fYear").change(function() {
            getdata();
            getLineChart();
            getrmaLineChart();
            //getLineChartSameDaySolution();
            getPiaChart();
            getRMAPiaChart();
            getDashboardCount();
            getAnalyticsLineChart();

            var year = $("#fYear option:selected").text();
            var month = $('#fMonth').val();
            
            var yearID = $("#fYear").val();
            if (month != "") {
                var myArray = year.split("-");
                var CurrYear = "";
                if (month > 3) {
                    CurrYear = myArray[0];
                } else {
                    CurrYear = myArray[1];
                }
                var daterange = getselectedDateRange(CurrYear, month);
                $("#date_range").val(daterange);
                $("#open_ticket_daterange").val(daterange);
                $("#Total_Company_daterange").val(daterange);
                $("#New_Company_Month").val(month);
                $("#New_TD_Company_Month").val(month);
                $("#New_CustomerTD_Month").val(month);
                $("#Total_Customer_daterange").val(daterange);
                $("#New_Customer_Month").val(month);
                
                $("#Total_CompnayYearID_daterange").val(yearID);
                $("#Total_TDiCompnayYearID_daterange").val(yearID);
                $("#Total_iCompnayYearID_daterange").val(yearID);
                
                $("#Total_CustomerYearID_daterange").val(yearID);
                $("#Total_TDiCustomerYearID_daterange").val(yearID);
                $("#Total_iCustomerYearID_daterange").val(yearID);
                $("#iTicketYearId").val(yearID);
                $("#iTicketMonth").val(month);
                $("#iTicketOpenYearId").val(yearID);
                $("#iTicketOpenMonth").val(month);
            } else {
                const currentDate = new Date();
                const currentMonth = currentDate.getMonth();
                const monthNumber = currentMonth + 1;

                $("#date_range").val('');
                $("#open_ticket_daterange").val('');
                $("#Total_Company_daterange").val('');
                $("#New_Company_Month").val('');
                $("#New_TD_Company_Month").val(monthNumber);
                $("#New_CustomerTD_Month").val(monthNumber);
                $("#Total_Customer_daterange").val('');
                $("#New_Customer_Month").val('');
                
                $("#Total_CompnayYearID_daterange").val(yearID);
                $("#Total_TDiCompnayYearID_daterange").val(yearID);
                $("#Total_iCompnayYearID_daterange").val(yearID);
                
                $("#Total_CustomerYearID_daterange").val(yearID);
                $("#Total_TDiCustomerYearID_daterange").val(yearID);
                $("#Total_iCustomerYearID_daterange").val(yearID);
                $("#iTicketYearId").val(yearID);
                $("#iTicketMonth").val(month);
                $("#iTicketOpenYearId").val(yearID);
                $("#iTicketOpenMonth").val(month);
            }

        });

        function getselectedDateRange(CurrYear, month) {
            var to_Date = LastDayOfMonth(CurrYear, month);
            var td = to_Date.getDate();
            var tm = to_Date.getMonth();
            tm += 1;
            var ty = to_Date.getFullYear();
            var toDate = tm + "/" + td + "/" + ty;

            var from_Date = new Date(CurrYear, (month - 1), 1, 0);
            var fd = from_Date.getDate();
            var fm = from_Date.getMonth();
            fm += 1;
            var fy = from_Date.getFullYear();
            //var fromDate = fm + "/" + fd + "/" + fy;
            var fromDate = 04 + "/" + 01 + "/" + 2022;

            return fromDate + ' - ' + toDate;

        }

        function LastDayOfMonth(Year, Month) {
            return new Date((new Date(Year, Month, 1)) - 1);
        }


        function getAnalyticsLineChart() {
            var yearID = $("#fYear").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('profile.getAnalyticsLineChart') }}",
                data: {
                    yearId: yearID
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // $.each(JSON.parse(response), function(key, value) {
                    //     myLineChart.data.datasets[0].data.push(value);
                    // });
                    // myLineChart.update();
                    $("#lineChartAnalytics").remove();
                    if (jQuery.isEmptyObject(JSON.parse(response)) == false) {
                        var e = $('<canvas id="lineChartAnalytics" style="height: 230px;"></canvas>');
                        $('#lineAnalyticsChart-graph-container').append(e);
                        var ctxL = document.getElementById("lineChartAnalytics").getContext('2d');
                        var myAnalyticsLineChart = new Chart(ctxL, {
                            type: 'line',
                            data: {
                                labels: [],
                                datasets: [{
                                        label: "Open by L1",
                                        data: [],
                                        backgroundColor: [
                                            'rgba(105, 0, 132, .2)',
                                        ],
                                        borderColor: [
                                            'rgba(200, 99, 132, .7)',
                                        ],
                                        borderWidth: 2
                                    },
                                    {
                                        label: "Resolved by L1",
                                        data: [],
                                        backgroundColor: [
                                            'rgba(97, 199, 102, .2)',
                                        ],
                                        borderColor: [
                                            'rgba(65, 186, 71, .7)',
                                        ],
                                        borderWidth: 2
                                    },
                                    {
                                        label: "Resolved by L2",
                                        data: [],
                                        backgroundColor: [
                                            'rgba(0, 137, 132, .2)',
                                        ],
                                        borderColor: [
                                            'rgba(0, 10, 130, .7)',
                                        ],
                                        borderWidth: 2
                                    }
                                ]
                            },
                            options: {
                                responsive: true
                            }
                        });
                        $.each(JSON.parse(response), function(key, value) {
                            var keys = Object.keys(value);
                            //console.log(keys);
                            if (key == "levelOne") {
                                $.each(value, function(key, val) {
                                    myAnalyticsLineChart.data.labels.push(val.key);
                                    myAnalyticsLineChart.data.datasets[0].data.push(val.Count);
                                });
                            }
                            if (key == "resolvedbyl1") {
                                $.each(value, function(key, val) {
                                    myAnalyticsLineChart.data.datasets[1].data.push(val);
                                });
                            }

                            if (key == "resolvedbyl2") {
                                $.each(value, function(key, val) {
                                    myAnalyticsLineChart.data.datasets[2].data.push(val);
                                });
                            }

                        });
                        myAnalyticsLineChart.update();
                    } else {
                        var e = $('<div id="lineChartAnalytics"><strong>No Data Found!</strong></div>');
                        $('#lineAnalyticsChart-graph-container').append(e);
                    }
                }
            });
        }
    </script>
@endsection
