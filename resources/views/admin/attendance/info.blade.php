@extends('layouts.admin')

@section('title', 'Attendance Information')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper pb-0">
            <div class="page-header">
                <h3>Attendance Information</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li>
                        <li class="breadcrumb-item active">Information </li>
                    </ol>
                </nav>
            </div>
            <!--/. page header ends-->
            <!-- first row starts here -->
            <div class="row d-flex justify-content-center mb-5">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <h4 class="card-title mt-0">User Name: {{ $callAttendent->strFirstName }} {{ $callAttendent->strLastName }}</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped" data-role="content" data-plugin="selectable"
                                            data-row-selectable="true">
                                            <thead class="bg-grey-100">
                                                <tr>
                                                    <th>Sr. No</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $iCounter = 1; ?>
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
                            </div>
                        </div>
                    </div>
                    <!--card end-->
                </div>
            </div><!-- end row -->
        </div>
        
    </div>
    
@endsection
