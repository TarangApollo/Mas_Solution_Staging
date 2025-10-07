@extends('layouts.wladmin')

@section('title', 'Add Reference')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Edit Reference Documents</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item"><a href="{{ route('reference.index') }}">Reference Documents</a></li>
                    <li class="breadcrumb-item active"> Edit Reference Documents </li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
        <!-- first row starts here -->
        @include('wladmin.wlcommon.alert')
        <div class="row">
            <div class="col-xl-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">Edit Document</h4>
                        <form class="was-validated p-4 pb-3" id="frmReference" action="{{ route('reference.update') }}"
                            method="post" enctype='multipart/form-data'>
                            <input type="hidden" name="iCompanyId" id="iCompanyId"
                                value="{{ $CompanyMaster->iCompanyId }}">
                            <input type="hidden" name="iRefId" id="iRefId" value="{{ $Reference->iRefId }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Reference Title*</label>
                                        <input type="text" class="form-control" value="{{ $Reference->strRefTitle }}"
                                            name="strRefTitle" id="strRefTitle" required="">
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select system*</label>
                                        <select class="js-example-basic-single" name="iSystemId" id="iSystemId"
                                            style="width: 100%;" required>
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($systemLists as $system)
                                                <option value="{{ $system->iSystemId }}"
                                                    {{ $Reference->iSystemId == $system->iSystemId ? 'selected' : '' }}>
                                                    {{ $system->strSystem }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Component*</label>
                                        <select class="js-example-basic-single" name="iComponentId" id="iComponentId"
                                            style="width: 100%;" required>
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($componentLists as $component)
                                                <option value="{{ $component->iComponentId }}"
                                                    @if ($Reference->iComponentId == $component->iComponentId) {{ 'selected' }} @endif>
                                                    {{ $component->strComponent }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Sub Component</label>
                                        <select class="js-example-basic-single" name="iSubComponentId" id="iSubComponentId"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($subcomponents as $subcomponent)
                                                <option value="{{ $subcomponent->iSubComponentId }}"
                                                    @if ($Reference->iSubComponentId == $subcomponent->iSubComponentId) {{ 'selected' }} @endif>
                                                    {{ $subcomponent->strSubComponent }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Content Type</label>
                                        <?php $arrContentType = explode(',', $Reference->strContentType); ?>
                                        <select multiple="multiple" name="strContentType[]" id="strContentType"
                                            class="js-example-basic-single" style="width: 100%;" required>
                                            <option data-tokens="All" value="0"
                                                @if (in_array(0, $arrContentType)) {{ 'selected' }} @endif>All</option>
                                            <option data-tokens="Document" value="1"
                                                @if (in_array(1, $arrContentType)) {{ 'selected' }} @endif>Document
                                            </option>
                                            <option data-tokens="Image" value="2"
                                                @if (in_array(2, $arrContentType)) {{ 'selected' }} @endif>Image</option>
                                            <option data-tokens="Video" value="3"
                                                @if (in_array(3, $arrContentType)) {{ 'selected' }} @endif>Video</option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4 Document">
                                    <div class="form-group">
                                        <label>Upload Document (pdf, excel, word below 10 MB)</label>
                                        <input class="form-control" name="strDocument[]" id="strDocument" type="file"
                                            accept=".pdf,.doc,.docx,.xlsx,.xls" multiple />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-4 Image">
                                    <div class="form-group">
                                        <label>Upload Images (max 5, each below 10 MB)</label>
                                        <input class="form-control" name="strImages[]" id="strImages"
                                            onchange="checkImageFiles(this.files)" type="file" id="formFileMultiple"
                                            multiple="" accept="image/jpeg,image/gif,image/png" />
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-4 Video">
                                    <div class="form-group">
                                        <label>Upload Video (max 2, each below 200 MB)</label>
                                        <input class="form-control" name="strVideo[]" id="strVideo"
                                            onchange="checkVideoFiles(this.files)" type="file" id="formFileMultiple"
                                            multiple="" accept="video/*" />
                                    </div>
                                </div> <!-- /.col -->

                            </div>
                            <!-- <div class="row my-4">
                                                    <div class="col-3">
                                                        @if (count($refDocuments) > 0)
                                                            <span class="badge badge-success">Document</span>
                                                            <br>
                                                            @foreach ($refDocuments as $doc)
    <span class="badge badge-secondary">{{ $doc->strFileName }}</span>
    @endforeach
                                                        @endif
                                                        <br>
                                                        @if (count($refImages) > 0)

                                                            <span class="badge badge-success">Image</span>
                                                            <br>
                                                            @foreach ($refImages as $img)
    <span class="badge badge-secondary">{{ $img->strFileName }}</span>
    @endforeach
                                                        @endif
                                                        <br>
                                                        @if (count($refVideos) > 0)

                                                            <span class="badge badge-success">Video</span>
                                                            <br>
                                                            @foreach ($refVideos as $video)
    <span class="badge badge-secondary">{{ $video->strFileName }}</span>
    @endforeach
                                                        @endif
                                                    </div>
                                                </div> -->
                            <div class="row">
                                <!-- remove docs start -->
                                @if (count($refDocuments) > 0)
                                    <div class="col-md-4">
                                        <h4>Documents</h4>
                                        <div class="row">
                                            @foreach ($refDocuments as $doc)
                                                @php
                                                    $extension = pathinfo($doc->strFileName, PATHINFO_EXTENSION);
                                                @endphp
                                                <div class="col-md-3 col-xs-6 pr-0 gallery-box faq-remove"
                                                    id='doc_{{ $doc->iRefDocumentId }}'>
                                                    @if ($extension == 'xls' || $extension == 'xlsx')
                                                        <img src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                            alt="{{ $doc->strFileName }}"
                                                            title="{{ $doc->strFileName }}">
                                                    @elseif ($extension == 'doc' || $extension == 'docx')
                                                        <img src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                            alt="{{ $doc->strFileName }}"
                                                            title="{{ $doc->strFileName }}">
                                                    @else
                                                        <img src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                            alt="{{ $doc->strFileName }}"
                                                            title="{{ $doc->strFileName }}">
                                                    @endif
                                                    <div class="overlay">
                                                        <a onclick="deleteDoc('{{ $doc->iRefDocumentId }}')"
                                                            class="icon" title="{{ $doc->strFileName }}">
                                                            <i class="mas-trash"></i>
                                                        </a>
                                                    </div>
                                                </div><!-- /.col -->
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if (count($refImages) > 0)
                                    <div class="col-md-4">
                                        <h4>Images</h4>
                                        <div class="row">
                                            @foreach ($refImages as $img)
                                                <div class="col-md-3 pr-0 gallery-box faq-remove"
                                                    id='doc_{{ $img->iRefDocumentId }}'>
                                                    <img src="{{ asset('RefDocument/Image/') . '/' . $img->strFileName }}"
                                                        alt="Image" title="{{ $img->strFileName }}">
                                                    <div class="overlay">
                                                        <a onclick="deleteDoc('{{ $img->iRefDocumentId }}')"
                                                            class="icon" title="{{ $img->strFileName }}">
                                                            <i class="mas-trash"></i>
                                                        </a>
                                                    </div>
                                                </div><!-- /.col -->
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if (count($refVideos) > 0)
                                    <div class="col-md-4">
                                        <h4>Video</h4>
                                        <div class="row">
                                            @foreach ($refVideos as $video)
                                                <div class="col-md-3 pr-0 gallery-box faq-remove"
                                                    id='doc_{{ $video->iRefDocumentId }}'>
                                                    <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                        alt="Video" title="{{ $video->strFileName }}">
                                                    <div class="overlay">
                                                        <a onclick="deleteDoc('{{ $video->iRefDocumentId }}')"
                                                            class="icon" title="{{ $video->strFileName }}">
                                                            <i class="mas-trash"></i>
                                                        </a>
                                                    </div>
                                                </div><!-- /.col -->
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <input type="submit" class="btn btn-success text-uppercase mt-3 mr-2" value="Submit">
                            <input type="button" class="btn btn-default text-uppercase mt-3" onclick="clearData();"
                                value="Clear">
                        </form>
                    </div>
                    <!--card body end-->
                </div>
                <!--card end-->
            </div>
        </div>
        <!--row-->
    </div>
@endsection
@section('script')
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->

    <script>
        $(document).ready(function() {
            // $('.Document').hide();
            // $('.Image').hide();
            // $('.Video').hide();
            $("#strContentType").change(function() {
                var strContentType = $(this).val();
                $('.Document').hide();
                $('.Image').hide();
                $('.Video').hide();
                $.each($(this).find('option:selected'), function(index, item) {
                    var selected = $(item).data('tokens');
                    if (selected == 'All') {
                        $('.Document').show();
                        $('.Image').show();
                        $('.Video').show();
                        return true;
                    } else {
                        $("." + selected).show();
                    }
                });
            });
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
            var iComponentId = $(this).val();
            $.ajax({
                type: 'POST',
                url: "{{ route('faq.getsubcomponent') }}",
                data: {
                    iComponentId: iComponentId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#iSubComponentId").html(response);
                }
            });
        });

        function deleteDoc(id) {
            if (confirm("Are you sure you want to delete this document?")) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('reference.deletedoc') }}",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response == 0) {

                            $("#doc_" + id).css("display", "none");
                        } else {

                        }
                    }
                });
            }
            return false;
        }

        function checkImageFiles(files) {
            if (files.length > 5) {
                alert("length exceeded; Image have been truncated");

                let list = new DataTransfer;
                for (let i = 0; i < 5; i++)
                    list.items.add(files[i])

                document.getElementById('strImages').files = list.files
            }
        }

        function checkVideoFiles(files) {
            if (files.length > 2) {
                alert("length exceeded; Video have been truncated");

                let list = new DataTransfer;
                for (let i = 0; i < 2; i++)
                    list.items.add(files[i])

                document.getElementById('strVideo').files = list.files
            }
        }

        function clearData() {
            window.location.href = "";
        }
    </script>
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
    <script>
        // $('#frmReference').validate({
        //     rules: {
        //         "strDocument": {
        //             //extension: "jpg|jpeg|png",
        //             filesize: 10485760
        //         },
        //         "strImages[]": {
        //             //extension: "jpg|jpeg|png",
        //             filesize: 10485760,
        //         },
        //         "strVideo[]": {
        //             //extension: "jpg|jpeg|png",
        //             filesize: 209715200,
        //         }
        //     }
        // });
    </script>
@endsection
