@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
    <!-- Required meta tags -->

        <div class="content-wrapper pb-0">
            <div class="page-header">
                <h3>Role Information</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Role</li>
                        <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role List</a></li>
                        <li class="breadcrumb-item active">Information </li>
                    </ol>
                </nav>
            </div>
            <!--/. page header ends-->
            <!-- first row starts here -->
            <div class="row d-flex justify-content-center mb-5">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <h4 class="card-title mt-0">Role Name: {{ $Role->name }}</h4>
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
                                                    <td>User List</td>
                                                    <td>
                                                        @if (count($userList))
                                                            @foreach ($userList as $user)
                                                                <span
                                                                    class="badge badge-success">{{ $user->strFirstName . ' ' . $user->strLastName }}</span>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Permission List</td>
                                                    <td>

                                                        @foreach ($permissionArray as $permission)
                                                            @if (array_key_exists('submenu', $permission))
                                                                <span
                                                                    class="badge badge-success">{{ $permission['name'] }}</span>
                                                                @foreach ($permission['submenu'] as $submenulist)
                                                                    @if (in_array($submenulist['id'], $menuArray))
                                                                        <br>
                                                                        <span
                                                                            class="badge badge-secondary">{{ $submenulist['name'] }}</span>
                                                                    @endif
                                                                @endforeach
                                                                <br>
                                                            @endif
                                                        @endforeach

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--/. col 6-->
                                <div class="col-md-6">
                                    <div class="table-responsive">
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
                                    </div>
                                </div>
                                <!--/. col 6-->
                            </div>
                        </div>
                    </div>
                    <!--card end-->
                </div>
            </div><!-- end row -->
        </div>
        <!-- content-wrapper ends -->

        <!--/. footer ends-->

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

            $alertBtn.click(function() {
                alert("You pressed on Alert");
            });

        });
    </script>

    <!--toggle button active/inactive-->
    <script src="../global/assets/vendors/toggle-button/bootstrap4-toggle.min.js"></script>

@endsection
