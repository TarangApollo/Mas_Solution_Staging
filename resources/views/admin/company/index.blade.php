@extends('layouts.admin')

@section('title', 'Company List')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>List of Companies</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Company</li>
                    <li class="breadcrumb-item active"> Company List </li>
                </ol>
            </nav>
        </div>
        <!-- first row starts here -->
        @include('admin.common.alert')

        <div class="row d-flex justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">search by categories</h4>
                        <form class="was-validated p-4 pb-3" action="{{ route('company.index') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select OEM Company</label>
                                        <select class="js-example-basic-single" name="search_company" style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            @foreach ($CompanyMaster as $Companies)
                                                <option value="{{ $Companies->iCompanyId }}"
                                                    @if (isset($search_company) && $search_company == $Companies->iCompanyId) {{ 'selected' }} @endif>
                                                    {{ $Companies->strOEMCompanyName }}</option>
                                            @endforeach
                                            <!-- <option value="SM">LG</option>
                                                                <option value="MR">Samsung</option> -->
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Status</label>
                                        <select class="js-example-basic-single" name="search_status" style="width: 100%;">
                                            <option label="Please Select" value="">-- Select --</option>
                                            <option value="1"
                                                @if (isset($search_status) && $search_status == 1) {{ 'selected' }} @endif>Active</option>
                                            <option value="0"
                                                @if (isset($search_status) && $search_status == 0) {{ 'selected' }} @endif>Inactive
                                            </option>
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                            </div>
                            <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                            <input type="button" class="btn btn-fill btn-default text-uppercase mt-3"
                                onclick="clearData();" value="Clear">
                        </form>
                        <form class="was-validated p-4 pb-3" action="{{ route('company.migration') }}" method="post">
                            @csrf
                            <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Migrate">
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
                            <th data-field="no" data-sortable="true">OEM Company ID</th>
                            <th data-field="status">Status</th>
                            <th data-field="prefix">OEM Company Prefix</th>
                            <th data-field="company" data-sortable="true">OEM Company Name</th>
                            <th data-field="city" data-sortable="true">City</th>
                            <th data-field="by" data-sortable="true">Updated by</th>
                            <th data-field="action">Actions</th>
                        </thead>
                        <tbody>
                            @if (!$Company->isEmpty())
                                <?php $iCounter = 1; ?>
                                @foreach ($Company as $Companies)
                                    <tr>
                                        <td>{{ $iCounter }}</td>
                                        <td>{{ $Companies->strOEMCompanyId }}</td>
                                        <td>
                                            @if ($Companies->iStatus == 1)
                                                <input type="checkbox" name="iStatus" id="iStatus" value="Active"
                                                    onchange="updateStatus(<?= $Companies->iStatus ?>,<?= $Companies->iCompanyId ?>);"
                                                    data-toggle="toggle" data-width="90" checked>
                                            @else
                                                <input type="checkbox" name="iStatus" id="iStatus" value="Inactive"
                                                    onchange="updateStatus(<?= $Companies->iStatus ?>,<?= $Companies->iCompanyId ?>);"
                                                    data-toggle="toggle" data-width="90">
                                            @endif
                                        </td>
                                        <td>{{ $Companies->strCompanyPrefix }}</td>
                                        <td>{{ $Companies->strOEMCompanyName }}</td>
                                        <td>{{ $Companies->strCityName }}</td>
                                        <td>{{ $Companies->first_name }} {{ $Companies->last_name }}</td>
                                        <td>
                                            <a href="{{ route('company.create', $Companies->iCompanyId) }}" title="Edit"
                                                class="table-action">
                                                <i class="mas-edit"></i>
                                            </a>
                                            <a href="{{ route('company.info', $Companies->iCompanyId) }}" title="Info"
                                                class="table-action">
                                                <i class="mas-info-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $iCounter++; ?>
                                @endforeach
                            @endif
                            <!-- <tr>
                                                    <td>2</td>
                                                    <td>P100905</td>
                                                    <td>
                                                        <input type="checkbox" data-toggle="toggle" data-width="90">
                                                    </td>
                                                    <td>LG</td>
                                                    <td>Ahmedabad</td>
                                                    <td>Admin</td>
                                                    <td>
                                                        <a href="add-company.html" title="Edit" class="table-action">
                                                            <i class="mas-edit"></i>
                                                        </a>
                                                        <a href="{{ route('company.info') }}" title="Info" class="table-action">
                                                            <i class="mas-info-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>P102805</td>
                                                    <td>
                                                        <input type="checkbox" data-toggle="toggle" data-width="90">
                                                    </td>
                                                    <td>Samsung</td>
                                                    <td>Surat</td>
                                                    <td>Admin</td>
                                                    <td>
                                                        <a href="add-company.html" title="Edit" class="table-action">
                                                            <i class="mas-edit"></i>
                                                        </a>
                                                        <a href="{{ route('company.info') }}" title="Info" class="table-action">
                                                            <i class="mas-info-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--table end-->
    </div>

@endsection
@section('script')

    <!--table plugin-->
    <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
    </script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js">
    </script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
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
                pageSize: 50,
                pageList: [50, 100, 200, 300, 400],

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

            // $alertBtn.click(function() {
            //     alert("You pressed on Alert");
            // });
        });
        $(function() {
            $('.mas-download').click(function(e) {
                var table = $("#fresh-table");
                if (table && table.length) {
                    $(table).table2excel({
                        exclude: ".noExl",
                        name: "Excel Document Name",
                        filename: "company" + new Date().toISOString().replace(/[\-\:\.]/g, "") +
                            ".xls",
                        fileext: ".xls",
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true,
                        preserveColors: false
                    });
                }
            });
            $('.mas-refresh').click(function(e) {
                $('#fresh-table').bootstrapTable('resetSearch');
            });
        });

        function clearData() {
            window.location.href = "";
        }

        function updateStatus(status, iCompanyId) {
            //$("p").toggle();
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: "{{ route('company.updateStatus') }}",
                data: {
                    status: status,
                    iCompanyId: iCompanyId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#loading').css("display", "none");

                    if (response == 1) {
                        $('#loading').css("display", "none");
                        $("#msgdata").html("<strong>Success !</strong> Company Created Successfully.");
                        window.location.href = "";
                        return true;
                    } else {
                        $('#loading').css("display", "none");
                        return false;
                    }
                }
            });
        }
    </script>
    <!--toggle button active/inactive-->
    <script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script>
@endsection
