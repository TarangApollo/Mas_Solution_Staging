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

    @include('call_attendant.callattendantcommon.alert')

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex flex-row-reverse">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-md-2 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <p class="m-0 pr-8">Complaint Details</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- first row starts here -->
                <div class="row d-flex justify-content-center my-5 pb-5">
                    <div class="col-sm-10">
                        <div class="card">
                            <div class="card-body p-0">
                                <h4 class="card-title mt-0">Complaint Details for ID:
                                    {{ str_pad($ticketInfo->iTicketId, 4, '0', STR_PAD_LEFT) }}</h4>
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
                                                        <td>{{ str_pad($ticketInfo->iTicketId, 4, '0', STR_PAD_LEFT) }}</td>
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
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h4>Advance Information</h4>
                                                    <p>Latest Updated by:
                                                        {{ $ticketInfo->TicketEditedBy ?? $ticketInfo->TicketCreatedBy }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6 text-right mt-2">
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

                                                        @foreach ($ticketDetail as $tDetail)
                                                            @if ($tDetail->DocumentType == 2)
                                                                <div class="col-md-2 gallery-box">
                                                                    <div data-toggle="modal" data-target="#myModal">
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
                                                                <!-- <div class="carousel-item">
                                                                                                                                                                                    <div class="embed-responsive embed-responsive-16by9">
                                                                                                                                                                                        <iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}" allowfullscreen></iframe>
                                                                                                                                                                                    </div>
                                                                                                                                                                                </div> -->
                                                                <div class="col-md-2 gallery-box">
                                                                    <div data-toggle="modal" data-target="#myModal">
                                                                        <img src="{{ asset('global/assets/images/customer/photo/video.jpg') }}"
                                                                            alt="Image 1" data-target="#myCarousel"
                                                                            data-slide-to="{{ $tDetail->iTicketDetailId }}">
                                                                        <!-- <iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}" allowfullscreen></iframe> -->
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
                                                                            @foreach ($ticketDetail as $tDetail)
                                                                                <li data-target="#myCarousel"
                                                                                    data-slide-to="{{ $tDetail->iTicketDetailId }}"
                                                                                    {{ $i == 1 ? 'class="active"' : '' }}>
                                                                                </li>
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
                                                                                            <!--<iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}" allowfullscreen></iframe>-->
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
                                            @if ($ticketInfo->recordUrl != '')
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
                                    <div class="col-md-6">
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
                                                            <td>{{ $history['Status'] }}</td>
                                                            <td>{{ $history['Level'] }}</td>
                                                            <td>{{ date('d-m-Y', strtotime($history['Date'])) }}<br>
                                                                <small
                                                                    class="position-static">{{ date('H:i:s', strtotime($history['Date'])) }}</small>
                                                            </td>
                                                            <td>{{ $history['user'] }}</td>
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
                                                    <h4>Level {{ $Detail->LevelId }} Ticket Details</h4>
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
                                                                <td>Suggested Resolution</td>
                                                                <td class="ws-break">{{ $Detail->newResolution }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>5</td>
                                                                <td>Comments</td>
                                                                <td class="ws-break">{{ $Detail->comments }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>6</td>
                                                                <td>Next Assign</td>
                                                                <td class="ws-break">Level {{ $Detail->LevelId }}</td>
                                                            </tr>
                                                            @if ($Detail->LevelId == 2)
                                                                <tr>
                                                                    <td>7</td>
                                                                    <td>Assign To Executive</td>
                                                                    <td>
                                                                        {{ $Detail->strFirstName . ' ' . $Detail->strLastName }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if ($Detail->recordUrl != '')
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
                                                                {{ $Detail->first_name . '' . $Detail->last_name }}</h4>
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
                                                                                    data-slide-to="0">
                                                                            </div>
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
                                                                        </div><!-- /.col -->
                                                                    @elseif($gallery->DocumentType == 3)
                                                                        <div class="col-md-2 gallery-box">
                                                                            <div data-toggle="modal"
                                                                                data-target="#myModal{{ $Detail->iTicketLogId }}">
                                                                                <img src="{{ asset('global/assets/images/customer/photo/video.jpg') }}"
                                                                                    alt="Image 1"
                                                                                    data-target="#myCarousel{{ $Detail->iTicketLogId }}"
                                                                                    data-slide-to="0">
                                                                                <!-- <iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $gallery->DocumentName }}" allowfullscreen></iframe> -->
                                                                            </div>
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
                                                                        </div>
                                                                    @else
                                                                    @endif
                                                                @endforeach


                                                            </div> <!-- /.row -->
                                                            <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                                            <!-- Modal -->
                                                            <div class="modal fade"
                                                                id="myModal{{ $Detail->iTicketLogId }}" tabindex="-1"
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
                                                                                                    <div
                                                                                                        class="embed-responsive-item">
                                                                                                        <video
                                                                                                            controls=""
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
                                                                                </div>

                                                                                <a class="carousel-control-prev"
                                                                                    href="#myCarousel{{ $Detail->iTicketLogId }}"
                                                                                    role="button" data-slide="prev">
                                                                                    <span
                                                                                        class="carousel-control-prev-icon"
                                                                                        aria-hidden="true"></span>
                                                                                    <span class="sr-only">Previous</span>
                                                                                </a>
                                                                                <a class="carousel-control-next"
                                                                                    href="#myCarousel{{ $Detail->iTicketLogId }}"
                                                                                    role="button" data-slide="next">
                                                                                    <span
                                                                                        class="carousel-control-next-icon"
                                                                                        aria-hidden="true"></span>
                                                                                    <span class="sr-only">Next</span>
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
                                                        </div> <!-- /.col 12 level 2 images -->
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
                                                                <i class="mas-trash mas-1x" title="Delete"></i>
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

                                                @foreach ($additionalData as $addiData)
                                                    @if ($addiData->DocumentType == 2)
                                                        <div class="col-md-3 gallery-box">
                                                            <div data-toggle="modal" data-target="#myModal">
                                                                <img src="{{ asset('ticket_images/') . '/' . $addiData->DocumentName }}"
                                                                    alt="Image 1" data-target="#myCarousel"
                                                                    data-slide-to="1">
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
                                                        <div class="col-md-3 gallery-box">
                                                            <div data-toggle="modal" data-target="#myModal">
                                                                <img src="{{ asset('ticket_video/') . '/' . $addiData->DocumentName }}"
                                                                    alt="Video 2" data-target="#myCarousel"
                                                                    data-slide-to="3">
                                                            </div>
                                                            <div class="text-center del-img mt-2">
                                                                <form
                                                                    action="{{ route('callList.delete', $addiData->iTicketDetailId) }}"
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
                                                            <p class="text-center">
                                                                {{ $addiData->first_name . ' ' . $addiData->last_name }}
                                                                <br>
                                                                {{ date('d-m-Y', strtoTime($addiData->strEntryDate)) }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </div>

                                        </div>
                                        <!--/. additional Images & Video -->

                                    </div>
                                    <!--/. col 6-->
                                </div>
                            </div>
                        </div>
                        <!--card end-->
                    </div>
                </div><!-- end row -->
            </div>
            <div class="modal fade bd-example-modal-lg" id="edit-info" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Customer Email</label>
                                            <input type="text" name="CustomerEmail"
                                                value="{{ $ticketInfo->CustomerEmail ?? '' }}" id="CustomerEmail"
                                                class="form-control" />
                                        </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
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
                                    <div class="col-md-4" id="otherCompanyDiv" style="display: none;">
                                        <div class="form-group">
                                            <label>Other Company Name*</label>
                                            <input type="text" class="form-control" name="othrcompanyname"
                                                id="othrcompanyname" value="{{ $ticketInfo->othrcompanyname ?? '' }}" />
                                        </div>
                                    </div> <!-- /.col -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Customer Company Profile</label>
                                            <select class="js-example-basic-single" id="iCompanyProfileId"
                                                name="iCompanyProfileId" style="width: 100%;">
                                                <option label="Please Select" value="">-- Select --</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4" id="otherCompanyProfileDiv" style="display: none;">
                                        <div class="form-group">
                                            <label>Other Company Profile*</label>
                                            <input type="text" class="form-control" name="othrcompanyprofile"
                                                id="othrcompanyprofile"
                                                value="{{ $ticketInfo->othrcompanyprofile ?? '' }}" />
                                        </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Customer Company Email ID</label>
                                            <input type="text" class="form-control" name="CustomerEmailCompany"
                                                id="CustomerEmailCompany"
                                                value="{{ $ticketInfo->CustomerEmailCompany ?? '' }}" />
                                        </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Other Information</label>
                                            <input type="text" class="form-control" name="OtherInformation"
                                                id="OtherInformation"
                                                value="{{ $ticketInfo->OtherInformation ?? '' }}" />
                                        </div>
                                    </div> <!-- /.col -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Distributor</label>
                                            <select class="js-example-basic-single" id="iDistributorId"
                                                name="iDistributorId" style="width: 100%;">
                                                <option label="Please Select" value="">-- Select --</option>
                                                {{-- <option value="fs">Distributor 1</option>
                                <option value="af">Distributor 2</option>
                                <option value="otc">Distributor 3</option> --}}
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Project Name</label>
                                            <input type="text" class="form-control" name="ProjectName"
                                                id="ProjectName" value="{{ $ticketInfo->ProjectName ?? '' }}" />
                                        </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Project State</label>
                                            <select class="js-example-basic-single" id="iStateId" name="iStateId"
                                                style="width: 100%;">
                                                <option label="Please Select" value="">-- Select --</option>
                                                <option value="fs">System Integrator</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Project City</label>
                                            <select class="js-example-basic-single" name="iCityId" id="iCityId"
                                                style="width: 100%;">
                                                <option label="Please Select" value="">-- Select --</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>User defined 1</label>
                                            <input type="text" class="form-control" name="UserDefiine1"
                                                id="UserDefiine1" value="{{ $ticketInfo->UserDefiine1 ?? '' }}" />
                                        </div>
                                    </div> <!-- /.col -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>System</label>
                                            <select class="js-example-basic-single" style="width: 100%;" name="iSystemId"
                                                id="iSystemId">
                                                <option label="Please Select" value="">-- Select --</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Component</label>
                                            <select class="js-example-basic-single" style="width: 100%;"
                                                name="iComponentId" id="iComponentId">
                                                <option value="">-- Select --</option>
                                                <option value="{{ $ticketInfo->iComponentId }}" selected>
                                                    {{ $ticketInfo->strComponent }}</option>
                                            </select>
                                        </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
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

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Support Type</label>
                                            <select class="js-example-basic-single" style="width: 100%;"
                                                name="iSupportType" id="iSupportType">
                                                <option label="Please Select" value="">-- Select --</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Issue</label>
                                            <input type="text" class="form-control" name="issue" id="issue"
                                                value="{{ $ticketInfo->issue ?? '' }}" />
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Resolution Details</label>
                                            <input type="text" class="form-control" name="Resolutiondetail"
                                                id="Resolutiondetail"
                                                value="{{ $ticketInfo->Resolutiondetail ?? '' }}" />
                                        </div>
                                    </div> <!-- /.col -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Solution Type</label>
                                            <select class="js-example-basic-single" style="width: 100%;"
                                                name="iResolutionCategoryId" id="iResolutionCategoryId">
                                                <option label="Please Select" value="">-- Select --</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Issue Type</label>
                                            <select class="js-example-basic-single" style="width: 100%;"
                                                name="iIssueTypeId" id="iIssueTypeId">
                                                <option label="Please Select" value="">-- Select --</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Caller Competency </label>
                                            <select class="js-example-basic-single" style="width: 100%;"
                                                name="CallerCompetencyId" id="CallerCompetencyId">
                                                <option label="Please Select" value="">-- Select --</option>
                                            </select>
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
            <div class="modal fade" id="add-image" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
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
                                            <input type="text" name="recordingIds" class="form-control" />
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


            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022 Mas
                            Solutions. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                                href="#"> Excellent Computers </a> </span>
                    </div>
                </div>
            </footer>
            <!-- partial -->

        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->

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
@endsection
