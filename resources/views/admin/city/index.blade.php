@extends('layouts.admin')

@section('title', 'Setting')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>City </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">City Master</li>
                </ol>
            </nav>
        </div>
        @include('admin.common.alert')

        <!-- first row starts here -->
        <div class="row">
            <div class="col-xl-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="accordion-content clearfix">

                            <div class="col-lg-12 col-md-8">
                                <div class="accordion-box-content">
                                    <div class="tab-content clearfix">
                                        <div class="tab-pane fade in active" id="general">
                                            <h3 class="tab-content-title">General</h3>
                                            <form class="was-validated pb-3" name="frmGeneral" id="frmGeneral"
                                                action="{{ route('admincity.addCity') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="CompanyInfoId" id="CompanyInfoId"
                                                    value="{{ $CompanyInfo->iCompanyInfoId ?? '0' }}">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>City Name*</label>
                                                            <input type="text" class="form-control" name="strCityName"
                                                                id="strCityName" value="">
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>State*</label>
                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                name="iStateId" id="iStateId" onchange="getCity();"
                                                                required>
                                                                <option label="Please Select" value="">--
                                                                    Select --</option>
                                                                @foreach ($statemasters as $state)
                                                                    <option value="{{ $state->iStateId }}"
                                                                        @if (isset($Company->iStateId) && $Company->iStateId == $state->iStateId) {{ 'selected' }} @endif>
                                                                        {{ $state->strStateName }}</option>
                                                                @endforeach

                                                            </select>

                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->


                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <button type="submit"
                                                            class="btn btn-success text-uppercase mt-4 mr-2" value="Submit"
                                                            name="submit" id="GeneralDataSubmit">
                                                            Save
                                                        </button>
                                                        <button type="button" class="btn btn-default text-uppercase mt-4"
                                                            onclick="ClearGeneralData();">
                                                            Clear
                                                        </button>
                                                        
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button"
                                                            class="btn btn-success text-uppercase mt-4 mr-2" value="Submit"
                                                            name="submit" id="GeneralDataSubmit" onclick="genrateExcel();">
                                                            Download City List
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                            <!--buttons start-->

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

    </div>

@endsection
@section('script')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        function ClearGeneralData() {
            $("#iStateId").val('');
            $("#strCityName").val('');

        }

        function genrateExcel() {
            var url = "{{ route('admincity.downloadCity') }}";
            window.location.href = url;
        }
    </script>
@endsection
