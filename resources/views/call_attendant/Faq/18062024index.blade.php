@extends('layouts.callAttendant')

@section('title', 'Faq')

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
                <div class="d-flex flex-row-reverse">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-md-2 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <p class="m-0 pr-8">Frequently Asked Questions</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('call_attendant.callattendantcommon.alert')
                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <div class="card mt-5">
                            <div class="card-body p-0">
                                <h4 class="card-title mt-0">search by categories</h4>
                                <form class="was-validated p-4 pb-3" action="" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Search by Word</label>
                                                <input type="text" class="form-control" name="searchText"
                                                    value="{{ isset($postarray['searchText']) ? $postarray['searchText'] : '' }}" />
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Select OEM Company</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="OemCompannyId" id="OemCompannyId">
                                                    <option label="Please Select" value=""> -- Select --</option>
                                                    @foreach ($CompanyMaster as $company)
                                                        <option value="{{ $company->iCompanyId }}"
                                                            {{ isset($postarray['OemCompannyId']) && $postarray['OemCompannyId'] == $company->iCompanyId ? 'selected' : '' }}>
                                                            {{ $company->strOEMCompanyName }}</option>
                                                    @endforeach
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Select System</label>

                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="iSystemId" id="iSystemId">
                                                    <option label="Please Select" value=""> -- Select --</option>
                                                    @if (isset($postarray['iSystemId']))
                                                        @foreach ($systemLists as $system)
                                                            <option value="{{ $system->iSystemId }}"
                                                                {{ $postarray['iSystemId'] == $system->iSystemId ? 'selected' : '111' }}>
                                                                {{ $system->strSystem }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Select Component</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="iComponentId" id="iComponentId">
                                                    <option value="">--Select--</option>
                                                    @if (isset($postarray['iComponentId']))
                                                        @foreach ($componentLists as $compo)
                                                            <option value="{{ $compo->iComponentId }}"
                                                                {{ $postarray['iComponentId'] == $compo->iComponentId ? 'selected' : '' }}>
                                                                {{ $compo->strComponent }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div> <!-- /.form-group -->
                                        </div> <!-- /.col -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Select Sub Component</label>
                                                <select class="js-example-basic-single" style="width: 100%;"
                                                    name="iSubComponentId" id="iSubComponentId">
                                                    <option value="">--Select--</option>
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
                                        name="searchFaq">
                                    <a class="btn btn-default text-uppercase mt-3"
                                        href="{{ route('callattendantfaq.index') }}"> Clear </a>
                                </form>
                            </div>
                        </div>
                        <!--card end-->
                    </div>
                </div> <!-- end row -->

                <!--row 2 faq-->
                <div class="row d-flex justify-content-center my-5">
                    <div class="col-sm-10">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            @if (count($Faqs) > 0)
                                @foreach ($Faqs as $faq)
                                    <div class="panel panel-default">
                                        <div class="panel-heading bg-white" role="tab" id="heading{{ $faq->iFAQId }}">
                                            <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                    data-parent="#accordion" href="#collapse{{ $faq->iFAQId }}"
                                                    aria-expanded="false" aria-controls="collapse{{ $faq->iFAQId }}">
                                                    <div class="col-md-7">
                                                        {{ $faq->strFAQTitle }}
                                                    </div>
                                                    <div class="com-md-5 text-right">
                                                        <small>{{ $faq->strOEMCompanyName }}, System:
                                                            {{ $faq->strSystem }}, Component:
                                                            {{ $faq->strComponent }}</small>
                                                    </div>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse{{ $faq->iFAQId }}" class="panel-collapse collapse"
                                            role="tabpanel" aria-labelledby="heading{{ $faq->iFAQId }}">
                                            <div class="panel-body">
                                                <p>
                                                    {!! $faq->strFAQDescription !!}
                                                </p>
                                                <!--reference images-->
                                                @if (count($faq->gallery) > 0)
                                                    <div class="my-3">
                                                        <h4> Reference Documents:</h4>
                                                        <div class="row mt-3">
                                                            @foreach ($faq->gallery as $gallery)
                                                                @if ($gallery->iDocumentType == 1)
                                                                    <div class="col-md-1">
                                                                        <a
                                                                            onclick="openDoc('{{ $gallery->iFAQDocumentId }}')">
                                                                            <img class="width-90"
                                                                                src="{{ asset('global/assets/images/reference/photo/document.jpg') }}"
                                                                                alt="{{ $gallery->strFileName }}">
                                                                        </a>
                                                                    </div><!-- /.col -->
                                                                @elseif($gallery->iDocumentType == 2)
                                                                    <div class="col-md-1">
                                                                        <a href="{{ asset('FaqDocument/Image/') . '/' . $gallery->strFileName }}"
                                                                            target="_blank">
                                                                            <img class="width-90"
                                                                                src="{{ asset('FaqDocument/Image/') . '/' . $gallery->strFileName }}"
                                                                                alt="{{ $gallery->strFileName }}">
                                                                        </a>
                                                                    </div><!-- /.col -->
                                                                @else
                                                                    <div class="col-md-1">
                                                                        <a href="{{ asset('FaqDocument/Video/') . '/' . $gallery->strFileName }}"
                                                                            target="_blank">
                                                                            <img class="width-90"
                                                                                src="{{ asset('global/assets/images/reference/photo/video.jpg') }}"
                                                                                alt="{{ $gallery->strFileName }}">
                                                                        </a>
                                                                    </div><!-- /.col -->
                                                                @endif
                                                            @endforeach

                                                        </div> <!-- /.row -->
                                                    </div> <!-- /.reference images div -->
                                                @endif
                                            </div>
                                        </div>
                                    </div> <!-- /.panel 1 -->
                                @endforeach
                            @endif
                        </div> <!-- /.panel-group -->
                    </div> <!-- /.col -->
                </div><!-- end row -->

            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                            2022 Mas Solutions. All rights reserved.</span>
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
        $(document).ready(function() {
            @if (!isset($postarray['iSystemId']))
                $("#OemCompannyId").change();
            @endif
        });
        $("#OemCompannyId").change(function() {

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

        function openDoc(url) {
            var newurl = "{{ route('faq.openDocument', ':id') }}";
            newurl = newurl.replace(':id', url);
            newurl = newurl.replace('?', '/');
            window.open(newurl, '_blank');

        }
    </script>
@endsection
