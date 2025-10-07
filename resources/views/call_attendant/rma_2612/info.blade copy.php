@extends('layouts.callAttendant')
@section('title', 'Add Rma')
@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('global/assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/fonts/mas-solution/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/css/vendor.bundle.base.css') }}">

    <!--bootstrap table-->
    <link href="{{ asset('global/assets/vendors/bootstrap-table/css/fresh-bootstrap-table.css') }}" rel="stylesheet" />
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <!--date picker-->
    <link href="{{ asset('global/assets/vendors/date-picker/datepicker.css') }}" rel="stylesheet" type="text/css" />


    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex justify-content-center">
                    <div class="page-header flex-wrap">
                        <div class="header-right d-flex flex-wrap mt-sm-5 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <h3 class="m-0">Return Details</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <div class="card mt-4">
                            <div class="card-body p-0">
                                <h4 class="card-title mt-0">RMA Details for: {{ $rmaList->iRMANumber }} </h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-striped" data-role="content" data-plugin="selectable"
                                                data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Complaint ID</td>
                                                        <td>0407</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Customer Company</td>
                                                        <td>static </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Customer Engineer</td>
                                                        <td>static </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Distributor</td>
                                                        <td>{{ $rmaList->distributor_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Project Name</td>
                                                        <td>{{ $rmaList->strProjectName }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!--/. responsive table 1 div-->
                                        <hr>
                                        <!-- other information table div-->

                                        <div class="table-responsive">
                                            <div class="row mx-1">
                                                <div class="col-md-6 col-xs-8">
                                                    <h4>Other Information</h4>
                                                    <p>Latest Updated by: Prakash Shivnani</p>
                                                </div>
                                                <div class="col-md-6 col-xs-4 text-right mt-2">
                                                    <button type="button" class="btn btn-success text-uppercase"
                                                        data-toggle="modal" data-target="#edit-info" id="editinfo">
                                                        Edit Info
                                                    </button>
                                                </div>
                                            </div>

                                            <table class="table table-striped" data-role="content" data-plugin="selectable"
                                                data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>RMA Registration Date</td>
                                                        <td>
                                                            {{ $rmaList->strRMARegistrationDate && $rmaList->strRMARegistrationDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strRMARegistrationDate))
                                                                : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Item</td>
                                                        <td> {{ $rmaList->strItem ?? '-' }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Item Description</td>
                                                        <td> {{ $rmaList->strItemDescription ?? '-' }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Serial No</td>
                                                        <td>{{ $rmaList->strSerialNo ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Date Code</td>
                                                        <td>{{ $rmaList->strDateCode ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>In warranty</td>
                                                        <td>{{ $rmaList->strInwarranty ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7</td>
                                                        <td>Quantity</td>
                                                        <td>{{ $rmaList->strQuantity ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>8</td>
                                                        <td>Select System</td>
                                                        <td>{{ $rmaList->strSelectSystem ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>9</td>
                                                        <td>Fault description</td>
                                                        <td>{{ $rmaList->strFaultdescription ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>10</td>
                                                        <td>Facts</td>
                                                        <td>{{ $rmaList->strFacts ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>11</td>
                                                        <td>Additional Details</td>
                                                        <td>{{ $rmaList->strAdditionalDetails ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!--/. other information table div-->
                                        <!-- Testing & Approval table div-->
                                        <div class="table-responsive">
                                            <div class="row mx-1">
                                                <div class="col-md-12">
                                                    <h4>Testing & Approval</h4>
                                                </div>
                                            </div>

                                            <table class="table table-striped" data-role="content" data-plugin="selectable"
                                                data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <input type="hidden" name="rma_id" id="put_rma_id"
                                                    value="{{ $rmaList->rma_id }}">
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Material Received</td>
                                                        <td>{{ $rmaList->strMaterialReceived ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Material Received Date</td>
                                                        <td>
                                                            {{ $rmaList->strMaterialReceivedDate && $rmaList->strMaterialReceivedDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strMaterialReceivedDate))
                                                                : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Testing</td>
                                                        <td>{{ $rmaList->strTesting ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Testing Complete Date</td>
                                                        <td>
                                                            {{ $rmaList->strTestingCompleteDate && $rmaList->strTestingCompleteDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strTestingCompleteDate))
                                                                : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Fault Covered in Warranty</td>
                                                        <td>{{ $rmaList->strFaultCoveredinWarranty ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>Replacement Approved</td>
                                                        <td>{{ $rmaList->strReplacementApproved ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7</td>
                                                        <td>Replacement Reason</td>
                                                        <td>{{ $rmaList->strReplacementReason ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <!-- image / video row -->
                                            @if (count($Documents) > 0)
                                                <div class="row mx-1">
                                                    <div class="col-md-12">
                                                        <h4>Testing images, videos & Docs 12</h4>
                                                        <div class="row mt-4">

                                                            @foreach ($Documents as $index => $item)
                                                                @php
                                                                    $extension = pathinfo(
                                                                        $item->strDocs,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp
                                                                <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                    <div data-toggle="modal"
                                                                        data-index="{{ $index }}"
                                                                        data-target="#myModal">
                                                                        @if ($item->strImages)
                                                                            <img src="{{ asset('/RMADOC/images') . '/' . $item->strImages }}"
                                                                                alt="Image 1" data-target="#myCarousel"
                                                                                data-slide-to="{{ $item->rma_docs_id }}">
                                                                        @elseif ($item->strVideos)
                                                                            <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                                                alt="Video" data-target="#myCarousel"
                                                                                data-slide-to="{{ $item->rma_docs_id }}">
                                                                        @else
                                                                            <img src="{{ asset('global/assets/images/reference/photo/document.jpg') }}"
                                                                                alt="Document" data-target="#myCarousel"
                                                                                data-slide-to="{{ $item->rma_docs_id }}">
                                                                        @endif
                                                                    </div>
                                                                    <div class="text-center del-img mt-2">
                                                                        <i class="mas-trash mas-1x" title="Delete"></i>
                                                                    </div>
                                                                </div><!-- /.col -->
                                                            @endforeach




                                                        </div> <!-- /.row -->
                                                        <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="myModal" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div> <!-- /.header -->
                                                                    <div class="modal-body">
                                                                        <!-- Carousel -->
                                                                        <div id="myCarousel" class="carousel slide">
                                                                            <ol class="carousel-indicators">
                                                                                <?php $i = 1; ?>
                                                                                @foreach ($Documents as $index => $item)
                                                                                    <li data-target="#myCarousel"
                                                                                        data-slide-to="{{ $index }}"
                                                                                        class="{{ $loop->first ? 'active' : '' }}">
                                                                                    </li>
                                                                                    <?php $i++; ?>
                                                                                @endforeach

                                                                            </ol>
                                                                            <div class="carousel-inner">
                                                                                <?php $i = 1; ?>
                                                                                @foreach ($Documents as $index => $item)
                                                                                    @if ($item->strImages)
                                                                                        <div
                                                                                            class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                            <img src="{{ asset('/RMADOC/images') . '/' . $item->strImages }}"
                                                                                                class="d-block w-100"
                                                                                                alt="Image 1">
                                                                                        </div>
                                                                                    @elseif($item->strVideos)
                                                                                        <div
                                                                                            class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                            <div
                                                                                                class="embed-responsive embed-responsive-16by9">
                                                                                                <div
                                                                                                    class="embed-responsive-item">
                                                                                                    <video controls=""
                                                                                                        name="media">
                                                                                                        <source
                                                                                                            src="{{ asset('RMADOC/videos/') . '/' . $item->strVideos }}"
                                                                                                            type="video/mp4">
                                                                                                    </video>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @else
                                                                                        <div
                                                                                            class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                            <img src="{{ asset('/RMADOC/docs') . '/' . $item->strDocs }}"
                                                                                                class="d-block w-100"
                                                                                                alt="Image 1">
                                                                                        </div>
                                                                                    @endif
                                                                                    <?php $i++; ?>
                                                                                @endforeach

                                                                            </div> <!-- /.carousel inner -->

                                                                            <a class="carousel-control-prev"
                                                                                href="#myCarousel" role="button"
                                                                                data-slide="prev">
                                                                                <span class="carousel-control-prev-icon"
                                                                                    aria-hidden="true"></span>
                                                                                <span class="sr-only">Previous</span>
                                                                            </a>
                                                                            <a class="carousel-control-next"
                                                                                href="#myCarousel" role="button"
                                                                                data-slide="next">
                                                                                <span class="carousel-control-next-icon"
                                                                                    aria-hidden="true"></span>
                                                                                <span class="sr-only">Next</span>
                                                                            </a>
                                                                        </div> <!-- /.carousel slide -->

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                    </div> <!-- /.footer -->
                                                                </div>
                                                            </div>
                                                        </div> <!-- /.modal -->
                                                    </div>
                                                    <!-- /.col 12 level 1 images -->
                                                </div>
                                            @endif
                                            <!-- /. image / video row -->

                                        </div><!--/. Testing & Approval table div-->
                                        <!-- Factory table div-->
                                        <div class="table-responsive">
                                            <div class="row mx-1">
                                                <div class="col-md-12">
                                                    <h4>Factory</h4>
                                                </div>
                                            </div>

                                            <table class="table table-striped" data-role="content"
                                                data-plugin="selectable" data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Factory RMA</td>
                                                        <td>not know</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Replacement Approved</td>
                                                        <td>{{ $rmaList->strFactory_ReplacementApproved ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>To be Returned</td>
                                                        <td>not know</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Material Sent</td>
                                                        <td>not know</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Replacement Received</td>
                                                        <td>not know</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>HFI Status</td>
                                                        <td>not know</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!--/. Factory table div-->
                                        <!-- Customer Status table div-->
                                        <div class="table-responsive">
                                            <div class="row mx-1">
                                                <div class="col-md-12">
                                                    <h4>Customer Status</h4>
                                                </div>
                                            </div>

                                            <table class="table table-striped" data-role="content"
                                                data-plugin="selectable" data-row-selectable="true">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Label</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Material Dispatched</td>
                                                        <td>{{ $rmaList->strMaterialDispatched ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Material Dispatched Date</td>
                                                        <td>
                                                            {{ $rmaList->strMaterialDispatchedDate && $rmaList->strMaterialDispatchedDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strMaterialDispatchedDate))
                                                                : '-' }}

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Status</td>
                                                        <td>{{ $rmaList->strStatus ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!--/. Customer Status table div-->
                                    </div><!--/. col 6-->

                                    {{-- ravi start --}}
                                    <div class="col-lg-6 col-md-12">
                                        <!-- status table -->
                                        <div class="table-responsive">
                                            <table class="table table-striped" data-role="content"
                                                data-plugin="selectable" data-row-selectable="true">
                                                <thead class="bg-grey-100">
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Status</th>
                                                        <th>Date of Updates</th>
                                                        <th>Submitted by</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td class="ws-break">Open</td>
                                                        <td>22-05-2022 <br>
                                                            <small class="position-static">18:22:58</small>
                                                        </td>
                                                        <td>Namita Das</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Open</td>
                                                        <td>22-05-2022 <br>
                                                            <small class="position-static">23:18:00</small>
                                                        </td>
                                                        <td>Namita Das</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Closed</td>
                                                        <td>23-05-2022 <br>
                                                            <small class="position-static">10:18:05</small>
                                                        </td>
                                                        <td>Srikant Reddy</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!--/. table 1 resonsive div-->

                                        <!-- Additional RMA Details 1 -->

                                        @php
                                            $counter = 1;
                                        @endphp

                                        @foreach ($rmadetailList as $item)
                                            @php
                                                $documents = DB::table('rma_docs')
                                                    ->orderBy('rma_docs_id', 'desc')
                                                    ->where([
                                                        'iStatus' => 1,
                                                        'isDelete' => 0,
                                                        'rma_id' => $item->rma_id,
                                                        'rma_detail_id' => $item->rma_detail_id,
                                                    ])
                                                    ->get();
                                            @endphp
                                            <div class="row mx-1">
                                                <div class="col-md-9 col-xs-8">
                                                    <h4>Additional RMA Details {{ $counter }}</h4>
                                                    <p>Latest Updated by: Vaibhav P</p>
                                                </div>
                                                <div class="col-md-3 col-xs-4 text-right mt-2">
                                                    <button type="button" class="btn btn-success text-uppercase"
                                                        data-toggle="modal" data-target="#edit-info"
                                                        onclick="getEditData(<?= $item->rma_id ?>)">
                                                        edit info
                                                    </button>
                                                </div>

                                                <div class="accordion px-0" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFive">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                                                aria-expanded="false" aria-controls="collapseFive">
                                                                <span class="text-orange">View Details</span>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseFive" class="accordion-collapse collapse"
                                                            aria-labelledby="headingFive"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body px-0">
                                                                <!-- Additional RMA ravi1 other information table div -->
                                                                <div class="table-responsive">
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Other Information123</h4>
                                                                        </div>
                                                                    </div>

                                                                    <table class="table table-striped" data-role="content"
                                                                        data-plugin="selectable"
                                                                        data-row-selectable="true">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr. No</th>
                                                                                <th>Label</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>RMA Registration Date</td>
                                                                                <td>{{ $item->strRMARegistrationDate && $item->strRMARegistrationDate != '0000-00-00'
                                                                                    ? date('d-m-Y', strtotime($item->strRMARegistrationDate))
                                                                                    : '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Item</td>
                                                                                <td> {{ $item->strItem ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Item Description</td>
                                                                                <td>{{ $item->strItemDescription ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Serial No</td>
                                                                                <td>{{ $item->strSerialNo ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Date Code</td>
                                                                                <td>{{ $item->strDateCode ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>6</td>
                                                                                <td>In warranty</td>
                                                                                <td>{{ $item->strInwarranty ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>7</td>
                                                                                <td>Quantity</td>
                                                                                <td>{{ $item->strQuantity ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>8</td>
                                                                                <td>Select System</td>
                                                                                <td>{{ $item->system_name ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            {{-- <tr>
                                                                                <td>9</td>
                                                                                <td>Model Number</td>
                                                                                <td>ECV5847-87</td>
                                                                            </tr> --}}
                                                                            <tr>
                                                                                <td>9</td>
                                                                                <td>Fault description</td>
                                                                                <td>{{ $item->strFaultdescription ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>10</td>
                                                                                <td>Facts</td>
                                                                                <td>{{ $item->strFacts ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>11</td>
                                                                                <td>Additional Details</td>
                                                                                <td>{{ $item->strAdditionalDetails ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>

                                                                </div>

                                                                <!--/. other information table div-->

                                                                <!-- Testing & Approval table div-->
                                                                <div class="table-responsive">
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Testing & Approval</h4>
                                                                        </div>
                                                                    </div>

                                                                    <table class="table table-striped" data-role="content"
                                                                        data-plugin="selectable"
                                                                        data-row-selectable="true">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr. No</th>
                                                                                <th>Label</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Material Received</td>
                                                                                <td>{{ $item->strMaterialReceived ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Material Received Date</td>
                                                                                <td> {{ $item->strMaterialReceivedDate && $item->strMaterialReceivedDate != '0000-00-00'
                                                                                    ? date('d-m-Y', strtotime($item->strMaterialReceivedDate))
                                                                                    : '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Testing</td>
                                                                                <td>{{ $item->strTesting ?? '-' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Testing Complete Date</td>
                                                                                <td>{{ $item->strTestingCompleteDate && $item->strTestingCompleteDate != '0000-00-00'
                                                                                    ? date('d-m-Y', strtotime($item->strTestingCompleteDate))
                                                                                    : '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            {{-- <tr>
                                                                                <td>4</td>
                                                                                <td>Testing Result</td>
                                                                                <td>Product Failure</td>
                                                                            </tr> --}}
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Fault Covered in Warranty</td>
                                                                                <td>{{ $item->strFaultCoveredinWarranty ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Replacement Approved</td>
                                                                                <td>{{ $item->strReplacementApproved ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>6</td>
                                                                                <td>Replacement Reason</td>
                                                                                <td>{{ $item->strReplacementReason ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>7</td>
                                                                                <td>Comments</td>
                                                                                <td>{{ $item->strReplacementReason ?? '-' }}
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>


                                                                    <!-- image / video row -->
                                                                @if (count($documents) > 0)
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Testing images, videos & Docs</h4>
                                                                            <div class="row mt-4">
                                                                            @foreach ($Documents as $index => $item)
                                                                                @php
                                                                                    $extension = pathinfo(
                                                                                        $item->strDocs,
                                                                                        PATHINFO_EXTENSION,
                                                                                    );
                                                                                @endphp








                                                                                </div><!-- /.col -->

                                                                                <div
                                                                                    class="col-md-3 col-sm-4 col-xs-6 gallery-box text-center">
                                                                                    <a href="https://view.officeapps.live.com/op/embed.aspx?src=www.excellentcomputers.co.in/Clients/mas-solution/Test_word.docx"
                                                                                        target="_blank">
                                                                                        <img src="../global/assets/images/reference/photo/pdf.jpg"
                                                                                            alt="document"
                                                                                            title="name of the doument">
                                                                                    </a>
                                                                                    <div class="text-center del-img mt-2">
                                                                                        <i class="mas-trash mas-1x"
                                                                                            title="Delete"></i>
                                                                                    </div>
                                                                                </div><!-- /.col -->

                                                                            </div> <!-- /.row -->
                                                                            <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                                                            <!-- Modal -->
                                                                            <div class="modal fade" id="myModal"
                                                                                tabindex="-1" aria-hidden="true">
                                                                                <div class="modal-dialog modal-lg">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div> <!-- /.header -->
                                                                                        <div class="modal-body">
                                                                                            <!-- Carousel -->
                                                                                            <div id="myCarousel"
                                                                                                class="carousel slide">
                                                                                                <ol
                                                                                                    class="carousel-indicators">
                                                                                                    <li data-target="#myCarousel"
                                                                                                        data-slide-to="0"
                                                                                                        class="active">
                                                                                                    </li>
                                                                                                    <li data-target="#myCarousel"
                                                                                                        data-slide-to="1">
                                                                                                    </li>
                                                                                                    <li data-target="#myCarousel"
                                                                                                        data-slide-to="5">
                                                                                                    </li>
                                                                                                    <li data-target="#myCarousel"
                                                                                                        data-slide-to="3">
                                                                                                    </li>
                                                                                                    <li data-target="#myCarousel"
                                                                                                        data-slide-to="2">
                                                                                                    </li>
                                                                                                </ol>
                                                                                                <div
                                                                                                    class="carousel-inner">
                                                                                                    <div
                                                                                                        class="carousel-item active">
                                                                                                        <img src="../global/assets/images/customer/photo/1.jpg"
                                                                                                            class="d-block w-100"
                                                                                                            alt="Image 1">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="carousel-item">
                                                                                                        <div
                                                                                                            class="embed-responsive embed-responsive-16by9">
                                                                                                            <img src="../global/assets/images/customer/photo/4.jpg"
                                                                                                                class="d-block w-100"
                                                                                                                alt="Image 4">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="carousel-item">
                                                                                                        <img src="../global/assets/images/customer/photo/2.jpg"
                                                                                                            class="d-block w-100"
                                                                                                            alt="Image 2">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="carousel-item">
                                                                                                        <div
                                                                                                            class="embed-responsive embed-responsive-16by9">
                                                                                                            <iframe
                                                                                                                class="embed-responsive-item"
                                                                                                                src="../global/assets/images/customer/video/2185490981.mp4"
                                                                                                                allowfullscreen></iframe>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="carousel-item">
                                                                                                        <div
                                                                                                            class="embed-responsive embed-responsive-16by9">
                                                                                                            <iframe
                                                                                                                class="embed-responsive-item"
                                                                                                                src="../global/assets/images/customer/video/2185490981.mp4"
                                                                                                                allowfullscreen></iframe>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- /.carousel inner -->
                                                                                                <a class="carousel-control-prev"
                                                                                                    href="#myCarousel"
                                                                                                    role="button"
                                                                                                    data-slide="prev">
                                                                                                    <span
                                                                                                        class="carousel-control-prev-icon"
                                                                                                        aria-hidden="true"></span>
                                                                                                    <span
                                                                                                        class="sr-only">Previous</span>
                                                                                                </a>
                                                                                                <a class="carousel-control-next"
                                                                                                    href="#myCarousel"
                                                                                                    role="button"
                                                                                                    data-slide="next">
                                                                                                    <span
                                                                                                        class="carousel-control-next-icon"
                                                                                                        aria-hidden="true"></span>
                                                                                                    <span
                                                                                                        class="sr-only">Next</span>
                                                                                                </a>
                                                                                            </div> <!-- /.carousel slide -->

                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Close</button>
                                                                                        </div> <!-- /.footer -->
                                                                                    </div>
                                                                                </div>
                                                                            </div> <!-- /.modal -->
                                                                        </div> <!-- /.col 12 level 1 images -->
                                                                    </div>
                                                                   @endforeach
                                                                @endif
                                                                    <!-- /. image / video row -->
                                                                </div><!--/. Testing & Approval table div-->

                                                                <!-- Factory table div-->
                                                                <div class="table-responsive">
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Factory</h4>
                                                                        </div>
                                                                    </div>

                                                                    <table class="table table-striped" data-role="content"
                                                                        data-plugin="selectable"
                                                                        data-row-selectable="true">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr. No</th>
                                                                                <th>Label</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Factory RMA No</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Material Received</td>
                                                                                <td>Yes</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Material Received Date</td>
                                                                                <td>12-04-2024</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Testing</td>
                                                                                <td>In Progress</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Testing Complete Date</td>
                                                                                <td>15-04-2024</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>6</td>
                                                                                <td>Fault Covered in Warranty</td>
                                                                                <td>Yes</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>7</td>
                                                                                <td>Replacement Approved</td>
                                                                                <td>Yes</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>8</td>
                                                                                <td>Replacement Reason</td>
                                                                                <td>Warranty</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>9</td>
                                                                                <td>Status</td>
                                                                                <td>HFI</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>10</td>
                                                                                <td>Comments</td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>

                                                                    <!-- factory documents -->
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Documents</h4>
                                                                            <div class="row mt-4">
                                                                                <div
                                                                                    class="col-md-3 col-sm-4 col-xs-6 gallery-box text-center">
                                                                                    <a href="https://view.officeapps.live.com/op/embed.aspx?src=www.excellentcomputers.co.in/Clients/mas-solution/Test_word.docx"
                                                                                        target="_blank">
                                                                                        <img src="../global/assets/images/reference/photo/pdf.jpg"
                                                                                            alt="document"
                                                                                            title="name of the doument">
                                                                                    </a>
                                                                                    <div class="text-center del-img mt-2">
                                                                                        <i class="mas-trash mas-1x"
                                                                                            title="Delete"></i>
                                                                                    </div>
                                                                                </div><!-- /.col -->
                                                                            </div> <!-- /.row -->
                                                                        </div> <!-- /.col 12 level 1 images -->
                                                                    </div>
                                                                    <!-- /. factory documents row -->
                                                                </div><!--/. Factory table div-->

                                                                <!-- Customer Status table div-->
                                                                <div class="table-responsive">
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Customer Status</h4>
                                                                        </div>
                                                                    </div>

                                                                    <table class="table table-striped" data-role="content"
                                                                        data-plugin="selectable"
                                                                        data-row-selectable="true">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr. No</th>
                                                                                <th>Label</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Material Dispatched</td>
                                                                                <td>Yes</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Material Dispatched Date</td>
                                                                                <td>2-11-2024</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Status</td>
                                                                                <td>Closed</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Comments</td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div><!--/. Customer Status table div-->

                                                            </div><!-- /. accordion body -->
                                                        </div> <!-- /. collapse One -->
                                                    </div> <!-- /. accordion-item -->
                                                </div><!-- /. accordion -->
                                            </div>
                                            @php
                                                $counter++;
                                            @endphp
                                        @endforeach

                                    </div><!--/. col 6-->
                                    {{-- ravi --}}

                                </div>
                            </div>
                        </div><!--card end-->
                    </div>
                </div><!-- end row -->
            </div>
            <!-- content-wrapper ends -->
            <!-- modal one edit info start -->
            <div class="modal fade bd-example-modal-lg" id="edit-info" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Other Information for
                                {{ str_pad($rmaList->rma_id, 4, '0', STR_PAD_LEFT) }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="was-validated" action="{{ route('rma.rma_update') }}" method="post">
                            @csrf
                            <input type="hidden" name="rma_id" id="rma_id">
                            <div class="modal-body">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                Other Information
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">

                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group" id="basicExample1">
                                                            <label>RMA Registration Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date1"
                                                                    name="strRMARegistrationDate" value="21-04-2024" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Item</label>
                                                            <input type="text" id="editstrItem" class="form-control"
                                                                name="Item" />
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Item Description</label>
                                                            <input type="text" class="form-control" value=""
                                                                name="strItemDescription" id="editstrItemDescription" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Serial No</label>
                                                            <input type="text" class="form-control" name="strSerialNo"
                                                                id="editstrSerialNo" value="" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Date Code</label>
                                                            <input type="text" class="form-control" name="strDateCode"
                                                                id="editstrDateCode" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>In warranty</label>
                                                            <select class="js-example-basic-single" name="strInwarranty"
                                                                style="width: 100%;"id="EditstrInwarranty" required>

                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Quantity</label>
                                                            <input type="text" class="form-control" value=""
                                                                name="strQuantity" id="editstrQuantity" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Select System</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                name="strSelectSystem" id="editstrSelectSystem" required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Fault description</label>
                                                            <input type="text" class="form-control"
                                                                name="strFaultdescription" id="ditstrFaultdescription" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Facts</label>
                                                            <input type="text" class="form-control" name="strFacts"
                                                                id="editstrFacts" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Additional Details</label>
                                                            <input type="text" class="form-control"
                                                                name="strAdditionalDetails"
                                                                id="editstrAdditionalDetails" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                </div> <!-- /.row -->

                                            </div><!-- /. accordion body -->
                                        </div> <!-- /. collapse One -->
                                    </div> <!-- /. accordion-item -->

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                aria-expanded="false" aria-controls="collapseTwo">
                                                Testing & Approval
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">

                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Material Received</label>
                                                            <select class="js-example-basic-single"
                                                                id="EditstrMaterialReceived" class="form-control"
                                                                style="width: 100%;" name="strMaterialReceived">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-4" id="strMaterialReceivedDateDiv"
                                                        style="display: none;">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Material Received Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date2"
                                                                    name="Material_Received_Date"
                                                                    placeholder="dd-mm-yyyy" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Testing</label>
                                                            <select class="js-example-basic-single" id="EditstrTesting"
                                                                class="form-control" style="width: 100%;"
                                                                name="strTesting">
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4" id="strTestingCompleteDateDiv"
                                                        style="display: none;">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Testing Complete Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date3"
                                                                    name="strTestingCompleteDate" placeholder="" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Fault Covered in Warranty</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="EditFault_Covered" name="strFaultCoveredinWarranty"
                                                                required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Replacement Approved</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="Replacement_Approved" name="strReplacementApproved"
                                                                required>

                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Replacement Reason</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="EditReplacement_Reason" name="strReplacementReason"
                                                                required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Images</label>
                                                            <input class="form-control py-6" type="file"
                                                                name="strImages" id="formFileMultiple" multiple />
                                                        </div>
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Video</label>
                                                            <input class="form-control py-6" type="file"
                                                                name="strVideos" id="formFileMultiple" multiple />
                                                        </div>
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Docs</label>
                                                            <input class="form-control py-6" type="file"
                                                                name="strDocs" id="formFileMultiple" multiple />
                                                        </div>
                                                    </div> <!-- /.col -->

                                                </div> <!-- /.row -->

                                            </div><!-- /. accordion body -->
                                        </div><!-- /. collapse Two -->
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                aria-expanded="false" aria-controls="collapseThree">
                                                Factory
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse"
                                            aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">

                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Material Received</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                name="strFactory_MaterialReceived"
                                                                id="editMaterial_Received" required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4" id="strFactory_MaterialReceivedDateDiv"
                                                        style="display: none;">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Material Received Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date7"
                                                                    name="Factory_Material_Received_Date"
                                                                    placeholder="dd-mm-yyyy" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Testing</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="EditFactory_Testing" name="strFactory_Testing"
                                                                required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4" id="strFactory_TestingCompleteDateDiv"
                                                        style="display: none;">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Testing Complete Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date8"
                                                                    name="strFactory_TestingCompleteDate"
                                                                    placeholder="dd-mm-yyyy" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Fault Covered in Warranty</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="EditFault_Covered_in_Warranty"
                                                                name="strFactory_FaultCoveredinWarranty" required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Replacement Approved</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="EditFactory_Replacement_Approved"
                                                                name="strFactory_ReplacementApproved" required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Replacement Reason</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="EditFactory_Replacement_Reason"
                                                                name="strFactory_ReplacementReason" required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                </div> <!-- /.row -->

                                            </div><!-- /. accordion body -->
                                        </div><!-- /. collapse Three -->
                                    </div><!-- /. accordion-item -->

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingFour">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                aria-expanded="false" aria-controls="collapseFour">
                                                Customer Status
                                            </button>
                                        </h2>
                                        <div id="collapseFour" class="accordion-collapse collapse"
                                            aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">

                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Material Dispatched</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="EditMaterial_Dispatched" name="strMaterialDispatched"
                                                                required>

                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4" id="strMaterialDispatchedDateDiv"
                                                        style="display: none;">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Material Dispatched Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date9"
                                                                    name="strMaterialDispatchedDate" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="Editcus_Status" name="strStatus" required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                </div> <!-- /.row -->

                                            </div><!-- /. accordion body -->
                                        </div><!-- /. collapse Four -->
                                    </div><!-- /. accordion-item -->

                                </div><!-- /. accordion -->
                            </div><!--main body-->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary">Cancle</button>
                                <button type="submit" class="btn btn-fill btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /. modal one edit info End -->

            <!------------------------------------------- Additional RMA modal  edit info start25 --------------------------------------->
            <div class="modal fade bd-example-modal-lg" id="Edit_info" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Other Information for
                                {{ str_pad($rmaList->rma_id, 4, '0', STR_PAD_LEFT) }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            Other Information
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <form class="was-validated" action="" method="post">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group" id="basicExample">
                                                            <label>RMA Registration Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text"
                                                                    id="Additional_rma"
                                                                    name="Additional_rma_registration_date"
                                                                    value="21-04-2024" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Item</label>
                                                            <input type="text" class="form-control" value=""
                                                                id="Additionalrma_Item" name="Additional_rma_Item" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Item Description</label>
                                                            <input type="text" class="form-control" value=""
                                                                id="Additional_rma_Item_Description"
                                                                name="Additionalrma_Item_Description" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Serial No</label>
                                                            <input type="text" class="form-control" value=""
                                                                id="Additional_rmaSerialNo"
                                                                name="Additional_rma_SerialNo" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Date Code</label>
                                                            <input type="text" class="form-control"
                                                                id="Additional_rmaDateCode"
                                                                name="Additional_rma_DateCode" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>In warranty</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="Additional_rma_Inwarranty" name="strInwarranty"
                                                                required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Quantity</label>
                                                            <input type="text" class="form-control" value=""
                                                                id="Additional_rmaQuantity" name="strQuantity" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Select System</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                id="Additional_rmaSelectSystem" name="strSelectSystem"
                                                                required>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    {{-- <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Model Number</label>
                                                            <input type="text" class="form-control" id="" name="" />
                                                        </div>
                                                    </div>  --}}
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Fault description</label>
                                                            <input type="text" class="form-control"
                                                                id="Additional_rmaFaultdescription"
                                                                name="strFaultdescription" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Facts</label>
                                                            <input type="text" class="form-control"
                                                                id="Additional_rmastrFacts" name="strFacts" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Additional Details</label>
                                                            <input type="text" class="form-control"
                                                                id="Additional_rma_strAdditionalDetails"
                                                                name="strAdditionalDetails" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                </div> <!-- /.row -->
                                            </form>
                                        </div><!-- /. accordion body -->
                                    </div> <!-- /. collapse One -->
                                </div> <!-- /. accordion-item -->

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                            Testing & Approval
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <form class="was-validated" action="" method="post">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Material Received</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI" selected>Yes</option>
                                                                <option value="SM">No</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Material Received Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date1"
                                                                    name="date1" placeholder="dd-mm-yyyy" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Testing</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI">Done</option>
                                                                <option value="SM" selected>In Progress</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Testing Complete Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date2"
                                                                    name="date2" placeholder="15-11-2024" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Testing Result</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI">No Fault Found</option>
                                                                <option value="SM">Customer Liability</option>
                                                                <option value="SM">Product Failure</option>
                                                                <option value="SM">Programming Issue</option>
                                                                <option value="SM">Repair Locally</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Fault Covered in Warranty</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI">Yes</option>
                                                                <option value="SM">No</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Replacement Approved</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI" selected>Yes</option>
                                                                <option value="SM">No</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Replacement Reason</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI" selected>Warranty</option>
                                                                <option value="SM">Sales Call</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Images</label>
                                                            <input class="form-control py-6" type="file"
                                                                id="formFileMultiple" multiple />
                                                        </div>
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Video</label>
                                                            <input class="form-control py-6" type="file"
                                                                id="formFileMultiple" multiple />
                                                        </div>
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Docs</label>
                                                            <input class="form-control py-6" type="file"
                                                                id="formFileMultiple" multiple />
                                                        </div>
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Comments</label>
                                                            <textarea class="form-control" rows="4"></textarea>
                                                        </div>
                                                    </div> <!-- /.col -->

                                                </div> <!-- /.row -->
                                            </form>
                                        </div><!-- /. accordion body -->
                                    </div><!-- /. collapse Two -->
                                </div><!-- /. accordion-item -->

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
                                            Factory
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <form class="was-validated" action="" method="post">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Factory RMA No</label>
                                                            <input type="text" class="form-control" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Material Received</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI">Yes</option>
                                                                <option value="SM">No</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Material Received Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date7"
                                                                    name="date7" placeholder="dd-mm-yyyy" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Testing</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI">Done</option>
                                                                <option value="SM">In Progress</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Testing Complete Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text" id="date2"
                                                                    name="date2" placeholder="dd-mm-yyyy" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Fault Covered in Warranty</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI">Yes</option>
                                                                <option value="SM">No</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Replacement Approved</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI">Yes</option>
                                                                <option value="SM" selected>No</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Replacement Reason</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                <option value="HI">Warranty</option>
                                                                <option value="SM" selected>Sales Call</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select
                                                                    --
                                                                </option>
                                                                <option value="HI">HFI</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Docs</label>
                                                            <input class="form-control py-6" type="file"
                                                                id="formFileMultiple" multiple />
                                                        </div>
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Comments</label>
                                                            <textarea class="form-control" rows="4"></textarea>
                                                        </div>
                                                    </div> <!-- /.col -->

                                                </div> <!-- /.row -->
                                            </form>
                                        </div><!-- /. accordion body -->
                                    </div><!-- /. collapse Three -->
                                </div><!-- /. accordion-item -->

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFour">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                            aria-expanded="false" aria-controls="collapseFour">
                                            Customer Status
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse"
                                        aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <form class="was-validated" action="" method="post">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Material Dispatched</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select
                                                                    --
                                                                </option>
                                                                <option value="HI">Yes</option>
                                                                <option value="SM">No</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group" id="basicExample">
                                                            <label>Material Dispatched Date</label>
                                                            <div class="input-group date datepicker"
                                                                data-date-format="dd-mm-yyyy">
                                                                <input class="form-control" type="text"
                                                                    id="date3" name="date3" />
                                                                <span class="input-group-addon"><i
                                                                        class="mas-month-calender mas-105x"></i></span>
                                                            </div>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                required>
                                                                <option label="Please Select" value="">-- Select
                                                                    --</option>
                                                                <option value="HI">Open</option>
                                                                <option value="SM">Closed</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Comments</label>
                                                            <textarea class="form-control" rows="4"></textarea>
                                                        </div>
                                                    </div> <!-- /.col -->

                                                </div> <!-- /.row -->
                                            </form>
                                        </div><!-- /. accordion body -->
                                    </div><!-- /. collapse Four -->
                                </div><!-- /. accordion-item -->

                            </div><!-- /. accordion -->
                        </div><!--main body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary">Cancle</button>
                            <button type="button" class="btn btn-fill btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------- /. Additional RMA Modal Edit Info End ------------------------------->



            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022
                            Mas Solutions. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                                href="#"> Excellent Computers </a> </span>
                    </div>
                </div>
            </footer>
            <!-- partial -->

        </div>
        <!-- main-panel ends -->
    </div>






    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/custom.js') }}"></script>

    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>

    <!--form validation-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.validate.min.js') }}"></script>

    <!--date picker-->
    <script src="{{ asset('global/assets/vendors/date-picker/bootstrap-datepicker.js') }}"></script>

    <!--table plugin-->
    <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
    </script>
    <script type="text/javascript">
        var $table = $('#fresh-table'),

            full_screen = false;

        $().ready(function() {
            $table.bootstrapTable({
                toolbar: ".toolbar",

                showDownload: true,
                showRefresh: true,
                search: true,
                showColumns: true,
                pagination: true,
                striped: true,
                pageSize: 8,
                pageList: [8, 10, 25, 50, 100],

                formatShowingRows: function(pageFrom, pageTo, totalRows) {
                    //do nothing here, we don't want to show the text "showing x of y from..."
                },
                formatRecordsPerPage: function(pageNumber) {
                    return pageNumber + " rows visible";
                },
                icons: {
                    download: 'mas-download',
                    refresh: 'mas-refresh',
                    toggle: 'fa fa-th-list',
                    columns: 'mas-columns',
                    detailOpen: 'fa fa-plus-circle',
                    detailClose: 'fa fa-minus-circle'
                }
            });

            $(window).resize(function() {
                $table.bootstrapTable('resetView');
            });

            window.operateEvents = {
                'click .like': function(e, value, row, index) {
                    alert('You click like icon, row: ' + JSON.stringify(row));
                    console.log(value, row, index);
                },
                'click .edit': function(e, value, row, index) {
                    alert('You click edit icon, row: ' + JSON.stringify(row));
                    console.log(value, row, index);
                },
                'click .remove': function(e, value, row, index) {
                    $table.bootstrapTable('remove', {
                        field: 'id',
                        values: [row.id]
                    });
                }
            };
        });
    </script>

    <script>
        $("#iComplaintId").on("change", function(e) {

            $.ajax({
                type: 'POST',
                url: "{{ route('rma.get_details') }}",
                data: {
                    iTicketId: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);

                    // Ensure the response contains ProjectName
                    if (response) {
                        $("#strProjectName").val(response.ProjectName);

                        if ($("#strDistributor option[value='" + response.iDistributorId + "']")
                            .length) {
                            $("#strDistributor").val(response.iDistributorId).trigger('change');
                        }

                        //$("#strDistributor").val(response.iDistributorId);
                    } else {
                        $("#strProjectName").val("");
                        $("#strDistributor").val("");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    //alert("Failed to fetch ticket details.");

                    $("#strProjectName").val("");
                    $("#strDistributor").val("");
                },
            });

        });

        $("#EditstrMaterialReceived").on("change", function() {
            let getvalue = this.value;
            if (getvalue === "No") {
                $("#strMaterialReceivedDateDiv").hide();
            } else if (getvalue === "Yes") {
                $("#strMaterialReceivedDateDiv").show();
            } else {
                $("#strMaterialReceivedDateDiv").hide();
            }
        });
        $(document).ready(function() {
            $("#EditstrMaterialReceived").trigger("change");
        });

        $("#EditstrTesting").on("change", function(e) {
            let getvalue = this.value;
            if (getvalue == "In Progress") {
                $("#strTestingCompleteDateDiv").css("display", "none");
            } else if (getvalue == "Done") {
                $("#strTestingCompleteDateDiv").css("display", "block");
            } else {
                $("#strTestingCompleteDateDiv").css("display", "none");
            }

        });

        $("#editMaterial_Received").on("change", function(e) {

            let getvalue = this.value;

            if (getvalue == "No") {
                $("#strFactory_MaterialReceivedDateDiv").css("display", "none");
            } else if (getvalue == "Yes") {
                $("#strFactory_MaterialReceivedDateDiv").css("display", "block");
            } else {
                $("#strFactory_MaterialReceivedDateDiv").css("display", "none");
            }

        });

        $("#EditFactory_Testing").on("change", function(e) {

            let getvalue = this.value;

            if (getvalue == "In Progress") {
                $("#strFactory_TestingCompleteDateDiv").css("display", "none");
            } else if (getvalue == "Done") {
                $("#strFactory_TestingCompleteDateDiv").css("display", "block");
            } else {
                $("#strFactory_TestingCompleteDateDiv").css("display", "none");
            }

        });

        $("#EditMaterial_Dispatched").on("change", function(e) {

            let getvalue = this.value;

            if (getvalue == "No") {
                $("#strMaterialDispatchedDateDiv").css("display", "none");
            } else if (getvalue == "Yes") {
                $("#strMaterialDispatchedDateDiv").css("display", "block");
            } else {
                $("#strMaterialDispatchedDateDiv").css("display", "none");
            }

        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- first model --}}
    <script>
        $(document).ready(function() {
            $("#editinfo").click(function() {
                var rma_id = $("#put_rma_id").val();

                $.ajax({
                    type: 'GET',
                    url: "{{ route('rma.rma_edit') }}",
                    data: {
                        rma_id: rma_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        var obj = response[0];
                        $("#rma_id").val(obj.rma_id);
                        $("#basicExample1").val(obj.strRMARegistrationDate);
                        $("#editstrItem").val(obj.strItem);
                        $("#editstrItemDescription").val(obj.strItemDescription);
                        $("#editstrSerialNo").val(obj.strSerialNo);
                        $("#editstrDateCode").val(obj.strDateCode);
                        $("#editstrQuantity").val(obj.strQuantity);
                        $("#ditstrFaultdescription").val(obj.strFaultdescription);
                        $("#editstrFacts").val(obj.strFacts);
                        $("#editstrAdditionalDetails").val(obj.strAdditionalDetails);
                        //date set first from start
                        var Rma_registration_date = obj.strRMARegistrationDate;
                        if (Rma_registration_date) {
                            var dateParts = Rma_registration_date.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date1").val(
                                formattedDate);
                        } else {
                            $("#date1").val('');
                        }
                        //
                        var materialReceivedDate = obj.strMaterialReceivedDate;
                        if (materialReceivedDate) {
                            var dateParts = materialReceivedDate.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date2").val(
                                formattedDate);
                        } else {
                            $("#date2").val('');
                        }
                        //
                        var Testing_Complete_Date = obj.strTestingCompleteDate;
                        if (Testing_Complete_Date) {
                            var dateParts = Testing_Complete_Date.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date3").val(
                                formattedDate);
                        } else {
                            $("#date3").val('');
                        }
                        //date set second from start
                        //
                        var Factory_Received_Date = obj.strFactory_MaterialReceivedDate;
                        if (Factory_Received_Date) {
                            var dateParts = Factory_Received_Date.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date7").val(
                                formattedDate);
                        } else {
                            $("#date7").val('');
                        }
                        //
                        //
                        var Factory_MaterialReceivedDate = obj.strFactory_TestingCompleteDate;
                        if (Factory_MaterialReceivedDate) {
                            var dateParts = Factory_MaterialReceivedDate.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date8").val(
                                formattedDate);
                        } else {
                            $("#date8").val('');
                        }
                        //
                        //
                        var strMaterialDispatchedDate = obj.strMaterialDispatchedDate;
                        if (strMaterialDispatchedDate) {
                            var dateParts = strMaterialDispatchedDate.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date9").val(
                                formattedDate);
                        } else {
                            $("#date9").val('');
                        }
                        //

                        var warrantyValue = response[1];
                        $("#EditstrInwarranty").html(warrantyValue);

                        var Materialhtml = response[2];
                        if (obj.strMaterialReceived == "Yes") {
                            $("#strMaterialReceivedDateDiv").show();
                        } else if (obj.strMaterialReceived == "No") {
                            $("#strMaterialReceivedDateDiv").hide();
                        } else {
                            $("#strMaterialReceivedDateDiv").hide();
                        }
                        $("#EditstrMaterialReceived").html(Materialhtml);


                        var systemhtml = response[3];

                        $("#editstrSelectSystem").html(systemhtml);

                        var Testinghtml = response[4];
                        if (obj.strTesting == 'Done') {
                            $("#strTestingCompleteDateDiv").show();
                        } else if (obj.strTesting == "In Progress") {
                            $("#strTestingCompleteDateDiv").hide();
                        } else {
                            $("#strTestingCompleteDateDiv").hide();
                        }
                        $("#EditstrTesting").html(Testinghtml);

                        var Fault_Covered_html = response[5];
                        $("#EditFault_Covered").html(Fault_Covered_html);

                        var Replacement_Approved_html = response[6];
                        $("#Replacement_Approved").html(Replacement_Approved_html);

                        var Replacement_Reason_html = response[7];
                        $("#EditReplacement_Reason").html(Replacement_Reason_html);

                        var Material_Received_html = response[8];
                        if (obj.strFactory_MaterialReceived == 'Yes') {
                            $("#strFactory_MaterialReceivedDateDiv").show();
                        } else if (obj.strFactory_MaterialReceived == "No") {
                            $("#strFactory_MaterialReceivedDateDiv").hide();
                        } else {
                            $("#strFactory_MaterialReceivedDateDiv").hide();
                        }
                        $("#editMaterial_Received").html(Material_Received_html);

                        var Factory_Testing_html = response[9];
                        if (obj.strFactory_Testing == 'Done') {
                            $("#strFactory_TestingCompleteDateDiv").show();
                        } else if (obj.strFactory_Testing == "In Progress") {
                            $("#strFactory_TestingCompleteDateDiv").hide();
                        } else {
                            $("#strFactory_TestingCompleteDateDiv").hide();
                        }
                        $("#EditFactory_Testing").html(Factory_Testing_html);

                        var Fault_Covered_in_Warranty = response[10];
                        $("#EditFault_Covered_in_Warranty").html(Fault_Covered_in_Warranty);

                        var Factory_Replacement_Approved = response[11];
                        $("#EditFactory_Replacement_Approved").html(
                            Factory_Replacement_Approved);

                        var Factory_Replacement_Reason_html = response[12];
                        $("#EditFactory_Replacement_Reason").html(
                            Factory_Replacement_Reason_html);

                        var Material_Dispatched = response[13];
                        if (obj.strMaterialDispatched == "Yes") {
                            $("#strMaterialDispatchedDateDiv").show();
                        } else if (obj.strMaterialDispatched == "No") {
                            $("#strMaterialDispatchedDateDiv").hide();
                        } else {
                            $("#strMaterialDispatchedDateDiv").hide();
                        }
                        $("#EditMaterial_Dispatched").html(Material_Dispatched);
                        var cus_Status = response[14];
                        $("#Editcus_Status").html(cus_Status);

                    },
                    error: function(xhr) {
                        console.error("Error: ", xhr.responseText);
                    }
                });
            });
        });
    </script>
    {{-- first model --}}

    {{-- Second model --}}
    <script>
        $(document).ready(function() {

            $("#edit_info").click(function() {
                var rma_id = $("#put_rma_id").val();
                $.ajax({
                    type: 'GET',
                    url: "{{ route('rma.Additional_rma_edit') }}",
                    data: {
                        rma_id: rma_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        var obj = response[0];
                        $("#Additional_rma_id").val(obj.rma_id);
                        $("#Additional_rma").val(obj.strRMARegistrationDate);
                        $("#Additionalrma_Item").val(obj.strItem);
                        $("#Additional_rma_Item_Description").val(obj.strItemDescription);
                        $("#Additional_rmaSerialNo").val(obj.strSerialNo);
                        $("#Additional_rmaDateCode").val(obj.strDateCode);
                        $("#Additional_rmaQuantity").val(obj.strQuantity);

                        $("#Additional_rmaFaultdescription").val(obj.strFaultdescription);
                        $("#Additional_rmastrFacts").val(obj.strFacts);
                        $("#Additional_rma_strAdditionalDetails").val(obj.strAdditionalDetails);
                        //date set first from start
                        var Rma_registration_date = obj.strRMARegistrationDate;
                        if (Rma_registration_date) {
                            var dateParts = Rma_registration_date.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#Additional_rma").val(
                                formattedDate);
                        } else {
                            $("#Additional_rma").val('');
                        }
                        //
                        var materialReceivedDate = obj.strMaterialReceivedDate;
                        if (materialReceivedDate) {
                            var dateParts = materialReceivedDate.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date2").val(
                                formattedDate);
                        } else {
                            $("#date2").val('');
                        }
                        //
                        var Testing_Complete_Date = obj.strTestingCompleteDate;
                        if (Testing_Complete_Date) {
                            var dateParts = Testing_Complete_Date.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date3").val(
                                formattedDate);
                        } else {
                            $("#date3").val('');
                        }
                        //date set second from start
                        //
                        var Factory_Received_Date = obj.strFactory_MaterialReceivedDate;
                        if (Factory_Received_Date) {
                            var dateParts = Factory_Received_Date.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date7").val(
                                formattedDate);
                        } else {
                            $("#date7").val('');
                        }
                        //
                        //
                        var Factory_MaterialReceivedDate = obj.strFactory_TestingCompleteDate;
                        if (Factory_MaterialReceivedDate) {
                            var dateParts = Factory_MaterialReceivedDate.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date8").val(
                                formattedDate);
                        } else {
                            $("#date8").val('');
                        }
                        //
                        //
                        var strMaterialDispatchedDate = obj.strMaterialDispatchedDate;
                        if (strMaterialDispatchedDate) {
                            var dateParts = strMaterialDispatchedDate.split(
                                '-');
                            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' +
                                dateParts[0];
                            $("#date9").val(
                                formattedDate);
                        } else {
                            $("#date9").val('');
                        }
                        //

                        var Additional_rma_warrantyHtml = response[1];
                        $("#Additional_rma_Inwarranty").html(Additional_rma_warrantyHtml);

                        var Materialhtml = response[2];
                        if (obj.strMaterialReceived == "Yes") {
                            $("#strMaterialReceivedDateDiv").show();
                        } else if (obj.strMaterialReceived == "No") {
                            $("#strMaterialReceivedDateDiv").hide();
                        } else {
                            $("#strMaterialReceivedDateDiv").hide();
                        }
                        $("#EditstrMaterialReceived").html(Materialhtml);


                        var Additional_rma_systemhtml = response[3];
                        $("#Additional_rmaSelectSystem").html(Additional_rma_systemhtml);

                        var Testinghtml = response[4];
                        if (obj.strTesting == 'Done') {
                            $("#strTestingCompleteDateDiv").show();
                        } else if (obj.strTesting == "In Progress") {
                            $("#strTestingCompleteDateDiv").hide();
                        } else {
                            $("#strTestingCompleteDateDiv").hide();
                        }
                        $("#EditstrTesting").html(Testinghtml);

                        var Fault_Covered_html = response[5];
                        $("#EditFault_Covered").html(Fault_Covered_html);

                        var Replacement_Approved_html = response[6];
                        $("#Replacement_Approved").html(Replacement_Approved_html);

                        var Replacement_Reason_html = response[7];
                        $("#EditReplacement_Reason").html(Replacement_Reason_html);

                        var Material_Received_html = response[8];
                        if (obj.strFactory_MaterialReceived == 'Yes') {
                            $("#strFactory_MaterialReceivedDateDiv").show();
                        } else if (obj.strFactory_MaterialReceived == "No") {
                            $("#strFactory_MaterialReceivedDateDiv").hide();
                        } else {
                            $("#strFactory_MaterialReceivedDateDiv").hide();
                        }
                        $("#editMaterial_Received").html(Material_Received_html);

                        var Factory_Testing_html = response[9];
                        if (obj.strFactory_Testing == 'Done') {
                            $("#strFactory_TestingCompleteDateDiv").show();
                        } else if (obj.strFactory_Testing == "In Progress") {
                            $("#strFactory_TestingCompleteDateDiv").hide();
                        } else {
                            $("#strFactory_TestingCompleteDateDiv").hide();
                        }
                        $("#EditFactory_Testing").html(Factory_Testing_html);

                        var Fault_Covered_in_Warranty = response[10];
                        $("#EditFault_Covered_in_Warranty").html(Fault_Covered_in_Warranty);

                        var Factory_Replacement_Approved = response[11];
                        $("#EditFactory_Replacement_Approved").html(
                            Factory_Replacement_Approved);

                        var Factory_Replacement_Reason_html = response[12];
                        $("#EditFactory_Replacement_Reason").html(
                            Factory_Replacement_Reason_html);

                        var Material_Dispatched = response[13];
                        if (obj.strMaterialDispatched == "Yes") {
                            $("#strMaterialDispatchedDateDiv").show();
                        } else if (obj.strMaterialDispatched == "No") {
                            $("#strMaterialDispatchedDateDiv").hide();
                        } else {
                            $("#strMaterialDispatchedDateDiv").hide();
                        }
                        $("#EditMaterial_Dispatched").html(Material_Dispatched);
                        var cus_Status = response[14];
                        $("#Editcus_Status").html(cus_Status);

                    },
                    error: function(xhr) {
                        console.error("Error: ", xhr.responseText);
                    }
                });
            });
        });
    </script>
    {{-- Second model --}}

    <script>
        $(document).ready(function() {
            $('.modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var index = button.data('index');
                var modal = $(this);
                var carousel = modal.find('.carousel');

                carousel.find('.carousel-item').removeClass('active').eq(index).addClass('active');
                carousel.find('.carousel-indicators li').removeClass('active').eq(index).addClass('active');
                carousel.carousel(index);
            });
        });
    </script>

@endsection
