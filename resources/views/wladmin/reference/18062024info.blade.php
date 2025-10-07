@extends('layouts.wladmin')

@section('title', 'Edit New Reference Information')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Reference Information</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item"><a href="{{ route('reference.index') }}">Reference Documents</a></li>
                    <li class="breadcrumb-item active"> Infornmation</li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
        <!-- first row starts here -->

        <div class="row">
            <div class="col-xl-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">Reference ID: RF{{ $References->iRefId }}</h4>
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
                                                <td>Reference ID</td>
                                                <td>RF{{ $References->iRefId }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Reference Title</td>
                                                <td>{{ $References->strRefTitle }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>System</td>
                                                <td>{{ $References->strSystem }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Component</td>
                                                <td>{{ $References->strComponent }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Sub Component</td>
                                                <td>{{ $References->strSubComponent }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--responsive table div-->
                                <!-- image / video row -->
                                <div class="row mx-1">
                                    <!-- Documents start -->
                                    @if (count($refDocuments) > 0)
                                        <div class="col-md-12">
                                            <h4 class="">Documents</h4>
                                            <div class="row">
                                                @foreach ($refDocuments as $doc)
                                                    <div class="col-md-3 col-xs-6 gallery-box text-center">

                                                        @php
                                                            $extension = pathinfo(
                                                                $doc->strFileName,
                                                                PATHINFO_EXTENSION,
                                                            );
                                                        @endphp
                                                        @if ($extension == 'xls' || $extension == 'xlsx')
                                                            <a onclick="openDoc('{{ $doc->iRefDocumentId }}')">
                                                                <img src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                                    alt="document" title="{{ $doc->strFileName }}">
                                                            </a>
                                                        @elseif ($extension == 'doc' || $extension == 'docx')
                                                            <a onclick="openDoc('{{ $doc->iRefDocumentId }}')">
                                                                <img src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                                    alt="document" title="{{ $doc->strFileName }}">
                                                            </a>
                                                        @else
                                                            <a onclick="openDoc('{{ $doc->iRefDocumentId }}')">
                                                                <img src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                                    alt="document" title="{{ $doc->strFileName }}">
                                                            </a>
                                                        @endif

                                                        {{--  <a onclick="openDoc('{{ $doc->iRefDocumentId }}')">
                                                            <img src="{{ asset('global/assets/images/reference/photo/document.jpg') }}"
                                                                alt="document" title="{{ $doc->strFileName }}">
                                                        </a>  --}}
                                                    </div><!-- /.col -->
                                                @endforeach
                                            </div> <!-- /.row -->
                                        </div> <!-- /.col 12 Documents -->
                                    @endif
                                    <!-- images start -->
                                    @if (count($refImages) > 0)
                                        <div class="col-md-12">
                                            <h4 class="">Images</h4>
                                            <div class="row">
                                                @foreach ($refImages as $image)
                                                    <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                        <div data-toggle="modal" data-target="#myModal">
                                                            <img src="{{ asset('RefDocument/Image/') . '/' . $image->strFileName }}"
                                                                alt="{{ $image->strFileName }}" data-target="#myCarousel"
                                                                data-slide-to="{{ $image->iRefDocumentId }}">
                                                        </div>
                                                    </div><!-- /.col -->
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
                                                                    @foreach ($refImages as $image)
                                                                        <li data-target="#myCarousel"
                                                                            data-slide-to="{{ $image->iRefDocumentId }}"
                                                                            {{ $i == 1 ? 'class="active"' : '' }}></li>
                                                                        <?php $i++; ?>
                                                                    @endforeach

                                                                </ol>
                                                                <div class="carousel-inner">
                                                                    <?php $i = 1; ?>
                                                                    @foreach ($refImages as $image)
                                                                        <div
                                                                            class="carousel-item    {{ $i == 1 ? 'active' : '' }}">
                                                                            <img src="{{ asset('RefDocument/Image/') . '/' . $image->strFileName }}"
                                                                                class="d-block w-100" alt="Image 1">
                                                                        </div>
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
                                        </div> <!-- /.col 12 images -->
                                    @endif
                                    <!-- videos start -->
                                    @if (count($refVideos) > 0)
                                        <div class="col-md-12">
                                            <h4 class="ml-3">Videos</h4>
                                            <div class="row">
                                                @foreach ($refVideos as $video)
                                                    <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                        <div data-toggle="modal" data-target="#myModal1">
                                                            <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                                alt="Video 2" data-target="#myCarousel1"
                                                                data-slide-to="{{ $video->iRefDocumentId }}"
                                                                title="{{ $video->strFileName }}">
                                                        </div>
                                                    </div><!-- /.col -->
                                                @endforeach

                                            </div> <!-- /.row -->
                                            <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                            <!-- Modal -->
                                            <div class="modal fade" id="myModal1" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal1"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div> <!-- /.header -->
                                                        <div class="modal-body">
                                                            <!-- Carousel -->
                                                            <div id="myCarousel1" class="carousel slide">
                                                                <ol class="carousel-indicators">
                                                                    <?php $i = 1; ?>
                                                                    @foreach ($refVideos as $video)
                                                                        <li data-target="#myCarousel1"
                                                                            data-slide-to="{{ $video->iRefDocumentId }}"
                                                                            {{ $i == 1 ? 'class="active"' : '' }}></li>
                                                                        <?php $i++; ?>
                                                                    @endforeach
                                                                </ol>
                                                                <div class="carousel-inner">
                                                                    <?php $i = 1; ?>
                                                                    @foreach ($refVideos as $video)
                                                                        <div
                                                                            class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                                                                            <div
                                                                                class="embed-responsive embed-responsive-16by9">
                                                                                {{-- <iframe class="embed-responsive-item" src="{{asset('RefDocument/Video/').'/'.$video->strFileName}}" allowfullscreen></iframe> --}}
                                                                                <div class="embed-responsive-item">
                                                                                    <video controls="" name="media">
                                                                                        <source
                                                                                            src="{{ asset('RefDocument/Video/') . '/' . $video->strFileName }}"
                                                                                            type="video/mp4">
                                                                                    </video>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php $i++; ?>
                                                                    @endforeach
                                                                </div> <!-- /.carousel inner -->
                                                                <a class="carousel-control-prev" href="#myCarousel1"
                                                                    role="button" data-slide="prev">
                                                                    <span class="carousel-control-prev-icon"
                                                                        aria-hidden="true"></span>
                                                                    <span class="sr-only">Previous</span>
                                                                </a>
                                                                <a class="carousel-control-next" href="#myCarousel1"
                                                                    role="button" data-slide="next">
                                                                    <span class="carousel-control-next-icon"
                                                                        aria-hidden="true"></span>
                                                                    <span class="sr-only">Next</span>
                                                                </a>
                                                            </div> <!-- /.carousel slide -->

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal1">Close</button>
                                                        </div> <!-- /.footer -->
                                                    </div>
                                                </div>
                                            </div> <!-- /.modal -->
                                        </div> <!-- /.col 12 videos -->
                                    @endif
                                </div>
                                <!-- /. image / video row -->
                            </div>
                            <!--/. col 6-->
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    @if ($infoTables)
                                        <table class="table table-striped" data-role="content" data-plugin="selectable"
                                            data-row-selectable="true">
                                            <thead class="bg-grey-100">
                                                <tr>
                                                    <th>Sr. No</th>
                                                    <th>Date of Updates</th>
                                                    <th>Updated by</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $icounter = 1; ?>
                                                @foreach ($infoTables as $log)
                                                    <tr>
                                                        <td>{{ $icounter }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($log->strEntryDate)) }}<br>
                                                            <small
                                                                class="position-static">{{ date('H:i:s', strtotime($log->strEntryDate)) }}</small>
                                                        </td>
                                                        <td>{{ $log->actionBy }}</td>
                                                    </tr>
                                                    <?php $icounter++; ?>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
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
            var newurl = "{{ route('reference.openDocument', ':id') }}";
            newurl = newurl.replace(':id', url);
            newurl = newurl.replace('?', '/');
            window.open(newurl, '_blank');

        }
    </script>
@endsection
