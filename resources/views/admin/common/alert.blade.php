{{-- Message --}}
@if (Session::has('Success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success !</strong> {{ session('Success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (Session::has('Error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error !</strong> {{ session('Error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="alert alert-success" id="successalert" role="alert" style="display:none">
    <button type="button" class="close" data-dismiss="alert">
        <i class="fa fa-times"></i>
    </button>
    <span id="msgdataSuccess"></span>
</div>
<div class="alert alert-danger" id="erroralert" role="alert" style="display:none">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <span id="msgdataError"></span>
</div>
