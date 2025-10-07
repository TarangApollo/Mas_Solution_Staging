@extends('layouts.wladmin')
@section('title', 'Dashboard')
@section('content')

    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper pb-0">
            <div class="page-header">
                <h3>RMA Summary Information</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">RMA</li>
                        <li class="breadcrumb-item"><a href="{{ route('Wl_RMA.index') }}">RMA Summary</a></li>
                        <li class="breadcrumb-item active">Information </li>
                    </ol>
                </nav>
            </div><!--/. page header ends-->
            <!-- first row starts here -->
            <div class="row d-flex justify-content-center mb-5">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <h4 class="card-title mt-0">RMA Details for:{{ $rmaList->iRMANumber ?? 'RN0015' }}</h4>
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
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
                                                    <td>{{ $rmaList->iComplaintId == 0 ? 'Other' : $rmaList->iComplaintId ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Customer Company</td>
                                                    <td>{{ $rmaList->strCustomerCompany ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Customer Engineer</td>
                                                    <td>{{ $rmaList->strCustomerEngineer ?? '-' }}</td>
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
                                            <div class="col-md-12">
                                                <h4>Other Information</h4>
                                                @if (isset($rmaList->first_name))
                                                    <p>Latest Updated by:
                                                        {{ $rmaList->first_name . ' ' . $rmaList->last_name ?? 'not updated' }}
                                                    </p>
                                                @endif
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
                                                    <td>{{ $rmaList->strRMARegistrationDate && $rmaList->strRMARegistrationDate != '0000-00-00'
                                                        ? date('d-m-Y', strtotime($rmaList->strRMARegistrationDate))
                                                        : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Item</td>
                                                    <td>{{ $rmaList->strItem ?? '-' }} </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Item Description</td>
                                                    <td>{{ $rmaList->strItemDescription ?? '-' }}</td>
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
                                                {{-- <tr>
                                                    <td>8</td>
                                                    <td>Model Number</td>
                                                    <td>ECV5847-87</td>
                                                </tr> --}}
                                                <tr>
                                                    <td>8</td>
                                                    <td>Fault description</td>
                                                    <td>{{ $rmaList->strFaultdescription ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>9</td>
                                                    <td>Facts</td>
                                                    <td>{{ $rmaList->strFacts ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
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
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Material Received</td>
                                                    <td>{{ $rmaList->strMaterialReceived ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Material Received Date</td>
                                                    @if ($rmaList->strMaterialReceived == 'Yes')
                                                        <td>
                                                            {{ $rmaList->strMaterialReceivedDate && $rmaList->strMaterialReceivedDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strMaterialReceivedDate))
                                                                : '-' }}
                                                        </td>
                                                    @else
                                                        <td>-</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Testing</td>
                                                    <td>{{ $rmaList->strTesting ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>Testing Complete Date</td>
                                                    @if ($rmaList->strTesting == 'Done')
                                                        <td>
                                                            {{ $rmaList->strTestingCompleteDate && $rmaList->strTestingCompleteDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strTestingCompleteDate))
                                                                : '-' }}
                                                        </td>
                                                    @else
                                                        <td>-</td>
                                                    @endif
                                                </tr>
                                                {{-- <tr>
                                                    <td>4</td>
                                                    <td>Testing Result</td>
                                                    <td>Product Failure</td>
                                                </tr> --}}
                                                <tr>
                                                    <td>4</td>
                                                    <td>Fault Covered in Warranty</td>
                                                    <td>{{ $rmaList->strFaultCoveredinWarranty ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>Replacement Approved</td>
                                                    <td>{{ $rmaList->strReplacementApproved ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>7</td>
                                                    <td>Replacement Reason</td>
                                                    <td>{{ $rmaList->strReplacementReason ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>7</td>
                                                    <td>Comments</td>
                                                    <td>-</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <!-- image / video row img1 -->
                                        @if (count($Documents) > 0)
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
                                                            <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                <div data-toggle="modal" data-target="#myModal"
                                                                    data-index="{{ $index }}" class="modal-trigger">
                                                                    @if ($item->strImages)
                                                                        <img src="{{ asset('/RMADOC/images') . '/' . $item->strImages }}"
                                                                            alt="Image" data-target="#myCarousel"
                                                                            data-slide-to="{{ $index }}">
                                                                    @elseif ($item->strVideos)
                                                                        <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                                            alt="Video" data-target="#myCarousel"
                                                                            data-slide-to="{{ $index }}">
                                                                    @else
                                                                        <img src="{{ asset('global/assets/images/reference/photo/document.jpg') }}"
                                                                            alt="Document" data-target="#myCarousel"
                                                                            data-slide-to="{{ $index }}">
                                                                    @endif
                                                                </div>
                                                            </div>
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
                                                                        <?php $i = 1; ?>
                                                                        <ol class="carousel-indicators">
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
                                                                                            alt="Image">
                                                                                    </div>
                                                                                @elseif($item->strVideos)
                                                                                    <div
                                                                                        class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                        <div
                                                                                            class="embed-responsive embed-responsive-16by9">
                                                                                            <video controls>
                                                                                                <source
                                                                                                    src="{{ asset('RMADOC/videos/') . '/' . $item->strVideos }}"
                                                                                                    type="video/mp4">
                                                                                            </video>
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <div
                                                                                        class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                        <div class="text-center">
                                                                                            <a href="{{ asset('/RMADOC/docs') . '/' . $item->strDocs }}"
                                                                                                target="_blank"
                                                                                                class="btn btn-primary">Download
                                                                                                Document</a>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                <?php $i++; ?>
                                                                            @endforeach
                                                                        </div> <!-- /.carousel inner -->

                                                                        <a class="carousel-control-prev" href="#myCarousel"
                                                                            role="button" data-slide="prev">
                                                                            <span class="carousel-control-prev-icon"
                                                                                aria-hidden="true"></span>
                                                                            <span class="sr-only">Previous</span>
                                                                        </a>
                                                                        <a class="carousel-control-next" href="#myCarousel"
                                                                            role="button" data-slide="next">
                                                                            <span class="carousel-control-next-icon"
                                                                                aria-hidden="true"></span>
                                                                            <span class="sr-only">Next</span>
                                                                        </a>
                                                                    </div> <!-- /.carousel slide -->

                                                                </div>
                                                                <div class="modal-footer <button type="button"
                                                                    class="btn btn-secondary" data-dismiss="modal">
                                                                    Close</button>
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
                                                {{-- <tr>
                                                    <td>1</td>
                                                    <td>Factory RMA No</td>
                                                    <td>-</td>
                                                </tr> --}}
                                                <tr>
                                                    <td>1</td>
                                                    <td>Material Received</td>
                                                    <td>{{ $rmaList->strFactory_MaterialReceived ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Material Received Date</td>
                                                    @if ($rmaList->strFactory_MaterialReceived == 'Yes')
                                                        <td>
                                                            {{ $rmaList->strFactory_MaterialReceivedDate && $rmaList->strFactory_MaterialReceivedDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strFactory_MaterialReceivedDate))
                                                                : '-' }}
                                                        </td>
                                                    @else
                                                        <td>-</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Testing</td>
                                                    <td>{{ $rmaList->strFactory_Testing ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>Testing Complete Date</td>
                                                    @if ($rmaList->strFactory_Testing == 'Done')
                                                        <td>
                                                            {{ $rmaList->strFactory_TestingCompleteDate && $rmaList->strFactory_TestingCompleteDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strFactory_TestingCompleteDate))
                                                                : '-' }}
                                                        </td>
                                                    @else
                                                        <td>-</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>Fault Covered in Warranty</td>
                                                    <td>{{ $rmaList->strFactory_FaultCoveredinWarranty ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td>Replacement Approved</td>
                                                    <td>{{ $rmaList->strFactory_ReplacementApproved ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>7</td>
                                                    <td>Replacement Reason</td>
                                                    <td>{{ $rmaList->strFactory_ReplacementReason ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>8</td>
                                                    <td>Status</td>
                                                    <td>HFI</td>
                                                </tr>
                                                <tr>
                                                    <td>9</td>
                                                    <td>Comments</td>
                                                    <td>-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- factory documents -->
                                        {{-- <div class="row mx-1">
                                            <div class="col-md-12">
                                                <h4>Documents</h4>
                                                <div class="row mt-4">
                                                    <div class="col-md-3 col-sm-4 col-xs-6 gallery-box text-center">
                                                        <a href="https://view.officeapps.live.com/op/embed.aspx?src=www.excellentcomputers.co.in/Clients/mas-solution/Test_word.docx"
                                                            target="_blank">
                                                            <img src="../global/assets/images/reference/photo/pdf.jpg"
                                                                alt="document" title="name of the doument">
                                                        </a>
                                                        <div class="text-center del-img mt-2">
                                                            <i class="mas-trash mas-1x" title="Delete"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <!-- /. factory documents row -->
                                    </div><!--/. Factory table div-->

                                    <!-- Customer Status table div-->
                                    <div class="table-responsive">
                                        <div class="row mx-1">
                                            <div class="col-md-12">
                                                <h4>Customer Status</h4>
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
                                                    <td>Material Dispatched</td>
                                                    <td>{{ $rmaList->strMaterialDispatched ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Material Dispatched Date</td>
                                                    @if ($rmaList->strMaterialDispatched == 'Yes')
                                                        <td>
                                                            {{ $rmaList->strMaterialDispatchedDate && $rmaList->strMaterialDispatchedDate != '0000-00-00'
                                                                ? date('d-m-Y', strtotime($rmaList->strMaterialDispatchedDate))
                                                                : '-' }}

                                                        </td>
                                                    @else
                                                        <td>-</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Status</td>
                                                    <td>{{ $rmaList->strStatus ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>Comments</td>
                                                    <td>-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div><!--/. Customer Status table div-->
                                </div><!--/. col 6-->

                                <div class="col-lg-6 col-md-12">
                                    <!-- status table -->
                                    <div class="table-responsive">
                                        <table class="table table-striped" data-role="content" data-plugin="selectable"
                                            data-row-selectable="true">
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
                                            <div class="col-md-12">
                                                <h4>Additional RMA Details 1</h4>
                                                @if (isset($rmaList->first_name))
                                                    <p>Latest Updated by:
                                                        {{ $rmaList->first_name . ' ' . $rmaList->last_name ?? 'not updated' }}
                                                    </p>
                                                @endif
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
                                                        aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body px-0">
                                                            <!-- other information table div-->
                                                            <div class="table-responsive">
                                                                <div class="row mx-1">
                                                                    <div class="col-md-12">
                                                                        <h4>Other Information</h4>
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
                                                            </div><!--/. other information table div-->

                                                            <!-- Testing & Approval table div-->
                                                            <div class="table-responsive">
                                                                <div class="row mx-1">
                                                                    <div class="col-md-12">
                                                                        <h4>Testing & Approval</h4>
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
                                                                            <td>Material Received</td>
                                                                            <td>{{ $item->strMaterialReceived ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td>Material Received Date</td>
                                                                            @if ($item->strMaterialReceived == 'Yes')
                                                                                <td> {{ $item->strMaterialReceivedDate && $item->strMaterialReceivedDate != '0000-00-00'
                                                                                    ? date('d-m-Y', strtotime($item->strMaterialReceivedDate))
                                                                                    : '-' }}
                                                                                </td>
                                                                            @else
                                                                                <td>-</td>
                                                                            @endif
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td>Testing</td>
                                                                            <td>{{ $item->strTesting ?? '-' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4</td>
                                                                            <td>Testing Complete Date</td>
                                                                            @if ($item->strTesting == 'Done')
                                                                                <td>{{ $item->strTestingCompleteDate && $item->strTestingCompleteDate != '0000-00-00'
                                                                                    ? date('d-m-Y', strtotime($item->strTestingCompleteDate))
                                                                                    : '-' }}
                                                                                </td>
                                                                            @else
                                                                                <td>-</td>
                                                                            @endif
                                                                        </tr>
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
                                                                            <td>-</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                                <!-- image / video row -->
                                                                @if (count($documents) > 0)
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Testing images, videos & Docs</h4>
                                                                            <div class="row mt-4">
                                                                                @foreach ($documents as $index => $data)
                                                                                    @php
                                                                                        $extension = pathinfo(
                                                                                            $data->strDocs,
                                                                                            PATHINFO_EXTENSION,
                                                                                        );

                                                                                    @endphp

                                                                                    <div
                                                                                        class="col-md-3 col-xs-6 gallery-box text-center">
                                                                                        <div data-toggle="modal"
                                                                                            data-index="{{ $index }}"
                                                                                            data-target="#myModal">
                                                                                            @if ($data->strImages)
                                                                                                <img src="{{ asset('/RMADOC/images') . '/' . $data->strImages }}"
                                                                                                    alt="Image 1"
                                                                                                    data-target="#myCarousel"
                                                                                                    data-slide-to="{{ $data->rma_docs_id }}">
                                                                                            @elseif($data->strVideos)
                                                                                                <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                                                                    alt="Video"
                                                                                                    data-target="#myCarousel"
                                                                                                    data-slide-to="{{ $data->rma_docs_id }}">
                                                                                            @else
                                                                                                <img src="{{ asset('global/assets/images/reference/photo/document.jpg') }}"
                                                                                                    alt="Document"
                                                                                                    data-target="#myCarousel"
                                                                                                    data-slide-to="{{ $data->rma_docs_id }}">
                                                                                            @endif
                                                                                        </div>

                                                                                        <div
                                                                                            class="text-center del-img mt-2">
                                                                                            <form
                                                                                                action="{{ route('rma.rma_detail_delete', $data->rma_docs_id) }}"
                                                                                                method="POST"
                                                                                                onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                                style="display: inline-block;">
                                                                                                <input type="hidden"
                                                                                                    name="_method"
                                                                                                    value="DELETE">
                                                                                                <input type="hidden"
                                                                                                    name="_token"
                                                                                                    value="{{ csrf_token() }}">
                                                                                                <button type="submit"
                                                                                                    class="p-0 border-0 bg-none">
                                                                                                    <i class="mas-trash mas-1x"
                                                                                                        title="Delete"></i>
                                                                                                </button>
                                                                                            </form>
                                                                                        </div>

                                                                                    </div><!-- /.col -->
                                                                                @endforeach



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
                                                                                            </div>
                                                                                            <!-- /.carousel slide -->

                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Close</button>
                                                                                        </div> <!-- /.footer -->
                                                                                    </div>
                                                                                </div>
                                                                            </div> <!-- /.modal -->
                                                                        </div>
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
                                                                        {{-- <tr>
                                                                            <td>1</td>
                                                                            <td>Factory RMA No</td>
                                                                            <td>-</td>
                                                                        </tr> --}}
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td>Material Received</td>
                                                                            <td>{{ $item->strFactory_MaterialReceived ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td>Material Received Date</td>
                                                                            @if ($item->strFactory_MaterialReceived == 'Yes')
                                                                                <td>{{ $item->strFactory_MaterialReceivedDate && $item->strFactory_MaterialReceivedDate != '0000-00-00'
                                                                                    ? date('d-m-Y', strtotime($item->strFactory_MaterialReceivedDate))
                                                                                    : '-' }}
                                                                                </td>
                                                                            @else
                                                                                <td>-</td>
                                                                            @endif
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td>Testing</td>
                                                                            <td>{{ $item->strFactory_Testing ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4</td>
                                                                            <td>Testing Complete Date</td>
                                                                            @if ($item->strFactory_Testing == 'Done')
                                                                                <td>{{ $item->strFactory_TestingCompleteDate && $item->strFactory_TestingCompleteDate != '0000-00-00'
                                                                                    ? date('d-m-Y', strtotime($item->strFactory_TestingCompleteDate))
                                                                                    : '-' }}
                                                                                </td>
                                                                            @else
                                                                                <td>-</td>
                                                                            @endif
                                                                        </tr>
                                                                        <tr>
                                                                            <td>5</td>
                                                                            <td>Fault Covered in Warranty</td>
                                                                            <td>{{ $item->strFactory_FaultCoveredinWarranty ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>6</td>
                                                                            <td>Replacement Approved</td>
                                                                            <td>{{ $item->strFactory_ReplacementApproved ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>7</td>
                                                                            <td>Replacement Reason</td>
                                                                            <td>{{ $item->strFactory_ReplacementReason ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>8</td>
                                                                            <td>Status</td>
                                                                            <td>{{ $item->strStatus ?? '-' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>9</td>
                                                                            <td>Comments</td>
                                                                            <td>-</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                                <!-- factory documents -->
                                                                {{-- <div class="row mx-1">
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
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> --}}
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
                                                                            <td>{{ $item->strMaterialDispatched ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td>Material Dispatched Date</td>
                                                                            @if ($item->strMaterialDispatched == 'Yes')
                                                                                <td>{{ $item->strMaterialDispatchedDate && $item->strMaterialDispatchedDate != '0000-00-00'
                                                                                    ? date('d-m-Y', strtotime($item->strMaterialDispatchedDate))
                                                                                    : '-' }}
                                                                                </td>
                                                                            @else
                                                                                <td>-</td>
                                                                            @endif
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td>Status</td>
                                                                            <td>{{ $item->strStatus ?? '-' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4</td>
                                                                            <td>Comments</td>
                                                                            <td>-</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div><!--/. Customer Status table div-->

                                                        </div><!-- /. accordion body -->
                                                    </div> <!-- /. collapse One -->
                                                </div> <!-- /. accordion-item -->
                                            </div><!-- /. accordion -->
                                        </div><!--/. row-->

                                        @php
                                            $counter++;
                                        @endphp
                                    @endforeach

                                </div><!--/. col 6-->
                            </div>
                        </div>
                    </div><!--card end-->
                </div>
            </div><!-- end row -->
        </div>
        <!-- content-wrapper ends -->
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center d-block d-sm-inline-block">Copyright © 2022 Mas Solutions. All rights
                    reserved.</span>
                <span class="float-none text-black-50 d-block mt-1 mt-sm-0 text-center">Developed by <a href="#">
                        Excellent Computers </a> </span>
            </div>
        </footer>
        <!--/. footer ends-->
    </div>
    <!-- main-panel ends -->
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
<script>
    $(document).ready(function() {
        setTestMaterial();
        setTestTesting();
        setFactoryMaterial();
        setFactoryTesting();
        setCustomerMaterialDispatched();
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

<script>
    $(document).ready(function() {
        $('.modal-trigger').on('click', function() {
            var index = $(this).data('index');

            $('#myCarousel').carousel(index);
        });
        $('#myCarousel').on('slide.bs.carousel', function(e) {
            var nextIndex = $(e.relatedTarget).index();
            $('#myCarousel .carousel-indicators li').removeClass('active');
            $('#myCarousel .carousel-indicators li').eq(nextIndex).addClass('active');
        });
    });
</script>
