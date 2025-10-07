@extends('layouts.wladmin')

@section('title', 'Distributor Summary Information')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Distributor Summary Information
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Report</li>
                    <li class="breadcrumb-item"><a href="{{ route('distributorsummary.index') }}">Distributor Summary</a></li>
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
                        <h4 class="card-title mt-0">INSTALLER LIST FOR: {{ $GetDistributorName->Name }}
                        </h4>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped" data-role="content" data-plugin="selectable"
                                        data-row-selectable="true">
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Customer Company Name</th>
                                                <th>Call ID</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $iCounter = 1; ?>
                                            @foreach ($res as $ticket)
                                                <tr>
                                                    <td>{{ $iCounter }}</td>
                                                    <td>{{ $ticket['DistributorName'] }}</td>
                                                    <td>
                                                        <?php $array = explode(',', $ticket['iTicketId']); ?>
                                                        <?php $strTicketUniqueIDarray = explode(',', $ticket['strTicketUniqueID']);
                                                        $iCounter = 0;
                                                        ?>
                                                        @foreach ($array as $tid)
                                                            <a href="{{ route('distributorsummary.callinfo', $tid) }}">
                                                                <?php $name = $strTicketUniqueIDarray[$iCounter] . ',  '; ?>
                                                                {{ $name }}
                                                            </a>
                                                            <?php $iCounter++; ?>
                                                        @endforeach
                                                    </td>
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
    <!--row-->

@endsection
@section('script')


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/material-bootstrap-wizard.js') }}"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <script>
        function openDoc(url) {
            var newurl = "{{ route('faq.openDocument', ':id') }}";
            newurl = newurl.replace(':id', url);
            newurl = newurl.replace('?', '/');
            window.open(newurl, '_blank');

        }
    </script>
@endsection
