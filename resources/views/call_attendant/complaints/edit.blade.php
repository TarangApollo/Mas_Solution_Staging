@extends('layouts.callAttendant')

@section('title', 'Complaints Edit')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- css for this page -->
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />

    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />


    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex flex-row-reverse">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-md-2 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <p class="m-0 pr-8">Edit Complaint</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('call_attendant.callattendantcommon.alert')
                <!-- first row starts here -->
                <div class="row d-flex justify-content-center my-5">
                    <div class="col-sm-10">
                        <div class="card">
                            <div class="card-body p-0">
                                <h4 class="card-title mt-0">Modify Complaint ID:
                                    {{ $ticketInfo->strTicketUniqueID ?? str_pad($ticketInfo->iTicketId, 4, '0', STR_PAD_LEFT) }}
                                </h4>
                                <div class="container">
                                    <div class="row bg-orange bg-opacity-25 p-4">
                                        <div class="col-md-6 font-15">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2"><strong>Complaint ID:</strong>
                                                </div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    <strong>{{ $ticketInfo->strTicketUniqueID ?? str_pad($ticketInfo->iTicketId, 4, '0', STR_PAD_LEFT) }}</strong>
                                                </div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Name:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->CustomerName }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Contact:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->CustomerMobile }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Email:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->CustomerEmail }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">OEM Company:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strOEMCompanyName }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Customer Company:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->CompanyName }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Company Profile:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strCompanyClientProfile }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Company Email ID:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->CustomerEmailCompany }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Other Information:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->OtherInformation }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Distributor:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">{{ $ticketInfo->Name }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Project Name:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->ProjectName }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Project State:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strStateName }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Project City:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strCityName }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Caller Connected Through:
                                                </div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->iCallThrough }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">User defined 1:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->UserDefiine1 }}</div>
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-md-6 font-15">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">System:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">{{ $ticketInfo->strSystem }}
                                                </div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Component:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strComponent }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Sub Component:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strSubComponent }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Support Type:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strSupportType }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Issue:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">{{ $ticketInfo->issue }}
                                                </div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Resolution Details:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->Resolutiondetail }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Solution Type:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strResolutionCategory }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Issue Type:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strIssueType }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Caller Competency:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->strCallCompetency }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Status:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ $ticketInfo->startStatus }}
                                                </div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Level:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">Level
                                                    {{ $ticketInfo->LevelId }}</div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Comments:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">{{ $ticketInfo->comments }}
                                                </div>
                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Call Date:</div>
                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                    {{ date('d-m-Y', strtotime($ticketInfo->ComplainDate)) }} <small
                                                        class="top-0">{{ date('H:i:s', strtotime($ticketInfo->ComplainDate)) }}</small>
                                                </div>
                                                @if ($ticketInfo->recordUrl != '')
                                                    <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Recording:</div>
                                                    <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                        <audio controls>
                                                            <source src="{{ $ticketInfo->recordUrl }}" type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    </div>
                                                @else
                                                    <div class="col-lg-8 col-md-12 col-xs-6 mb-2">no recording found</div>
                                                @endif
                                            </div>
                                        </div> <!-- /.col -->
                                        @if (count($ticketDetail) > 0)
                                            <!-- level 1 images start -->
                                            <div class="col-md-12">
                                                <h4 class="info-text mt-4">Reference Images & Video</h4>
                                                <!-- First row of images -->
                                                <div class="row">

                                                    @foreach ($ticketDetail as $tDetail)
                                                        @if ($tDetail->DocumentType == 2)
                                                            <div class="col-md-2 gallery-box">
                                                                <div data-toggle="modal" data-target="#myModal">
                                                                    <img src="{{ asset('ticket_images/') . '/' . $tDetail->DocumentName }}"
                                                                        alt="Image 1" data-target="#myCarousel"
                                                                        data-slide-to="0">
                                                                </div>
                                                            </div><!-- /.col -->
                                                        @elseif($tDetail->DocumentType == 3)
                                                            <!-- <div class="carousel-item">
                                                                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                                                                <iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}" allowfullscreen></iframe>
                                                                                                            </div>
                                                                                                        </div> -->
                                                            <div class="col-md-2 gallery-box">
                                                                <div data-toggle="modal" data-target="#myModal">
                                                                    <img src="{{ asset('global/assets/images/customer/photo/video.jpg') }}"
                                                                        alt="Image 1" data-target="#myCarousel"
                                                                        data-slide-to="0">
                                                                    <!-- <iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}" allowfullscreen></iframe> -->
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-md-2 gallery-box">
                                                                <div data-toggle="modal" data-target="#myModal">
                                                                    <img src="{{ asset('ticket_images/') . '/' . $tDetail->DocumentName }}"
                                                                        alt="Image 1" data-target="#myCarousel"
                                                                        data-slide-to="0">
                                                                </div>
                                                            </div><!-- /.col -->
                                                        @endif
                                                    @endforeach


                                                    <!-- /.col -->
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
                                                                        @foreach ($ticketDetail as $tDetail)
                                                                            <li data-target="#myCarousel"
                                                                                data-slide-to="{{ $tDetail->iTicketDetailId }}"
                                                                                {{ $i == 1 ? 'class="active"' : '' }}></li>
                                                                            <?php $i++; ?>
                                                                        @endforeach
                                                                    </ol>
                                                                    <div class="carousel-inner">
                                                                        <?php $i = 1; ?>
                                                                        @foreach ($ticketDetail as $tDetail)
                                                                            @if ($tDetail->DocumentType == 2)
                                                                                <div
                                                                                    class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                                                                                    <img src="{{ asset('ticket_images/') . '/' . $tDetail->DocumentName }}"
                                                                                        class="d-block w-100"
                                                                                        alt="Image 1">
                                                                                </div>
                                                                            @elseif($tDetail->DocumentType == 3)
                                                                                <div
                                                                                    class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                                                                                    <div
                                                                                        class="embed-responsive embed-responsive-16by9">
                                                                                        <!--<iframe-->
                                                                                        <!--    class="embed-responsive-item"-->
                                                                                        <!--    src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}"-->
                                                                                        <!--    allowfullscreen></iframe>-->
                                                                                        <div class="embed-responsive-item">
                                                                                            <video controls=""
                                                                                                name="media">
                                                                                                <source
                                                                                                    src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}"
                                                                                                    type="video/mp4">
                                                                                            </video>
                                                                                        </div>
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
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div> <!-- /.footer -->
                                                        </div>
                                                    </div>
                                                </div> <!-- /.modal -->
                                            </div> <!-- /.col 12 level 1 images -->
                                        @endif
                                    </div> <!-- /.row level 1 details -->

                                    <!-- row level 1 update ticket details -->
                                    @if (count($ticketLogs) > 0)
                                        @foreach ($ticketLogs as $Detail)
                                            <div class="row bg-orange bg-opacity-25 p-4">
                                                <hr class="p-0">
                                                <h3 class="mb-5">Level {{ $Detail->LevelId }} Updated Ticket Details
                                                </h3>
                                                <div class="col-md-6 font-15">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Executive Name:</div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                            {{ $Detail->first_name . ' ' . $Detail->last_name }}</div>
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Status:</div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                            {{ $Detail->ticketName }}</div>
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Call Date:</div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                            {{ date('d-m-Y', strtotime($Detail->strEntryDate)) }} <small
                                                                class="top-0">{{ date('H:i:s', strtotime($Detail->strEntryDate)) }}</small>
                                                        </div>
                                                        @if ($Detail->recordUrl)
                                                            <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Recording:</div>
                                                            <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                                <audio controls>
                                                                    <source src="{{ $Detail->recordUrl }}"
                                                                        type="audio/mpeg">
                                                                    Your browser does not support the audio element.
                                                                </audio>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div> <!-- /.col -->
                                                <div class="col-md-6 font-15">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Issue:</div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                            {{ $Detail->strIssue ?? '' }}
                                                        </div>
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Solution Type:
                                                        </div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                            {{ $Detail->strResolutionCategory }}</div>
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Issue Type:</div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                            {{ $Detail->strIssueType }}</div>
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Suggested Resolution:
                                                        </div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                            {{ $Detail->newResolution }}</div>
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Comments:</div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                            {{ $Detail->comments }}</div>
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Assign Level:</div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">Level
                                                            {{ $Detail->LevelId }}</div>
                                                        @if ($Detail->LevelId == 2)
                                                            <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Assign To:</div>
                                                            <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                                {{ $Detail->strFirstName . ' ' . $Detail->strLastName }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div> <!-- /.col -->

                                                <!-- level 1 update ticket images start -->
                                                @if (count($Detail->gallery) > 0)
                                                    <div class="col-md-12">
                                                        <h4 class="info-text mt-4">Reference Images & Video</h4>
                                                        <!-- First row of images -->
                                                        <div class="row">
                                                            @foreach ($Detail->gallery as $gallery)
                                                                @if ($gallery->DocumentType == 2)
                                                                    <div class="col-md-2 gallery-box">
                                                                        <div data-toggle="modal"
                                                                            data-target="#myModal{{ $Detail->iTicketLogId }}">
                                                                            <img src="{{ asset('ticket_images/') . '/' . $gallery->DocumentName }}"
                                                                                alt="Image 1"
                                                                                data-target="#myCarousel{{ $Detail->iTicketLogId }}"
                                                                                data-slide-to="{{ $gallery->iTicketDetailId }}">
                                                                        </div>
                                                                    </div><!-- /.col -->
                                                                @elseif($gallery->DocumentType == 3)
                                                                    <div class="col-md-2 gallery-box">
                                                                        <div data-toggle="modal"
                                                                            data-target="#myModal{{ $Detail->iTicketLogId }}">
                                                                            <img src="{{ asset('global/assets/images/customer/photo/video.jpg') }}"
                                                                                alt="Image 1"
                                                                                data-target="#myCarousel{{ $Detail->iTicketLogId }}"
                                                                                data-slide-to="{{ $gallery->iTicketDetailId }}">
                                                                            <!-- <iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $gallery->DocumentName }}" allowfullscreen></iframe> -->
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div> <!-- /.row -->
                                                        <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="myModal{{ $Detail->iTicketLogId }}"
                                                            tabindex="-1" aria-hidden="true">
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
                                                                        <div id="myCarousel{{ $Detail->iTicketLogId }}"
                                                                            class="carousel slide">
                                                                            <ol class="carousel-indicators">
                                                                                <?php $i = 1; ?>
                                                                                @foreach ($Detail->gallery as $gallery)
                                                                                    <li data-target="#myCarousel{{ $Detail->iTicketLogId }}"
                                                                                        data-slide-to="{{ $gallery->iTicketDetailId }}"
                                                                                        {{ $i == 1 ? 'class="active"' : '' }}>
                                                                                    </li>
                                                                                    <?php $i++; ?>
                                                                                @endforeach

                                                                            </ol>
                                                                            <div class="carousel-inner">
                                                                                <?php $i = 1; ?>
                                                                                @foreach ($Detail->gallery as $gallery)
                                                                                    @if ($gallery->DocumentType == 2)
                                                                                        <div
                                                                                            class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                                                                                            <img src="{{ asset('ticket_images/') . '/' . $gallery->DocumentName }}"
                                                                                                class="d-block w-100"
                                                                                                alt="Image 1">
                                                                                        </div>
                                                                                    @elseif($gallery->DocumentType == 3)
                                                                                        <div
                                                                                            class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                                                                                            <div
                                                                                                class="embed-responsive embed-responsive-16by9">
                                                                                                <!--<iframe-->
                                                                                                <!--    class="embed-responsive-item"-->
                                                                                                <!--    src="{{ asset('ticket_video/') . '/' . $gallery->DocumentName }}"-->
                                                                                                <!--    allowfullscreen></iframe>-->
                                                                                                <div
                                                                                                    class="embed-responsive-item">
                                                                                                    <video controls=""
                                                                                                        name="media">
                                                                                                        <source
                                                                                                            src="{{ asset('ticket_video/') . '/' . $gallery->DocumentName }}"
                                                                                                            type="video/mp4">
                                                                                                    </video>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                    <?php $i++; ?>
                                                                                @endforeach

                                                                            </div> <!-- /.carousel inner -->
                                                                            <a class="carousel-control-prev"
                                                                                href="#myCarousel{{ $Detail->iTicketLogId }}"
                                                                                role="button" data-slide="prev">
                                                                                <span class="carousel-control-prev-icon"
                                                                                    aria-hidden="true"></span>
                                                                                <span class="sr-only">Previous</span>
                                                                            </a>
                                                                            <a class="carousel-control-next"
                                                                                href="#myCarousel{{ $Detail->iTicketLogId }}"
                                                                                role="button" data-slide="next">
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
                                                    </div> <!-- /.col 12 level 1 update ticket images -->
                                                @endif
                                            </div> <!-- /.row level 1 update ticket details -->
                                        @endforeach
                                    @endif


                                    <div class="row my-4">
                                        <h4 class="info-text"> Would you like to change status?</h4>
                                        <div class="col-sm-12">
                                            <form class="was-validated" id="frmparameter"
                                                action="{{ route('complaint.update') }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="iticketId" id="iticketId"
                                                    value="{{ $ticketInfo->iTicketId }}">
                                                <input type="hidden" name="OemCompannyId" id="OemCompannyId"
                                                    value="{{ $ticketInfo->OemCompannyId }}">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Select Status</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                name="iStatus" id="iStatus" required>
                                                                <option value="0">Open</option>
                                                                <option value="3"
                                                                    {{ in_array($ticketInfo->finalStatus, [1, 4, 5]) ? 'selected' : '' }}>
                                                                    Reopen</option>
                                                                <option value="1">Closed</option>
                                                                <option value="5">Customer Feedback Awaited </option>
                                                                <option value="4">Closed with RMA</option>
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Select Level</label>
                                                            @if ($ticketInfo->LevelId == 2 || Session::get('exeLevel') == 2)
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" required name="LevelId"
                                                                    id="LevelId">
                                                                    <option value="2">Level 2</option>
                                                                </select>
                                                            @else
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" required name="LevelId"
                                                                    id="LevelId">
                                                                    <option value="1">Level 1</option>
                                                                    <option value="2">Level 2</option>
                                                                </select>
                                                            @endif
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4" id="iLevel2CallAttendentIdDiv"
                                                        {{ $ticketInfo->LevelId == 2 || Session::get('exeLevel') == 2 ? 'style=display:block;' : 'style=display:none;' }}>

                                                        <div class="form-group">
                                                            <label>Select Level 2 Executive</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                name="iLevel2CallAttendentId" id="iLevel2CallAttendentId">
                                                                {{-- {{ $ticketInfo->LevelId == 2 || Session::get('exeLevel') == 2 ? 'required' : '' }} --}}
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                @if ($ticketInfo->LevelId == 2 || Session::get('exeLevel') == 2)
                                                                    @foreach ($executiveList as $exe)
                                                                        <option value="{{ $exe->iCallAttendentId }}">
                                                                            {{ $exe->strFirstName . ' ' . $exe->strLastName }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div> <!-- /.form-group -->

                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Images (max 3, each below 10 MB)</label>
                                                            <input class="form-control py-6" type="file"
                                                                id="tcktImages" name="tcktImages[]" multiple
                                                                accept="image/*" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Video (max 2, each below 200 MB)</label>
                                                            <input class="form-control py-6" type="file"
                                                                id="tcktVideo" name="tcktVideo[]" multiple
                                                                accept="video/*" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Solution Type</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                name="iResolutionCategoryId" id="iResolutionCategoryId">
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                @foreach ($resolutionCategories as $category)
                                                                    <option
                                                                        value="{{ $category->iResolutionCategoryId }}">
                                                                        {{ $category->strResolutionCategory }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Issue</label>
                                                            <input type="text" class="form-control" name="strIssue"
                                                                id="strIssue" />
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Issue Type</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                name="iIssueTypeId" id="iIssueTypeId">
                                                                <option label="Please Select" value="">-- Select --
                                                                </option>
                                                                @foreach ($issuetypes as $issues)
                                                                    <option value="{{ $issues->iSSueTypeId }}">
                                                                        {{ $issues->strIssueType }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Suggested Resolution</label>
                                                            <input type="text" class="form-control"
                                                                name="newResolution" id="newResolution" value="" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="form-group">
                                                            <label>Add Comments</label>
                                                            <input type="text" class="form-control" name="comments"
                                                                id="comments" value="" />
                                                        </div>
                                                    </div> <!-- /.col -->
                                                </div>
                                                <input type="hidden" name="oldStatus"
                                                    value="{{ in_array($ticketInfo->finalStatus, [1, 4, 5]) ? '3' : $ticketInfo->finalStatus }}">
                                                <input type="hidden" name="oldStatusDatetime"
                                                    value="{{ in_array($ticketInfo->finalStatus, [1, 4, 5]) ? date('Y-m-d H:i:s') : '' }}">
                                                @if ($ticketInfo->LevelId == 2 && Session::get('exeLevel') == 1)
                                                @else
                                                    <input type="submit"
                                                        class="btn btn-fill btn-success text-uppercase mt-3"
                                                        value="Submit" id="submit">
                                                @endif
                                            </form>
                                        </div>
                                    </div> <!-- /.row -->
                                </div>
                                <!--container end-->
                            </div>
                        </div>
                        <!--card end-->
                    </div>
                </div><!-- end row -->
                <!--notify messages-->

                <!--END notify messages-->
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022 Mas
                            Solutions. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                                href="https://www.excellentcomputers.co.in/" target="_blank"> Excellent Computers </a>
                        </span>
                    </div>
                </div>
            </footer>
            <!-- partial -->

        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>

    <!-- Plugin js for this page -->
    <!--step wizard-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/material-bootstrap-wizard.js') }}"></script>

    <!--Plugin js for this page -->
    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>
    <!--lightbox gallery-->

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $("#LevelId").change();
        });
        $("#iStatus").change(function() {
            if (this.value == 1 || this.value == 4 || this.value == 5) {
                @if ($ticketInfo->LevelId == 2 || Session::get('exeLevel') == 2)
                    $('#LevelId').val('2').change();
                    // $("#iLevel2CallAttendentId").attr('required', 'required');
                @else
                    $('#LevelId').val('1').change();
                @endif
                $("#LevelId").attr('disabled', true);
                $("#iLevel2CallAttendentIdDiv").val('');
            } else {
                $("#LevelId").attr('disabled', false);
            }
        });
        $("#LevelId").change(function() {
            if (this.value == 2) {
                $("#iLevel2CallAttendentIdDiv").css("display", "block");
                var oemCompanyId = $("#OemCompannyId").val();
                var url = "{{ route('complaint.getExecutives', ':id') }}";
                url = url.replace(":id", oemCompanyId);
                $.ajax({
                    type: 'GET',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            $("#iLevel2CallAttendentId").html(response);
                        } else {
                            $("#iLevel2CallAttendentIdDiv").css("display", "none");
                        }
                    }
                });
            } else if (this.value == 1) {
                $("#iLevel2CallAttendentIdDiv").css("display", "none");
            }
        });

        $("#tcktImages").on("change", function(e) {

            var count = 1;
            var files = e.currentTarget.files; // puts all files into an array
            var approvedHTML = '';
            // call them as such; files[0].size will get you the file size of the 0th file
            for (var x in files) {

                var filesize = ((files[x].size / 1024) / 1024).toFixed(4); // MB

                if (files[x].name != "item" && typeof files[x].name != "undefined" && filesize <= 10) {

                    if (count > 1) {

                        approvedHTML += ", " + files[x].name;
                    } else {

                        approvedHTML += files[x].name;
                    }

                    count++;
                }
            }

            // $("#approvedFiles").val(approvedHTML);

        });
        // $('#submit').on("click", function() {
        //    $('#loading').css("display", "block");
        //     $.ajax({
        //         type: 'POST',
        //         url: "{{ route('complaint.update') }}",
        //         data: $('#frmparameter').serialize(),
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {

        //             console.log(response);
        //               $('#loading').css("display", "none");
        //             if (response == 1) {
        //                 window.location.href = "{{ route('complaint.index') }}"
        //             }

        //         }
        //     });

        // });
    </script>
@endsection
