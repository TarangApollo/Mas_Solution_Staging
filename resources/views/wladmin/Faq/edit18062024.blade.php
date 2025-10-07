@extends('layouts.wladmin')

@section('title', 'Edit New Faq')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Edit New Question</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item"><a href="{{ route('faq.index') }}">Faq</a></li>
                    <li class="breadcrumb-item active"> Edit Faq </li>
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
                        <h4 class="card-title mt-0">Edit Questions</h4>
                        <form class="was-validated p-4 pb-3" id="frmFaq" action="{{ route('faq.update') }}"
                            method="post" enctype='multipart/form-data'>
                            <input type="hidden" name="iCompanyId" id="iCompanyId"
                                value="{{ $CompanyMaster->iCompanyId }}">
                            <input type="hidden" name="iFAQId" id="iFAQId" value="{{ $Faqs->iFAQId }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Faq Title*</label>
                                        <input type="text" class="form-control" value="{{ $Faqs->strFAQTitle }}"
                                            name="strFAQTitle" id="strFAQTitle" required="">
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
                                                    {{ $Faqs->iSystemId == $system->iSystemId ? 'selected' : '' }}>
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
                                                    @if ($Faqs->iComponentId == $component->iComponentId) {{ 'selected' }} @endif>
                                                    {{ $component->strComponent }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->

                                <div class="col-md-4" id="iSubComponentIdDiv"
                                    {{ $Faqs->iSubComponentId != null ? 'style="display:block;"' : 'style="display:block;"' }}>
                                    <div class="form-group">
                                        <label>Select Sub Component</label>
                                        <select class="js-example-basic-single" name="iSubComponentId" id="iSubComponentId"
                                            style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($subcomponents as $subcomponent)
                                                <option value="{{ $subcomponent->iSubComponentId }}"
                                                    @if ($Faqs->iSubComponentId == $subcomponent->iSubComponentId) {{ 'selected' }} @endif>
                                                    {{ $subcomponent->strSubComponent }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Upload Document pdf, excel, word (Max 10, each below 10 MB)</label>
                                        <input class="form-control" name="strDocument[]" id="strDocument" type="file"
                                            accept=".pdf,.doc,.docx,.xlsx,.xls" multiple="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Upload Images (max 5, each below 10 MB)</label>
                                        <input class="form-control" name="strImages[]" id="strImages"
                                            onchange="checkImageFiles(this.files)" type="file" id="formFileMultiple"
                                            multiple="" accept="image/jpeg,image/gif,image/png">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Upload Video (max 2, each below 200 MB)</label>
                                        <input class="form-control" name="strVideo[]" id="strVideo"
                                            onchange="checkVideoFiles(this.files)" type="file" id="formFileMultiple"
                                            multiple="" accept="video/*">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- remove docs start -->
                                @if (count($faqDocuments) > 0)
                                    <div class="col-md-4">
                                        <h4>Documents</h4>
                                        <div class="row">
                                            @foreach ($faqDocuments as $doc)
                                                <div class="col-md-3 pr-0 gallery-box faq-remove"
                                                    id='doc_{{ $doc->iFAQDocumentId }}'>
                                                    <img src="{{ asset('global/assets/images/reference/photo/document.jpg') }}"
                                                        alt="document" title="name of the doument">
                                                    <div class="overlay">
                                                        <a onclick="deleteDoc('{{ $doc->iFAQDocumentId }}')"
                                                            class="icon" title="{{ $doc->strFileName }}">
                                                            <i class="mas-trash"></i>
                                                        </a>
                                                    </div>
                                                </div><!-- /.col -->
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if (count($faqImages) > 0)
                                    <div class="col-md-4">
                                        <h4>Images</h4>
                                        <div class="row">
                                            @foreach ($faqImages as $img)
                                                <div class="col-md-3 pr-0 gallery-box faq-remove"
                                                    id='doc_{{ $img->iFAQDocumentId }}'>
                                                    <img src="{{ asset('FaqDocument/Image/') . '/' . $img->strFileName }}"
                                                        alt="Image" title="{{ $img->strFileName }}">
                                                    <div class="overlay">
                                                        <a onclick="deleteDoc('{{ $img->iFAQDocumentId }}')"
                                                            class="icon" title="{{ $img->strFileName }}">
                                                            <i class="mas-trash"></i>
                                                        </a>
                                                    </div>
                                                </div><!-- /.col -->
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if (count($faqVideos) > 0)
                                    <div class="col-md-4">
                                        <h4>Video</h4>
                                        <div class="row">
                                            @foreach ($faqVideos as $video)
                                                <div class="col-md-3 pr-0 gallery-box faq-remove"
                                                    id='doc_{{ $video->iFAQDocumentId }}'>
                                                    <img src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                        alt="Video" title="{{ $video->strFileName }}">
                                                    <div class="overlay">
                                                        <a onclick="deleteDoc('{{ $video->iFAQDocumentId }}')"
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

                            <!--text editor row-->
                            <div class="row my-4">
                                <div class="col-12">
                                    <textarea id="strFAQDescription" name="strFAQDescription" rows="800" cols="80">{{ $Faqs->strFAQDescription }}
                                </textarea>
                                </div>

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

    <!-- <p><strong>1. High Battery Drainage problem in Samsung Mobiles</strong></p>

                                                                <p>
                                                                    If your battery is draining at an abnormal pace then this doesn’t mean bad battery health only. There might be some other reasons, which you can easily get control over, to obtain a good battery life throughout the day.
                                                                </p>

                                                                <p>Solution: Follow these simple steps to get your desired battery time:</p>

                                                                <ul>
                                                                    <li>Turn off GPS, Wi-Fi, NFC, Bluetooth, and other connectivity options if you are not using them.</li>
                                                                    <li>Clean the Apps by using a phone optimizer.</li>
                                                                    <li>Turn on the Power Saving Mode.</li>
                                                                    <li>Dim the screen brightness.</li>
                                                                    <li>Turn off the Always On Display.</li>
                                                                    <li>Remove Live Wallpapers.</li>
                                                                </ul> -->

@endsection
@section('script')
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->

    <script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
    <script>
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
            $("#iSubComponentIdDiv").css("display", "block");
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
                    if (response.length > 0) {
                        $("#iSubComponentId").html(response);
                    } else {
                        $("#iSubComponentIdDiv").css("display", "none");
                    }
                }
            });
        });

        function deleteDoc(id) {
            if (confirm("Are you sure you want to delete this document?")) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('faq.deletedoc') }}",
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

        $(function() {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('strFAQDescription');
            //bootstrap WYSIHTML5 - text editor
            $(".textarea").wysihtml5();
        });

        function clearData() {
            window.location.href = "";
        }
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $('#frmFaq').validate({
            rules: {
                "strDocument": {
                    //extension: "jpg|jpeg|png",
                    filesize: 10485760
                },
                "strImages[]": {
                    //extension: "jpg|jpeg|png",
                    filesize: 10485760,
                },
                "strVideo[]": {
                    //extension: "jpg|jpeg|png",
                    filesize: 209715200,
                }
            }
        });
    </script>
@endsection
