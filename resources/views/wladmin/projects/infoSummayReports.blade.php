@extends('layouts.wladmin')
@section('title', 'Dashboard')
@section('content')
<link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Project Summary Information</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Projects</li>
                <li class="breadcrumb-item"><a href="{{ route('projects.projectsSummayReports') }}">Project Summary</a></li>
                <li class="breadcrumb-item active">Information </li>
            </ol>
        </nav>
    </div><!--/. page header ends-->
    <!-- first row starts here -->
    <div class="row d-flex justify-content-center mb-5">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body p-0">
                    <h4 class="card-title mt-0">Project Name: {{ $project->projectName }}</h4>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table table-striped" data-role="content" data-plugin="selectable" data-row-selectable="true">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Call ID</th>
                                            <th>Company Name</th>
                                            <th>Customer Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $iCounter = 1; ?>
                                        @foreach($ticketmasters as $ticketmaster)
                                        <tr>
                                            <td>{{ $iCounter }}</td>
                                            <td>
                                                <a href="{{ route('projects.projectsSummayReportsInfo',$ticketmaster->iTicketId) }}">{{ $ticketmaster->strTicketUniqueID }}</a>
                                            </td>
                                            <td>{{ $ticketmaster->CompanyName }}</td>
                                            <td>{{ $ticketmaster->CustomerName }}</td>
                                        </tr>
                                        <?php $iCounter++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div><!--responsive table div-->
                        </div><!--/. col 6-->
                    </div>

                </div><!--card body-->
            </div><!--card end-->
        </div>
    </div><!-- end row -->
</div>
@endsection