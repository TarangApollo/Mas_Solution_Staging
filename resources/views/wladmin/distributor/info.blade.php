@extends('layouts.wladmin')

@section('title', 'Add New Call Attendant')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Distributor Information</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">

                    <li class="breadcrumb-item">Distributor</li>
                    <li class="breadcrumb-item"><a href="{{ route('distributor.index') }}">Distributor List</a></li>
                    <li class="breadcrumb-item active">Information </li>
                </ol>
            </nav>
        </div>
        @include('wladmin.wlcommon.alert')
        <!-- first row starts here -->
        <div class="row d-flex justify-content-center mb-5">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">Company Name: {{ $CompanyClients->Name ?? "" }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped" data-role="content" data-plugin="selectable"
                                        data-row-selectable="true">
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
                                                <td>Distributor Name</td>
                                                <td colspan="3">{{ $CompanyClients->Name ?? "" }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Distributor Email ID</td>
                                                <td colspan="3">{{ $CompanyClients->EmailId ?? "" }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Distributor phone</td>
                                                <td colspan="3">{{ $CompanyClients->distributorPhone ?? "" }}</td>
                                            </tr>

                                            <tr>
                                                <td>4</td>
                                                <td>Address</td>
                                                <td colspan="3">{{ $CompanyClients->Address ?? "" }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>State</td>
                                                <td colspan="3">{{ $CompanyClients->strStateName ?? "" }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>City</td>
                                                <td colspan="3">{{ $CompanyClients->strCityName ?? "" }}</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Branch Office</td>
                                                <td colspan="3">{{ $CompanyClients->branchOffice ?? "" }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Company Profile</td>
                                                <td colspan="3">
                                                    {{ $CompanyClients->strCompanyClientProfile ?? "" }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Sales Person</td>
                                                <?php $iSale = 1; ?>
                                                @if(isset($CompanyClients->salesperson))
                                                @if (count($CompanyClients->salesperson) > 0)
                                                    @foreach ($CompanyClients->salesperson as $sales)
                                                        <?php if ($iSale > 1) {
                                                            echo '<tr><td>&nbsp;</td><td>&nbsp;</td>';
                                                        } ?>
                                                        <td>{{ $sales->salesPersonName }}</td>
                                                        <td>{{ $sales->salesPersonEmail }}</td>
                                                        <td>{{ $sales->salesPersonNumber }}</td>
                                                        <?php if ($iSale > 1) {
                                                            echo '</tr>';
                                                        } ?>
                                                        <?php $iSale++; ?>
                                                    @endforeach
                                                @else
                                                    <td colspan="3">&nbsp;</td>
                                                @endif
                                                @endif
                                                
                                            </tr>




                                            <tr>
                                                <td>12</td>
                                                <td>Technical Person</td>
                                                <?php $iSale = 1; ?>
                                                @if(!empty($CompanyClients->technicalperson))
                                                @foreach ($CompanyClients->technicalperson as $technical)
                                                    <?php if ($iSale > 1) {
                                                        echo '<tr><td>&nbsp;</td><td>&nbsp;</td>';
                                                    } ?>
                                                    <td>{{ $technical->technicalPersonName }}</td>
                                                    <td>{{ $technical->technicalPersonEmail }}</td>
                                                    <td>{{ $technical->technicalPersonNumber }}</td>
                                                    <?php if ($iSale > 1) {
                                                        echo '</tr>';
                                                    } ?>
                                                    <?php $iSale++; ?>
                                                @endforeach
                                                @endif
                                            </tr>


                                            <tr>
                                                <td>13</td>
                                                <td>User Defined</td>
                                                <td>User Defiened 1</td>
                                                <td>{{ $CompanyClients->userDefine1 ?? "" }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>User Defiened 2</td>
                                                <td>{{ $CompanyClients->userDefine2 ?? "" }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>User Defiened 3</td>
                                                <td>{{ $CompanyClients->userDefine3 ?? "" }}</td>
                                                <td></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--/. col 6-->
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped" data-role="content" data-plugin="selectable"
                                        data-row-selectable="true">
                                        <thead class="bg-grey-100">
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Date of Updates</th>
                                                <th>Updated by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $icounter = 1; ?>
                                            @foreach ($infoTables as $log)
                                                <tr>
                                                    <td>{{ $icounter }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($log->strEntryDate)) }}<br>
                                                        <small
                                                            class="position-static">{{ date('H:i:s', strtotime($log->strEntryDate)) }}</small>
                                                    </td>
                                                    <td>{{ $log->actionBy }}</td>
                                                </tr>
                                                <?php $icounter++; ?>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--/. col 6-->
                        </div>
                    </div>
                </div>
                <!--card end-->
            </div>
        </div><!-- end row -->

    </div>
    <!--END notify messages-->


@endsection

@section('script')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

@endsection
