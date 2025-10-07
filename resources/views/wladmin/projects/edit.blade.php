@extends('layouts.wladmin')

@section('title', 'Add New Call Attendant')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Edit Project</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Projects</li>
                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">List of Projects
                        </a></li>
                    <li class="breadcrumb-item active"> Edit Project </li>
                </ol>
            </nav>
        </div>

        <!-- first row starts here -->
        @include('wladmin.wlcommon.alert')
        <div class="row">
            <div class="col-xl-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="accordion-content clearfix">
                            <div class="col-lg-3 col-md-4">
                                <div class="accordion-box">
                                    <div class="panel-group" id="RoleTabs">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Project Information
                                                    </a>
                                                </h4>
                                            </div>

                                            <div id="role_information" class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                    <ul class="accordion-tab nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#account" data-toggle="tab">Project Profile</a>
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
                                        <div class="tab-pane fade in active" id="account">
                                            <h3 class="tab-content-title">Project Profile</h3>
                                            <form class="was-validated pb-3" name="frmparameter" id="frmparameter"
                                                action="{{ route('projects.update', $id) }}" method="post"
                                                enctype="multipart/form-data">

                                                <input type="hidden" name="save" id="save" value="0">
                                                <input type="hidden" name="projectProfileId" id="projectProfileId"
                                                    value="{{ $datas->projectProfileId ?? 0 }}">

                                                @csrf
                                                <div class="field_wrapper">
                                                    <div class="accordion" id="accordionExample">
                                                        {{--  accordion-item  --}}
                                                        <div id="add-option" class="">

                                                            <div id="collapse0" class="accordion-collapse collapse show"
                                                                aria-labelledby="heading0"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="new-option clearfix">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Project Name*</label>
                                                                                    <input type="text" name="projectName"
                                                                                        id="projectName"
                                                                                        value="{{ $ticketmasters->ProjectName }}"
                                                                                        class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label> State </label>
                                                                                    <select class="js-example-basic-single"
                                                                                        name="iStateId" id="iStateId"
                                                                                        style="width: 100%;"
                                                                                        onchange="getCity();">
                                                                                        <option value="">-- Select
                                                                                            --</option>
                                                                                        @foreach ($statemasters as $state)
                                                                                            <option
                                                                                                value="{{ $state->iStateId }}"
                                                                                                @if (isset($datas->iStateId) && $datas->iStateId == $state->iStateId) {{ 'selected' }} @endif>
                                                                                                {{ $state->strStateName }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label> City </label>
                                                                                    <select class="js-example-basic-single"
                                                                                        name="iCityId" id="iCityId"
                                                                                        style="width: 100%;">
                                                                                        <option label="Please Select"
                                                                                            value="">-- Select
                                                                                            --</option>
                                                                                        @foreach ($citymasters as $city)
                                                                                            <option
                                                                                                value="{{ $city->iCityId }}"
                                                                                                @if (isset($datas->iCityId) && $datas->iCityId == $city->iCityId) {{ 'selected' }} @endif>
                                                                                                {{ $city->strCityName }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Vertical</label>
                                                                                    <input type="text" name="strVertical"
                                                                                        id="strVertical"
                                                                                        value="{{ $datas->strVertical ?? '' }}"
                                                                                        class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Sub-Vertical</label>
                                                                                    <input type="text"
                                                                                        name="strSubVertical"
                                                                                        id="strSubVertical"
                                                                                        value="{{ $datas->strSubVertical ?? '' }}"
                                                                                        class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>SI</label>
                                                                                    <input type="text" name="strSI"
                                                                                        id="strSI"value="{{ $datas->strSI ?? '' }}"
                                                                                        class="form-control">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Engineer</label>
                                                                                    <input type="text"
                                                                                        name="strEngineer"
                                                                                        id="strEngineer"
                                                                                        value="{{ $datas->strEngineer ?? '' }}"
                                                                                        class="form-control">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Commissioned in</label>
                                                                                    <input type="text"
                                                                                        name="strCommissionedIn"
                                                                                        id="strCommissionedIn"
                                                                                        value="{{ $datas->strCommissionedIn ?? '' }}"
                                                                                        class="form-control">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label> System </label>
                                                                                    <select class="js-example-basic-single"
                                                                                        name="iSystemId" id="iSystemId"
                                                                                        style="width: 100%;">
                                                                                        <option label="Please Select"
                                                                                            value="">-- Select
                                                                                            --</option>
                                                                                        @foreach ($systemmasters as $system)
                                                                                            <option
                                                                                                value="{{ $system->iSystemId }}"
                                                                                                @if (isset($datas->iSystemId) && $datas->iSystemId == $system->iSystemId) {{ 'selected' }} @endif>
                                                                                                {{ $system->strSystem }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Panel</label>
                                                                                    <input type="text" name="strPanel"
                                                                                        id="strPanel"
                                                                                        value="{{ $datas->strPanel ?? '' }}"
                                                                                        class="form-control">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Panel Quantity</label>
                                                                                    <input type="text"
                                                                                        name="strPanelQuantity"
                                                                                        id="strPanelQuantity"
                                                                                        value="{{ $datas->strPanelQuantity ?? '' }}"
                                                                                        class="form-control"
                                                                                        onkeypress="return isNumber(event)">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Devices</label>
                                                                                    <input type="text"
                                                                                        name="strDevices" id="strDevices"
                                                                                        value="{{ $datas->strDevices ?? '' }}"
                                                                                        class="form-control">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Device Quantity</label>
                                                                                    <input type="text"
                                                                                        name="strDeviceQuantity"
                                                                                        id="strDeviceQuantity"
                                                                                        value="{{ $datas->strDeviceQuantity ?? '' }}"
                                                                                        class="form-control"
                                                                                        onkeypress="return isNumber(event)">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Other Components</label>
                                                                                    <input type="text"
                                                                                        name="strOtherComponents"
                                                                                        id="strOtherComponents"
                                                                                        value="{{ $datas->strOtherComponents ?? '' }}"
                                                                                        class="form-control">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>BOQ</label>
                                                                                    <input type="text" name="strBOQ"
                                                                                        id="strBOQ"
                                                                                        value="{{ $datas->strBOQ ?? '' }}"
                                                                                        class="form-control">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>BOQ Upload</label>
                                                                                    <input type="file"
                                                                                        name="strBOQUpload[]"
                                                                                        id="strBOQUpload" value=""
                                                                                        class="form-control" multiple
                                                                                        accept=".pdf, .doc, .docx , .xlsx , .xls">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Completion Document
                                                                                        Upload</label>
                                                                                    <input type="file"
                                                                                        name="CompletionDocumentUpload[]"
                                                                                        id="CompletionDocumentUpload"
                                                                                        value=""
                                                                                        class="form-control" multiple
                                                                                        accept=".pdf, .doc, .docx , .xlsx , .xls">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>AMC</label>
                                                                                    <input type="text" name="strAMC"
                                                                                        id="strAMC"
                                                                                        value="{{ $datas->strAMC ?? '' }}"
                                                                                        class="form-control">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Other Information</label>
                                                                                    <input type="text"
                                                                                        name="strOtherInformation"
                                                                                        id="strOtherInformation"
                                                                                        value="{{ $datas->strOtherInformation ?? '' }}"
                                                                                        class="form-control">
                                                                                    <span id="erremail"
                                                                                        class="text-danger"></span>
                                                                                </div>
                                                                            </div>



                                                                        </div>


                                                                    </div>
                                                                    <!--new option-->
                                                                </div>
                                                                <!--accordion body-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-end">
                                                        <button type="submit"
                                                            class="btn btn-success text-uppercase mt-4">
                                                            Save & Exit
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!--Advance information form start-->

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

    </div>
    <!--END notify messages-->


@endsection

@section('script')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        function isNumber(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43)
                return false;
            return true;
        }
        $("#strEmail").blur(function() {
            var email = $(this).val();
            var userId = $('#iCompanyUserId').val();
            $.ajax({
                type: 'POST',
                url: "{{ route('user.emailvalidate') }}",
                data: {
                    email: email,
                    ID: userId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response == 0) {
                        $("#erremail").html("Email is already in used.");
                        $('#strEmail').val('');
                        $('#submit').prop('disabled', true);
                        $('#savesubmit').prop('disabled', true);
                        return false;
                    } else {
                        var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
                        if (!pattern.test(email)) {
                            $("#erremail").html('not a valid e-mail address');
                            $('#strEmail').val('');
                            $('#submit').prop('disabled', true);
                            $('#savesubmit').prop('disabled', true);
                            return false;
                        } else {
                            $("#erremail").html("");
                            $('#submit').prop('disabled', false);
                            $('#savesubmit').prop('disabled', false);
                            return true;
                        }
                    }
                }
            });
        });
    </script>

    <script>
        function getCity() {
            var iStateId = $("#iStateId").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('company.getCity') }}",
                data: {
                    iStateId: iStateId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#iCityId").html(response);
                }
            });
        }
    </script>

    <script>
        function deleteDoc(id) {
            if (confirm("Are you sure you want to delete this document?")) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('projects.deletedoc') }}",
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
    </script>
@endsection
