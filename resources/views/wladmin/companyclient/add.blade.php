@extends('layouts.wladmin')

@section('title', 'Dashboard')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Add New Profile</h3>
    </div>
    <!--/. page header ends-->
    <!-- <div class="row">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" class="btn btn-success text-uppercase pt-1 mb-3 mr-2">
                <i class="mas-upload btn-icon"></i>
                Upload Profile List
            </button>
        </div>
    </div> -->
    @include('wladmin.wlcommon.alert')
    <!-- first row starts here -->
    <div class="row">
        <div class="col-xl-12 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">


                    <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{ route('companyclient.store')}}" method="post">
                        <input type="hidden" name="company_id" id="company_id" value="{{ $CompanyMaster->iCompanyId }}">
                        <input type="hidden" name="save" id="save" value="0">

                        @csrf
                        <div class="row">
                            <!--sub support list div start-->
                            <div class="col-md-12 ">
                                <div class="table-responsive">

                                    <div class="col-md-9 field_wrapper">
                                        @if($CompanyClientProfile->isEmpty())
                                        <div class="form-group d-flex">
                                            <input type="hidden" name="iCompanyClientProfileId[]" value="0">
                                            <select class="profile-select w-100" name="type[]" >
                                                <option value="1">Customer Company</option>
                                                <option value="2">Distributor</option>
                                            </select>
                                            <input type="text" class="form-control ml-3" name="field_name[]">
                                            <a href="javascript:void(0);" class="btn btn-success add_button add-more" title="Add Issue"><i class="mas-plus-circle lh-normal"></i></a>
                                        </div>
                                        @else
                                        <?php $iCounter = 0; ?>
                                        @foreach($CompanyClientProfile as $Profile)
                                        @if($iCounter == 0)
                                        <div class="form-group d-flex">
                                            <input type="hidden" name="iCompanyClientProfileId[]" value="{{ $Profile->iCompanyClientProfileId ?? 0 }}">
                                            <select class="profile-select w-100" name="type[]">
                                                <option value="1" {{ ($Profile->type ==1) ?'selected' : 0 }}>Customer Company</option>
                                                <option value="2" {{ ($Profile->type ==2) ?'selected' : 0 }}>Distributor</option>
                                            </select>
                                            <input type="text" class="form-control ml-3" name="field_name[]" value="{{ $Profile->strCompanyClientProfile }}">
                                            <a href="javascript:void(0);" class="btn btn-success add_button add-more" title="Add Issue"><i class="mas-plus-circle lh-normal"></i></a>
                                        </div>
                                        @else
                                        <div class="form-group d-flex">
                                            <input type="hidden" name="iCompanyClientProfileId[]" value="{{ $Profile->iCompanyClientProfileId ?? 0 }}">
                                            <select class="profile-select w-100" name="type[]">
                                                <option value="1" {{ ($Profile->type ==1) ?'selected' : 0 }}>Customer Company</option>
                                                <option value="2" {{ ($Profile->type ==2) ?'selected' : 0 }}>Distributor</option>
                                            </select>
                                            <input type="text" class="form-control ml-3" name="field_name[]" value="{{ $Profile->strCompanyClientProfile }}">
                                            <a href="javascript:void(0);" class="btn btn-danger remove_button add-more" title="Delete Issue"><i class="mas-minus-circle lh-normal"></i></a>
                                        </div>
                                        @endif
                                        <?php $iCounter++; ?>
                                        @endforeach
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <!--/.col-md-12 issue type list-->
                        </div>

                        <div class="row">

                            <div class="col-md-9 d-flex justify-content-end">
                                <button type="button" class="btn btn-success text-uppercase mt-4 mr-2" name="submit" id="submit">
                                    Save
                                </button>

                            </div>
                        </div>
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
<!--remove panel on delete action-->
<script>
    $('body').on('click', 'button.deleteDep', function() {
        $(this).parents('.delete-option').remove();
    });
</script>

<!-- add more inputs -->
<script type="text/javascript">
    $(document).ready(function() {
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML =
            '<div class="form-group d-flex"><input type="hidden" name="iCompanyClientProfileId[]" value="0">   <select class="profile-select w-100" name="type[]" style="width: 100%;"><option label="Please Select" value="">-- Select --</option><option value="1">Customer Company</option><option value="2">Distributor</option>      </select><input type="text" class="form-control ml-3" name="field_name[]" value=""/><a href="javascript:void(0);" class="btn btn-danger remove_button pull-right add-more" title="Remove Option"><i class="mas-minus-circle lh-normal"></i></a></div>'; //New input field html
        var x = 1; //Initial field counter is 1

        //Once add button is clicked
        $(addButton).click(function() {
            //Check maximum number of input fields
            if (x < maxField) {
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            }
        });

        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e) {
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
</script>

<!--other company inputs form show/hide-->
<script>
    function showhide() {
        var div = document.getElementById("add-option");
        if (div.style.display !== "none") {
            div.style.display = "none";
        } else {
            div.style.display = "block";
        }
    }
</script>
@endsection
