@extends('layouts.callAttendant')

@section('title', 'Add New Complaint')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <!-- css for this page -->
    <!--step wizard-->
    <link href="{{ asset('global/assets/vendors/wizard/css/material-bootstrap-wizard.css') }}" rel="stylesheet" />
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <!--bootstrap table-->
    <link href="{{ asset('global/assets/vendors/bootstrap-table/css/fresh-bootstrap-table.css') }}" rel="stylesheet" />

    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper pb-0">
                <div class="d-flex flex-row-reverse">
                    <div class="page-header flex-wrap">

                        <div class="header-right d-flex flex-wrap mt-md-2 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <a class="border-0" href="#">
                                    <p class="m-0 pr-8">Add New Complaint</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('call_attendant.callattendantcommon.alert')
                <!-- first row starts here -->
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-10">
                        <!--      Wizard container        -->
                        <div class="wizard-container">
                            <div class="card wizard-card" data-color="orange" id="wizardProfile">
                                <!-- <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{ route('callattendantAdmin.create') }}" method="post"> -->
                                <form name="frmparameter" id="frmparameter"
                                    action="{{ route('callattendantAdmin.create') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="wizard-navigation">
                                        <ul>
                                            <li><a href="#company" data-toggle="tab">Basic Information</a></li>
                                            <li><a href="#ticket" data-toggle="tab">Add Ticket</a></li>
                                        </ul>
                                    </div>

                                    <div class="tab-content">
                                        <div class="tab-pane" id="company">
                                            <div class="row">
                                                <h4 class="info-text"> Add Customer Information</h4>
                                                <div class="col-sm-12">

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Name*</label>
                                                                <input type="text" class="form-control"
                                                                    name="customerName" id="customerName" value=""
                                                                    required />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Number*</label>
                                                                <input type="text" class="form-control"
                                                                    name="customerNumber" id="customerNumber" value=""
                                                                    required onkeypress="return isNumber(event)" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Email ID</label>
                                                                <input type="email" class="form-control"
                                                                    name="customerEmail" id="customerEmail"
                                                                    value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>OEM Company</label>

                                                                <select class="js-example-basic-single" style="width: 100%;"
                                                                    name="OemCompannyId" id="OemCompannyId">
                                                                    <option label="Please Select" value=""> -- Select
                                                                        --</option>
                                                                    @foreach ($CompanyMaster as $company)
                                                                        <option value="{{ $company->iCompanyId }}"
                                                                            {{ $company->iCompanyId == 6 ? 'selected' : '' }}>
                                                                            {{ $company->strOEMCompanyName }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Company</label>
                                                                <select class="js-example-basic-single" style="width: 100%;"
                                                                    name="iCompanyId" id="iCompanyId">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>

                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4" id="otherCompanyDiv" style="display: none;">
                                                            <div class="form-group">
                                                                <label>Other Company Name*</label>
                                                                <input type="text" class="form-control"
                                                                    name="othrcompanyname" id="othrcompanyname"
                                                                    value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Company Profile</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iCompanyProfileId"
                                                                    id="iCompanyProfileId">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>

                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4" id="otherCompanyProfileDiv"
                                                            style="display: none;">
                                                            <div class="form-group">
                                                                <label>Other Company Profile*</label>
                                                                <input type="text" class="form-control"
                                                                    name="othrcompanyprofile" id="othrcompanyprofile"
                                                                    value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Customer Company Email ID</label>
                                                                <input type="text" class="form-control"
                                                                    name="CustomerEmailCompany" id="CustomerEmailCompany"
                                                                    value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Other Information</label>
                                                                <input type="text" class="form-control"
                                                                    name="OtherInformation" id="OtherInformation"
                                                                    value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Distributor</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iDistributorId"
                                                                    id="iDistributorId">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Project Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="ProjectName" id="ProjectName" value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Project State</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iStateId" id="iStateId"
                                                                    onchange="getCity();">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>
                                                                    @foreach ($statemasters as $state)
                                                                        <option value="{{ $state->iStateId }}">
                                                                            {{ $state->strStateName }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Project City</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iCityId" id="iCityId">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>
                                                                    @foreach ($citymasters as $city)
                                                                        <option value="{{ $city->iCityId }}">
                                                                            {{ $city->strCityName }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Caller Connected Through</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iCallThrough"
                                                                    id="iCallThrough">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>
                                                                    <option value="Phone">Phone</option>
                                                                    <option value="Whatsapp">Whatsapp</option>
                                                                    <option value="Email">Email</option>
                                                                    <option value="Other">Other</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>User defined 1</label>
                                                                <input type="text" class="form-control"
                                                                    name="UserDefiine1" id="UserDefiine1"
                                                                    value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!--pan 2 start-->
                                        <div class="tab-pane" id="ticket">
                                            <div class="row">
                                                <h4 class="info-text"> Register a complaint</h4>
                                                <div class="col-sm-12">

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>System</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iSystemId" id="iSystemId">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>

                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Component</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iComponentId"
                                                                    id="iComponentId">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>

                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4" id="iSubComponentIdDiv">
                                                            <div class="form-group">
                                                                <label>Sub Component</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iSubComponentId[]"
                                                                    id="iSubComponentId" multiple>
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>

                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Support Type</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iSupportType"
                                                                    id="iSupportType">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>

                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Issue</label>
                                                                <input type="text" class="form-control" name="issue"
                                                                    id="issue" value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Resolution Details</label>
                                                                <input type="text" class="form-control"
                                                                    name="Resolutiondetail" id="Resolutiondetail"
                                                                    value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Solution Type</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iResolutionCategoryId"
                                                                    id="iResolutionCategoryId">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>

                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Issue Type</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iIssueTypeId"
                                                                    id="iIssueTypeId">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Caller Competency</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="CallerCompetencyId"
                                                                    id="CallerCompetencyId">
                                                                    <option label="Please Select" value="">
                                                                        -- Select
                                                                        --</option>

                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Upload Images (max 10, each below 10 MB)</label>
                                                                <input class="form-control py-6" type="file"
                                                                    name="tcktImages[]" id="tcktImages" multiple
                                                                    accept="image/*" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Upload Video (max 2, each below 200 MB)</label>
                                                                <input class="form-control py-6" type="file"
                                                                    name="tcktVideo[]" id="tcktVideo[]" multiple
                                                                    accept="video/*" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                    </div>
                                                    <hr>


                                                    <div class="row mt-5">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Select Status</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iTicketStatus"
                                                                    id="iTicketStatus">
                                                                    <option value="0">Open</option>
                                                                    <option value="3">Reopen</option>
                                                                    <option value="1">Closed</option>
                                                                    <option value="5">Customer Feedback Awaited
                                                                    </option>
                                                                    <option value="4">Closed with RMA</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Select Level</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="LevelId" id="LevelId">
                                                                    @if (Session::get('exeLevel') == 2)
                                                                        <option value="2">Level 2</option>
                                                                    @else
                                                                        <option value="1">Level 1</option>
                                                                        <option value="2">Level 2</option>
                                                                    @endif
                                                                </select>
                                                            </div> <!-- /.form-group -->
                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4" id="iLevel2CallAttendentIdDiv"
                                                            {{ Session::get('exeLevel') == 2 ? 'style=display:block;' : 'style=display:none;' }}>

                                                            <div class="form-group">
                                                                <label>Select Level 2 Executive</label>
                                                                <select class="js-example-basic-single"
                                                                    style="width: 100%;" name="iLevel2CallAttendentId"
                                                                    id="iLevel2CallAttendentId">
                                                                    <option label="Please Select" value="">-- Select
                                                                        --</option>
                                                                </select>
                                                            </div> <!-- /.form-group -->

                                                        </div> <!-- /.col -->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Add Comments</label>
                                                                <input type="text" class="form-control"
                                                                    name="comments" id="comments" value="" />
                                                            </div>
                                                        </div> <!-- /.col -->
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wizard-footer d-flex">
                                        <input type='button' class='btn btn-previous btn-fill btn-secondary btn-wd mr-4'
                                            name='previous' value='Previous' />
                                        <input type="hidden" name="oldStaus" value="0">
                                        <input type="hidden" name="oldStausDatetime" value="{{ date('Y-m-d H:i:s') }}">
                                        <input type='button' class='btn btn-next btn-fill btn-success btn-wd'
                                            onclick="jQuery('form').data('bootstrapValidator').validate();return false;"
                                            name='next' value='Next' />
                                        <input type='submit' class='btn btn-finish btn-fill btn-success btn-wd'
                                            name='finish' value='Finish' id="submit" />

                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                        </div> <!-- wizard container -->
                    </div>
                </div><!-- end row -->

                <!--table start-->
                <div class="row d-flex justify-content-center my-5">
                    <div class="col-md-10">

                        <div class="fresh-table toolbar-color-orange" id="ticketList" style="display: none;">
                            <table id="fresh-table" class="table"
                                data-url="{{ route('callattendantAdmin.dashboard') }}">
                                <thead>
                                    <th data-field="id">No</th>
                                    <th data-field="no" data-sortable="true">ID</th>
                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th data-field="contactname" data-sortable="true">Contact Name</th>
                                    <th data-field="contact" data-sortable="true">Contact No</th>
                                    <th data-field="company" data-sortable="true">OEM Company</th>
                                    <th data-field="companyname" data-sortable="true">Company Name</th>
                                    <th data-field="system" data-sortable="true">System</th>
                                    <th data-field="component" data-sortable="true">Component</th>
                                    <th data-field="subComponent" data-sortable="true">Sub Component</th>
                                    <th data-field="complaint">Solution Type</th>
                                    <th data-field="date" data-sortable="true">Complaint Date</th>
                                    <th data-field="resolvedDate" data-sortable="true">Resolved Date</th>
                                    <th data-field="issue">Issue</th>
                                    <th data-field="attendant" data-sortable="true">Attendant</th>
                                    <th data-field="actions">Actions</th>
                                </thead>
                                <tbody id="myticketList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--table end-->
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="container">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                            2022
                            Mas Solutions. All rights reserved.</span>
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

    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/js/settings.js') }}"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>

    <!-- Plugin js for this page -->
    <!--step wizard-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/material-bootstrap-wizard.js') }}"></script>

    <!--select 2 form-->
    <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/assets/js/select2.js') }}"></script>

    <!--form validation-->
    <script src="{{ asset('global/assets/vendors/wizard/js/jquery.validate.min.js') }}"></script>



    <!--other company inputs form show/hide-->
    <script>
        function isNumber(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43)
                return false;
            return true;
        }

        function showhide() {
            var div = document.getElementById("other-company");
            if (div.style.display !== "none") {
                div.style.display = "none";
            } else {
                div.style.display = "block";
            }
        }
    </script>

    <!--table plugin-->
    <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
        < script src = "https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js" >
    </script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js">
    </script>

    </script>

    <script type="text/javascript">
        var $table = $('#fresh-table'),

            full_screen = false;

        $().ready(function() {
            $table.bootstrapTable({
                toolbar: ".toolbar",

                showDownload: true,
                showRefresh: true,
                search: true,
                showColumns: true,
                pagination: true,
                striped: true,
                pageSize: 8,
                pageList: [8, 10, 25, 50, 100],

                formatShowingRows: function(pageFrom, pageTo, totalRows) {
                    //do nothing here, we don't want to show the text "showing x of y from..."
                },
                formatRecordsPerPage: function(pageNumber) {
                    return pageNumber + " rows visible";
                },
                icons: {
                    download: 'mas-download',
                    refresh: 'mas-refresh',
                    toggle: 'fa fa-th-list',
                    columns: 'mas-columns',
                    detailOpen: 'fa fa-plus-circle',
                    detailClose: 'fa fa-minus-circle'
                }
            });
            $(window).resize(function() {
                $table.bootstrapTable('resetView');
            });

            window.operateEvents = {
                'click .like': function(e, value, row, index) {
                    alert('You click like icon, row: ' + JSON.stringify(row));
                    console.log(value, row, index);
                },
                'click .edit': function(e, value, row, index) {
                    alert('You click edit icon, row: ' + JSON.stringify(row));
                    console.log(value, row, index);
                },
                'click .remove': function(e, value, row, index) {
                    $table.bootstrapTable('remove', {
                        field: 'id',
                        values: [row.id]
                    });
                }
            };
        });
        $(document).ready(function() {
            $("#OemCompannyId").change();
        });
        $("#OemCompannyId").change(function() {
            $("#CustomerEmailCompany").val('');
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
                    $("#iLevel2CallAttendentId").html(dataItems.exeList);
                }

            });
        });
        $("#iCompanyProfileId").change(function() {
            if (this.value == 'Other') {
                $("#otherCompanyProfileDiv").css("display", "block");
                $("#othrcompanyprofile").attr("required", "true");
            } else {
                $("#otherCompanyProfileDiv").css("display", "none");
                $("#othrcompanyprofile").attr("required", "false");
            }
        });
        $("#iCompanyId").change(function() {
            if (this.value == 'Other') {
                $("#otherCompanyDiv").css("display", "block");
                $("#othrcompanyname").attr("required", "true");
                $("#CustomerEmailCompany").val("");
            } else {
                $("#otherCompanyDiv").css("display", "none");
                $("#othrcompanyname").attr("required", "false");
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
        $("#iTicketStatus").change(function() {
            if (this.value == 1 || this.value == 4 || this.value == 5) {
                $("#LevelId").attr('disabled', true);
                $("#LevelId").val('1');
                $("#iLevel2CallAttendentIdDiv").val('');
            }else{
                $("#LevelId").attr('disabled', false);
            }
        });
        $("#LevelId").change(function() {
            if (this.value == 2) {
                $("#iLevel2CallAttendentIdDiv").css("display", "block");
                var oemCompanyId = $("#OemCompannyId").val();
                var url = "{{ route('complaint.getExecutives', ':id') }}";
                url = url.replace(":id", oemCompanyId);
                $.ajax({
                    type: 'GET',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            $("#iLevel2CallAttendentId").attr("required", "true");
                            $("#iLevel2CallAttendentId").html(response);
                        } else {
                            $("#iLevel2CallAttendentIdDiv").css("display", "none");
                        }
                    }
                });
            } else if (this.value == 1) {
                $("#iLevel2CallAttendentIdDiv").css("display", "none");
            }
        });

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

        $("#tcktImages").on("change", function(e) {

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

        $("#customerNumber").blur(function() {

            $('#myticketList').html('');
            var custNumber = $(this).val();
            $.ajax({
                type: 'POST',
                url: "{{ route('callattendantAdmin.checkCustNumber') }}",
                data: {
                    custNumber: custNumber
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (response != 0) {
                        $('#ticketList').css('display', '');
                        $('#myticketList').html(response);
                    } else {
                        $("#ticketList").css("display", "none");
                        $('#myticketList').html('');
                    }

                }
            });
            getAutoFillComplain();
        });

        $("#customerName").blur(function(){
            getAutoFillComplain();
        });
        function getAutoFillComplain(){
            let customerName = $("#customerName").val();
            let customerNumber = $("#customerNumber").val();
            if((customerName !="") && (customerNumber !="")){
                $.ajax({
                    type: 'POST',
                    url: "{{ route('callattendantAdmin.autofillCustNumberListComplain') }}",
                    data: {
                        customerNumber: customerNumber,
                        customerName: customerName
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        var obj = JSON.parse(response);
                        console.log(obj.iCompanyId);
                        $("#customerEmail").val(obj.CustomerEmail);
                        $("#OemCompannyId").val(obj.OemCompannyId);
                        $("#iCompanyId").val(obj.iCompanyId);
                        $('#iCompanyId').trigger('change');
                        $("#iCompanyProfileId").val(obj.iCompanyProfileId);
                        $('#iCompanyProfileId').trigger('change');
                        $("#CustomerEmailCompany").val(obj.CustomerEmailCompany);
                        $("#OtherInformation").val(obj.OtherInformation);
                        $("#iDistributorId").val(obj.iDistributorId);
                        $('#iDistributorId').trigger('change');
                        $("#ProjectName").val(obj.ProjectName);
                        $("#iStateId").val(obj.iStateId);
                        $('#iStateId').trigger('change');
                        $("#iCityId").val(obj.iCityId);
                        $('#iCityId').trigger('change');
                        $("#iCallThrough").val(obj.iCallThrough);
                        $('#iCallThrough').trigger('change');
                        $("#UserDefiine1").val(obj.UserDefiine1);
                        // $("#iSystemId").val(obj.iSystemId);
                        // $('#iSystemId').trigger('change');
                        // $("#iComponentId").val(obj.iComnentId);
                        // $('#iComponentId').trigger('change');
                        // $("#iSubComponentId").val(obj.iSubComponentId);
                        // $('#iSubComponentId').trigger('change');
                        // $("#iSupportType").val(obj.iSupportType);
                        // $('#iSupportType').trigger('change');
                        // $("#issue").val(obj.issue);

                        // $("#Resolutiondetail").val(obj.Resolutiondetail);
                        // $("#iResolutionCategoryId").val(obj.iResolutionCategoryId);
                        // $('#iResolutionCategoryId').trigger('change');
                        // $("#iIssueTypeId").val(obj.iIssueTypeId);
                        // $('#iIssueTypeId').trigger('change');
                        // $("#CallerCompetencyId").val(obj.CallerCompetencyId);
                        // $('#CallerCompetencyId').trigger('change');
                        // $("#iTicketStatus").val(obj.iTicketStatus);
                        // $('#iTicketStatus').trigger('change');
                        $("#LevelId").val(obj.LevelId);
                        $('#LevelId').trigger('change');
                        // $("#comments").val(obj.comments);
                    }
                });
            }
        }
        $(function() {
            $('.mas-download').click(function(e) {
                var table = $("#fresh-table");
                if (table && table.length) {
                    $(table).table2excel({
                        exclude: ".noExl",
                        name: "Excel Document Name",
                        filename: "callattendantList_" + new Date().toISOString().replace(
                            /[\-\:\.]/g, "") + ".xls",
                        fileext: ".xls",
                        exclude_img: true,
                        exclude_links: false,
                        exclude_inputs: false,
                        preserveColors: false
                    });
                }
            });
            $('.mas-refresh').click(function(e) {
                var table = $("#fresh-table");
                $('.search input[class="form-control"]').val('');
                //$(table).bootstrapTable()
                $(table).bootstrapTable('refreshOptions', {
                    clickToSelect: true,
                    // Other options you want to override
                });
            });
        });

        // // $('#submit').on("click", function() {
        // //     $('#loading').css("display", "block");
        // //   //  $("#submit").attr('disabled', 'disabled');
        // //     $.ajax({
        // //         type: 'POST',
        // //         url: "{{ route('callattendantAdmin.create') }}",
        // //         data: $('#frmparameter').serialize(),
        // //         headers: {
        // //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        // //         },
        // //         success: function(response) {
        // //             console.log(response);
        // //             $('#loading').css("display", "none");
        // //             $("#submit").attr('disabled', 'disabled');
        // //             if (response == 1) {

        // //                 window.location.href = "{{ route('complaint.index') }}"
        // //             }

        // //         }
        // //     });

        // });
    </script>

@endsection
