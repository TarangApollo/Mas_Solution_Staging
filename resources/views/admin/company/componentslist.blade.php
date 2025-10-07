@extends('layouts.admin')

@section('title', 'Components List')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>List of Components</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">My Components</li>
                <li class="breadcrumb-item active"> Components </li>
            </ol>
        </nav>
    </div>
    <!-- first row starts here -->
    @include('admin.common.alert')
    <div class="alert alert-success" id="successalert" role="alert" style="display:none">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <span id="msgdata"></span>
    </div>
    <div class="alert alert-danger" id="erroralert" role="alert" style="display:none">
        <strong>Error !</strong> {{ session('Error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <span id="msgdata"></span>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body p-0">
                    <h4 class="card-title mt-0">search by categories</h4>
                    <form class="was-validated p-4 pb-3" action="{{ route('company.componentslist') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Company*</label>
                                    <select class="js-example-basic-single" name="search_company" id="search_company" style="width: 100%;">
                                        <option label="Please Select" value="">-- Select --</option>
                                        @foreach($CompanyMaster as $Company)
                                            <option value="{{ $Company->iCompanyId }}">{{ $Company->strOEMCompanyName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select System*</label>
                                    <select class="js-example-basic-single" name="search_system" id="search_system" style="width: 100%;">
                                        <option label="Please Select" value="">-- Select --</option>
                                        @foreach($systems as $system)
                                            <option value="{{ $system->iSystemId }}">{{ $system->strSystem }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Component</label>
                                    <select class="js-example-basic-single" name="search_component" id="search_component" style="width: 100%;" >
                                        <option label="Please Select" value="">-- Select --</option>
                                        @foreach($componentLists as $component)
                                            <option value="{{ $component->iComponentId }}">{{ $component->strComponent }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-fill btn-success text-uppercase mt-3 mr-2" value="Search">
                        <input type="button" onclick="clearData();" class="btn btn-fill btn-default text-uppercase mt-3" value="Clear">
                    </form>
                </div>
            </div><!--card end-->
        </div>
    </div><!-- end row -->

    <!--table start-->
    <div class="row d-flex justify-content-center my-5">
        <div class="col-md-12">
            <div class="fresh-table toolbar-color-orange">
                <table id="fresh-table" class="table">
                    <thead>
                        <th data-field="id">No</th>
                        <th data-field="company">Company</th>
                        <th data-field="system">System</th>
                        <th data-field="component" data-sortable="true">Component</th>
                        <th data-field="sub">Sub Component</th>
                        <th data-field="action">Actions</th>
                    </thead>
                    <tbody>
                        <?php $iCounter=1;?>
                        @foreach($components as $component)
                            <tr>
                                <td>{{ $iCounter }}</td>
                                <td>{{ $component->strOEMCompanyName }}</td>
                                <td>{{ $component->strSystem }}</td>
                                <td>{{ $component->strComponent}}</td>
                                <td>
                                    @if($component->IsSubComponent == 1)
                                        @foreach($subcomponents as $subcomponent)
                                            @if($subcomponent->iComponentId == $component->iComponentId)
                                            <span class="badge badge-secondary">{{ $subcomponent->strSubComponent }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('company.componentsedit', $component->iComponentId) }}" title="Edit" class="table-action">
                                        <i class="mas-edit"></i>
                                    </a>
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
<script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>
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
                    filename: "Components" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            }
        });
        $('.mas-refresh').click(function(e) {
            var table = $("#fresh-table");
            $('.search input[class="form-control"]').val('');
            //$(table).bootstrapTable()
            $(table).bootstrapTable('refreshOptions',{
                clickToSelect: true,
                // Other options you want to override
            });
        });
    });

    function clearData() {
        window.location.href = "";
    }

    $("#search_company").change(function(){
        var search_company = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{route('company.getsystem')}}",
            data: {
                search_company: search_company
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#search_system").html(response);
            }
        });
    });

    $("#search_system").change(function(){
        var search_system = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{route('company.getcomponent')}}",
            data: {
                search_system: search_system
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#search_component").html(response);
            }
        });
    });
</script>
<!--toggle button active/inactive-->
<script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script>
@endsection
