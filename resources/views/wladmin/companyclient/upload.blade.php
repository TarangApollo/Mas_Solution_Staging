@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Upload Company List</h3>
    </div>
    @include('wladmin.wlcommon.alert')
    <!-- first row starts here -->
    <div class="row">
        <div class="col-xl-12 stretch-card grid-margin">
            <div class="card">
                <h4 class="card-title mt-0">Upload Company List</h4>
                <div class="card-body">
                    <form class="was-validated p-4 pb-3" id="frmFaq" action="{{ route('companyclient.uploadsubmit') }}"
                        method="post" enctype='multipart/form-data'>
                        <input type="hidden" name="iCompanyId" id="iCompanyId" value="{{ Session::get('CompanyId') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Upload excel</label>
                                    <input class="form-control" name="strDocument" id="strDocument" type="file"
                                        accept=".xlsx,.xls" required="">
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-success text-uppercase mt-3 mr-2" value="Submit">
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
</div>
@endsection

@section('script')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$('#submit').on("click", function() {
    $('#save').val('1');
    $('#loading').css("display", "block");
    $.ajax({
        type: 'POST',
        url: "{{route('companyclient.store')}}",
        data: $('#frmparameter').serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#loading').css("display", "none");
            if (response > 0) {
                $('#loading').css("display", "none");
                $('#save').val('0');
                window.location.href = "";
                return true;
            } else {
                $('#loading').css("display", "none");
                return false;
            }
        }
    });
});
</script>

<script>
$('body').on('click', 'button.deleteDep', function() {
    $(this).parents('.delete-option').remove();
});
</script>
@endsection