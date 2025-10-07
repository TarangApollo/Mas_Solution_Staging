@extends('layouts.wladmin')

@section('title', 'List of Users')

@section('content')

        <div class="content-wrapper pb-0">
            <div class="page-header">
                <h3>List of Users</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Users</li>
                        <li class="breadcrumb-item active"> User List </li>
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
                                            <label>First Name</label>
                                            <input type="text" class="form-control" required="">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contact</label>
                                            <input type="text" class="form-control" required="">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email ID</label>
                                            <input type="email" class="form-control" required="">
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Role</label>
                                            <select class="js-example-basic-single" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="BJ">Admin</option>
                                                <option value="SM">Operation</option>
                                                <option value="MR">Support Team</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Status</label>
                                            <select class="js-example-basic-single" style="width: 100%;" required>
                                                <option label="Please Select" value="">-- Select --
                                                </option>
                                                <option value="CL">Active</option>
                                                <option value="OP">Inactive</option>
                                            </select>
                                        </div> <!-- /.form-group -->
                                    </div> <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Date Range</label>
                                            <input type="text" class="form-control" name="daterange" required />
                                        </div>
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
                                <th data-field="status">Status</th>
                                <th data-field="first" data-sortable="true">First Name</th>
                                <th data-field="last" data-sortable="true">Last Name</th>
                                <th data-field="contact" data-sortable="true">Contact</th>
                                <th data-field="mail" data-sortable="true">Email ID</th>
                                <th data-field="role" data-sortable="true">Role</th>
                                <th data-field="login" data-sortable="true">Last Login</th>
                                <th data-field="date" data-sortable="true">Created Date</th>
                                <th data-field="by" data-sortable="true">Created by</th>
                                <th data-field="action">Actions</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>EN28956</td>
                                    <td>
                                        <input type="checkbox" data-toggle="toggle" data-width="90" checked>
                                    </td>
                                    <td>George</td>
                                    <td>Williams</td>
                                    <td>9855825852</td>
                                    <td>g.williams@yahoo.com</td>
                                    <td>Support Team</td>
                                    <td>10-06-2022<br>
                                        <small>10:10:18</small>
                                    </td>
                                    <td>21-05-2022<br>
                                        <small>12:40:18</small>
                                    </td>
                                    <td>Admin</td>
                                    <td>
                                        <a href="add-user.html" title="Edit" class="table-action">
                                            <i class="mas-edit"></i>
                                        </a>
                                        <a href="{{ route('user.info') }}" title="Info" class="table-action">
                                            <i class="mas-info-circle"></i>
                                        </a>
                                        <a href="#" title="Delete" class="table-action">
                                            <i class="mas-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>EN14956</td>
                                    <td>
                                        <input type="checkbox" data-toggle="toggle" data-width="90">
                                    </td>
                                    <td>Harry</td>
                                    <td>Brown</td>
                                    <td>7758452225</td>
                                    <td>harry158@gmail.com</td>
                                    <td>Operation</td>
                                    <td>10-06-2022<br>
                                        <small>09:50:10</small>
                                    </td>
                                    <td>18-05-2022<br>
                                        <small>22:20:15</small>
                                    </td>
                                    <td>employee name</td>
                                    <td>
                                        <a href="add-user.html" title="Edit" class="table-action">
                                            <i class="mas-edit"></i>
                                        </a>
                                        <a href="{{ route('user.info') }}" title="Info" class="table-action">
                                            <i class="mas-info-circle"></i>
                                        </a>
                                        <a href="#" title="Delete" class="table-action">
                                            <i class="mas-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>EN15956</td>
                                    <td>
                                        <input type="checkbox" data-toggle="toggle" data-width="90">
                                    </td>
                                    <td>Charls</td>
                                    <td>Davil</td>
                                    <td>9947525895</td>
                                    <td>davil123@gmail.com</td>
                                    <td>Admin</td>
                                    <td>10-06-2022<br>
                                        <small>09:15:15</small>
                                    </td>
                                    <td>12-05-2022<br>
                                        <small>10:25:15</small>
                                    </td>
                                    <td>employee name</td>
                                    <td>
                                        <a href="add-user.html" title="Edit" class="table-action">
                                            <i class="mas-edit"></i>
                                        </a>
                                        <a href="{{ route('user.info') }}" title="Info" class="table-action">
                                            <i class="mas-info-circle"></i>
                                        </a>
                                        <a href="#" title="Delete" class="table-action">
                                            <i class="mas-trash"></i>
                                        </a>
                                    </td>
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

            $alertBtn.click(function() {
                alert("You pressed on Alert");
            });

        });
    </script>

    <!--toggle button active/inactive-->
    <script src="../global/assets/vendors/toggle-button/bootstrap4-toggle.min.js"></script>

@endsection
