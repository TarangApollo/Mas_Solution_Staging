@extends('layouts.wladmin')

@section('title', 'Reference List')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Reference Documents</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active"> Reference Documents </li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('reference.create') }}" class="btn btn-success text-uppercase pt-1 mb-3 mr-2">
                    <i class="mas-plus btn-icon"></i>
                    Add Reference Documents
                </a>
            </div>
        </div>
        <!-- first row starts here -->
        @include('wladmin.wlcommon.alert')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">Search by categories</h4>
                        <form class="was-validated p-4 pb-3" action="{{ route('reference.index') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select System</label>
                                        <select class="js-example-basic-single" style="width: 100%;" name="iSystemId"
                                            id="iSystemId">
                                            <option label="Please Select" value="">-- Select -- </option>
                                            @if (!empty($systems))
                                                @foreach ($systems as $system)
                                                    <option value="{{ $system->iSystemId }}"
                                                        {{ isset($iSystemId) && $iSystemId == $system->iSystemId ? 'selected' : '' }}>
                                                        {{ $system->strSystem }}</option>
                                                @endforeach
                                            @else
                                                <option label="Please Select" value="">No Record Found</option>
                                            @endif
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Component</label>
                                        <select class="js-example-basic-single" name="search_component"
                                            id="search_component" style="width: 100%;" required>
                                            <option label="Please Select" value="">-- Select --
                                            </option>
                                            @foreach ($componentLists as $component)
                                                <option value="{{ $component->iComponentId }}"
                                                    @if (isset($search_component) && $search_component == $component->iComponentId) {{ 'selected' }} @endif>
                                                    {{ $component->strComponent }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Sub Component</label>
                                        <select class="js-example-basic-single" name="search_sub_component"
                                            id="search_sub_component" style="width: 100%;" required>
                                            <option label="Please Select" value="">-- Select --
                                            </option>
                                            @foreach ($subcomponents as $subcomponent)
                                                <option value="{{ $subcomponent->iSubComponentId }}"
                                                    @if (isset($search_sub_component) && $search_sub_component == $subcomponent->iSubComponentId) {{ 'selected' }} @endif>
                                                    {{ $subcomponent->strSubComponent }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- /.form-group -->
                                </div> <!-- /.col -->
                            </div>
                            <input type="submit" class="btn btn-success text-uppercase mt-3 mr-2" value="Search">
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
        <!--table start-->
        <div class="row d-flex justify-content-center my-5">
            <div class="col-md-12">
                <div class="fresh-table toolbar-color-orange">
                    <table id="fresh-table" class="table">
                        <thead>
                            <th data-field="id">No</th>
                            <th data-field="ReferenceID">Reference ID</th>
                            <th data-field="system" data-sortable="true">System</th>
                            <th data-field="component" data-sortable="true">Component</th>
                            <th data-field="sub-component" data-sortable="true">Sub Component</th>
                            <th data-field="title">Title</th>
                            <th data-field="content-type">Content Type</th>
                            <th data-field="action">Actions</th>
                        </thead>
                        <tbody>
                            <?php $iCounter = 1; ?>
                            @foreach ($references as $reference)
                                <tr>
                                    <td>{{ $iCounter }}</td>
                                    <td>RF{{ $reference->iRefId }}</td>
                                    <td>{{ $reference->strSystem }}</td>
                                    <td>{{ $reference->strComponent }}</td>
                                    <td>{{ $reference->strSubComponent }}</td>
                                    <td>{{ $reference->strRefTitle }}</td>
                                    <td>
                                        @if ($reference->strContentType != '')
                                            <?php $content_type = explode(',', $reference->strContentType); ?>
                                            @foreach ($content_type as $type)
                                                @if ($type == 1)
                                                    Document,
                                                @endif
                                                @if ($type == 2)
                                                    Image,
                                                @endif
                                                @if ($type == 3)
                                                    Video,
                                                @endif
                                            @endforeach
                                            <!-- <a href="../global/assets/images/customer/video/2185490981.mp4" target="_blank">Video, </a>
                                        <a href="../global/assets/images/customer/photo/4.jpg" target="_blank">Image</a> -->
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('reference.edit', $reference->iRefId) }}" title="Edit"
                                            class="table-action">
                                            <i class="mas-edit"></i>
                                        </a>
                                        <a href="{{ route('reference.info', $reference->iRefId) }}" title="Edit"
                                            class="table-action">
                                            <i class="mas-info-circle"></i>
                                        </a>
                                        <form action="{{ route('reference.delete', $reference->iRefId) }}" method="POST"
                                            onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <a href="#" title="Delete" class="table-action">
                                                <button type="submit" class="p-0 border-0 bg-none"><i
                                                        class="mas-trash"></i></button>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                                <?php $iCounter++; ?>
                            @endforeach

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
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js">
    </script>
    <?php  if (in_array('46', \Session::get('menuList')) ) { ?>
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
                    // pageSize: 8,
                    // pageList: [8, 10, 25, 50, 100],
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
            });
            $(function() {
                $('.mas-download').click(function(e) {
                    var table = $("#fresh-table");
                    if (table && table.length) {
                        $(table).table2excel({
                            exclude: ".noExl",
                            name: "Excel Document Name",
                            filename: "Reference" + new Date().toISOString().replace(/[\-\:\.]/g, "") +
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
        </script>
    <?php } else { ?>
        <script type="text/javascript">
            var $table = $('#fresh-table'),
    
                full_screen = false;
    
            $().ready(function() {
                $table.bootstrapTable({
                    toolbar: ".toolbar",
                    showRefresh: true,
                    search: true,
                    showColumns: true,
                    pagination: true,
                    striped: true,
                    // pageSize: 8,
                    // pageList: [8, 10, 25, 50, 100],
                    pageSize: 50,
                    pageList: [50, 100, 200, 300, 400],
    
                    formatShowingRows: function(pageFrom, pageTo, totalRows) {
                        //do nothing here, we don't want to show the text "showing x of y from..."
                    },
                    formatRecordsPerPage: function(pageNumber) {
                        return pageNumber + " rows visible";
                    },
                    icons: {
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
            $(function() {
                $('.mas-refresh').click(function(e) {
                    $('#fresh-table').bootstrapTable('resetSearch');
                });
            });
        </script>
    <?php } ?>
    <script type="text/javascript">
        function clearData() {
            window.location.href = "";
        }

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
                        $("#search_component").html(response);
                    } else {

                    }
                }
            });
        });

        $("#search_component").change(function() {
            var iComponentId = $(this).val();
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
                    $("#search_sub_component").html(response);
                }
            });
        });
    </script>
    <!--toggle button active/inactive-->
    <script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script>

@endsection
