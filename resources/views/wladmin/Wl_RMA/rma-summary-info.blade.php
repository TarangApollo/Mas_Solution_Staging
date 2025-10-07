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
                                                    <td>{{ $rmaList->strTicketUniqueID == 0 ? 'Other' : $rmaList->strTicketUniqueID ?? '-' }}
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
                                                    <td>{{ $rmaList->strSystem ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>9</td>
                                                    <td>Model Number</td>
                                                    <td>{{ $rmaList->model_number ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td>Fault description</td>
                                                    <td>{{ $rmaList->strFaultdescription ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>11</td>
                                                    <td>Facts</td>
                                                    <td>{{ $rmaList->strFacts ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>12</td>
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
                                                <tr>
                                                    <td>5</td>
                                                    <td>Testing Result</td>
                                                    <td>{{ $rmaList->Testing_result ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td>Fault Covered in Warranty</td>
                                                    <td>{{ $rmaList->strFaultCoveredinWarranty ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>7</td>
                                                    <td>Replacement Approved</td>
                                                    <td>{{ $rmaList->strReplacementApproved ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>8</td>
                                                    <td>Replacement Reason</td>
                                                    <td>{{ $rmaList->strReplacementReason ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>9</td>
                                                    <td>Comments</td>
                                                    <td class="ws-break">{{ $rmaList->Testing_Comments ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <!-- image / video row img1 -->
                                       @if (count($Documents) > 0)
                                                <div class="row mx-1">
                                                    <div class="col-md-12">
                                                        <h4>Testing Images, Videos & Docs</h4>
                                                        <div class="row mt-4">
                                                            @foreach ($Documents as $index => $item)
                                                                @if ($item->strImages || $item->strVideos || $item->strDocs)
                                                                    <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                        @if ($item->strImages)
                                                                            <div class="modal-trigger rma_model"
                                                                                data-toggle="modal"
                                                                                data-target="#documentModal"
                                                                                data-type="image"
                                                                                data-index="{{ $index }}">
                                                                                {{-- data-url="{{ asset('/RMADOC/images') . '/' . $item->strImages }}" --}}
                                                                                <img src="{{ asset('/RMADOC/images') . '/' . $item->strImages }}"
                                                                                    alt="Image">
                                                                            </div>
                                                                        @elseif ($item->strVideos)
                                                                            <div class="modal-trigger rma_model"
                                                                                data-toggle="modal"
                                                                                data-target="#documentModal"
                                                                                data-type="video"
                                                                                data-index="{{ $index }}">
                                                                                {{-- data-url="{{ asset('/RMADOC/videos') . '/' . $item->strVideos }}" --}}
                                                                                <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                                                    alt="Video">
                                                                            </div>
                                                                        @elseif ($item->strDocs)
                                                                            @php
                                                                                $extension = pathinfo(
                                                                                    $item->strDocs,
                                                                                    PATHINFO_EXTENSION,
                                                                                );
                                                                            @endphp
                                                                            @if ($extension == 'xls' || $extension == 'xlsx')
                                                                                <a
                                                                                    onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                    <img 
                                                                                        src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                                                        alt="{{ $item->strDocs }}">
                                                                                </a>
                                                                            @elseif ($extension == 'doc' || $extension == 'docx')
                                                                                <a
                                                                                    onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                    <img 
                                                                                        src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                                                        alt="{{ $item->strDocs }}">
                                                                                </a>
                                                                            @else
                                                                                <a
                                                                                    onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                    <img 
                                                                                        src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                                                        alt="{{ $item->strDocs }}">
                                                                                </a>
                                                                            @endif
                                                                        @endif

                                                                        <div class="text-center del-img mt-2">
                                                                            <form
                                                                                action="{{ route('rma.rma_delete', $item->rma_docs_id) }}"
                                                                                method="POST"
                                                                                onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                style="display: inline-block;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="p-0 border-0 bg-none">
                                                                                    <i class="mas-trash mas-1x"
                                                                                        title="Delete"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @foreach ($Documents_files as $index => $doc)
                                                            @if($doc->strDocs)
                                                                @php
                                                                    $extension = pathinfo($doc->strDocs, PATHINFO_EXTENSION);
                                                                @endphp
                                                                <!-- Document Display -->
                                                                <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                    <a onclick="openDoc('{{ $doc->rma_docs_id }}')">
                                                                        @if (in_array($extension, ['xls', 'xlsx']))
                                                                            <img  src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}" alt="{{ $doc->strDocs }}">
                                                                        @elseif (in_array($extension, ['doc', 'docx']))
                                                                            <img  src="{{ asset('global/assets/images/reference/photo/word.jpg') }}" alt="{{ $doc->strDocs }}">
                                                                        @else
                                                                            <img  src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}" alt="{{ $doc->strDocs }}">
                                                                        @endif
                                                                    </a>

                                                                    <!-- Delete Button -->
                                                                    <div class="text-center del-img mt-2">
                                                                        <form action="{{ route('rma.rma_delete', $doc->rma_docs_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete?');" style="display: inline-block;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="p-0 border-0 bg-none">
                                                                                <i class="mas-trash mas-1x" title="Delete"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach

                                                        </div>
                                                        <div class="modal fade" id="documentModal" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <div id="modalCarousel" class="carousel slide"
                                                                            data-ride="carousel">
                                                                            <ol class="carousel-indicators"
                                                                                id="carouselIndicators">
                                                                            </ol>
                                                                            <div class="carousel-inner"
                                                                                id="carouselContent">
                                                                            </div>
                                                                            <a class="carousel-control-prev"
                                                                                role="button"
                                                                                onclick="navigateDocument('prev')">
                                                                                <span class="carousel-control-prev-icon"
                                                                                    aria-hidden="true"></span>
                                                                                <span class="sr-only">Previous</span>
                                                                            </a>
                                                                            <a class="carousel-control-next"
                                                                                role="button"
                                                                                onclick="navigateDocument('next')">
                                                                                <span class="carousel-control-next-icon"
                                                                                    aria-hidden="true"></span>
                                                                                <span class="sr-only">Next</span>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

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
                                                    <td>Factory RMA No</td>
                                                    <td>{{ $rmaList->Factory_rma_no ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Material Received</td>
                                                    <td>{{ $rmaList->strFactory_MaterialReceived ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Material Received Date</td>
                                                    <td> {{ $rmaList->strFactory_MaterialReceivedDate && $rmaList->strFactory_MaterialReceivedDate != '0000-00-00'
                                                        ? date('d-m-Y', strtotime($rmaList->strFactory_MaterialReceivedDate))
                                                        : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>Testing</td>
                                                    <td>{{ $rmaList->strFactory_Testing ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>Testing Complete Date</td>
                                                    <td>{{ $rmaList->strFactory_TestingCompleteDate && $rmaList->strFactory_TestingCompleteDate != '0000-00-00'
                                                        ? date('d-m-Y', strtotime($rmaList->strFactory_TestingCompleteDate))
                                                        : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td>Fault Covered in Warranty</td>
                                                    <td>{{ $rmaList->strFactory_FaultCoveredinWarranty ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>7</td>
                                                    <td>Replacement Approved</td>
                                                    <td>{{ $rmaList->strFactory_ReplacementApproved ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>8</td>
                                                    <td>Replacement Reason</td>
                                                    <td>{{ $rmaList->strFactory_ReplacementReason ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>9</td>
                                                    <td>HFI Status</td>
                                                    <td>{{ $rmaList->Factory_Status ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td>Comments</td>
                                                    <td class="ws-break">{{ $rmaList->Factory_Comments ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if (count($Documents_files) > 0)
                                            <div class="row mx-1">
                                                <div class="col-md-12">
                                                    <h4>Documents</h4>
                                                    <div class="row mt-4">
                                                        @foreach ($Documents_files as $index => $item)
                                                            @if ($item->Factory_strDocs)
                                                                <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                    @if ($item->Factory_strDocs)
                                                                        @php
                                                                            $extension = pathinfo(
                                                                                $item->Factory_strDocs,
                                                                                PATHINFO_EXTENSION,
                                                                            );
                                                                        @endphp
                                                                        @if ($extension == 'xls' || $extension == 'xlsx')
                                                                            <a onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                <img 
                                                                                    src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                                                    alt="{{ $item->Factory_strDocs }}">
                                                                            </a>
                                                                        @elseif($extension == 'doc' || $extension == 'docx')
                                                                            <a onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                <img 
                                                                                    src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                                                    alt="{{ $item->Factory_strDocs }}">
                                                                            </a>
                                                                        @else
                                                                            <a onclick="openDoc('{{ $item->rma_docs_id }}')">
                                                                                <img 
                                                                                    src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                                                    alt="{{ $item->Factory_strDocs }}">
                                                                            </a>
                                                                        @endif
                                                                        {{-- <div class="text-center del-img mt-2">
                                                                            <form
                                                                                action="{{ route('rma.rma_delete', $item->rma_docs_id) }}"
                                                                                method="POST"
                                                                                onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                style="display: inline-block;">
                                                                                <input type="hidden" name="_method"
                                                                                    value="DELETE">
                                                                                <input type="hidden" name="_token"
                                                                                    value="{{ csrf_token() }}">
                                                                                <button type="submit"
                                                                                    class="p-0 border-0 bg-none">
                                                                                    <i class="mas-trash mas-1x"
                                                                                        title="Delete"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div> --}}
                                                                    @endif



                                                                </div><!-- /.col -->
                                                            @endif
                                                        @endforeach
                                                    </div> <!-- /.row -->
                                                    <!-- Lightbox (made with Bootstrap modal and carousel) -->


                                                </div>
                                                <!-- /.col 12 level 1 images -->
                                            </div>
                                        @endif
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
                                                    <td class="ws-break">{{ $rmaList->Cus_Comments ?? '-' }}</td>
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
                                                @foreach ($datalog as $index => $rma_info)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            @if($rma_info->tableName != "rma")
                                                                    {{ 'Additional RMA' }}
                                                                @endif
                                                            {{ $rma_info->strStatus }}
                                                        </td>
                                                        @if($rma_info->strStatus == 'Open')
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($rma_info->strRMARegistrationDate)->format('d-m-Y') }}
                                                                <br>
                                                                <small
                                                                    class="position-static">{{ \Carbon\Carbon::parse($rma_info->strEntryDate)->format('H:i:s') }}</small>
                                                            </td>
                                                        @elseif($rma_info->strStatus == 'Closed')
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($rma_info->strMaterialDispatchedDate)->format('d-m-Y') }}
                                                                <br>
                                                                <small
                                                                    class="position-static">{{ \Carbon\Carbon::parse($rma_info->strEntryDate)->format('H:i:s') }}</small>
                                                            </td>
                                                        @endif
                                                        <td>{{ $rma_info->actionBy }}</td>
                                                    </tr>
                                                @endforeach
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
                                                    ->where(function ($query) {
                                                        $query->whereNotNull('strImages')->orWhereNotNull('strVideos');
                                                    })
                                                    ->get();

                                                $documentFiles = DB::table('rma_docs')
                                                    ->orderBy('rma_docs_id', 'desc')
                                                    ->where([
                                                        'iStatus' => 1,
                                                        'isDelete' => 0,
                                                        'rma_id' => $item->rma_id,
                                                        'rma_detail_id' => $item->rma_detail_id,
                                                    ])
                                                    ->where(function ($query) {
                                                        $query
                                                            ->whereNotNull('strDocs')
                                                            ->orWhereNotNull('Factory_strDocs');
                                                    })
                                                    ->get();
                                            @endphp
                                        <div class="row mx-1">
                                            <div class="col-md-12">
                                                <h4>Additional RMA Details {{ $counter }}</h4>
                                                @if (isset($rmaList->first_name))
                                                    <p>Latest Updated by:
                                                        {{ $rmaList->first_name . ' ' . $rmaList->last_name ?? 'not updated' }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="accordion px-0" id="accordionExample_{{$item->rma_detail_id}}">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingFive_{{$item->rma_detail_id}}">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseFive_{{$item->rma_detail_id}}"
                                                            aria-expanded="false" aria-controls="collapseFive_{{$item->rma_detail_id}}">
                                                            <span class="text-orange">View Details</span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFive_{{$item->rma_detail_id}}" class="accordion-collapse collapse"
                                                        aria-labelledby="headingFive_{{$item->rma_detail_id}}" data-bs-parent="#accordionExample_{{$item->rma_detail_id}}">
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
                                                                        <tr>
                                                                            <td>9</td>
                                                                            <td>Model Number</td>
                                                                            <td>{{ $item->Additional_rma_model_number ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>10</td>
                                                                            <td>Fault description</td>
                                                                            <td>{{ $item->strFaultdescription ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>11</td>
                                                                            <td>Facts</td>
                                                                            <td>{{ $item->strFacts ?? '-' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>12</td>
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
                                                                            <td>5</td>
                                                                            <td>Testing Result</td>
                                                                            <td>{{ $item->Additional_Testing_result ?? '-' }}
                                                                            </td>
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
                                                                            <td class="ws-break">{{ $item->Additional_Testing_Comments ?? '-' }}
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
                                                                                    @foreach ($documents as $index => $data)
                                                                                        @php
                                                                                            $extension = pathinfo(
                                                                                                $data->strDocs,
                                                                                                PATHINFO_EXTENSION,
                                                                                            );
                                                                                            $modalId =
                                                                                                'myModal_' .
                                                                                                $data->rma_detail_id;
                                                                                            $carouselId =
                                                                                                'myCarousel_' .
                                                                                                $data->rma_detail_id;
                                                                                        @endphp
                                                                                        @if ($data->strImages || $data->strVideos || $data->strDocs)
                                                                                            <div
                                                                                                class="col-md-3 col-xs-6 gallery-box text-center">
                                                                                                @if ($data->strImages)
                                                                                                    <div data-toggle="modal"
                                                                                                        data-target="#{{ $modalId }}"
                                                                                                        data-index="{{ $index }}"
                                                                                                        class="open-image-modal">
                                                                                                        <img src="{{ asset('/RMADOC/images') . '/' . $data->strImages }}"
                                                                                                            alt="Image 1">
                                                                                                    </div>
                                                                                                @elseif($data->strVideos)
                                                                                                    <div data-toggle="modal"
                                                                                                        data-target="#{{ $modalId }}"
                                                                                                        data-index="{{ $index }}"
                                                                                                        class="open-video-modal">
                                                                                                        <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                                                                            alt="Video">
                                                                                                    </div>
                                                                                                @else
                                                                                                    <a
                                                                                                        onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                        <img 
                                                                                                            src="{{ asset('global/assets/images/reference/photo/' . ($extension == 'xls' || $extension == 'xlsx' ? 'excel.jpg' : ($extension == 'doc' || $extension == 'docx' ? 'word.jpg' : 'pdf.jpg'))) }}"
                                                                                                            alt="{{ $data->strDocs }}">
                                                                                                    </a>
                                                                                                @endif
                                                                                                <div
                                                                                                    class="text-center del-img mt-2">
                                                                                                    <form
                                                                                                        action="{{ route('rma.rma_detail_delete', $data->rma_docs_id) }}"
                                                                                                        method="POST"
                                                                                                        onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                                        style="display: inline-block;">
                                                                                                        @csrf
                                                                                                        @method('DELETE')
                                                                                                        <button
                                                                                                            type="submit"
                                                                                                            class="p-0 border-0 bg-none">
                                                                                                            <i class="mas-trash mas-1x"
                                                                                                                title="Delete"></i>
                                                                                                        </button>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @foreach ($documentFiles as $index => $data)
                                                                                        @php
                                                                                            $extension = pathinfo(
                                                                                                $data->strDocs,
                                                                                                PATHINFO_EXTENSION,
                                                                                            );
                                                                                            $modalId =
                                                                                                'myModal_' .
                                                                                                $data->rma_detail_id;
                                                                                            $carouselId =
                                                                                                'myCarousel_' .
                                                                                                $data->rma_detail_id;
                                                                                        @endphp
                                                                                        @if ($data->strDocs)
                                                                                            <div
                                                                                                class="col-md-3 col-xs-6 gallery-box text-center">

                                                                                                <a
                                                                                                    onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                    <img 
                                                                                                        src="{{ asset('global/assets/images/reference/photo/' . ($extension == 'xls' || $extension == 'xlsx' ? 'excel.jpg' : ($extension == 'doc' || $extension == 'docx' ? 'word.jpg' : 'pdf.jpg'))) }}"
                                                                                                        alt="{{ $data->strDocs }}">
                                                                                                </a>

                                                                                                <div
                                                                                                    class="text-center del-img mt-2">
                                                                                                    <form
                                                                                                        action="{{ route('rma.rma_detail_delete', $data->rma_docs_id) }}"
                                                                                                        method="POST"
                                                                                                        onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                                                        style="display: inline-block;">
                                                                                                        @csrf
                                                                                                        @method('DELETE')
                                                                                                        <button
                                                                                                            type="submit"
                                                                                                            class="p-0 border-0 bg-none">
                                                                                                            <i class="mas-trash mas-1x"
                                                                                                                title="Delete"></i>
                                                                                                        </button>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>


                                                                                @foreach ($documents as $index => $data)
                                                                                    @php
                                                                                        $modalId =
                                                                                            'myModal_' .
                                                                                            $data->rma_detail_id;
                                                                                        $carouselId =
                                                                                            'myCarousel_' .
                                                                                            $data->rma_detail_id;
                                                                                    @endphp
                                                                                    <div class="modal fade rma_additional_model"
                                                                                        id="{{ $modalId }}"
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
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <div id="{{ $carouselId }}"
                                                                                                        class="carousel slide"
                                                                                                        data-ride="carousel">
                                                                                                        <ol
                                                                                                            class="carousel-indicators">
                                                                                                            @foreach ($documents as $carouselIndex => $modeldata)
                                                                                                                @if ($modeldata->strImages || $modeldata->strVideos)
                                                                                                                    <li data-target="#{{ $carouselId }}"
                                                                                                                        data-slide-to="{{ $carouselIndex }}"
                                                                                                                        class="{{ $carouselIndex == $index ? 'active' : '' }}">
                                                                                                                    </li>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                        </ol>
                                                                                                        <div
                                                                                                            class="carousel-inner">
                                                                                                            @foreach ($documents as $carouselIndex => $itemdetail)
                                                                                                                @if ($itemdetail->strImages || $itemdetail->strVideos)
                                                                                                                    <div
                                                                                                                        class="carousel-item {{ $carouselIndex == $index ? 'active' : '' }}">
                                                                                                                        @if ($itemdetail->strImages)
                                                                                                                            <img src="{{ asset('/RMADOC/images') . '/' . $itemdetail->strImages }}"
                                                                                                                                class="d-block w-100"
                                                                                                                                alt="Image">
                                                                                                                        @elseif($itemdetail->strVideos)
                                                                                                                            <div
                                                                                                                                class="embed-responsive embed-responsive-16by9">
                                                                                                                                <video
                                                                                                                                    controls>
                                                                                                                                    <source
                                                                                                                                        src="{{ asset('RMADOC/videos/') . '/' . $itemdetail->strVideos }}"
                                                                                                                                        type="video/mp4">
                                                                                                                                </video>
                                                                                                                            </div>
                                                                                                                        @else
                                                                                                                        @endif
                                                                                                                    </div>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                        <a class="carousel-control-prev"
                                                                                                            href="javascript:void(0)"
                                                                                                            role="button"
                                                                                                            onclick="navigate_detail('prev', '{{ $carouselId }}')">
                                                                                                            <span
                                                                                                                class="carousel-control-prev-icon"
                                                                                                                aria-hidden="true"></span>
                                                                                                            <span
                                                                                                                class="sr-only">Previous</span>
                                                                                                        </a>
                                                                                                        <a class="carousel-control-next"
                                                                                                            href="javascript:void(0)"
                                                                                                            role="button"
                                                                                                            onclick="navigate_detail('next', '{{ $carouselId }}')">
                                                                                                            <span
                                                                                                                class="carousel-control-next-icon"
                                                                                                                aria-hidden="true"></span>
                                                                                                            <span
                                                                                                                class="sr-only">Next</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button"
                                                                                                        class="btn btn-secondary"
                                                                                                        data-dismiss="modal">Close</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
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
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td>Factory RMA No</td>
                                                                            <td>{{ $item->Additional_Factory_rma_no ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td>Material Received</td>
                                                                            <td>{{ $item->strFactory_MaterialReceived ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
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
                                                                            <td>4</td>
                                                                            <td>Testing</td>
                                                                            <td>{{ $item->strFactory_Testing ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>5</td>
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
                                                                            <td>6</td>
                                                                            <td>Fault Covered in Warranty</td>
                                                                            <td>{{ $item->strFactory_FaultCoveredinWarranty ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>7</td>
                                                                            <td>Replacement Approved</td>
                                                                            <td>{{ $item->strFactory_ReplacementApproved ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>8</td>
                                                                            <td>Replacement Reason</td>
                                                                            <td>{{ $item->strFactory_ReplacementReason ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>9</td>
                                                                            <td>HFI Status</td>
                                                                            <td>{{ $item->Additional_Factory_Status ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>10</td>
                                                                            <td>Comments</td>
                                                                            <td class="ws-break">{{ $item->Additional_Factory_Comments ?? '-' }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                @if (count($documentFiles) > 0)
                                                                    <div class="row mx-1">
                                                                        <div class="col-md-12">
                                                                            <h4>Documents</h4>
                                                                            <div class="row mt-4">
                                                                                @foreach ($documentFiles as $index => $data)
                                                                                    @if ($data->Factory_strDocs)
                                                                                        @php
                                                                                            $extension = pathinfo(
                                                                                                $data->Factory_strDocs,
                                                                                                PATHINFO_EXTENSION,
                                                                                            );

                                                                                        @endphp

                                                                                        <div
                                                                                            class="col-md-3 col-xs-6 gallery-box text-center">

                                                                                            @if ($extension == 'xls' || $extension == 'xlsx')
                                                                                                <a onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                    <img 
                                                                                                        src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                                                                        alt="{{ $data->Factory_strDocs }}">
                                                                                                </a>
                                                                                            @elseif($extension == 'doc' || $extension == 'docx')
                                                                                                <a onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                    <img 
                                                                                                        src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                                                                        alt="{{ $data->Factory_strDocs }}">
                                                                                                </a>
                                                                                            @else
                                                                                                <a onclick="openDoc('{{ $data->rma_docs_id }}')">
                                                                                                    <img 
                                                                                                        src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                                                                        alt="{{ $data->Factory_strDocs }}">
                                                                                                </a>
                                                                                            @endif
                                                                                            {{-- <img src="{{ asset('global/assets/images/reference/photo/document.jpg') }}"
                                                                                                alt="Document"
                                                                                                data-target="#myCarousel"
                                                                                                data-slide-to="{{ $data->rma_docs_id }}"> --}}

                                                                                            {{-- <div
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
                                                                                            </div> --}}

                                                                                        </div><!-- /.col -->
                                                                                    @endif
                                                                                @endforeach
                                                                            </div> <!-- /.row -->
                                                                            <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                                                        </div>
                                                                    </div>
                                                                @endif

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
                                                                            <td class="ws-break">{{ $item->Additional_Cus_Comments ?? '-' }}
                                                                            </td>
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

        <!--/. footer ends-->
    </div>
    <!-- main-panel ends -->
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- container-scroller -->
<!-- plugins:js -->
<!--<script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>-->
<!--<script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>-->
<!--<script src="{{ asset('global/assets/js/settings.js') }}"></script>-->
<!--<script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>-->
<!--<script src="{{ asset('global/assets/js/custom.js') }}"></script>-->

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

  {{-- rma model --}}
    <script>
        let currentIndex = 0;
        let documents = @json($Documents);

        // Filter out documents with no images or videos
        let validDocuments = documents.filter(item => item.strImages || item.strVideos);

        $(document).on('click', '.rma_model', function() {
            currentIndex = $(this).data('index');
            if (validDocuments[currentIndex] === undefined) {
                currentIndex = 0;
            }
            loadCarouselContent();
        });

        function loadCarouselContent() {
            let carouselItems = '';
            let carouselIndicators = '';

            validDocuments.forEach((item, index) => {
                console.log('Current Index:', item);
                const type = item.strImages ? 'image' : item.strVideos ? 'video' : 'doc';
                let url = '';

                if (item.strImages) {
                    url = "{{ asset('/RMADOC/images') }}" + '/' + item.strImages;
                } else if (item.strVideos) {
                    url = "{{ asset('/RMADOC/videos') }}" + '/' + item.strVideos;
                }

                if (type === 'image') {
                    carouselItems += `
                    <div class="carousel-item ${index === currentIndex ? 'active' : ''}">
                        <img src="${url}" alt="Image" class="d-block w-100" oncontextmenu="return false;">
                    </div>`;
                } else if (type === 'video') {
                    carouselItems += `
                    <div class="carousel-item ${index === currentIndex ? 'active' : ''}">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video class="embed-responsive-item" controls oncontextmenu="return false;">
                                <source src="${url}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>`;
                } else {
                    carouselItems += `
                    <div class="carousel-item ${index === currentIndex ? 'active' : ''}">
                        <div class="alert alert-info">No content available for this document.</div>
                    </div>`;
                }

                carouselIndicators +=
                    `
                    <li data-target="#modalCarousel" data-slide-to="${index}" class="${index === currentIndex ? 'active' : ''}"></li>`;
            });

            $('#carouselContent').html(carouselItems);
            $('#carouselIndicators').html(carouselIndicators);
        }

        function navigateDocument(direction) {
            if (direction === 'prev') {
                currentIndex = currentIndex > 0 ? currentIndex - 1 : validDocuments.length - 1;
            } else if (direction === 'next') {
                currentIndex = currentIndex < validDocuments.length - 1 ? currentIndex + 1 : 0;
            }

            loadCarouselContent();
        }

        function openDoc(url) {
            var newurl = "{{ route('rma.openDocument', ':id') }}";
            newurl = newurl.replace(':id', url);
            newurl = newurl.replace('?', '/');
            window.open(newurl, '_blank');
        }
    </script>
    {{-- rma model --}}

<script>
    $(document).on('click', '.open-image-modal, .open-video-modal', function () {
    const index = $(this).data('index');
    const modalId = $(this).data('target');
    const $carousel = $(modalId).find('.carousel');
    $carousel.find('.carousel-item').removeClass('active').eq(index).addClass('active');
    $carousel.find('.carousel-indicators li').removeClass('active').eq(index).addClass('active');
});

function navigate_detail(directiondetail, carouselId) {
    const $carousel = $(`#${carouselId}`);
    const $items = $carousel.find('.carousel-item');
    const totalItems = $items.length;

    // Find the current active index
    let currentIndex = $items.index($carousel.find('.carousel-item.active'));
    if (directiondetail === 'prev') {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalItems - 1;
    } else if (directiondetail === 'next') {
        currentIndex = (currentIndex < totalItems - 1) ? currentIndex + 1 : 0;
    }

    // Update the active state for items and indicators
    $items.removeClass('active').eq(currentIndex).addClass('active');
    $carousel.find('.carousel-indicators li').removeClass('active').eq(currentIndex).addClass('active');
}
    </script>
