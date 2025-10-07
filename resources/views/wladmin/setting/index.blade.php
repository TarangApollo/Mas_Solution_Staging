@extends('layouts.wladmin')

@section('title', 'Settings')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- Layout styles -->

        <div class="content-wrapper pb-0">
            <div class="page-header">
                <h3>Settings</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Settings</li>
                    </ol>
                </nav>
            </div>
            @include('wladmin.wlcommon.alert')
            <!-- first row starts here -->
            <div class="row">
                <div class="col-xl-12 stretch-card grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion-content clearfix">
                                <div class="col-lg-3 col-md-4">
                                    <div class="accordion-box">
                                        <div class="panel-group" id="RoleTabs">
                                            <div class="panel panel-default">
                                                <div id="role_information" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        <ul class="accordion-tab nav nav-tabs">
                                                            <li class="active">
                                                                <a href="#general" data-toggle="tab">General
                                                                    Settings</a>
                                                            </li>
                                                            <li>
                                                                <a href="#social-media" data-toggle="tab"
                                                                    aria-expanded="true">Social Media Links</a>
                                                            </li>
                                                            <li>
                                                                <a href="#mail" data-toggle="tab"
                                                                    aria-expanded="true">Mail Settings</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-8">
                                    <div class="accordion-box-content">
                                        <div class="tab-content clearfix">
                                            <div class="tab-pane fade in active" id="general">
                                                <h3 class="tab-content-title">General</h3>
                                                <form class="was-validated pb-3" method="post"
                                                    action="{{ route('setting.generalSetting') }}" id="generalForm"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>OEM Company Name*</label>
                                                                <input type="text" class="form-control"
                                                                    name="strCompanyName" id="strCompanyName"
                                                                    value="{{ $CompanyInfo->strCompanyName ?? Session::get('CompanyName') }}"
                                                                    disabled>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Upload Company Logo</label>
                                                                <input type="file" class="form-control" name="photo"
                                                                    value=""
                                                                    accept="image/png, image/gif, image/jpeg">
                                                                    (Max width 165 px)
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        {{-- <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Support Phone</label>
                                                                <input type="text" class="form-control" >
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col --> --}}
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Support Mobile</label>
                                                                <input type="text" class="form-control" name="ContactNo"
                                                                    id="ContactNo"
                                                                    value="{{ $CompanyInfo->ContactNo ?? '' }}">
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Support Mail ID*</label>
                                                                <input type="text" class="form-control" name="EmailId"
                                                                    id="EmailId"
                                                                    value="{{ $CompanyInfo->EmailId ?? '' }}">
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                    </div> <!-- /.row -->
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="full-width">Header Color</label>
                                                                <input class="p-1 border-1 form-control" type="color"
                                                                    name="headerColor" id="colorpicker"
                                                                    value="{{ $CompanyInfo->headerColor ?? '' }}">
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="full-width">Menu Hover
                                                                    Color</label>
                                                                <input class="p-1 form-control" type="color"
                                                                    id="colorpicker1"
                                                                    value="{{ $CompanyInfo->menuColor ?? '' }}"
                                                                    name="menucolor">
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="full-width">Only Icon Menu
                                                                    Background Color</label>
                                                                <input class="p-1 form-control" type="color"
                                                                    id="colorpicker1"
                                                                    value="{{ $CompanyInfo->menubgColor ?? '' }}"
                                                                    name="iconcolor">
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                    </div><!-- /.row -->

                                                    <!--buttons start-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <button type="button"
                                                                class="btn btn-default text-uppercase mt-4">
                                                                Clear
                                                            </button>
                                                        </div>
                                                        <div class="col-md-6 d-flex justify-content-end">
                                                            <button type="submit"
                                                                class="btn btn-success text-uppercase mt-4 mr-2">
                                                                Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!--/.buttons end-->
                                            </div>
                                            <!--social media form start-->
                                            <div class="tab-pane fade" id="social-media">
                                                <h3 class="tab-content-title">Social Media Links</h3>
                                                <form class="was-validated pb-3"
                                                    action="{{ route('setting.socialSetting') }}" method="post"
                                                    id="socialForm">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Facebook</label>
                                                                <input type="text" class="form-control" required=""
                                                                    name="facebookLink"
                                                                    value="{{ $CompanyInfo->facebookLink ?? '' }}">
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Twitter</label>
                                                                <input type="text" class="form-control" required=""
                                                                    name="twitterlink"
                                                                    value="{{ $CompanyInfo->twitterlink ?? '' }}">
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Instagram</label>
                                                                <input type="text" class="form-control" required=""
                                                                    name="instaLink"
                                                                    value="{{ $CompanyInfo->instaLink ?? '' }}">
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Linkedin</label>
                                                                <input type="text" class="form-control" required=""
                                                                    name="linkedinlink"
                                                                    value="{{ $CompanyInfo->linkedinlink ?? '' }}">
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                    </div>

                                                    <!--buttons start-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <button type="button"
                                                                class="btn btn-default text-uppercase mt-4">
                                                                Clear
                                                            </button>
                                                        </div>
                                                        <div class="col-md-6 d-flex justify-content-end">
                                                            <button type="button"
                                                                class="btn btn-success text-uppercase mt-4 mr-2"
                                                                onclick="submitsocial()">
                                                                Save
                                                            </button>
                                                            <button type="submit"
                                                                class="btn btn-success text-uppercase mt-4">
                                                                Save & Exit
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!--/.buttons end-->
                                            </div>
                                            <!--mails form start-->
                                            <div class="tab-pane fade" id="mail">
                                                <h3 class="tab-content-title">Send Mail Settings</h3>
                                                <form class="was-validated pb-3"
                                                    action="{{ route('setting.mailSetting') }}" method="post"
                                                    id="mailForm">
                                                    @csrf
                                                    <div class="accordion" id="accordionExample">
                                                        <div id="add-option" class="accordion-item"
                                                            style="display: none;">
                                                            <h2 class="accordion-header" id="headingFour">
                                                                <button class="accordion-button" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapseFour" aria-expanded="true"
                                                                    aria-controls="collapseFour">
                                                                    <span class="drag-icon pull-left">
                                                                        <i class="mas-arrow-move"></i>
                                                                    </span>
                                                                    New Option
                                                                </button>
                                                            </h2>
                                                            <div id="collapseFour"
                                                                class="accordion-collapse collapse show"
                                                                aria-labelledby="headingFour"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="new-option clearfix">

                                                                        <div class="row">
                                                                            <div class="col-lg-4 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>Title</label>
                                                                                    <input type="text"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                            <!--/.col-->

                                                                            <div class="col-lg-4 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>Select Type</label>
                                                                                    <select name="fieldtype"
                                                                                        class="js-example-basic-single"
                                                                                        style="width: 100%;">
                                                                                        <option label="Please Select"
                                                                                            value="">--
                                                                                            Select --</option>
                                                                                        <option value="FI">
                                                                                            Field</option>
                                                                                        <option value="TA">
                                                                                            Text Area</option>
                                                                                        <option value="DR">
                                                                                            Dropdown</option>
                                                                                    </select>
                                                                                </div> <!-- /.form-group -->
                                                                            </div>
                                                                            <!--/.col-->

                                                                            <div class="col-lg-4 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Required</label>
                                                                                </div>
                                                                                <!--/.check box-->

                                                                                <button type="button"
                                                                                    class="btn delete-option pull-right deleteDep"
                                                                                    title="Delete Option">
                                                                                    <i class="mas-trash"></i>
                                                                                </button>
                                                                                <!--/.delete icon-->
                                                                            </div>
                                                                            <!--/.col-->
                                                                        </div>
                                                                        <!--/.row-->

                                                                    </div>
                                                                    <!--new option-->
                                                                </div>
                                                                <!--accordion body-->
                                                            </div>
                                                            <!--/.collapse FOUR-->
                                                        </div>
                                                        <!--/.display none new option-->


                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <button class="accordion-button" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapseOne" aria-expanded="true"
                                                                    aria-controls="collapseOne">
                                                                    <span class="drag-icon pull-left">
                                                                        <i class="mas-arrow-move"></i>
                                                                    </span>
                                                                    Open / Update Ticket
                                                                </button>
                                                            </h2>
                                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                                aria-labelledby="headingOne"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="new-option clearfix">

                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <input type="hidden" name="iStatusId[]"
                                                                                    value="0">
                                                                                <textarea id="editor1" name="editor1_0" rows="800" cols="80">{{ isset($openmessage) ? html_entity_decode($openmessage->strMessage) : '' }}</textarea>
                                                                                <input type="hidden" name="msg_0"
                                                                                    id="msg_0" value="">
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input"
                                                                                        name="toCustomer_0"
                                                                                        {{ (isset($openmessage) && $openmessage->toCustomer == 1) ? 'checked' : '' }}
                                                                                        value="1">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Mail to
                                                                                        Customer</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div>
                                                                            <!--/.col-->
                                                                            <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input"
                                                                                        name="toExecutive_0"
                                                                                        {{ (isset($openmessage) && $openmessage->toExecutive == 1) ? 'checked' : '' }}
                                                                                        value="1">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Mail to Level -
                                                                                        2 Executive</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div>
                                                                            <!--/.col-->
                                                                            {{-- <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Whatsapp Message
                                                                                        to Customer</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div> --}}
                                                                            <!--/.col-->
                                                                            {{-- <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Whatsapp Message
                                                                                        to Level - 2
                                                                                        Executive</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div> --}}
                                                                            <!--/.col-->
                                                                            <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input"
                                                                                        name="toCompany_0"
                                                                                        {{ (isset($openmessage) && $openmessage->toCompany == 1) ? 'checked' : '' }}
                                                                                        value="1">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Mail to User
                                                                                        Company</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div>
                                                                            <!--/.col-->
                                                                        </div>
                                                                        <!--/.row-->

                                                                    </div>
                                                                    <!--new option-->
                                                                </div>
                                                                <!--accordion body-->
                                                            </div>
                                                            <!--/.collapse ONE-->
                                                        </div>

                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingTwo">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapseTwo" aria-expanded="false"
                                                                    aria-controls="collapseTwo">
                                                                    <span class="drag-icon pull-left">
                                                                        <i class="mas-arrow-move"></i>
                                                                    </span>
                                                                    Close Ticket
                                                                </button>
                                                            </h2>
                                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                                aria-labelledby="headingTwo"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="new-option clearfix">

                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <input type="hidden" name="iStatusId[]"
                                                                                    value="1">
                                                                                <textarea id="editor2" name="editor1_1" rows="800" cols="80">{{ isset($closemessage) ? html_entity_decode($closemessage->strMessage) : '' }}</textarea>
                                                                                <input type="hidden" name="msg_1"
                                                                                    id="msg_1" value="">
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input"
                                                                                        name="toCustomer_1"
                                                                                        {{ isset($closemessage) && $closemessage->toCustomer == 1 ? 'checked' : '' }}
                                                                                        value="1">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Mail to
                                                                                        Customer</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div>
                                                                            <!--/.col-->
                                                                            <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input"
                                                                                        name="toExecutive_1"
                                                                                        {{ isset($closemessage) && $closemessage->toExecutive == 1 ? 'checked' : '' }}
                                                                                        value="1">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Mail to Level -
                                                                                        2 Executive</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div>
                                                                            <!--/.col-->
                                                                            {{-- <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Whatsapp Message
                                                                                        to Customer</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div> --}}
                                                                            <!--/.col-->
                                                                            {{-- <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input">
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Whatsapp Message
                                                                                        to Level - 2
                                                                                        Executive</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div> --}}
                                                                            <!--/.col-->
                                                                            <div class="col-lg-6 col-md-12">
                                                                                <div class="checkbox">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input"
                                                                                        name="toCompany_1" value="1"
                                                                                        {{ isset($closemessage) && $closemessage->toCompany == 1 ? 'checked' : '' }}>
                                                                                    <i class="input-helper"></i>
                                                                                    <label>Send Mail to User
                                                                                        Company</label>
                                                                                </div>
                                                                                <!--/.check box-->
                                                                            </div>
                                                                            <!--/.col-->
                                                                        </div>
                                                                        <!--/.row-->

                                                                    </div>
                                                                    <!--new option-->
                                                                </div>
                                                                <!--/.accordion body-->
                                                            </div>
                                                            <!--/.collapse TWO-->
                                                        </div>

                                                    </div>
                                                    <!--buttons start-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <button type="button"
                                                                class="btn btn-default text-uppercase mt-4">
                                                                Clear
                                                            </button>
                                                        </div>
                                                        <div class="col-md-6 d-flex justify-content-end">
                                                            <button type="button"
                                                                class="btn btn-success text-uppercase mt-4 mr-2"
                                                                onclick="submitmail()">
                                                                Save
                                                            </button>

                                                        </div>
                                                    </div>
                                                </form>
                                                <!--/.buttons end-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--card body end-->
                    </div>
                    <!--card end-->
                </div>
            </div>
            <!--row-->

            <!--notify messages-->

            <!--END notify messages-->

        </div>
        <!-- content-wrapper ends -->


    <!-- main-panel ends -->
@endsection
<!-- plugins:js -->
{{-- <script src="../global/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="../global/assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="../global/assets/js/settings.js"></script>
<script src="../global/assets/js/custom.js"></script>
<script src="../global/assets/js/off-canvas.js"></script>
<script src="../global/assets/js/hoverable-collapse.js"></script>

<!-- Plugin js for this page -->
<script src="../global/assets/vendors/bootstrap-table/js/bootstrap.js"></script>

<!--select 2 form-->
<script src="../global/assets/vendors/select2/select2.min.js"></script>
<script src="../global/assets/js/select2.js"></script>

<!--form validation-->
<script src="../global/assets/vendors/wizard/js/jquery.validate.min.js"></script>

<!-- Text Editor -->
<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script> --}}
@section('script')
    <script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>

    <script>
        $(function() {
            CKEDITOR.replace('editor1');
            CKEDITOR.replace('editor2');

            $(".textarea").wysihtml5();
        });

        function submitmail() {
            var opendata = CKEDITOR.instances.editor1.getData();
            $("#msg_0").val(opendata);
            $("#msg_1").val(CKEDITOR.instances.editor2.getData());
            $.ajax({
                type: 'POST',
                url: "{{ route('setting.mailSetting') }}",
                data: $("#mailForm").serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    var newurl = "{{ route('setting.index') }}";
                    window.open(newurl, '_self');
                }
            });

        }

        function submitgeneral() {
            // $.ajax({
            //     type: 'POST',
            //     url: "{{ route('setting.generalSetting') }}",
            //     data: $("#generalForm").serialize(),
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     success: function(response) {
            //         console.log(response);
            //         $.each(response, function(k, v) {
            //             //  alert(v.iRefDocumentId);
            //             var newurl = "{{ route('callattendantreference.openDoc', ':id') }}";
            //             newurl = newurl.replace(':id', v.iRefDocumentId);
            //             newurl = newurl.replace('?', '/');
            //             window.open(newurl, '_blank');
            //             window.open(this.href, '_self');

            //         });


            //     }
            // });

        }

        function submitsocial() {
            $.ajax({
                type: 'POST',
                url: "{{ route('setting.socialSetting') }}",
                data: $("#socialForm").serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);

                    var newurl = "{{ route('setting.index') }}";
                    window.open(newurl);



                }
            });

        }
    </script>
@endsection
