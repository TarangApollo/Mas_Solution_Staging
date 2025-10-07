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

        <div class="row">
            <div class="col">
                <div class="card stretch-card">
                    <a href="#">
                        <div class="card-body d-flex flex-wrap justify-content-between p-3">
                            <h4 class="w-100 font-weight-semibold mb-4">Total Calls</h4>
                            <img class="pl-lg-3" src="../global/assets/images/dashboard/calls.png" alt="Calls">
                            <h3 class="pull-right text-success font-weight-bold d-flex align-items-center" id="TotalCalls">
                            </h3>
                        </div>
                    </a>
                </div>
            </div>
            <!--./col-->

            <div class="col pl-lg-0">
                <div class="card stretch-card">
                    <form action="{{ route('callList.index') }}" method="POST" style="display: inline-block;">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="daterange" id="date_range">
                        <a href="#">
                            <button type="submit" style="border: none;background: none;">
                                <div class="card-body d-flex flex-wrap justify-content-between p-3">
                                    <h4 class="w-100 font-weight-semibold mb-4">Total Tickets</h4>
                                    <img class="pl-lg-3" src="../global/assets/images/dashboard/ticket-closed.png"
                                        alt="Clock">
                                    <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"
                                        id="TotalTickets"></h3>
                                </div>
                            </button>
                        </a>
                    </form>
                </div>
            </div>
            <!--./col-->

            <div class="col pl-lg-0">
                <div class="card stretch-card grid-margin">
                    <form action="{{ route('callList.index') }}" method="POST" style="display: inline-block;">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="daterange" id="open_ticket_daterange">
                        <input type="hidden" name="level" value="0">
                        <a href="#">
                            <button type="submit" style="border: none;background: none;">
                                <div class="card-body d-flex flex-wrap justify-content-between p-3">
                                    <h4 class="w-100 font-weight-semibold mb-4">Open Tickets</h4>
                                    <img class="pl-lg-3" src="../global/assets/images/dashboard/ticket-open.png"
                                        alt="Clock">
                                    <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"
                                        id="OpenTickets"></h3>
                                </div>
                            </button>
                        </a>
                    </form>
                </div>
            </div>
            <!--./col-->

            <div class="col pl-lg-0">
                <div class="card stretch-card grid-margin">
                    <div class="card-body d-flex flex-wrap justify-content-between p-3">
                        <h4 class="w-100 font-weight-semibold mb-4">Companies</h4>
                        {{--  <h4 class="w-100 font-weight-semibold mb-4">Total Company</h4>  --}}
                        {{--  <img class="pl-lg-3" src="../global/assets/images/dashboard/company.png"
                            alt="Clock">  --}}
                            <form action="{{ route('companysummary.index') }}" method="POST" style="display: inline-block;">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="daterange" id="Total_Company_daterange">
                                <input type="hidden" name="status" value="Total">
                                <a href="#">
                                    <button type="submit" style="border: none;background: none;">
                                        <div class="text-left">
                                            <h4 class="w-100">Total</h4>
                                            <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"
                                                id="NewCompany"></h3>
                                        </div>
                                    </button>
                                </a>
                            </form>
                            <form action="{{ route('companysummary.index') }}" method="POST" style="display: inline-block;">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="daterange" id="Total_Company_daterange">
                                <input type="hidden" name="New_Company_Month" id="New_Company_Month">
                                <input type="hidden" name="status" value="new">
                                <a href="#">
                                    <button type="submit" style="border: none;background: none;">
                                        <div class="text-right">
                                            <h4 class="w-100  pull-right">New</h4>
                                            <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"
                                                id="TotalCompany">
                                            </h3>
                                        </div>
                                    </button>
                                </a>
                            </form>
                        </div>
                            
                </div>
            </div>
            <!--./col-->

            <div class="col pl-lg-0">
                <div class="card stretch-card grid-margin">
                    <div class="card-body d-flex flex-wrap justify-content-between p-3">
                        {{--  <h4 class="w-100 font-weight-semibold mb-4">Total Customer</h4>
                        <img class="pl-lg-3" src="../global/assets/images/dashboard/users.png"
                            alt="Clock">  --}}
                        <h4 class="w-100 font-weight-semibold mb-4">Customers</h4>
                            <form action="{{ route('customersummary.index') }}" method="POST" style="display: inline-block;">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="daterange" id="Total_Customer_daterange">
                                <input type="hidden" name="isDashboardList" value="1">
                                <input type="hidden" name="status" value="Total">
                                <a href="#">
                                    <button type="submit" style="border: none;background: none;">
                                        <div class="text-left">
                                            <h4 class="w-100">Total</h4>
                                            <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"
                                                id="TotalCustomer"></h3>
                                        </div>
                                    </button>
                                </a>
                            </form>                    
                            <form action="{{ route('customersummary.index') }}" method="POST" style="display: inline-block;">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="daterange" id="Total_Customer_daterange">
                                <input type="hidden" name="isDashboardList" value="1">
                                <input type="hidden" name="New_Customer_Month" id="New_Customer_Month">
                                <input type="hidden" name="status" value="new">
                                <a href="#">
                                    <button type="submit" style="border: none;background: none;">
                                    <div class="text-right">
                                        <h4 class="w-100  pull-right">New</h4>
                                        <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"
                                            id="NewCustomer">
                                        </h3>
                                    </div>
                                </button>
                            </a>
                        </form>
                    </div>
                            
                </div>
            </div>
            <!--./col-->

        </div>
        <!--./row-->
        <!-- year & month selector -->

        <!-- /. year & month selector -->
        <!-- second row starts here -->
        <div class="row">
            <!--chart start-->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">Tickets Analytics
                            <br><small class="text-black-50 text-lowercase">Data displayed in numbers</small>
                        </h4>
                        <div id="barChart-graph-container"> <canvas id="barChart" style="height: 230px;"></canvas></div>
                    </div>
                </div>
            </div>
            <!--/.chart end-->
            <!--line chart start-->
            <div class="col-md-6 stretch-card grid-margin">
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
            <!--/.line chart end-->
        </div>
        <!--./row-->

        <!-- third row starts here -->
        <div class="row">
            <!--call list start-->
            <!--chart start-->
            <div class="col-md-6 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">System Tickets Analytics
                            <br><small class="text-black-50 text-lowercase">Data displayed in numbers</small>
                        </h4>
                        <div id="graph-container"><canvas id="doughnutChart"></canvas></div>
                    </div>
                </div>
            </div>
            <!--/.chart end-->

            <!--line chart start-->
            <div class="col-md-6 stretch-card grid-margin">
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
            <!--/.line chart end-->

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
                    console.log(response);

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
                    console.log(response);
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
                        console.log((response));
                        $.each(JSON.parse(response), function(key, value) {
                            var keys = Object.keys(value);
                            //console.log(keys);
                            if (key == "levelOne") {
                                $.each(value, function(key, val) {
                                    myLineChart.data.labels.push(val.key);
                                    myLineChart.data.datasets[0].data.push(val.Count);

                                });
                            }
                            if (key == "samedaysolution") {
                                $.each(value, function(key, val) {
                                    console.log(val);
                                    myLineChart.data.datasets[1].data.push(val);
                                });

                            }

                            //console.log(value.levelOne);
                            // console.log(value.levelTwo);
                            // console.log(value.samedaysolution);
                            // myLineChart.data.labels.push(value.key);
                            // myLineChart.data.datasets[0].data.push(value.Count);
                        });
                        myLineChart.update();
                    } else {
                        var e = $('<div id="lineChart"><strong>No Data Found!</strong></div>');
                        $('#lineChart-graph-container').append(e);
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
                    console.log(response);
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
                    console.log(response);
                    var obj = JSON.parse(response);
                    $("#TotalCalls").html(obj.call);
                    $("#TotalTickets").html(obj.ticket);
                    $("#OpenTickets").html(obj.oTicket);
                    $("#TotalCompany").html(obj.custCompany);
                    $("#NewCompany").html(obj.NewCompany);
                    $("#TotalCustomer").html(obj.customer);
                    $("#NewCustomer").html(obj.NewCustomer);
                }
            });
        }

        $(document).ready(function() {
            getdata();
            getPiaChart();
            getLineChart();
            getDashboardCount();
            //getLineChartSameDaySolution();
            getAnalyticsLineChart();
            var year = $("#fYear option:selected").text();
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
                $("#New_Company_Month").val(month);
                $("#Total_Customer_daterange").val(daterange);
                $("#New_Customer_Month").val(month);
            } else {
                const currentDate = new Date();
                const currentMonth = currentDate.getMonth();
                const monthNumber = currentMonth + 1;
                $("#date_range").val('');
                $("#open_ticket_daterange").val('');
                $("#Total_Company_daterange").val('');
                $("#New_Company_Month").val(monthNumber);
                $("#Total_Customer_daterange").val('');
                $("#New_Customer_Month").val(monthNumber);
            }
        });

        $("#fMonth").change(function() {
            getdata();
            getPiaChart();
            getLineChart();
            getDashboardCount();
            getAnalyticsLineChart();

            var year = $("#fYear option:selected").text();
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
                $("#date_range").val(daterange);
                $("#open_ticket_daterange").val(daterange);
                $("#Total_Company_daterange").val(daterange);
                $("#New_Company_Month").val(month);
                $("#Total_Customer_daterange").val(daterange);
                $("#New_Customer_Month").val(month);
            } else {
                const currentDate = new Date();
                const currentMonth = currentDate.getMonth();
                const monthNumber = currentMonth + 1;
                
                $("#date_range").val('');
                $("#open_ticket_daterange").val('');
                $("#Total_Company_daterange").val('');
                $("#New_Company_Month").val(monthNumber);
                $("#Total_Customer_daterange").val('');
                $("#New_Customer_Month").val(monthNumber);
            }
        });
        $("#fYear").change(function() {
            getdata();
            getLineChart();
            //getLineChartSameDaySolution();
            getPiaChart();
            getDashboardCount();
            getAnalyticsLineChart();

            var year = $("#fYear option:selected").text();
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
                $("#New_Company_Month").val(month);
                $("#Total_Customer_daterange").val(daterange);
                $("#New_Customer_Month").val(month);
            } else {
                const currentDate = new Date();
                const currentMonth = currentDate.getMonth();
                const monthNumber = currentMonth + 1;
                
                $("#date_range").val('');
                $("#open_ticket_daterange").val('');
                $("#Total_Company_daterange").val('');
                $("#New_Company_Month").val(monthNumber);
                $("#Total_Customer_daterange").val('');
                $("#New_Customer_Month").val(monthNumber);
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
            var fromDate = fm + "/" + fd + "/" + fy;

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
                    console.log(response);
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
                        console.log((response));
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
                                    console.log(val);
                                    myAnalyticsLineChart.data.datasets[1].data.push(val);
                                });
                            }

                            if (key == "resolvedbyl2") {
                                $.each(value, function(key, val) {
                                    console.log(val);
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
