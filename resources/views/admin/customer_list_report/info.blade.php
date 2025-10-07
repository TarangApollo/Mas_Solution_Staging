@extends('layouts.admin')

@section('title', 'Customer List')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Customer Information</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Report</li>
                    <li class="breadcrumb-item"><a href="{{ route('customer_list.index') }}">Customer List</a></li>
                    <li class="breadcrumb-item active"> Information</li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
        <!-- first row starts here -->

        <div class="row">
            <div class="col-xl-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">CALL LIST FOR:
                            {{ !empty($ticketData) && $ticketData->strCustomerName != '' ? $ticketData->strCustomerName : '' }}
                        </h4>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped" data-role="content" data-plugin="selectable"
                                        data-row-selectable="true">
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Call ID</th>
                                                <th> Caller Competency </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $iCounter = 1; ?>
                                            @foreach ($ticketList as $ticket)
                                                <tr>
                                                    <td>{{ $iCounter }}</td>
                                                    <td>
                                                        <a href="{{ route('customer_list.callinfo', $ticket->iTicketId) }}">
                                                            {{ $ticket->strTicketUniqueID ?? str_pad($ticket->iTicketId, 4, '0', STR_PAD_LEFT) }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $ticket->CallerCompetencyId }}</td>
                                                </tr>
                                                <?php $iCounter++; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--/. col 6-->

                            <!--/. col 6-->
                        </div>
                    </div>
                    <!--card body-->
                </div>
                <!--card end-->
            </div>
            <!--card body end-->
        </div>
        <!--card end-->
    </div>
@endsection
@section('script')

@endsection
