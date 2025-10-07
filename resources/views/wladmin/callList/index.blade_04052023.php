@extends('layouts.wladmin')

@section('title', 'List of Calls')

@section('content')
    <!-- css for this page -->
    <!--select 2 form-->
    <link rel="stylesheet" href="../global/assets/vendors/select2/select2.min.css" />
    <link rel="stylesheet" href="../global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css" />
    <!--bootstrap table-->
    <link href="../global/assets/vendors/bootstrap-table/css/fresh-bootstrap-table.css" rel="stylesheet" />
    <!--date range picker-->
    <link rel="stylesheet" type="text/css" href="../global/assets/vendors/date-picker/daterangepicker.css" />
    <!--toggle button active/inactive-->
    <link href="../global/assets/vendors/toggle-button/bootstrap4-toggle.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="../global/assets/images/favicon.png" />

    <div class="main-panel">
        <div class="content-wrapper pb-0">
            <div class="page-header">
                <h3>List of Calls</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item active"> Call List </li>
                    </ol>
                </nav>
            </div>
            <!-- first row starts here -->

            <div class="row d-flex justify-content-center">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <h4 class="card-title mt-0">search by categories</h4>
                            <form class="was-validated p-4 pb-3" action="" method="post">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Search by Complaint ID</label>
                                            <input type="text" class="form-control" required />
                                        </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select User Company</label>
                                            <select class="js-example-basic-single" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="BJ">Fire System</option>
                                                <option value="SM">Attentive Fire</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select System</label>
                                            <select class="js-example-basic-single" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="BJ">Software</option>
                                                <option value="SM">Hardware</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Component</label>
                                            <select class="js-example-basic-single" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="BJ">Fan</option>
                                                <option value="SM">Air Coolers</option>
                                                <option value="MR">Water Heaters</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Sub Component</label>
                                            <select class="js-example-basic-single" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="SM">Samsung M2s</option>
                                                <option value="SSM">Samsung M30</option>
                                                <option value="GU">Galaxy S21 Ultra</option>
                                                <option value="ZF">Z3 Fold</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Ticket Status</label>
                                            <select class="js-example-basic-single" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="CL">Open</option>
                                                <option value="OP">Closed</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Date Range</label>
                                            <input type="text" class="form-control" name="daterange" required />
                                        </div>
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Call Attendant</label>
                                            <select class="js-example-basic-single" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="CA">Nandita Das</option>
                                                <option value="CB">Srikant Reddy</option>
                                                <option value="CC">Thomas David</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                </div>
                                <input type="button" class="btn btn-fill btn-success text-uppercase mt-3 mr-2"
                                    value="Search">
                                <input type="button" class="btn btn-fill btn-default text-uppercase mt-3" value="Clear">
                            </form>
                        </div>
                    </div>
                    <!--card end-->
                </div>
            </div><!-- end row -->
            <!--table start-->
            <div class="row d-flex justify-content-center my-5">
                <div class="col-md-12">
                    <div class="fresh-table toolbar-color-orange">
                        <table id="fresh-table" class="table">
                            <thead>
                                <th data-field="id">No</th>
                                <th data-field="no" data-sortable="true">ID</th>
                                <th data-field="outcome" data-sortable="true">Outcome</th>
                                <th data-field="status" data-sortable="true">Status</th>
                                <th data-field="date" data-sortable="true">Date</th>
                                <th data-field="system" data-sortable="true">System</th>
                                <th data-field="component" data-sortable="true">Component</th>
                                <th data-field="sub" data-sortable="true">Sub Component</th>
                                <th data-field="audio">Audio</th>
                                <th data-field="duration" data-sortable="true">Duration</th>
                                <th data-field="user-company" data-sortable="true">User Company</th>
                                <th data-field="attendant" data-sortable="true">Attendant</th>
                                <th data-field="action">Actions</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>CN28956</td>
                                    <td>Connected</td>
                                    <td>Open</td>
                                    <td>21-05-2022<br>
                                        <small>12:40:18</small>
                                    </td>
                                    <td>Software</td>
                                    <td>Mobile</td>
                                    <td>Galaxy M30</td>
                                    <td>
                                        <audio controls>
                                            <source src="../global/assets/recordings/Piano.mp3" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </td>
                                    <td>0:35</td>
                                    <td>Fire System</td>
                                    <td>Thomas David</td>
                                    <td>
                                        <a href="{{ route('callList.info') }}" title="Info" class="table-action">
                                            <i class="mas-info-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>CN28955</td>
                                    <td>No answer</td>
                                    <td></td>
                                    <td>21-05-2022<br>
                                        <small>09:20:18</small>
                                    </td>
                                    <td>Hardware</td>
                                    <td></td>
                                    <td></td>
                                    <td> </td>
                                    <td>0:20</td>
                                    <td>Fire System</td>
                                    <td></td>
                                    <td>
                                        <a href="{{ route('callList.info') }}" title="Info" class="table-action">
                                            <i class="mas-info-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>CN28954</td>
                                    <td>Connected</td>
                                    <td>Closed</td>
                                    <td>21-05-2022<br>
                                        <small>08:40:18</small>
                                    </td>
                                    <td>Software</td>
                                    <td>Refrigerator</td>
                                    <td></td>
                                    <td>
                                        <audio controls>
                                            <source src="../global/assets/recordings/Piano.mp3" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </td>
                                    <td>1:55</td>
                                    <td>Attentive Fire</td>
                                    <td>Oliver Taylor</td>
                                    <td>
                                        <a href="{{ route('callList.info') }}" title="Info" class="table-action">
                                            <i class="mas-info-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>CN28954</td>
                                    <td>Connected</td>
                                    <td>Open</td>
                                    <td>21-05-2022<br>
                                        <small>08:40:18</small>
                                    </td>
                                    <td>Software</td>
                                    <td>Fan</td>
                                    <td>Esteem 1200mm</td>
                                    <td>
                                        <audio controls>
                                            <source src="../global/assets/recordings/Piano.mp3" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </td>
                                    <td>1:55</td>
                                    <td>Fire System</td>
                                    <td>Oliver Taylor</td>
                                    <td>
                                        <a href="{{ route('callList.info') }}" title="Info" class="table-action">
                                            <i class="mas-info-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">Total Minutes</td>
                                    <td>5:00</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--table end-->
        </div>
        <!-- content-wrapper ends -->
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center d-block d-sm-inline-block">Copyright © 2022 Mas Solutions.
                    All rights reserved.</span>
                <span class="float-none text-black-50 d-block mt-1 mt-sm-0 text-center">Developed by <a href="#">
                        Excellent Computers </a> </span>
            </div>
        </footer>
        <!--/. footer ends-->
    </div>
    <!-- main-panel ends -->

    <!-- plugins:js -->
    <script src="../global/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../global/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../global/assets/js/settings.js"></script>
    <script src="../global/assets/js/custom.js"></script>
    <script src="../global/assets/js/off-canvas.js"></script>
    <script src="../global/assets/js/hoverable-collapse.js"></script>
    <script src="../global/assets/vendors/wizard/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- Plugin js for this page -->
    <!--select 2 form-->
    <script src="../global/assets/vendors/select2/select2.min.js"></script>
    <script src="../global/assets/js/select2.js"></script>

    <!--form validation-->
    <script src="../global/assets/vendors/wizard/js/jquery.validate.min.js"></script>

    <!--date range picker-->
    <script type="text/javascript" src="../global/assets/vendors/date-picker/moment.min.js"></script>
    <script type="text/javascript" src="../global/assets/vendors/date-picker/daterangepicker.min.js"></script>

    <!--table plugin-->
    <script type="text/javascript" src="../global/assets/vendors/bootstrap-table/js/bootstrap-table.js"></script>
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
    </script>

    <!--toggle button active/inactive-->
    <script src="../global/assets/vendors/toggle-button/bootstrap4-toggle.min.js"></script>

@endsection
