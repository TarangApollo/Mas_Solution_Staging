@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')

<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>User Information</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">User</li>
                <li class="breadcrumb-item"><a href="{{route('user.index')}}">User List</a></li>
                <li class="breadcrumb-item active">Information </li>
            </ol>
        </nav>
    </div>
    <!--/. page header ends-->
    <!-- first row starts here -->
    @include('wladmin.wlcommon.alert')
    <div class="row d-flex justify-content-center mb-5">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body p-0">
                    <h4 class="card-title mt-0">User Name: {{ $WlUser->strFirstName }} {{ $WlUser->strLastName }}</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-striped" data-role="content" data-plugin="selectable" data-row-selectable="true">
                                    <thead class="bg-grey-100">
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php $iCounter=1;?>
                                        @if(!$Loginlogs->isEmpty())
                                        @foreach($Loginlogs as $Loginlog)
                                        <tr>
                                            <td>{{ $iCounter }}</td>
                                            <td>{{ date('d-m-Y', strtotime($Loginlog->strEntryDate)) }}</td>
                                            <td>{{ date('H:i:s', strtotime($Loginlog->strEntryDate)) }}</td>
                                            <td>{{ $Loginlog->action }}</td>
                                        </tr>
                                        <?php $iCounter++; ?>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--/. col 6-->
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-striped" data-role="content" data-plugin="selectable" data-row-selectable="true">
                                    <thead class="bg-grey-100">
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Date of Updates</th>
                                            <th>Updated by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$infoTables->isEmpty())
                                        <?php $jCounter = 1; ?>
                                        @foreach($infoTables as $infoTable)
                                        <tr>
                                            <td>{{ $jCounter }}</td>
                                            <td>{{ date('d-m-Y', strtotime($infoTable->strEntryDate)) }} <br>
                                                <small class="position-static">{{ date('H:i:s', strtotime($infoTable->strEntryDate)) }}</small>
                                            </td>
                                            <td>{{ $infoTable->actionBy }} </td>
                                        </tr>
                                        <?php $jCounter++; ?>
                                        @endforeach
                                        @endif
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

@endsection
