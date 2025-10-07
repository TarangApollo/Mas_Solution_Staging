@extends('layouts.wladmin')

@section('title', 'Project Information')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Project Information</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Projects
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Project List</a></li>
                    <li class="breadcrumb-item active"> Information</li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
        <!-- first row starts here -->

        <div class="row d-flex justify-content-center mb-5">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="card-title mt-0">PROJECT NAME: {{ $Projects->projectName }}</h4>
                                <div class="col-md-6 text-right mt-2">
                                    <button type="button" class="btn btn-success text-uppercase" data-toggle="modal"
                                        data-target="#edit-info" id="editInfo">
                                        edit info
                                    </button>
                                </div>
                            </div>

                        </div>
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
                                                <td>Project Name</td>
                                                <td>{{ $Projects->projectName ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>State</td>
                                                <td>{{ $Projects->strStateName ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>City</td>
                                                <td>{{ $Projects->strCityName ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Vertical</td>
                                                <td>{{ $Projects->strVertical ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Sub-Vertical </td>
                                                <td>{{ $Projects->strSubVertical ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>SI</td>
                                                <td>{{ $Projects->strSI ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Engineer </td>
                                                <td>{{ $Projects->strEngineer ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Commissioned in </td>
                                                <td>{{ $Projects->strCommissionedIn ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>System</td>
                                                <td>{{ $Projects->strSystem ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Panel</td>
                                                <td>{{ $Projects->strPanel ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Panel Quantity </td>
                                                <td>{{ $Projects->strPanelQuantity ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>Devices </td>
                                                <td>{{ $Projects->strDevices ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Device Quantity </td>
                                                <td>{{ $Projects->strDeviceQuantity ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>Other Components </td>
                                                <td>{{ $Projects->strOtherComponents ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td>BOQ </td>
                                                <td>{{ $Projects->strBOQ ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>16</td>
                                                <td>AMC</td>
                                                <td>{{ $Projects->strAMC ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>17</td>
                                                <td>Other Information </td>
                                                <td>{{ $Projects->strOtherInformation ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <!-- BOQ Upload row -->
                                @if ($Projects->projectProfileId)
                                    <div class="row mx-1">
                                        <div class="col-md-6">
                                            <h4>BOQ Upload</h4>
                                        </div>

                                        <div class="col-md-6 text-right mt-2">
                                            <button type="button" class="btn btn-success text-uppercase"
                                                data-toggle="modal" data-target="#add-image">
                                                Add
                                            </button>
                                        </div>

                                    </div>
                                    <!--/. BOQ Upload row -->
                                @endif

                                <!-- BOQ Document row -->
                                @if (count($Documents) > 0)
                                    <div class="row mt-4 mx-1">
                                        @foreach ($Documents as $item)
                                            @if ($item->strBOQUpload)
                                                <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                    @php
                                                        $extension = pathinfo($item->strBOQUpload, PATHINFO_EXTENSION);
                                                    @endphp
                                                    @if ($extension == 'xls' || $extension == 'xlsx')
                                                        <a onclick="openDoc('{{ $item->projectProfileFilesId }}')">
                                                            <img src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                                alt="document" title="{{ $item->strBOQUpload }}">
                                                        </a>
                                                    @elseif ($extension == 'doc' || $extension == 'docx')
                                                        <a onclick="openDoc('{{ $item->projectProfileFilesId }}')">
                                                            <img src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                                alt="document" title="{{ $item->strBOQUpload }}">
                                                        </a>
                                                    @else
                                                        <a onclick="openDoc('{{ $item->projectProfileFilesId }}')">
                                                            <img src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                                alt="document" title="{{ $item->strBOQUpload }}">
                                                        </a>
                                                    @endif
                                                    <div class="text-center del-img mt-2">
                                                        <form
                                                            action="{{ route('projects.deletedoc', $item->projectProfileFilesId) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                            style="display: inline-block;">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <button type="submit" class="p-0 border-0 bg-none">
                                                                <i class="mas-trash mas-1x" title="Delete"></i>
                                                            </button>
                                                        </form>

                                                    </div>
                                                    <p class="text-center">
                                                        {{ $item->first_name . ' ' . $item->last_name }} <br>
                                                        {{ date('d-m-Y', strtoTime($item->created_at)) }}
                                                    </p>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                @if ($Projects->projectProfileId)
                                    <hr>

                                    <!-- Completion Document Upload row -->
                                    <div class="row mx-1">
                                        <div class="col-md-6">
                                            <h4>Completion Documents</h4>
                                        </div>
                                        <div class="col-md-6 text-right mt-2">
                                            <button type="button" class="btn btn-success text-uppercase"
                                                data-toggle="modal" data-target="#Completion-Documents">
                                                Add
                                            </button>
                                        </div>

                                    </div>
                                @endif
                                <!--/. Completion Document Upload row -->

                                <!-- Completion Document row -->
                                <div class="row mt-4 mx-1">
                                    @foreach ($Documents as $item)
                                        @if ($item->CompletionDocumentUpload)
                                            <div class="col-md-3 col-xs-6 gallery-box text-center">
                                                @php
                                                    $extension = strtolower(
                                                        pathinfo($item->CompletionDocumentUpload, PATHINFO_EXTENSION),
                                                    );
                                                @endphp

                                                @if (in_array($extension, ['xls', 'xlsx']))
                                                    <a onclick="openDoc('{{ $item->projectProfileFilesId }}')">
                                                        <img src="{{ asset('global/assets/images/reference/photo/excel.jpg') }}"
                                                            alt="document" title="{{ $item->CompletionDocumentUpload }}">
                                                    </a>
                                                @elseif (in_array($extension, ['doc', 'docx']))
                                                    <a onclick="openDoc('{{ $item->projectProfileFilesId }}')">
                                                        <img src="{{ asset('global/assets/images/reference/photo/word.jpg') }}"
                                                            alt="document" title="{{ $item->CompletionDocumentUpload }}">
                                                    </a>
                                                @elseif ($extension == 'pdf')
                                                    <a onclick="openDoc('{{ $item->projectProfileFilesId }}')">
                                                        <img src="{{ asset('global/assets/images/reference/photo/pdf.jpg') }}"
                                                            alt="document" title="{{ $item->CompletionDocumentUpload }}">
                                                    </a>
                                                @elseif (in_array($extension, ['jpeg', 'jpg', 'png', 'webp']))
                                                    <a target="_blank"
                                                        href="{{ asset('Project/Completion_Document_Upload') . '/' . $item->CompletionDocumentUpload }}">
                                                        <img src="{{ asset('Project/Completion_Document_Upload') . '/' . $item->CompletionDocumentUpload }}"
                                                            alt="document" title="{{ $item->CompletionDocumentUpload }}">
                                                    </a>
                                                @else
                                                    <a>
                                                        <img src="{{ asset('global/assets/images/reference/photo/document.jpg') }}"
                                                            alt="document" title="{{ $item->CompletionDocumentUpload }}"
                                                            style="cursor: no-drop!important;">
                                                    </a>
                                                @endif

                                                <div class="text-center del-img mt-2">
                                                    <form
                                                        action="{{ route('projects.deletedoc', $item->projectProfileFilesId) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you Sure You wanted to Delete?');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token"
                                                            value="{{ csrf_token() }}">
                                                        <button type="submit" class="p-0 border-0 bg-none">
                                                            <i class="mas-trash mas-1x" title="Delete"></i>
                                                        </button>
                                                    </form>
                                                    @if (!in_array($extension, ['xls', 'xlsx', 'doc', 'docx', 'pdf', 'jpeg', 'jpg', 'png', 'webp']))
                                                        <a href="{{ asset('Project/Completion_Document_Upload/' . $item->CompletionDocumentUpload) }}"
                                                            download>
                                                            <i class="mas-download mas-1x ml-2" title="Download"></i>
                                                        </a>
                                                    @endif

                                                </div>
                                                <p class="text-center">
                                                    {{ $item->first_name . ' ' . $item->last_name }} <br>
                                                    {{ date('d-m-Y', strtoTime($item->created_at)) }}
                                                </p>
                                            </div><!-- /.col -->
                                        @endif
                                    @endforeach

                                </div> <!-- /. Completion Document row -->
                            </div><!--/. col 6-->
                        </div>

                    </div>
                </div>
                <!--card body-->
            </div>
            <!--card end-->
        </div>
    </div>
    <!--card body end-->

    <!-- modal add images start -->
    <div class="modal fade" id="add-image" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New BOQ Files for {{ $Projects->projectName }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('projects.AddDocument') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="iTicketId" value="{{ $id }}">
                        <input type="hidden" name="Type" value="BOQ_Files">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Upload Documents</label>
                                    <input class="form-control py-6" type="file" name="strBOQUpload[]"
                                        id="formFileMultiple" multiple accept=".pdf, .doc, .docx ,.xlsx , .xls"
                                        required />
                                </div>
                            </div> <!-- /.col -->
                        </div><!--/.row-->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-secondary">Clear</button>
                            <button type="submit" class="btn btn-fill btn-success">Submit</button>
                        </div>
                    </form>
                </div><!--main body-->
            </div>
        </div>
    </div>
    <!-- /. modal add images End -->

    <!-- modal add images start -->
    <div class="modal fade" id="Completion-Documents" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Completion Documents for
                        {{ $Projects->projectName }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('projects.AddDocument') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="iTicketId" value="{{ $id }}">
                        <input type="hidden" name="Type" value="Completion_Documents">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Upload Documents</label>
                                    <input class="form-control py-6" type="file" name="CompletionDocumentUpload[]"
                                        id="formFileMultiple" multiple required />
                                </div>
                            </div> <!-- /.col -->
                        </div><!--/.row-->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-secondary">Clear</button>
                            <button type="submit" class="btn btn-fill btn-success">Submit</button>
                        </div>
                    </form>
                </div><!--main body-->
            </div>
        </div>
    </div>
    <!-- /. modal add images End -->



    <div class="modal fade bd-example-modal-lg" id="edit-info" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Project Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="was-validated" action="{{ route('projects.update', $id) }}" method="post">
                    @csrf
                    <input type="hidden" name="projectProfileId" id="projectProfileId"
                        value="{{ $Projects->projectProfileId ?? 0 }}">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Project Name</label>
                                    <input type="text" name="projectName" id="projectName"
                                        value="{{ $ticketmasters->ProjectName }}" class="form-control" readonly>
                                </div>
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> State </label>
                                    <select class="js-example-basic-single" name="iStateId" id="iStateId"
                                        style="width: 100%;" onchange="getCity();">
                                        <option value="">-- Select
                                            --</option>
                                        @foreach ($statemasters as $state)
                                            <option value="{{ $state->iStateId }}"
                                                @if (isset($Projects->iStateId) && $Projects->iStateId == $state->iStateId) {{ 'selected' }} @endif>
                                                {{ $state->strStateName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4" id="otherCompanyDiv" style="display: none;">
                                <div class="form-group">
                                    <label> City </label>
                                    <select class="js-example-basic-single" name="iCityId" id="iCityId"
                                        style="width: 100%;">
                                        <option label="Please Select" value="">-- Select
                                            --</option>
                                        @foreach ($citymasters as $city)
                                            <option value="{{ $city->iCityId }}"
                                                @if (isset($Projects->iCityId) && $Projects->iCityId == $city->iCityId) {{ 'selected' }} @endif>
                                                {{ $city->strCityName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> <!-- /.col -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vertical</label>
                                    <input type="text" name="strVertical" id="strVertical"
                                        value="{{ $Projects->strVertical ?? '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sub-Vertical</label>
                                    <input type="text" name="strSubVertical" id="strSubVertical"
                                        value="{{ $Projects->strSubVertical ?? '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>SI</label>
                                    <input type="text" name="strSI"
                                        id="strSI"value="{{ $Projects->strSI ?? '' }}" class="form-control">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Engineer</label>
                                    <input type="text" name="strEngineer" id="strEngineer"
                                        value="{{ $Projects->strEngineer ?? '' }}" class="form-control">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Commissioned in</label>
                                    <input type="text" name="strCommissionedIn" id="strCommissionedIn"
                                        value="{{ $Projects->strCommissionedIn ?? '' }}" class="form-control">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> System </label>
                                    <select class="js-example-basic-single" name="iSystemId" id="iSystemId"
                                        style="width: 100%;">
                                        <option label="Please Select" value="">-- Select
                                            --</option>
                                        @foreach ($systemmasters as $system)
                                            <option value="{{ $system->iSystemId }}"
                                                @if (isset($Projects->iSystemId) && $Projects->iSystemId == $system->iSystemId) {{ 'selected' }} @endif>
                                                {{ $system->strSystem }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Panel</label>
                                    <input type="text" name="strPanel" id="strPanel"
                                        value="{{ $Projects->strPanel ?? '' }}" class="form-control">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Panel Quantity</label>
                                    <input type="text" name="strPanelQuantity" id="strPanelQuantity"
                                        value="{{ $Projects->strPanelQuantity ?? '' }}" class="form-control"
                                        onkeypress="return isNumber(event)">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Devices</label>
                                    <input type="text" name="strDevices" id="strDevices"
                                        value="{{ $Projects->strDevices ?? '' }}" class="form-control">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Device Quantity</label>
                                    <input type="text" name="strDeviceQuantity" id="strDeviceQuantity"
                                        value="{{ $Projects->strDeviceQuantity ?? '' }}" class="form-control"
                                        onkeypress="return isNumber(event)">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Other Components</label>
                                    <input type="text" name="strOtherComponents" id="strOtherComponents"
                                        value="{{ $Projects->strOtherComponents ?? '' }}" class="form-control">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>BOQ</label>
                                    <input type="text" name="strBOQ" id="strBOQ"
                                        value="{{ $Projects->strBOQ ?? '' }}" class="form-control">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>AMC</label>
                                    <input type="text" name="strAMC" id="strAMC"
                                        value="{{ $Projects->strAMC ?? '' }}" class="form-control">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Other Information</label>
                                    <input type="text" name="strOtherInformation" id="strOtherInformation"
                                        value="{{ $Projects->strOtherInformation ?? '' }}" class="form-control">
                                    <span id="erremail" class="text-danger"></span>
                                </div>
                            </div>
                        </div><!--/.row-->
                    </div><!--main body-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Clear</button>
                        <button type="submit" class="btn btn-fill btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ asset('global/assets/vendors/wizard/js/material-bootstrap-wizard.js') }}"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <script>
        function openDoc(url) {
            var newurl = "{{ route('projects.openDocument', ':id') }}";
            newurl = newurl.replace(':id', url);
            newurl = newurl.replace('?', '/');
            window.open(newurl, '_blank');

        }
    </script>
@endsection
