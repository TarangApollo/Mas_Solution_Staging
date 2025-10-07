@extends('layouts.callAttendant')

@section('title', 'Reference Documents')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- css for this page -->
    <!--select 2 form-->
    <link rel="stylesheet" href="../global/assets/vendors/select2/select2.min.css" />
    <link rel="stylesheet" href="../global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css" />

    <!-- End layout styles -->
    <link rel="shortcut icon" href="../global/assets/images/favicon.png" />


    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex justify-content-center">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-sm-5 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <h3 class="m-0">Reference Documents</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <!--<div class="card mt-5">
                            <div class="card-body p-0">-->
                        <div class="wizard-container">
                            @include('call_attendant.callattendantcommon.alert')
                            <div class="card wizard-card" data-color="orange" id="wizardProfile">
                                <h4 class="card-title mt-0">search by categories</h4>
                                <form class="was-validated p-4 pb-3" action="" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Search by Word</label>
                                                <input type="text" class="form-control" name="searchWord"
                                                    value="{{ isset($postarray['searchWord']) ? $postarray['searchWord'] : '' }}" />
                                            </div>
                                        </div> <!-- /.col -->
                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Search by Title</label>
                                                <input type="text" class="form-control" name="searchText"
                                                    value="{{ isset($postarray['searchText']) ? $postarray['searchText'] : '' }}">
                                            </div>
                                        </div> --}}

                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select OEM Company</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="OemCompannyId" id="OemCompannyId">
                                                    <option label="Please Select" value=""> -- Select --</option>
                                                    @foreach ($CompanyMaster as $company)
                                                        @if (session()->has('CompanyId') && session('CompanyId') == $company->iCompanyId)
                                                            <option value="{{ $company->iCompanyId }}" {{ 'selected' }}>
                                                                {{ $company->strOEMCompanyName }}</option>
                                                        @else
                                                            <option value="{{ $company->iCompanyId }}"
                                                                {{ isset($postarray['OemCompannyId']) && $postarray['OemCompannyId'] == $company->iCompanyId ? 'selected' : '' }}>
                                                                {{ $company->strOEMCompanyName }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select System</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="iSystemId" id="iSystemId">
                                                    <option label="Please Select" value=""> -- Select --</option>
                                                    @if (isset($postarray['iSystemId']) || count($systemLists) > 0)
                                                        @foreach ($systemLists as $system)
                                                            <option value="{{ $system->iSystemId }}"
                                                                {{ isset($postarray['iSystemId']) && $postarray['iSystemId'] == $system->iSystemId ? 'selected' : '' }}>
                                                                {{ $system->strSystem }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select Component</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="iComponentId" id="iComponentId">
                                                    <option label="Please Select" value=""> -- Select --</option>
                                                    @if (isset($postarray['iComponentId']) || count($componentLists) > 0)
                                                        @foreach ($componentLists as $compo)
                                                            <option value="{{ $compo->iComponentId }}"
                                                                {{ isset($postarray['iComponentId']) && $postarray['iComponentId'] == $compo->iComponentId ? 'selected' : '' }}>
                                                                {{ $compo->strComponent }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="form-group">
                                                <label>Select Sub Component</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="iSubComponentId" id="iSubComponentId">
                                                    <option label="Please Select" value=""> -- Select --</option>
                                                    @if (isset($postarray['iSubComponentId']) || count($subcomponents) > 0)
                                                        @foreach ($subcomponents as $subcompo)
                                                            <option value="{{ $subcompo->iSubComponentId }}"
                                                                {{ isset($postarray['iSubComponentId']) && $postarray['iSubComponentId'] == $subcompo->iSubComponentId ? 'selected' : '' }}>
                                                                {{ $subcompo->strSubComponent }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                    </div>
                                    <input type="submit" class="btn btn-success text-uppercase mt-3 mr-2" value="Search"
                                        name="searchRef">
                                    <input type="button" class="btn btn-fill btn-default text-uppercase mt-3"
                                        onclick="window.location='{{ route('callattendantreference.index') }}'"
                                        value="Clear">
                                </form>
                            </div>
                        </div>
                        <!--card end-->
                    </div>
                </div> <!-- end row -->

                <!--row 2 faq-->
                <div class="row d-flex justify-content-center my-5">
                    <div class="col-sm-10">

                        <div class="row">
                            @if (count($Faqs) > 0)
                                @foreach ($Faqs as $faq)
                                    <div class="col-6 mb-4">
                                        <div class="card shadow p-4">
                                            <div class="row">
                                                <h3 class="text-black-80">{{ $faq->strRefTitle }}</h3>
                                                <h4 class="text-black-50">OEM Company: {{ $faq->strOEMCompanyName }}</h4>
                                                <h4 class="text-black-50">System: {{ $faq->strSystem }}</h4>
                                                <h4 class="text-black-50">Component: {{ $faq->strComponent }}</h4>
                                                <h4 class="text-black-30">Sub-component: {{ $faq->strSubComponent }}</h4>
                                                <h5 class="text-black-30">Content: <?php
                                                if ($faq->strContentType) {
                                                    $strContent = explode(',', $faq->strContentType);
                                                    foreach ($strContent as $type) {
                                                        if ($type == 1) {
                                                            echo 'Document,';
                                                        }
                                                        if ($type == 2) {
                                                            echo 'Image,';
                                                        }
                                                        if ($type == 3) {
                                                            echo 'Video,';
                                                        }
                                                    }
                                                }
                                                ?>
                                                </h5>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    @if (count($faq->gallery) > 0)
                                                        <a href="#" data-toggle="modal" data-target="#downloadtemp"
                                                            onclick="openModel({{ $faq->iRefId }},'download');">
                                                            <img class="mr-2"
                                                                src="../global/assets/images/direct-download.png"
                                                                width="30" alt="download icon" title="Download">
                                                        </a>
                                                        {{-- <a href="#" data-toggle="modal" data-target="#send-docs">
                                                <img class="mr-2" src="../global/assets/images/whatsapp.png"
                                                width="30" alt="whatsapp icon" title="Send link to whatsapp">
                                            </a> --}}
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#send-docstemp"
                                                            onclick="openModel({{ $faq->iRefId }},'mail');">
                                                            <img class="mr-2" src="../global/assets/images/mail.png"
                                                                width="30" alt="email icon"
                                                                title="Send link to email">
                                                        </a>
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#new-windowtemp"
                                                            onclick="openModel({{ $faq->iRefId }},'open');">
                                                            <img class="mr-2" src="../global/assets/images/resize.png"
                                                                width="30" alt="new tab icon"
                                                                title="Open in new tab">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- /.col -->
                                @endforeach
                            @endif

                        </div> <!-- end row -->
                    </div> <!-- /.col -->
                </div><!-- end row -->
                <!--notify messages-->

                <!--END notify messages-->
                <!-- modals starts -->
                <!-- modal download -->
                <div class="modal fade" id="download" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding-top: 100px;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Download Documents</h5>
                                <button type="button" class="close" onclick="javascript:$('#download').modal('hide');"
                                    data-dismiss="download" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form class="was-validated p-4 pb-3" id="DownloadForm" method="post">
                                @csrf
                                <div class="modal-body">

                                    <div class="row">
                                        <h4>Select Document You Wish to Download</h4>
                                        <div class="col-md-12" id="dimg" style="display: none;">
                                            <div class="checkbox">
                                                <input type="checkbox" class="form-check-input" name="type[]"
                                                    value="2">
                                                <i class="input-helper"></i>
                                                <label>Images</label>
                                            </div>
                                            <!--/.check box-->
                                        </div>
                                        <!--/.col-->
                                        <div class="col-md-12" id="dvd" style="display: none;">
                                            <div class="checkbox">
                                                <input type="checkbox" class="form-check-input" name="type[]"
                                                    value="3">
                                                <i class="input-helper"></i>
                                                <label>Videos</label>
                                            </div>
                                            <!--/.check box-->
                                        </div>
                                        <!--/.col-->
                                        <div class="col-md-12" id="dd" style="display: none;">
                                            <div class="checkbox">
                                                <input type="checkbox" class="form-check-input" name="type[]"
                                                    value="1">
                                                <i class="input-helper"></i>
                                                <label>Documents</label>
                                            </div>
                                            <!--/.check box-->
                                        </div>
                                        <!--/.col-->
                                    </div>
                                    <!--/.row-->

                                </div>
                                <!--main body-->
                                <div class="modal-footer">
                                    <a id="the-link"></a>
                                    <button type="button" class="btn btn-secondary" data-dismiss="download"
                                        onclick="javascript:$('#download').modal('hide');">Close</button>
                                    <input type="hidden" name="iRefId" id="iRefId" value="">
                                    <button type="button" class="btn btn-fill btn-success"
                                        onclick="downloadDoc();">Download</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /. modal download End -->
                <!-- modal send-docs -->
                <div class="modal fade" id="send-docs" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding-top: 100px;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Email Documents</h5>
                                <button type="button" class="close" data-dismiss="send-docs" aria-label="Close"
                                    onclick="javascript:$('#send-docs').modal('hide');">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form class="was-validated pb-3" action="{{ route('callattendantreference.emailDoc') }}"
                                method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <h4>Select Document You Wish to Send</h4>
                                        <div class="col-md-12">
                                            <div class="checkbox" id="eimg" style="display: none;">
                                                <input type="checkbox" class="form-check-input" name="type[]"
                                                    value="2">
                                                <i class="input-helper"></i>
                                                <label>Images</label>
                                            </div>
                                            <!--/.check box-->
                                        </div>
                                        <!--/.col-->
                                        <div class="col-md-12"id="evd" style="display: none;">
                                            <div class="checkbox">
                                                <input type="checkbox" class="form-check-input" name="type[]"
                                                    value="3">
                                                <i class="input-helper"></i>
                                                <label>Videos</label>
                                            </div>
                                            <!--/.check box-->
                                        </div>
                                        <!--/.col-->
                                        <div class="col-md-12" id="ed" style="display: none;">
                                            <div class="checkbox">
                                                <input type="checkbox" class="form-check-input" name="type[]"
                                                    value="1">
                                                <i class="input-helper"></i>
                                                <label>Documents</label>
                                            </div>
                                            <!--/.check box-->
                                        </div>
                                        <!--/.col-->
                                    </div>
                                    <!--/.row-->
                                    <div class="row mt-4">
                                        <h4>Email Documents</h4>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Add multiple email ids separated by comma.</label>
                                                <input type="text" class="form-control" name="emailids" />
                                            </div>
                                        </div> <!-- /.col -->
                                    </div>
                                    <!--/.row-->
                                    {{-- <div class="row mt-4">
                                        <h4>Whatsapp Documents</h4>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Add multiple mobile numbers separated by comma.</label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div> <!-- /.col -->
                                    </div> --}}
                                    <!--/.row-->

                                </div>
                                <!--main body-->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="send-docs"
                                        onclick="javascript:$('#send-docs').modal('hide');">Close</button>
                                    <input type="hidden" name="iRefId" id="iRefIdm" value="">
                                    <button type="submit" class="btn btn-fill btn-success">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /. modal send-docs End -->
                <!-- modal new-window -->
                <div class="modal fade" id="new-window" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding-top: 100px;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Open Documents in New Window</h5>
                                <button type="button" class="close" data-dismiss="new-window" aria-label="Close"
                                    onclick="javascript:$('#new-window').modal('hide');">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form class="was-validated pb-3" id="openDocForm" method="post">
                                @csrf
                                <div class="modal-body">

                                    <div class="row">
                                        <h4>Select Document You Wish to Open in New Window</h4>
                                        <div class="col-md-12">
                                            <div class="checkbox" id="oimg" style="display: none;">
                                                <input type="checkbox" class="form-check-input" name="type[]"
                                                    value="2">
                                                <i class="input-helper"></i>
                                                <label>Images</label>
                                            </div>
                                            <!--/.check box-->
                                        </div>
                                        <!--/.col-->
                                        <div class="col-md-12" id="ovd" style="display: none;">
                                            <div class="checkbox">
                                                <input type="checkbox" class="form-check-input" name="type[]"
                                                    value="3">
                                                <i class="input-helper"></i>
                                                <label>Videos</label>
                                            </div>
                                            <!--/.check box-->
                                        </div>
                                        <!--/.col-->
                                        <div class="col-md-12" id="od" style="display: none;">
                                            <div class="checkbox">
                                                <input type="checkbox" class="form-check-input" name="type[]"
                                                    value="1">
                                                <i class="input-helper"></i>
                                                <label>Documents</label>
                                            </div>
                                            <!--/.check box-->
                                        </div>
                                        <!--/.col-->
                                    </div>
                                    <!--/.row-->

                                </div>
                                <!--main body-->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="new-window"
                                        onclick="javascript:$('#new-window').modal('hide');">Close</button>
                                    <input type="hidden" name="iRefId" id="iRefIdo" value="">
                                    <button type="button" onclick="openDoc();"
                                        class="btn btn-fill btn-success">Open</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /. modal new-window End -->
                <!-- /.modals End -->
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                            2022 Mas Solutions. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Developed by <a
                                href="https://www.excellentcomputers.co.in/" target="_blank"> Excellent Computers </a> </span>
                    </div>
                </div>
            </footer>
            <!-- partial -->

        </div>
        <!-- main-panel ends -->
    </div>
    <!-- back to top button -->
    <a id="button"></a>
    <!-- plugins:js -->
    <script src="../global/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../global/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../global/assets/js/settings.js"></script>
    <script src="../global/assets/vendors/wizard/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../global/assets/js/custom.js"></script>

    <!--Plugin js for this page -->
    <!--select 2 form-->
    <script src="../global/assets/vendors/select2/select2.min.js"></script>
    <script src="../global/assets/js/select2.js"></script>
    <script type="text/javascript">
        function openModel(refId, type) {
            if (type == 'download') {
                $('#download').modal('show');
                $('#iRefId').val(refId);
            } else if (type == 'mail') {
                $('#send-docs').modal('show');
                $('#iRefIdm').val(refId);
            } else {
                $('#new-window').modal('show');
                $('#iRefIdo').val(refId);
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('callattendantreference.getContenType') }}",
                data: {
                    Id: refId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    console.log(response);
                    let dataItems = JSON.parse(response);


                    dataItems.forEach(element => {
                        if (element == 1) {
                            if (type == 'download')
                                $("#dd").css('display', 'block');
                            else if (type == 'mail')
                                $("#ed").css('display', 'block');
                            else
                                $("#od").css('display', 'block');
                        }
                        if (element == 2) {
                            if (type == 'download')
                                $("#dimg").css('display', 'block');
                            else if (type == 'mail')
                                $("#eimg").css('display', 'block');
                            else
                                $("#oimg").css('display', 'block');

                        }
                        if (element == 3) {
                            if (type == 'download')
                                $("#dvd").css('display', 'block');
                            else if (type == 'mail')
                                $("#evd").css('display', 'block');
                            else
                                $("#ovd").css('display', 'block');

                        }
                    });

                }

            });

        }

        $(document).ready(function() {
            @if (!isset($postarray['iSystemId']))
                $("#OemCompannyId").change();
            @endif
        });
        $("#OemCompannyId").change(function() {
            //$("#CustomerEmailCompany").val('');
            $("#iSystemId").html('<option label="Please Select" value=""> -- Select --</option>');
            $("#iComponentId").html('<option label="Please Select" value=""> -- Select --</option>');
            $("#iSubComponentId").html('<option label="Please Select" value=""> -- Select --</option>');
            $.ajax({
                type: 'POST',
                url: "{{ route('company.getCompany') }}",
                data: {
                    iCompanyId: this.value
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
                    $("#iSystemId").html(dataItems.system);
                    $("#iSupportType").html(dataItems.supporttype);
                    $("#CallerCompetencyId").html(dataItems.callcompetency);
                    $("#iIssueTypeId").html(dataItems.issuetype);
                    $("#iResolutionCategoryId").html(dataItems.resolutionCategory);
                }

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

        function openDoc() {
            $.ajax({
                type: 'POST',
                url: "{{ route('callattendantreference.openDocuments') }}",
                data: $("#openDocForm").serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    $.each(response, function(k, v) {
                        //  alert(v.iRefDocumentId);
                        var newurl = "{{ route('callattendantreference.openDoc', ':id') }}";
                        newurl = newurl.replace(':id', v.iRefDocumentId);
                        newurl = newurl.replace('?', '/');
                        window.open(newurl, '_blank');
                        window.open(this.href, '_self');

                    });

                    $('#new-window').modal('hide');
                }
            });


        }

        function downloadDoc() {
            $.ajax({
                type: 'POST',
                url: "{{ route('callattendantreference.downloadDoc') }}",
                data: $("#DownloadForm").serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function(data) {
                    // 'download': data.fileurl;
                    location.href = data;

                    $('#download').modal('hide');


                }
            });

        }
    </script>
@endsection
