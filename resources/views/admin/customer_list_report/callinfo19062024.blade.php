@extends('layouts.admin')

@section('title', 'Call Information')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Call Information</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Report</li>
                    <li class="breadcrumb-item"><a href="javascript:history.back(-1)">Call List</a></li>
                    <li class="breadcrumb-item active">Information</li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
        <!-- first row starts here -->

        <div class="row">
            <div class="col-xl-12 stretch-card grid-margin">
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
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Status</td>
                                                <td>{{ $ticketInfo->ticketName }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Complaint Date</td>
                                                <td>{{ date('d-m-Y', strtotime($ticketInfo->ComplainDate)) }} <small
                                                        class="position-static">{{ date('H:i:s', strtotime($ticketInfo->ComplainDate)) }}</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Resolved Date</td>
                                                <td>
                                                    @if ($ticketInfo->ResolutionDate != '')
                                                        {{ date('d-m-Y', strtotime($ticketInfo->ResolutionDate)) }} <small
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
                                        <div class="col-md-12">
                                            <h4>Advance Information</h4>
                                            <p>Latest Updated by:
                                                {{ $ticketInfo->TicketEditedBy ?? $ticketInfo->TicketCreatedBy }}</p>
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
                                        </tbody>
                                        <tr>
                                            <td>8</td>
                                            <td>Customer Email</td>
                                            <td>{{ $ticketInfo->CustomerEmail }}</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>User Company</td>
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
                                <!--responsive table div-->
                                <!-- image / video row -->
                                <div class="row">
                                    <!-- Documents start -->
                                    @if (count($ticketDetail) > 0)
                                        <div class="col-md-12 ml-3">
                                            <h4>Level 1 images & videos</h4>
                                            <!-- First row of images -->
                                            <div class="row">

                                                @foreach ($ticketDetail as $tDetail)
                                                    @if ($tDetail->DocumentType == 2)
                                                        <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                            <div data-toggle="modal" data-target="#myModal">
                                                                <img src="{{ asset('ticket_images/') . '/' . $tDetail->DocumentName }}"
                                                                    alt="Image 1" data-target="#myCarousel"
                                                                    data-slide-to="{{ $tDetail->iTicketDetailId }}">
                                                            </div>
                                                        </div><!-- /.col -->
                                                    @elseif($tDetail->DocumentType == 3)
                                                        <!-- <div class="carousel-item">
                                                                <div class="embed-responsive embed-responsive-16by9">
                                                                    <iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}" allowfullscreen></iframe>
                                                                </div>
                                                            </div> -->
                                                        <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                            <div data-toggle="modal" data-target="#myModal">
                                                                <img src="{{ asset('global/assets/images/customer/photo/video.jpg') }}"
                                                                    alt="Image 1" data-target="#myCarousel"
                                                                    data-slide-to="{{ $tDetail->iTicketDetailId }}">
                                                                <!-- <iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}" allowfullscreen></iframe> -->
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </div> <!-- /.row -->
                                            <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                            <!-- Modal -->
                                            <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
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
                                                                                    class="d-block w-100" alt="Image 1">
                                                                            </div>
                                                                        @elseif($tDetail->DocumentType == 3)
                                                                            <div
                                                                                class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                                                                                <div
                                                                                    class="embed-responsive embed-responsive-16by9">
                                                                                    <!--<iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $tDetail->DocumentName }}" allowfullscreen></iframe>-->
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
                                    <table class="table table-striped" data-role="content" data-plugin="selectable"
                                        data-row-selectable="true">
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

                                @if (count($ticketLogs) > 0)
                                    @foreach ($ticketLogs as $Detail)
                                        <div class="table-responsive">
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
                                                        <td>{{ $Detail->first_name . ' ' . $Detail->last_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Status</td>
                                                        <td>{{ $Detail->ticketName }}</td>
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
                                                            <td class="ws-break">
                                                                {{ $Detail->strFirstName . ' ' . $Detail->strLastName }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($Detail->recordUrl != '')
                                                        <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Recording:</div>
                                                        <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                            <audio controls>
                                                                <source src="{{ $Detail->recordUrl }}" type="audio/mpeg">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </div>
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
                                                                <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                    <div data-toggle="modal" data-target="#myModal1">
                                                                        <img src="{{ asset('ticket_images/') . '/' . $gallery->DocumentName }}"
                                                                            alt="Image 1" data-target="#myCarousel1"
                                                                            data-slide-to="0">
                                                                    </div>
                                                                </div><!-- /.col -->
                                                            @elseif($gallery->DocumentType == 3)
                                                                <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                                    <div data-toggle="modal" data-target="#myModal1">
                                                                        <img src="{{ asset('global/assets/images/customer/photo/video.jpg') }}"
                                                                            alt="Image 1" data-target="#myCarousel1"
                                                                            data-slide-to="0">
                                                                        <!-- <iframe class="embed-responsive-item" src="{{ asset('ticket_video/') . '/' . $gallery->DocumentName }}" allowfullscreen></iframe> -->
                                                                    </div>
                                                                </div>
                                                            @else
                                                            @endif
                                                        @endforeach


                                                    </div> <!-- /.row -->
                                                    <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="myModal1" tabindex="-1"
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
                                                                    <div id="myCarousel1" class="carousel slide">
                                                                        <ol class="carousel-indicators">
                                                                            <?php $i = 1; ?>
                                                                            @foreach ($Detail->gallery as $gallery)
                                                                                <li data-target="#myCarousel1"
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
                                                                                            <iframe
                                                                                                class="embed-responsive-item"
                                                                                                src="{{ asset('ticket_video/') . '/' . $gallery->DocumentName }}"
                                                                                                allowfullscreen></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                <?php $i++; ?>
                                                                            @endforeach
                                                                        </div> <!-- /.carousel inner -->
                                                                        <a class="carousel-control-prev"
                                                                            href="#myCarousel1" role="button"
                                                                            data-slide="prev">
                                                                            <span class="carousel-control-prev-icon"
                                                                                aria-hidden="true"></span>
                                                                            <span class="sr-only">Previous</span>
                                                                        </a>
                                                                        <a class="carousel-control-next"
                                                                            href="#myCarousel1" role="button"
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
                                                </div> <!-- /.col 12 level 2 images -->
                                                @if ($Detail->recordUrl)
                                                    <div class="col-lg-4 col-md-12 col-xs-6 mb-2">Recording:</div>
                                                    <div class="col-lg-8 col-md-12 col-xs-6 mb-2">
                                                        <audio controls>
                                                            <source src="{{ $Detail->recordUrl }}" type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
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
