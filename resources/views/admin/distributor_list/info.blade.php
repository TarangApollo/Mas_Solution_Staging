@extends('layouts.admin')

@section('title', 'Distributor Information')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Distributor Information</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">

                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item"><a href="{{ route('distributor_list.index') }}">Distributor List</a></li>
                <li class="breadcrumb-item active">Information </li>
            </ol>
        </nav>
    </div>
    @include('admin.common.alert')
    <!-- first row starts here -->
    <div class="row d-flex justify-content-center mb-5">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body p-0">
                    <h4 class="card-title mt-0">DISTRIBUTOR LIST FOR: {{$CompanyClients->Name}}</h4>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-striped" data-role="content" data-plugin="selectable" data-row-selectable="true">
                                    <thead class="bg-grey-100">
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Label</th>
                                            <th>Value</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>OEM Company Name	</td>
                                            <td colspan="3">{{ $CompanyClients->strOEMCompanyName ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Distributor Name</td>
                                            <td colspan="3">{{$CompanyClients->Name}}</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Distributor Email ID</td>
                                            <td colspan="3">{{$CompanyClients->EmailId}}</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Distributor phone</td>
                                            <td colspan="3">{{$CompanyClients->distributorPhone}}</td>
                                        </tr>

                                        <tr>
                                            <td>5</td>
                                            <td>Address</td>
                                            <td colspan="3">{{$CompanyClients->Address}}</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>State</td>
                                            <td colspan="3">{{$CompanyClients->strStateName}}</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>City</td>
                                            <td colspan="3">{{$CompanyClients->strCityName}}</td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Branch Office</td>
                                            <td colspan="3">{{$CompanyClients->branchOffice}}</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>Company Profile</td>
                                            <td colspan="3">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>Sales Person</td>
                                            <?php $iSale = 1; ?>
                                            @if(count($CompanyClients->salesperson)>0)
                                            @foreach ($CompanyClients->salesperson as $sales)
                                            <?php if ($iSale > 1) echo '<tr><td>&nbsp;</td><td>&nbsp;</td>'; ?>
                                            <td>{{$sales->salesPersonName}}</td>
                                            <td>{{$sales->salesPersonEmail}}</td>
                                            <td>{{$sales->salesPersonNumber}}</td>
                                            <?php if ($iSale > 1) echo '</tr>'; ?>
                                            <?php $iSale++; ?>
                                            @endforeach
                                            @else
                                            <td colspan="3">&nbsp;</td>
                                            @endif
                                        </tr>




                                        <tr>
                                            <td >11</td>
                                            <td >Technical Person</td>
                                            <?php $iSale = 1; ?>
                                            @foreach ($CompanyClients->technicalperson as $technical)
                                            <?php if ($iSale > 1) echo '<tr><td>&nbsp;</td><td>&nbsp;</td>'; ?>
                                            <td>{{$technical->technicalPersonName}}</td>
                                            <td>{{$technical->technicalPersonEmail}}</td>
                                            <td>{{$technical->technicalPersonNumber}}</td>
                                            <?php if ($iSale > 1) echo '</tr>'; ?>
                                            <?php $iSale++; ?>

                                            @endforeach
                                        </tr>


                                        <tr>
                                            <td>12</td>
                                            <td>User Defined</td>
                                            <td>User Defiened 1</td>
                                            <td>{{$CompanyClients->userDefine1}}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>User Defiened 2</td>
                                            <td>{{$CompanyClients->userDefine2}}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>User Defiened 3</td>
                                            <td>{{$CompanyClients->userDefine3}}</td>
                                            <td></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div><!--/. col 6-->
                       
                    </div>
                </div>
            </div><!--card end-->
        </div>
    </div><!-- end row -->

</div>
<!--END notify messages-->


@endsection

@section('script')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

@endsection
