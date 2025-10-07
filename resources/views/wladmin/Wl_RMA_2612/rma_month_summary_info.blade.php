@extends('layouts.wladmin')
@section('title', 'Dashboard')
@section('content')

    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper pb-0">
            <div class="page-header">
                <h3>RMA Monthly Summary Information</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">RMA</li>
                        <li class="breadcrumb-item"><a href="{{ route('Wl_RMA.rma_month_summary') }}">RMA Monthly Summary</a>
                        </li>
                    </ol>
                </nav>
            </div><!--/. page header ends-->
            <!-- first row starts here -->
            <div class="row d-flex justify-content-center mb-5">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <h4 class="card-title mt-0">RMA Monthly Summary: {{ $id . ' ' . $year }}</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <div class="row mx-1">
                                            <div class="col-md-12">
                                                <h4>Open RMA</h4>
                                            </div>
                                        </div>
                                        <table class="table table-striped" data-role="content" data-plugin="selectable"
                                            data-row-selectable="true">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No</th>
                                                    <th>Call ID</th>
                                                </tr>
                                            </thead>


                                            @if (count($open_rma_list) > 0)
                                                <tbody>
                                                    @foreach ($open_rma_list as $index => $data)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('Wl_RMA.rma_month_call_info', $data->rma_id) }}">{{ $data->iRMANumber ?? '-' }}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            @else
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2" class="text-center">No data found</td>
                                                    </tr>
                                                </tbody>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <div class="row mx-1">
                                            <div class="col-md-12">
                                                <h4>Closed RMA</h4>
                                            </div>
                                        </div>
                                        <table class="table table-striped" data-role="content" data-plugin="selectable"
                                            data-row-selectable="true">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No</th>
                                                    <th>Call ID</th>
                                                </tr>
                                            </thead>
                                            @if (count($closed_rma_list) > 0)
                                                <tbody>
                                                    @foreach ($closed_rma_list as $data => $item)
                                                        <tr>
                                                            <td>{{ $data + 1 }}</td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('Wl_RMA.rma_month_call_info', $item->rma_id) }}">{{ $item->item ?? '-' }}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            @else
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2" class="text-center">No data found</td>
                                                    </tr>
                                                </tbody>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main-panel ends -->


    <script src="../global/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../global/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../global/assets/js/settings.js"></script>
    <script src="../global/assets/js/custom.js"></script>
    <script src="../global/assets/js/off-canvas.js"></script>
    <script src="../global/assets/js/hoverable-collapse.js"></script>

    <!-- Plugin js for this page -->
    <!--lightbox gallery-->
    <script src="../global/assets/vendors/wizard/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../global/assets/vendors/wizard/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>


@endsection
