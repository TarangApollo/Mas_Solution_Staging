@extends('layouts.callAttendant')

@section('title', 'Complaints Info')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('global/assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/fonts/mas-solution/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />

    <!--@include('call_attendant.callattendantcommon.alert')-->

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex justify-content-center">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-sm-5 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <h3 class="m-0">Complaint Details</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <div class="card mt-5">
                            <div class="card-body p-0">
                                {{--  <div class="wizard-container">  --}}
                                @include('call_attendant.callattendantcommon.alert')
                                <h4 class="card-title mt-0">Complaint Details for ID:
                                    {{ $ticketInfo->strTicketUniqueID ?? str_pad($ticketInfo->iTicketId, 4, '0', STR_PAD_LEFT) }}
                                </h4>
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
                                                        <td>{{ $ticketInfo->strTicketUniqueID ?? str_pad($ticketInfo->iTicketId, 4, '0', STR_PAD_LEFT) }}
                                                        </td>
                                                        <input type="hidden" name="oemCompanyId" id="oemCompanyId"
                                                            value="{{ $ticketInfo->OemCompannyId }}">
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Status</td>
                                                        <td>{{ $ticketInfo->finstatus }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Complaint Date</td>
                                                        <td>{{ date('d-m-Y', strtotime($ticketInfo->ComplainDate)) }}
                                                            <small
                                                                class="position-static">{{ date('H:i:s', strtotime($ticketInfo->ComplainDate)) }}</small>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Resolved Date</td>
                                                        <td>
                                                            @if ($ticketInfo->ResolutionDate != '')
                                                                {{ date('d-m-Y', strtotime($ticketInfo->ResolutionDate)) }}
                                                                <small
                                                                    class="position-static">{{ date('H:i:s', strtotime($ticketInfo->ResolutionDate)) }}</small>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Customer Name</td>
                                                        <td>{{ $ticketInfo->CustomerName }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>Customer number</td>
                                                        <td>{{ $ticketInfo->CustomerMobile }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7</td>
                                                        <td>OEM Company</td>
                                                        <td>{{ $ticketInfo->strOEMCompanyName }}</td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!--/. responsive table 1 div-->
                                        <hr> <!-- responsive table 2 div-->
                                        <div class="table-responsive">
                                            <div class="row mx-1">
                                                <div class="col-md-6 col-xs-8">
                                                    <h4>Advance Information</h4>
                                                    <p>Latest Updated by:
                                                        {{ $ticketInfo->TicketEditedBy ?? $ticketInfo->TicketCreatedBy }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6 col-xs-4 text-right mt-2">
                                                    <button type="button" class="btn btn-success text-uppercase"
                                                        data-toggle="modal" data-target="#edit-info" id="editInfo">
                                                        edit info
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
                                                        <td>8</td>
                                                        <td>Customer Email</td>
                                                        <td>{{ $ticketInfo->CustomerEmail }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>9</td>
                                                        <td>Customer Company</td>
                                                        <td>{{ $ticketInfo->CompanyName }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>10</td>
                                                        <td>Company Profile</td>
                                                        <td>{{ $ticketInfo->strCompanyClientProfile }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>11</td>
                                                        <td>Company Email ID</td>
                                                        <td>{{ $ticketInfo->CustomerEmailCompany }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>12</td>
                                                        <td>Other Information</td>
                                                        <td>{{ $ticketInfo->OtherInformation }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>13</td>
                                                        <td>Distributor</td>
                                                        <td>{{ $ticketInfo->Name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>14</td>
                                                        <td>Call Connected Through</td>
                                                        <td>{{ $ticketInfo->iCallThrough }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>15</td>
                                                        <td>User Defined</td>
                                                        <td>{{ $ticketInfo->UserDefiine1 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>16</td>
                                                        <td>Project</td>
                                                        <td>{{ $ticketInfo->ProjectName }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>17</td>
                                                        <td>Project State</td>
                                                        <td>{{ $ticketInfo->strStateName }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>18</td>
                                                        <td>Project City</td>
                                                        <td>{{ $ticketInfo->strCityName }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>19</td>
                                                        <td>System</td>
                                                        <td>{{ $ticketInfo->strSystem }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>20</td>
                                                        <td>Component</td>
                                                        <td>{{ $ticketInfo->strComponent }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>21</td>
                                                        <td>Sub Component</td>
                                                        <td>{{ $ticketInfo->strSubComponent }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>22</td>
                                                        <td>Support Type</td>
                                                        <td class="ws-break">{{ $ticketInfo->strSupportType }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>23</td>
                                                        <td>Issue</td>
                                                        <td class="ws-break">{{ $ticketInfo->issue }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>24</td>
                                                        <td>Resolution Details</td>
                                                        <td class="ws-break">{{ $ticketInfo->Resolutiondetail }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>25</td>
                                                        <td>Issue Type</td>
                                                        <td>{{ $ticketInfo->strIssueType }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>26</td>
                                                        <td>Solution Type</td>
                                                        <td>{{ $ticketInfo->strResolutionCategory }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>27</td>
                                                        <td>Caller Competency</td>
                                                        <td class="ws-break">{{ $ticketInfo->strCallCompetency }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>28</td>
                                                        <td>Comments</td>
                                                        <td class="ws-break">{{ $ticketInfo->comments }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <!--/. responsive table div-->
                                        <!-- image / video row -->

                                        <div class="row">
                                            <!-- level 1 images start -->
                                            @if (count($ticketDetail) > 0)
                                                <div class="col-md-12 ml-3">
                                                    <h4>Level 1 images & videos</h4>
                                                    <!-- First row of images -->
                                                    <div class="row">

                                                        @foreach ($ticketDetail as $index => $tDetail)
                                                            @if ($tDetail->DocumentType == 2)
                                                                <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                    <div data-toggle="modal"
                                                                        data-index="{{ $index }}"
                                                                        data-target="#myModal">
                                                                        <img src="{{ asset('ticket_images/') . '/' . $tDetail->DocumentName }}"
                                                                            alt="Image 1" data-target="#myCarousel"
                                                                            data-slide-to="{{ $tDetail->iTicketDetailId }}">
                                                                    </div>
                                                                    @if (Auth::User()->id == $tDetail->iEnterBy)
                                                                        <div class="text-center del-img mt-2">
                                                                            <form
                                                                                action="{{ route('callList.delete', $tDetail->iTicketDetailId) }}"
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
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            @elseif($tDetail->DocumentType == 3)
                                                                <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                    <div data-toggle="modal"
                                                                        data-index="{{ $index }}"
                                                                        data-target="#myModal">
                                                                        <img src="{{ asset('global/assets/images/customer/photo/video.jpg') }}"
                                                                            alt="Image 1" data-target="#myCarousel"
                                                                            data-slide-to="{{ $tDetail->iTicketDetailId }}">

                                                                    </div>
                                                                    @if (Auth::User()->id == $tDetail->iEnterBy)
                                                                        <div class="text-center del-img mt-2">
                                                                            <form
                                                                                action="{{ route('callList.delete', $tDetail->iTicketDetailId) }}"
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
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
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
                                                                            @foreach ($ticketDetail as $index => $tDetail)
                                                                                <li data-target="#myCarousel"
                                                                                    data-slide-to="{{ $index }}"
                                                                                    class="{{ $loop->first ? 'active' : '' }}">
                                                                                </li>
                                                                                <?php $i++; ?>
                                                                            @endforeach

                                                                        </ol>
                                                                        <div class="carousel-inner">
                                                                            <?php $i = 1; ?>
                                                                            @foreach ($ticketDetail as $index => $tDetail)
                                                                                @if ($tDetail->DocumentType == 2)
                                                                                    <div
                                                                                        class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                        <img src="{{ asset('ticket_images/') . '/' . $tDetail->DocumentName }}"
                                                                                            class="d-block w-100"
                                                                                            alt="Image 1">
                                                                                    </div>
                                                                                @elseif($tDetail->DocumentType == 3)
                                                                                    <div
                                                                                        class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                        <div
                                                                                            class="embed-responsive embed-responsive-16by9">

                                                                                            <div
                                                                                                class="embed-responsive-item">
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
                                                </div> <!-- /.col 12 level 1 images -->
                                            @endif
                                            @if ($ticketInfo->aws_identifier != '')
                                                <!-- level 1 recording start -->
                                                <div class="col-md-12 mb-4 ml-3">
                                                    <h4>Level 1 Call recording</h4>
                                                    <audio controls>
                                                        <source src="{{ asset('ticket_audio/') . '/' .$ticketInfo->aws_identifier }}" type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </div>
                                            @elseif ($ticketInfo->recordUrl != '')
                                                <!-- level 1 recording start -->
                                                <div class="col-md-12 mb-4 ml-3">
                                                    <h4>Level 1 Call recording</h4>
                                                    <audio controls>
                                                        <source src="{{ $ticketInfo->recordUrl }}" type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- /. image / video row -->
                                    </div>
                                    <!--/. col 6-->
                                    <div class="col-lg-6 col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped" data-role="content"
                                                data-plugin="selectable" data-row-selectable="true">
                                                <thead class="bg-grey-100">
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Status</th>
                                                        <th>Open at Level</th>
                                                        <th>Date of Updates</th>
                                                        <th>Attend by</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $iCounter = 1; ?>
                                                    @foreach ($tickethistory as $history)
                                                        <tr>
                                                            <td>{{ $iCounter }}</td>
                                                            <td class="ws-break">{{ $history['Status'] }}</td>
                                                            <td>{{ $history['Level'] }}</td>
                                                            <td>{{ date('d-m-Y', strtotime($history['Date'])) }}<br>
                                                                <small
                                                                    class="position-static">{{ date('H:i:s', strtotime($history['Date'])) }}</small>
                                                            </td>
                                                            <td class="">{{ $history['user'] }}
                                                                @if (isset($history['TicketAssignTo']) && $history['TicketAssignTo'] != '')
                                                                    <br />
                                                                    <small
                                                                        class="position-static">{{ 'Assign to : ' . $history['TicketAssignTo'] }}</small>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <?php $iCounter++; ?>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                        <!--/. table 1 resonsive div-->

                                        <!-- table 2 responsive -->

                                        @if (count($ticketLogs) > 0)
                                            <div class="table-responsive">
                                                @foreach ($ticketLogs as $Detail)
                                                    <div class="row mx-1">
                                                        <div class="col-md-6 col-xs-8">
                                                            <h4>Level {{ $Detail->LevelId }} Ticket Details</h4>
                                                        </div>

                                                        @if ($Detail->iEntryBy == Auth::user()->id)
                                                            <div class="col-md-6 col-xs-4 text-right mt-2">

                                                                <button type="button"
                                                                    class="btn btn-success text-uppercase"
                                                                    data-toggle="modal"
                                                                    data-target="#edit-ticket-detail-info"
                                                                    id="edit_Ticket_detail_Info"
                                                                    onclick="getTicketData(<?= $Detail->iTicketLogId ?>);">
                                                                    edit info
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- leval 2 ticket details -->
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
                                                                <td>Executive Name</td>
                                                                <td>{{ $Detail->first_name . ' ' . $Detail->last_name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>Status</td>
                                                                <td>{{ $Detail->ticketstatus }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>Predefined Resolution</td>
                                                                <td>{{ $Detail->strResolutionCategory }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>4</td>
                                                                <td>Issue</td>
                                                                <td class="ws-break">{{ $Detail->strIssue }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>5</td>
                                                                <td> Issue Type </td>
                                                                <td class="ws-break">{{ $Detail->strIssueType }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>6</td>
                                                                <td>Suggested Resolution</td>
                                                                <td class="ws-break">{{ $Detail->newResolution }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>7</td>
                                                                <td>Comments</td>
                                                                <td class="ws-break">{{ $Detail->comments }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>8</td>
                                                                <td>Next Assign</td>
                                                                <td class="ws-break">Level {{ $Detail->LevelId }}</td>
                                                            </tr>
                                                            @if ($Detail->LevelId == 2)
                                                                <tr>
                                                                    <td>9</td>
                                                                    <td>Assign To Executive</td>
                                                                    <td>
                                                                        {{ $Detail->strFirstName . ' ' . $Detail->strLastName }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            
                                                            @if ($Detail->aws_identifier != '')
                                                <!-- level 1 recording start -->
                                                <div class="col-md-12 mb-4 ml-3">
                                                    <h4>Level 1 Call recording</h4>
                                                    <audio controls>
                                                        <source src="{{ asset('ticket_audio/') . '/' .$Detail->aws_identifier }}" type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </div>
                                            
                                            @elseif ($Detail->recordUrl != '')
                                                                {{-- <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Recording:
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                                    <audio controls>
                                                                        <source src="{{ $Detail->recordUrl }}"
                                                                            type="audio/mpeg">
                                                                        Your browser does not support the audio element.
                                                                    </audio>
                                                                </div> --}}
                                                            @endif
                                                        </tbody>
                                                    </table>


                                                    <!-- level 2 images start -->
                                                    @if (count($Detail->gallery) > 0)
                                                        <div class="col-12 ml-3">
                                                            <h4>Images & Videos by
                                                                {{ $Detail->first_name . ' ' . $Detail->last_name }}</h4>
                                                            <!-- First row of images -->
                                                            <div class="row pt-3">
                                                                @foreach ($Detail->gallery as $index => $gallery)
                                                                    @php
                                                                        $modalId = 'modal-' . $gallery->iTicketLogId; // Unique ID for each modal
                                                                        $carouselId =
                                                                            'carousel-' . $gallery->iTicketLogId; // Unique ID for each carousel
                                                                    @endphp
                                                                    <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                        <div data-toggle="modal"
                                                                            data-target="#{{ $modalId }}"
                                                                            data-index="{{ $index }}">
                                                                            <img src="{{ $gallery->DocumentType == 2
                                                                                ? asset('ticket_images/' . $gallery->DocumentName)
                                                                                : asset('global/assets/images/customer/photo/video.jpg') }}"
                                                                                alt="Image {{ $index }}"
                                                                                class="img-fluid">
                                                                        </div>
                                                                        @if ($Detail->iEntryBy == Auth::user()->id)
                                                                            <div class="text-center del-img mt-2">
                                                                                <form
                                                                                    action="{{ route('callList.delete', $gallery->iTicketDetailId) }}"
                                                                                    method="POST"
                                                                                    onsubmit="return confirm('Are you sure you want to delete this?');"
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
                                                                        @endif
                                                                    </div>

                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="{{ $modalId }}"
                                                                        tabindex="-1" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Close">
                                                                                        <span
                                                                                            aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <!-- Carousel -->
                                                                                    <div id="{{ $carouselId }}"
                                                                                        class="carousel slide"
                                                                                        data-ride="carousel">
                                                                                        <!-- Carousel Indicators -->
                                                                                        <ol class="carousel-indicators">
                                                                                            @foreach ($Detail->gallery as $galleryIndex => $galleryItem)
                                                                                                <li data-target="#{{ $carouselId }}"
                                                                                                    data-slide-to="{{ $galleryIndex }}"
                                                                                                    class="{{ $galleryIndex == 0 ? 'active' : '' }}">
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ol>

                                                                                        <div class="carousel-inner">
                                                                                            @foreach ($Detail->gallery as $galleryIndex => $galleryItem)
                                                                                                <div
                                                                                                    class="carousel-item {{ $galleryIndex == 0 ? 'active' : '' }}">
                                                                                                    @if ($galleryItem->DocumentType == 2)
                                                                                                        <img src="{{ asset('ticket_images/' . $galleryItem->DocumentName) }}"
                                                                                                            class="d-block w-100"
                                                                                                            alt="Image {{ $galleryIndex }}">
                                                                                                    @elseif($galleryItem->DocumentType == 3)
                                                                                                        <div
                                                                                                            class="embed-responsive embed-responsive-16by9">
                                                                                                            <video controls
                                                                                                                name="media">
                                                                                                                <source
                                                                                                                    src="{{ asset('ticket_video/' . $galleryItem->DocumentName) }}"
                                                                                                                    type="video/mp4">
                                                                                                            </video>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </div>
                                                                                            @endforeach
                                                                                        </div>

                                                                                        <a class="carousel-control-prev"
                                                                                            href="#{{ $carouselId }}"
                                                                                            role="button"
                                                                                            data-slide="prev">
                                                                                            <span
                                                                                                class="carousel-control-prev-icon"
                                                                                                aria-hidden="true"></span>
                                                                                            <span
                                                                                                class="sr-only">Previous</span>
                                                                                        </a>
                                                                                        <a class="carousel-control-next"
                                                                                            href="#{{ $carouselId }}"
                                                                                            role="button"
                                                                                            data-slide="next">
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



                                                    @if ($Detail->recordUrl)
                                                        <div class="col-12 ml-3">
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Recording:
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                                    <audio controls>
                                                                        <source src="{{ $Detail->recordUrl }}"
                                                                            type="audio/mpeg">
                                                                        Your browser does not support the audio element.
                                                                    </audio>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <hr>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- additional recording -->
                                        <div class="col-md-12 mb-4">


                                            <!-- title & button row -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h4>Additional Recording</h4>
                                                </div>
                                                <div class="col-md-6 text-right mt-2">
                                                    <button type="button" class="btn btn-success text-uppercase"
                                                        data-toggle="modal" data-target="#add-recording">
                                                        Add Recording
                                                    </button>
                                                </div>
                                            </div>
                                            <!--/. title & button row -->

                                            @foreach ($additionalRecording as $addiData)
                                                @if ($addiData->recordUrl != '' || $addiData->recordUrl != null)
                                                    <div class="row mt-4">
                                                        <div class="col-md-7">
                                                            <audio controls>
                                                                <source src="{{ $addiData->recordUrl }}"
                                                                    type="audio/mpeg">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </div><!--/. md 7-->
                                                        <div class="col-md-4">
                                                            <p>
                                                                {{ $addiData->callId }} <br>
                                                                28-03-2024 <br>
                                                                {{ $addiData->first_name . ' ' . $addiData->last_name }}
                                                            </p>
                                                        </div><!--/. md 4-->
                                                        <div class="col-md-1">
                                                            <div class="text-center del-img mt-2">
                                                                <form
                                                                    action="{{ route('complaint.additionalRecordingdelete', $addiData->iTicketCallId) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                                    style="display: inline-block;">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <input type="hidden" name="_token"
                                                                        value="{{ csrf_token() }}">
                                                                    <button type="submit" class="p-0 border-0 bg-none">
                                                                        <i class="mas-trash mas-1x" title="Delete"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div><!--/. md 1-->
                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>
                                        <!--/. additional recording -->

                                        <!-- additional Images & Video -->
                                        <div class="col-md-12 mb-4">
                                            <hr>
                                            <!-- title & button row -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h4>Additional Images & Videos</h4>
                                                </div>
                                                <div class="col-md-6 text-right mt-2">
                                                    <button type="button" class="btn btn-success text-uppercase"
                                                        data-toggle="modal" data-target="#add-image">
                                                        Add
                                                    </button>
                                                </div>
                                            </div>
                                            <!--/. title & button row -->

                                            <!-- image row -->
                                            <div class="row mt-4">

                                                @foreach ($additionalData as $index => $addiData)
                                                    @if ($addiData->DocumentType == 2)
                                                        <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                            <div data-toggle="modal" data-index="{{ $index }}"
                                                                data-target="#additionalmyModal">
                                                                <img src="{{ asset('ticket_images/') . '/' . $addiData->DocumentName }}"
                                                                    alt="Image 1" data-target="#additionalmyCarousel"
                                                                    data-slide-to="{{ $addiData->iTicketDetailId }}">
                                                            </div>
                                                            @if (Auth::User()->id == $addiData->iEnterBy)
                                                                <div class="text-center del-img mt-2">
                                                                    <form
                                                                        action="{{ route('callList.delete', $addiData->iTicketDetailId) }}"
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

                                                                </div>
                                                            @endif
                                                            <p class="text-center">
                                                                {{ $addiData->first_name . ' ' . $addiData->last_name }}
                                                                <br>
                                                                {{ date('d-m-Y', strtoTime($addiData->strEntryDate)) }}
                                                            </p>
                                                        </div>
                                                    @else
                                                        <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                            <div data-toggle="modal" data-index="{{ $index }}"
                                                                data-target="#additionalmyModal">

                                                                <img src="{{ asset('global/assets/images/customer/photo/video.jpg') }}"
                                                                    alt="Image 1" data-target="#additionalmyCarousel"
                                                                    data-slide-to="{{ $addiData->iTicketDetailId }}">

                                                            </div>
                                                            @if (Auth::User()->id == $addiData->iEnterBy)
                                                                <div class="text-center del-img mt-2">
                                                                    <form
                                                                        action="{{ route('callList.delete', $addiData->iTicketDetailId) }}"
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

                                                                </div>
                                                            @endif
                                                            <p class="text-center">
                                                                {{ $addiData->first_name . ' ' . $addiData->last_name }}
                                                                <br>
                                                                {{ date('d-m-Y', strtoTime($addiData->strEntryDate)) }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach


                                                <div class="modal fade" id="additionalmyModal" tabindex="-1"
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
                                                                <div id="additionalmyCarousel" class="carousel slide">
                                                                    <ol class="carousel-indicators">
                                                                        <?php $i = 1; ?>
                                                                        @foreach ($additionalData as $addiData)
                                                                            <li data-target="#additionalmyCarousel"
                                                                                data-slide-to="{{ $index }}"
                                                                                class="{{ $loop->first ? 'active' : '' }}">
                                                                            </li>
                                                                            <?php $i++; ?>
                                                                        @endforeach

                                                                    </ol>
                                                                    <div class="carousel-inner">
                                                                        <?php $i = 1; ?>
                                                                        @foreach ($additionalData as $addiData)
                                                                            @if ($addiData->DocumentType == 2)
                                                                                <div
                                                                                    class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                    <img src="{{ asset('ticket_images/') . '/' . $addiData->DocumentName }}"
                                                                                        class="d-block w-100"
                                                                                        alt="Image 1">
                                                                                </div>
                                                                            @elseif($addiData->DocumentType == 3)
                                                                                <div
                                                                                    class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                                                    <div
                                                                                        class="embed-responsive embed-responsive-16by9">

                                                                                        <div class="embed-responsive-item">
                                                                                            <video controls=""
                                                                                                name="media">
                                                                                                <source
                                                                                                    src="{{ asset('ticket_video/') . '/' . $addiData->DocumentName }}"
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
                                                                        href="#additionalmyCarousel" role="button"
                                                                        data-slide="prev">
                                                                        <span class="carousel-control-prev-icon"
                                                                            aria-hidden="true"></span>
                                                                        <span class="sr-only">Previous</span>
                                                                    </a>
                                                                    <a class="carousel-control-next"
                                                                        href="#additionalmyCarousel" role="button"
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
                                                </div>

                                            </div>

                                        </div>
                                        <!--/. additional Images & Video -->

                                    </div>
                                    <!--/. col 6-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--card end-->
                </div>
            </div><!-- end row -->
        </div>
        <div class="modal fade bd-example-modal-lg" id="edit-info" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Advance Information for
                            {{ str_pad($ticketInfo->iTicketId, 4, '0', STR_PAD_LEFT) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="was-validated" action="{{ route('callattendantAdmin.update') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="iTicketId" id="iTicketId"
                                value="{{ $ticketInfo->iTicketId }}">
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Customer Email</label>
                                        <input type="text" name="CustomerEmail"
                                            value="{{ $ticketInfo->CustomerEmail ?? '' }}" id="CustomerEmail"
                                            class="form-control" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Customer Company</label>
                                        <select class="js-example-basic-single" name="iCompanyId" id="iCompanyId"
                                            style="width: 100%;">
                                            {{-- <option label="Please Select" value="">-- Select --</option>
                                  <option value="fs">Fire System</option>
                                  <option value="af">Attentive Fire</option> --}}
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4" id="otherCompanyDiv" style="display: none;">
                                    <div class="form-group">
                                        <label>Other Company Name*</label>
                                        <input type="text" class="form-control" name="othrcompanyname"
                                            id="othrcompanyname" value="{{ $ticketInfo->othrcompanyname ?? '' }}" />
                                    </div>
                                </div> <!-- /.col -->

                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Customer Company Profile</label>
                                        <select class="js-example-basic-single" id="iCompanyProfileId"
                                            name="iCompanyProfileId" style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4" id="otherCompanyProfileDiv" style="display: none;">
                                    <div class="form-group">
                                        <label>Other Company Profile*</label>
                                        <input type="text" class="form-control" name="othrcompanyprofile"
                                            id="othrcompanyprofile"
                                            value="{{ $ticketInfo->othrcompanyprofile ?? '' }}" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Customer Company Email ID</label>
                                        <input type="text" class="form-control" name="CustomerEmailCompany"
                                            id="CustomerEmailCompany"
                                            value="{{ $ticketInfo->CustomerEmailCompany ?? '' }}" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Other Information</label>
                                        <input type="text" class="form-control" name="OtherInformation"
                                            id="OtherInformation" value="{{ $ticketInfo->OtherInformation ?? '' }}" />
                                    </div>
                                </div> <!-- /.col -->

                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Distributor</label>
                                        <select class="js-example-basic-single" id="iDistributorId" name="iDistributorId"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            {{-- <option value="fs">Distributor 1</option>
                                <option value="af">Distributor 2</option>
                                <option value="otc">Distributor 3</option> --}}
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Call Connected Through </label>
                                        <select class="js-example-basic-single" name="iCallThrough" id="iCallThrough"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            <option value="Phone"
                                                {{ isset($ticketInfo->iCallThrough) && $ticketInfo->iCallThrough == 'Phone' ? 'selected' : '' }}>
                                                Phone</option>
                                            <option value="Whatsapp"
                                                {{ isset($ticketInfo->iCallThrough) && $ticketInfo->iCallThrough == 'Whatsapp' ? 'selected' : '' }}>
                                                Whatsapp</option>
                                            <option value="Email"
                                                {{ isset($ticketInfo->iCallThrough) && $ticketInfo->iCallThrough == 'Email' ? 'selected' : '' }}>
                                                Email</option>
                                            <option value="Other"
                                                {{ isset($ticketInfo->iCallThrough) && $ticketInfo->iCallThrough == 'Other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>User defined 1</label>
                                        <input type="text" class="form-control" name="UserDefiine1" id="UserDefiine1"
                                            value="{{ $ticketInfo->UserDefiine1 ?? '' }}" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Project Name</label>
                                        <input type="text" class="form-control" name="ProjectName" id="ProjectName"
                                            value="{{ $ticketInfo->ProjectName ?? '' }}" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Project State</label>
                                        <select class="js-example-basic-single" id="iStateId" name="iStateId"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            <option value="fs">System Integrator</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Project City</label>
                                        <select class="js-example-basic-single" name="iCityId" id="iCityId"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                

                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>System</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iSystemId"
                                            id="iSystemId">
                                            <option label="Please Select" value="">-- Select --</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Component</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iComponentId"
                                            id="iComponentId">
                                            <option value="">-- Select --</option>
                                            <option value="{{ $ticketInfo->iComponentId }}" selected>
                                                {{ $ticketInfo->strComponent }}</option>
                                        </select>
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Sub Component</label>
                                        <select class="js-example-basic-single" style="width: 100%;"
                                            name="iSubComponentId[]" id="iSubComponentId" multiple>
                                            <option value="">-- Select --</option>
                                            <option value="{{ $ticketInfo->iSubComponentId }}" selected>
                                                {{ $ticketInfo->strSubComponent }}</option>
                                        </select>
                                    </div>
                                </div> <!-- /.col -->

                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Support Type</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iSupportType"
                                            id="iSupportType">
                                            <option label="Please Select" value="">-- Select --</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Issue</label>
                                        <input type="text" class="form-control" name="issue" id="issue"
                                            value="{{ $ticketInfo->issue ?? '' }}" />
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Resolution Details</label>
                                        <input type="text" class="form-control" name="Resolutiondetail"
                                            id="Resolutiondetail" value="{{ $ticketInfo->Resolutiondetail ?? '' }}" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Issue Type</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iIssueTypeId"
                                            id="iIssueTypeId">
                                            <option label="Please Select" value="">-- Select --</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Solution Type</label>
                                        <select class="js-example-basic-single" style="width: 100%;"
                                            name="iResolutionCategoryId" id="iResolutionCategoryId">
                                            <option label="Please Select" value="">-- Select --</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Caller Competency </label>
                                        <select class="js-example-basic-single" style="width: 100%;"
                                            name="CallerCompetencyId" id="CallerCompetencyId">
                                            <option label="Please Select" value="">-- Select --</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Comments </label>
                                        <input class="form-control" name="comments" id="comments"
                                            value="{{ $ticketInfo->comments ?? '' }}" />
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                            </div><!--/.row-->
                        </div><!--main body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Clear</button>
                            <button type="submit" class="btn btn-fill btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->

        <!-- modal add images start -->
        <div class="modal fade" id="add-image" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Add New Images & Videos for
                            {{ str_pad($ticketInfo->iTicketId, 4, '0', STR_PAD_LEFT) }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('complaint.additionalImagesVideosStore') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="iTicketId" value="{{ $ticketInfo->iTicketId }}">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Upload Images (max 10, each below 10 MB)</label>
                                        <input name="additional_images[]" class="form-control py-6" type="file"
                                            id="additional_images" multiple accept="image/*" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Upload Video (max 2, each below 200 MB)</label>
                                        <input name="additional_videos[]" class="form-control py-6" type="file"
                                            id="formFileMultiple" multiple accept="video/*" />
                                    </div>
                                </div>
                            </div>
                        </div><!--main body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary">Clear</button>
                            <button type="submit" class="btn btn-fill btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /. modal add images End -->


        <!-- modal add recording start -->
        <div class="modal fade" id="add-recording" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Recording for
                            {{ str_pad($ticketInfo->iTicketId, 4, '0', STR_PAD_LEFT) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('complaint.additionalRecordingStore') }}" method="post">
                        @csrf
                        <input type="hidden" name="iTicketId" value="{{ $ticketInfo->iTicketId }}">

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Add Multiple IDs by using ","</label>
                                        <input id="inputField" type="text" name="recordingIds"
                                            oninput="validateInput()" class="form-control" />
                                        <p id="errorMessage" style="color: red;"></p>

                                    </div>
                                </div>
                            </div>
                        </div><!--main body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary">Clear</button>
                            <button type="submit" class="btn btn-fill btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /. modal add recording End -->

        <!-- /. Edit Ticket detail Info Start -->
        <div class="modal fade bd-example-modal-lg" id="edit-ticket-detail-info" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Ticket Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="was-validated" action="{{ route('complaint.ticket_detail_data_update') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="iTicketId" id="iTicketId"
                                value="{{ $ticketInfo->iTicketId }}">
                            <input type="hidden" name="iTicketLogId" id="GetiTicketLogId">
                            <div class="row">

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label>Select Status</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iStatus"
                                            id="iStatus" required>
                                            <option value="0">Open</option>
                                            <option value="3">Reopen</option>
                                            <option value="1">Closed</option>
                                            <option value="5">Customer Feedback Awaited </option>
                                            <option value="4">Closed with RMA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label>Select Level</label>
                                        @if ($ticketInfo->LevelId == 2 || Session::get('exeLevel') == 2)
                                            <select class="js-example-basic-single" style="width: 100%;" required
                                                name="LevelId" id="LevelId">
                                                <option value="2">Level 2</option>
                                            </select>
                                        @else
                                            <select class="js-example-basic-single" style="width: 100%;" required
                                                name="LevelId" id="LevelId">
                                                <option value="1">Level 1</option>
                                                <option value="2">Level 2</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-lg-4 col-md-4" id="iLevel2CallAttendentIdDiv"
                                    {{ $ticketInfo->LevelId == 2 || Session::get('exeLevel') == 2 ? 'style=display:block;' : 'style=display:none;' }}>
                                    <div class="form-group">
                                        <label>Select Level 2 Executive</label>
                                        <select class="js-example-basic-single" style="width: 100%;"
                                            name="iLevel2CallAttendentId" id="iLevel2CallAttendentId">
                                            <option value="" label="Please Select" value="">-- Select --
                                            </option>
                                            @if ($ticketInfo->LevelId == 2 || Session::get('exeLevel') == 2)
                                                @foreach ($executiveList as $exe)
                                                    <option value="{{ $exe->iCallAttendentId }}">
                                                        {{ $exe->strFirstName . ' ' . $exe->strLastName }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label>Upload Images (max 3, each below 10 MB)</label>
                                        <input class="form-control py-6" type="file" id="tcktImages"
                                            name="tcktImages[]" multiple accept="image/*" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label>Upload Video (max 2, each below 200 MB)</label>
                                        <input class="form-control py-6" type="file" id="tcktVideo"
                                            name="tcktVideo[]" multiple accept="video/*" />
                                    </div>
                                </div> <!-- /.col -->

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label>Solution Type</label>
                                        <select class="js-example-basic-single" style="width: 100%;"
                                            name="iResolutionCategoryId" id="EditiResolutionCategoryId">
                                            <option label="Please Select" value="">-- Select --
                                            </option>
                                            @foreach ($resolutionCategories as $category)
                                                <option value="{{ $category->iResolutionCategoryId }}">
                                                    {{ $category->strResolutionCategory }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> <!-- /.col -->


                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label>Issue</label>
                                        <input type="text" class="form-control" name="strIssue" id="strIssue" />
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label>Issue Type</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iIssueTypeId"
                                            id="EditiIssueTypeId">
                                            <option label="Please Select" value="">-- Select --
                                            </option>
                                            @foreach ($issuetypes as $issues)
                                                <option value="{{ $issues->iSSueTypeId }}">
                                                    {{ $issues->strIssueType }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label>Suggested Resolution</label>
                                        <input type="text" class="form-control" name="newResolution"
                                            id="newResolution" value="" />
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label>Add Comments</label>
                                        <input type="text" class="form-control" name="comments" id="Editcomments"
                                            value="" />
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                            </div><!--/.row-->
                        </div><!--main body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Clear</button>
                            <button type="submit" class="btn btn-fill btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /. Edit Ticket detail Info End -->



        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="container">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022 Mas
                        Solutions. All rights reserved.</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                            href="https://www.excellentcomputers.co.in/" target="_blank"> Excellent Computers </a> </span>
                </div>
            </div>
        </footer>
        <!-- partial -->

    </div>
    <!-- main-panel ends -->
    <!--</div>-->
    <!-- page-body-wrapper ends -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Listen to the modal 'show.bs.modal' event for all modals
            $('.modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var index = button.data('index'); // Extract the slide index
                var modal = $(this); // Current modal
                var carousel = modal.find('.carousel'); // Find the carousel within the modal

                // Activate the specific carousel item
                carousel.find('.carousel-item').removeClass('active').eq(index).addClass('active');
                carousel.find('.carousel-indicators li').removeClass('active').eq(index).addClass('active');

                // Reset the carousel position
                carousel.carousel(index);
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#additionalmyModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var index = button.data('index'); // Extract info from data-* attributes
                var carousel = $('#additionalmyCarousel');

                // Activate the specific carousel item
                carousel.find('.carousel-item').removeClass('active').eq(index).addClass('active');
                carousel.find('.carousel-indicators li').removeClass('active').eq(index).addClass('active');

                // Reset the carousel position
                carousel.carousel(index);
            });
        });
    </script>

    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/custom.js') }}"></script>
    <!--Plugin js for this page -->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/material-bootstrap-wizard.js') }}"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>
    <script type="text/javascript">
        $("#editInfo").click(function() {
            var oemCompnyId = $("#oemCompanyId").val();
            $("#CustomerEmailCompany").val('');
            var iTicketId = $("#iTicketId").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('company.geteditCompany') }}",
                data: {
                    iCompanyId: oemCompnyId,
                    iTicketId: iTicketId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    console.log(response);
                    let dataItems = JSON.parse(response);
                    $("#iCompanyId").html(dataItems.client);
                    $("#iCompanyProfileId").html(dataItems.profile);
                    $("#iDistributorId").html(dataItems.distributor);
                    $("#iStateId").html(dataItems.stateList);
                    $("#iCityId").html(dataItems.cityList);
                    $("#iSystemId").html(dataItems.system);
                    $("#iComponentId").html(dataItems.component);
                    $("#iSubComponentId").html(dataItems.subcomponent);
                    $("#iSupportType").html(dataItems.supporttype);
                    $("#CallerCompetencyId").html(dataItems.callcompetency);
                    $("#iIssueTypeId").html(dataItems.issuetype);
                    $("#iResolutionCategoryId").html(dataItems.resolutionCategory);
                    $("#iLevel2CallAttendentId").html(dataItems.exeList);
                }

            });
        });
        $("#iCompanyId").change(function() {
            if (this.value == 'Other') {
                $("#otherCompanyDiv").css("display", "block");
                $("#othrcompanyname").attr("required", "true");
                $("#CustomerEmailCompany").val("");
            } else {
                $("#otherCompanyDiv").css("display", "none");
                $("#othrcompanyname").removeAttr("required");
                $.ajax({
                    type: 'POST',
                    url: "{{ route('company.getCompanyClientEmail') }}",
                    data: {
                        iCompanyId: this.value
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        $("#CustomerEmailCompany").val(response);

                    }
                });
            }
        });

        $("#iCompanyProfileId").change(function() {
            if (this.value == 'Other') {
                $("#otherCompanyProfileDiv").css("display", "block");
                $("#othrcompanyprofile").attr("required", "true");
            } else {
                $("#otherCompanyProfileDiv").css("display", "none");
                $("#othrcompanyprofile").removeAttr("required");
            }
        });

        $("#iSystemId").change(function() {
            $.ajax({
                type: 'POST',
                url: "{{ route('company.getcomponent') }}",
                data: {
                    search_system: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (response.length > 0) {
                        $("#iComponentId").html(response);
                    } else {

                    }
                }
            });
        });
        $("#iComponentId").change(function() {
            $("#iSubComponentIdDiv").css("display", "block");
            $.ajax({
                type: 'POST',
                url: "{{ route('faq.getsubcomponent') }}",
                data: {
                    iComponentId: this.value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (response.length > 0) {
                        $("#iSubComponentId").html(response);
                    } else {
                        $("#iSubComponentIdDiv").css("display", "none");
                    }
                }
            });
        });
    </script>


    <script>
        $("#additional_images").on("change", function(e) {

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
    </script>

    <script>
        function validateInput() {
            var input = document.getElementById('inputField').value;
            var commaCount = (input.match(/,/g) || []).length;

            if (commaCount > 1) {
                document.getElementById('errorMessage').innerText = "Please enter only one comma";
                document.getElementById('inputField').setCustomValidity("Please enter only one comma");
            } else {
                document.getElementById('errorMessage').innerText = "";
                document.getElementById('inputField').setCustomValidity("");
            }
        }
    </script>

    <script>
        function getTicketData(id) {

            var iTicketLogId = id;
            var iTicketId = $("#iTicketId").val();

            $.ajax({
                type: 'GET',
                url: "{{ route('complaint.get_ticket_detail_data') }}",
                data: {
                    iTicketId: iTicketId,
                    iTicketLogId: iTicketLogId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    console.log(response);
                    let dataItems = JSON.parse(response);

                    // Set dropdown values
                    if ($("#iStatus option[value='" + dataItems.iStatus + "']").length) {
                        $("#iStatus").val(dataItems.iStatus).trigger('change');
                    }
                    $("#LevelId").val(dataItems.LevelId);
                    if ($("#iLevel2CallAttendentId option[value='" + dataItems.iCallAttendentId + "']")
                        .length) {
                        $("#iLevel2CallAttendentId").val(dataItems.iCallAttendentId).trigger('change');
                    }

                    if ($("#EditiResolutionCategoryId option[value='" + dataItems.iResolutionCategoryId + "']")
                        .length) {
                        $("#EditiResolutionCategoryId").val(dataItems.iResolutionCategoryId).trigger('change');
                    }

                    if ($("#EditiIssueTypeId option[value='" + dataItems.iIssueTypeId + "']")
                        .length) {
                        $("#EditiIssueTypeId").val(dataItems.iIssueTypeId).trigger('change');
                    }


                    $("#strIssue").val(dataItems.strIssue);

                    $("#newResolution").val(dataItems.newResolution);

                    $("#iSubComponentId").val(dataItems.subcomponent);
                    $("#iSupportType").val(dataItems.supporttype);
                    $("#CallerCompetencyId").val(dataItems.callcompetency);
                    $("#iIssueTypeId").val(dataItems.issuetype);
                    $("#iResolutionCategoryId").val(dataItems.resolutionCategory);
                    $("#Editcomments").val(dataItems.comments);
                    $("#GetiTicketLogId").val(iTicketLogId);
                }
            });
        }
    </script>


@endsection
