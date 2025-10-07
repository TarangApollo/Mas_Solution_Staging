@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="content-wrapper pb-0">
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
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="row">
        <form class="was-validated pb-3" action="" method="post">
            <div class="col-md-3 pl-lg-0">
                <div class="form-group">
                    <label>Select Financial Year</label>
                    <select class="js-example-basic-single" style="width: 100%;" name="fYear" id="fYear" required>
                        @foreach ($yearList as $year)
                        <option value="{{ $year->iYearId }}">{{ $year->yearName }}</option>
                        @endforeach
                    </select>
                </div> <!-- /.form-group -->
            </div> <!-- /.col -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>Select Month</label>
                    <?php  $month = date('m'); ?>
                    <select class="js-example-basic-single" style="width: 100%;" name="fMonth" id="fMonth" required>
                        <option label="Please Select" value="">-- Select --</option>
                        <option value="4" {{ $month == "04" ? 'selected' : "" }}>April</option>
                        <option value="5" {{ $month == "05" ? 'selected' : "" }}>May</option>
                        <option value="6" {{ $month == "06" ? 'selected' : "" }}>June</option>
                        <option value="7" {{ $month == "07" ? 'selected' : "" }}>July</option>
                        <option value="8" {{ $month == "08" ? 'selected' : "" }}>August</option>
                        <option value="9" {{ $month == "09" ? 'selected' : "" }}>September</option>
                        <option value="10" {{ $month == "10"? 'selected' : "" }}>October</option>
                        <option value="11" {{ $month == "11" ? 'selected' : "" }}>November</option>
                        <option value="12" {{ $month == "12" ? 'selected' : "" }}>December</option>
                        <option value="1" {{ $month == "01" ? 'selected' : "" }}>January</option>
                        <option value="2" {{ $month == "02" ? 'selected' : "" }}>February</option>
                        <option value="3" {{ $month == "03" ? 'selected' : "" }}>March</option>
                    </select>
                </div> <!-- /.form-group -->
            </div> <!-- /.col -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>Select OEM Company</label>
                    <select class="js-example-basic-single" style="width: 100%;" name="fCompany" id="fCompany" required>
                        <option label="Please Select" value="">-- Select --</option>
                        @foreach($CompanyMaster as $Company)
                        <option value="{{ $Company->iCompanyId }}">{{ $Company->strOEMCompanyName }}</option>
                        @endforeach

                    </select>
                </div> <!-- /.form-group -->
            </div> <!-- /.col -->
        </form>
    </div>
    <?php 
        $fDate = date('m/01/Y');
        $tDate = date('m/d/Y');
        $range = $fDate . ' - ' .$tDate;
    ?>
    <div class="row">
        <!--<div class="col">
            <div class="card stretch-card">
                <a href="{{ route('call_report.index') }}">
                    <div class="card-body d-flex flex-wrap justify-content-between p-3">
                        <h4 class="w-100 font-weight-semibold mb-4">Total Calls</h4>
                        <img class="pl-lg-3" src="../global/assets/images/dashboard/calls.png" alt="Calls">
                        <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"><span
                                id="TotalCalls"></span></h3>
                    </div>
                </a>
            </div>
        </div>-->
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
        
        <!--./col-->
        <!--./col-->

        <!--<div class="col pl-lg-0">
            <div class="card stretch-card">
                <form action="{{ route('call_report.index') }}" method="POST" style="display: inline-block;">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="daterange" value="{{ $range }}">
                    <a href="#">
                        <button type="submit" style="border: none;background: none;">
                            <div class="card-body d-flex flex-wrap justify-content-between p-3">
                                <h4 class="w-100 font-weight-semibold mb-4">Total Tickets</h4>
                                <img class="pl-lg-3" src="../global/assets/images/dashboard/ticket-closed.png"
                                    alt="Clock">
                                <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"><span
                                        id="TotalTickets"></span></h3>
                            </div>
                        </button>
                    </a>
                </form>
            </div>
        </div>-->
        
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
                        <form action="{{ route('call_report.index') }}" method="POST" id="totalTicketsForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="total_ticket_daterange" value="{{ $range }}">
                            <input type="hidden" name="yearId" id="total_ticket_year">
                            <input type="hidden" name="OEMCompany" id="iOEM_Company" value="">
                            <div class="text-left">
                                <h4 class="w-100 text-black-50">Total</h4>
                                <a href="#" class="text-decoration-none"
                                    onclick="document.getElementById('totalTicketsForm').submit();">
                                    <h3 class="font-weight-bold d-flex align-items-center" id="TotalTickets">0</h3>
                                </a>
                            </div>
                        </form>
                        <!-- Open Tickets Form -->
                        <form action="{{ route('call_report.index') }}" method="POST" id="openTicketsForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="open_ticket_daterange" value="{{ $range }}">
                            <input type="hidden" name="TicketStatus" value="0">
                            <input type="hidden" name="OEMCompany" id="iOEMCompany" value="">
                            <div class="text-right">
                                <h4 class="w-100 text-black-50 pull-right">Open</h4>
                                <a href="#" onclick="document.getElementById('openTicketsForm').submit();">
                                    <h3 class="pull-right font-weight-bold d-flex align-items-center" id="TotalOpenTickets">0
                                    </h3>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        <!--./col-->
        <!--<div class="col pl-lg-0">
            <div class="card stretch-card grid-margin">
                <form action="{{ route('call_report.index') }}" method="POST" style="display: inline-block;">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="daterange" id="open_ticket_daterange" value="{{ $range }}">
                    <input type="hidden" name="TicketStatus" value="7">
                    <a href="#">
                        <button type="submit" style="border: none;background: none;">
                            <div class="card-body d-flex flex-wrap justify-content-between p-3">
                                <h4 class="w-100 font-weight-semibold mb-4">Open Tickets > 24</h4>
                                <img class="pl-lg-3" src="../global/assets/images/dashboard/ticket-open.png"
                                    alt="Clock">
                                <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"><span
                                        id="TotalOpenTickets"></span></h3>
                            </div>
                        </button>
                    </a>
                </form>
            </div>
        </div>-->
        <!--./col-->
        <!--./col-->
        <!--<div class="col pl-lg-0">
            <div class="card stretch-card grid-margin">
                <form action="{{ route('customer_company_list.index') }}" method="POST" style="display: inline-block;">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="daterange" id="Total_Company_daterange" value="{{ $range }}">
                    <input type="hidden" name="OEMCompany" id="OEMCompany" value="">
                    <a href="#">
                        <button type="submit" style="border: none;background: none;">
                            <div class="card-body d-flex flex-wrap justify-content-between p-3">
                                <h4 class="w-100 font-weight-semibold mb-4">Total Company</h4>
                                <img class="pl-lg-3" src="../global/assets/images/dashboard/company.png" alt="Clock">
                                <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"><span
                                        id="TotalCompany"></span></h3>
                            </div>
                        </button>
                    </a>
                </form>
            </div>
        </div>-->
        
        <!--<div class="col-lg-3 col-md-6">
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
                        <form action="{{ route('customer_company_list.index') }}" method="POST" id="totalAllCompanyForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="Total_Company_daterange" value="{{ $range }}">
                            <input type="hidden" name="OEMCompany" id="OEMCompany" value="">
                            <div class="text-left">
                                <h4 class="w-100 text-black-50">Total</h4>
                                <a href="javascript:void(0);" onclick="document.getElementById('totalAllCompanyForm').submit();">
                                    <h3 class="font-weight-bold d-flex align-items-center" id="totalCustCompany">0</h3>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>-->
        
        <div class="col-lg-3 col-md-6 pr-lg-0">
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
                        <form action="{{ route('customer_company_list.index') }}" method="POST" id="totalAllCompanyForm" style="display: inline-block;">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="daterange" id="Total_Company_daterange" value="{{ $range }}">
                            <input type="hidden" name="OEMCompany" id="OEMCompany" value="">
                            <div class="text-left">
                                <h4 class="w-100 text-black-50">Total</h4>
                                <a href="javascript:void(0);" onclick="document.getElementById('totalAllCompanyForm').submit();">
                                    <h3 class="font-weight-bold d-flex align-items-center" id="TotalCompany">0</h3>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>    
        <!--./col-->
        <!--./col-->
        <!--./col-->
        <!--<div class="col pl-lg-0">
            <div class="card stretch-card grid-margin">
                <form action="{{ route('customer_list.index') }}" method="POST" style="display: inline-block;">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="daterange" id="Total_Customer_daterange" value="{{ $range }}">
                    <input type="hidden" name="OEMCompany" id="OEM_Company" value="">
                    <input type="hidden" name="isDashboardList" value="1">
                    <a href="#">
                        <button type="submit" style="border: none;background: none;">
                            <div class="card-body d-flex flex-wrap justify-content-between p-3">
                                <h4 class="w-100 font-weight-semibold mb-4">Total Customer</h4>
                                <img class="pl-lg-3" src="../global/assets/images/dashboard/users.png" alt="Clock">
                                <h3 class="pull-right text-success font-weight-bold d-flex align-items-center"><span
                                        id="TotalCustomer"></span>
                                </h3>
                            </div>
                        </button>
                    </a>
                </form>
            </div>
        </div>-->
        
        
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
                    <form action="{{ route('customer_list.index') }}" method="POST" id="totalAllCustomerForm" style="display: inline-block;">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="daterange" id="Total_Customer_daterange" value="{{ $range }}">
                        <input type="hidden" name="OEMCompany" id="OEM_Company" value="">
                        <input type="hidden" name="isDashboardList" value="1">
                        <div class="text-left">
                            <h4 class="w-100 text-black-50">Total</h4>
                            <a href="javascript:void(0);" onclick="document.getElementById('totalAllCustomerForm').submit();">
                                <h3 class="font-weight-bold d-flex align-items-center" id="TotalCustomer">0</h3>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--./col-->
    </div>
    <!--./row-->

    <div class="row">
        <!--chart start-->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">
                        Calls Analytics<br>
                        <small class="text-black-50 text-lowercase">Data displayed in numbers</small>
                    </h4>
                    <div id="barChart-graph-container"><canvas id="barChart" style="height: 230px;"></canvas></div>
                </div>
            </div>
        </div>
        <!--/.chart end-->
        <!--line chart start-->
        <div class="col-md-6 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">
                        Resolution Analytics <br>
                        <small class="text-black-50 text-lowercase">Data displayed in numbers</small>
                    </h4>
                    <div id="lineChart-graph-container"><canvas id="lineChart"></canvas></div>
                </div>
            </div>
        </div>
        <!--/.line chart end-->
    </div>
    <!--./row-->

    <!-- third row starts here -->
    <div class="row">
        <!--chart start-->
        <div class="col-md-6 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title p-0 mt-0 mb-5 bg-transparent">
                        Company Calls Analytics <br>
                        <small class="text-black-50 text-lowercase">Data displayed in numbers</small>
                    </h4>
                    <div id="graph-container"><canvas id="doughnutChart"></canvas></div>

                </div>
            </div>
        </div>
        <!--/.chart end-->
    </div>
    <!--./row-->

</div>
@endsection
@section('script')
<!--chart-->
<script src="{{ asset('global/assets/vendors/charts/Chart.min.js') }}"></script>
<script src="{{ asset('global/assets/vendors/charts/chart.js') }}"></script>

<!--chart-->
<!-- Calls Analytics -->
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script src="{{ asset('global/assets/vendors/charts/Chart.min.js') }}"></script>
<!-- <script src="{{ asset('global/assets/vendors/charts/chart.js') }}"></script> -->

<!-- <script src="{{ asset('global/assets/vendors/charts/line-chart.js') }}"></script> -->
<!-- <script src="{{ asset('global/assets/vendors/charts/doughnut-chart.js') }}"></script> -->
<script>
$(document).ready(function() {
    getCountData();
    getPiaChart();
    getBarChartData();
    getLineChart();
    // getLineChartLevelTwo();
    // getLineChartSameDaySolution();
});
$("#fMonth").change(function() {
    getCountData();
    getPiaChart();
    getBarChartData();
    getLineChart();
    var year = $("#fYear option:selected").text();
    var month = $(this).val();
    var iYaerId = $("#fYear").val();
    if(month != ""){
        var myArray = year.split("-");
        var CurrYear = "";
        if(month > 3){
            CurrYear = myArray[0];
        } else {
            CurrYear = myArray[1];
        }
        var daterange = getselectedDateRange(CurrYear,month);
        $("#date_range").val(daterange);
        $("#open_ticket_daterange").val(daterange);
        $("#total_ticket_daterange").val(daterange);
        $("#total_ticket_year").val(iYaerId);
        $("#Total_Company_daterange").val(daterange);
        $("#Total_Customer_daterange").val(daterange);
    } else {
        $("#date_range").val('');
        $("#open_ticket_daterange").val('');
        $("#total_ticket_daterange").val('');
        $("#total_ticket_year").val(iYaerId);
        $("#Total_Company_daterange").val('');
        $("#Total_Customer_daterange").val('');
    }
});
$("#fYear").change(function() {
    getCountData();
    getPiaChart();
    getBarChartData();
    getLineChart();
    var year = $("#fYear option:selected").text();
    var fMonth = $("#fMonth").val();
    var iYaerId = $("#fYear").val();
    if(month != ""){
        var myArray = year.split("-");
        var CurrYear = "";
        if(month > 3){
            CurrYear = myArray[0];
        } else {
            CurrYear = myArray[1];
        }
        var daterange = getselectedDateRange(CurrYear,month);
        $("#date_range").val(daterange);
        $("#open_ticket_daterange").val(daterange);
        $("#total_ticket_daterange").val(daterange);
        $("#total_ticket_year").val(iYaerId);
        $("#Total_Company_daterange").val(daterange);
        $("#Total_Customer_daterange").val(daterange);
    } else {
        $("#date_range").val('');
        $("#open_ticket_daterange").val('');
        $("#total_ticket_daterange").val('');
        $("#total_ticket_year").val(iYaerId);
        $("#Total_Company_daterange").val('');
        $("#Total_Customer_daterange").val('');
    }
});
$("#fCompany").change(function() {
    var iCompanyId  = $(this).val();
    $("#OEMCompany").val(iCompanyId);
    $("#OEM_Company").val(iCompanyId);
    $("#iOEMCompany").val(iCompanyId);
    $("#iOEM_Company").val(iCompanyId);
    getCountData();
    getPiaChart();
    getBarChartData();
    getLineChart();
    var year = $("#fYear option:selected").text();
    var fMonth = $("#fMonth").val();
    var iYaerId = $("#fYear").val();
    if(month != ""){
        var myArray = year.split("-");
        var CurrYear = "";
        if(month > 3){
            CurrYear = myArray[0];
        } else {
            CurrYear = myArray[1];
        }
         
        var daterange = getselectedDateRange(CurrYear,month);
        $("#date_range").val(daterange);
        $("#open_ticket_daterange").val(daterange);
        $("#total_ticket_daterange").val(daterange);
        $("#Total_Company_daterange").val(daterange);
        $("#Total_Customer_daterange").val(daterange);
        $("#total_ticket_year").val(iYaerId);
    } else {
        $("#date_range").val('');
        $("#open_ticket_daterange").val('');
        $("#total_ticket_daterange").val('');
        $("#Total_Company_daterange").val('');
        $("#Total_Customer_daterange").val('');
        $("#total_ticket_year").val(iYaerId);
    }
});

function getselectedDateRange(CurrYear,month){
    var to_Date = LastDayOfMonth(CurrYear,month);
    var td = to_Date.getDate();
    var tm =  to_Date.getMonth();
    tm += 1; 
    var ty = to_Date.getFullYear();
    var toDate = tm + "/" + td + "/" + ty;
 
    var from_Date = new Date(CurrYear,(month - 1),1,0);
    var fd = from_Date.getDate();
    var fm =  from_Date.getMonth();
    fm += 1; 
    var fy = from_Date.getFullYear();
    var fromDate = fm + "/" + fd + "/" + fy;

    return fromDate + ' - ' + toDate;

}
function LastDayOfMonth(Year, Month) {
  return new Date((new Date(Year, Month, 1)) - 1);
}

function getCountData() {
    var yearID = $("#fYear").val();
    var fMonth = $("#fMonth").val();
    var fCompany = $("#fCompany").val();
    $.ajax({
        type: 'POST',
        url: "{{ route('admindashboard.getCountData') }}",
        data: {
            yearId: yearID,
            fMonth: fMonth,
            fCompany: fCompany
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            var obj = JSON.parse(response)
            $("#TotalCalls").html(obj.call);
            $("#TotalTickets").html(obj.ticket);
            $("#TotalOpenTickets").html(obj.oTicket);
            $("#TotalCompany").html(obj.custCompany);
            $("#TotalCustomer").html(obj.customer);
        }
    });
}

function getPiaChart() {
    var yearID = $("#fYear").val();
    var fMonth = $("#fMonth").val();
    var fCompany = $("#fCompany").val();
    $.ajax({
        type: 'POST',
        url: "{{ route('admindashboard.getPiaChart') }}",
        data: {
            yearId: yearID,
            fMonth: fMonth,
            fCompany: fCompany
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
                            backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1",
                                "#4D5360",
                                "#ff355e", "#fd5b78", "#ff6037", "#ff9966", "#ff9933",
                                "#ffcc33", "#ffff66", "#ccff00", "#66ff66", "#aaf0d1",
                                "#16d0cb", "#50bfe6", "#9c27b0", "#ee34d2", "#ff00cc"
                            ],
                            hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870",
                                "#A8B3C5", "#616774",
                                "#ff355e", "#fd5b78", "#ff6037", "#ff9966", "#ff9933",
                                "#ffcc33", "#ffff66", "#ccff00", "#66ff66", "#aaf0d1",
                                "#16d0cb", "#50bfe6", "#9c27b0", "#ee34d2", "#ff00cc"
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


function getBarChartData() {
    var yearID = $("#fYear").val();
    var fMonth = $("#fMonth").val();
    var fCompany = $("#fCompany").val();
    $.ajax({
        type: 'POST',
        url: "{{ route('admindashboard.getBarChart') }}",
        data: {
            yearId: yearID,
            fMonth: fMonth,
            fCompany: fCompany
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

                                'rgba(255, 53, 94,0.2)',
                                'rgba(253, 91, 120,0.2)',
                                'rgba(255, 96, 55,0.2)',
                                'rgba(255, 153, 102,0.2)',
                                'rgba(255, 153, 51,0.2)',
                                'rgba(255, 204, 51,0.2)',
                                'rgba(255, 255, 102,0.2)',
                                'rgba(204, 255, 0,0.2)',
                                'rgba(102, 255, 102,0.2)',
                                'rgba(170, 240, 209,0.2)',
                                'rgba(22, 208, 203,0.2)',
                                'rgba(80, 191, 230,0.2)',
                                'rgba(156, 39, 176,0.2)',
                                'rgba(238, 52, 210,0.2)',
                                'rgba(255, 0, 204,0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)'
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

                                'rgba(255, 53, 94, 1)',
                                'rgba(253, 91, 120, 1)',
                                'rgba(255, 96, 55, 1)',
                                'rgba(255, 153, 102, 1)',
                                'rgba(255, 153, 51, 1)',
                                'rgba(255, 204, 51, 1)',
                                'rgba(255, 255, 102, 1)',
                                'rgba(204, 255, 0, 1)',
                                'rgba(102, 255, 102, 1)',
                                'rgba(170, 240, 209, 1)',
                                'rgba(22, 208, 203, 1)',
                                'rgba(80, 191, 230, 1)',
                                'rgba(156, 39, 176, 1)',
                                'rgba(238, 52, 210, 1)',
                                'rgba(255, 0, 204, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: options
                });
                $.each(JSON.parse(response), function(key, value) {
                    barChart.data.labels.push(value.key);
                    barChart.data.datasets[0].data.push(value.Count);
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
    var fMonth = $("#fMonth").val();
    var fCompany = $("#fCompany").val();
    $.ajax({
        type: 'POST',
        url: "{{ route('admindashboard.getLineChart') }}",
        data: {
            yearId: yearID,
            fMonth: fMonth,
            fCompany: fCompany
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //console.log(response);
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
                                label: "Resolution at L2",
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
                        // tooltips: {
                        //     callbacks: {
                        //         label: function(tooltipItem, data) {
                        //             //alert("Tooltip : " + data);
                        //             return data.datasets[tooltipItem.datasetIndex].label +
                        //                 " : " + Number(tooltipItem.yLabel).toFixed(0).replace(
                        //                     /./g,
                        //                     function(c, i, a) {
                        //                         return i > 0 && c !== "." && (a.length - i) %
                        //                             3 === 0 ? "," + c : c;
                        //                     }) + "%";
                        //         }
                        //     }
                        // },
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
                    if (key == "levelTwo") {
                        //myLineChart.data.datasets[1].data.push(value);
                        $.each(value, function(key, val) {

                            myLineChart.data.datasets[1].data.push(val);
                        });
                    }
                    if (key == "samedaysolution") {
                        $.each(value, function(key, val) {
                            console.log(val);
                            myLineChart.data.datasets[2].data.push(val);
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

// function getLineChartLevelTwo() {
//     var yearID = $("#fYear").val();
//     var fMonth = $("#fMonth").val();
//     var fCompany = $("#fCompany").val();
//     $.ajax({
//         type: 'POST',
//         url: "{{ route('admindashboard.getLineChartLevelTwo') }}",
//         data: {
//             yearId: yearID,
//             fMonth: fMonth,
//             fCompany: fCompany
//         },
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             $.each(JSON.parse(response), function(key, value) {

//                 myLineChart.data.datasets[1].data.push(value.Count);
//             });
//             myLineChart.update();
//         }
//     });
// }

// function getLineChartSameDaySolution() {
//     var yearID = $("#fYear").val();
//     var fMonth = $("#fMonth").val();
//     var fCompany = $("#fCompany").val();
//     $.ajax({
//         type: 'POST',
//         url: "{{ route('admindashboard.getLineChartSameDayResolve') }}",
//         data: {
//             yearId: yearID,
//             fMonth: fMonth,
//             fCompany: fCompany
//         },
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             $.each(JSON.parse(response), function(key, value) {
//                 myLineChart.data.datasets[2].data.push(value.Count);
//             });
//             myLineChart.update();
//         }
//     });
// }
</script>
@endsection
