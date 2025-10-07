@extends('layouts.admin')

@section('title', 'Add New Company')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Add New Company</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Company</li>
                <li class="breadcrumb-item active"> Add New Company </li>
            </ol>
        </nav>
    </div>
    <!--/. page header ends-->
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
    <div class="row">
        <div class="col-xl-12 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="accordion-content clearfix">
                        <div class="col-lg-3 col-md-4">
                            <div class="accordion-box">
                                <div class="panel-group" id="RoleTabs">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a>
                                                    Company Information
                                                </a>
                                            </h4>
                                        </div>
                                        @include('admin.company.companysidebar')
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8">
                            <div class="accordion-box-content">
                                <div class="tab-content clearfix">
                                    <!--support type start-->
                                    <div class="tab-pane fade in active" id="support">
                                        <h3 class="tab-content-title">Module Permission</h3>
                                        <form class="was-validated pb-3" name="frmparameter" id="frmparameter" action="{{route('company.allowmodulestore')}}" method="post">
                                            <input type="hidden" name="company_id" id="company_id" value="{{ request()->id ?? 0 }}">
                                            <input type="hidden" name="save" id="save" value="0">
                                            @csrf
                                            <div class="row">
                                                <!--sub support list div start-->
                                               
                                               
                                                                            <!--<div class="form-group d-flex">-->
                                                                                @foreach($moduleMasters as $moduleMaster)
                                                                                <?php
                                                                                
                                                                                $iModuleId=0;?>
                                                                                @foreach($oemCompanyModules as $oemCompanyModule)
                                                                              
                                                                                    @if($oemCompanyModule['iModuleId'] == $moduleMaster->id)
                                                                                    <?php  $iModuleId = $oemCompanyModule['iModuleId']; ?>
                                                                                    @endif
                                                                                @endforeach
                                                                                
                                                                                   
                                                                                        <input type="hidden" name="iModuleId[]" value="{{ $moduleMaster->id }}">
                                                                                        <input type="hidden" name="id[]" value="{{ $iModuleId }}">
                                                                                         <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label>{{ $moduleMaster->module_name }}</label>
                                                                                            <select class="js-example-basic-single" style="width: 100%;"
                                                                                                name="module[]" id="module_{{ $moduleMaster->id }}">
                                                                                                <option label="Please Select" value="">--Select --</option>
                                                                                                <option value="1" @if(isset($iModuleId) && $iModuleId !=0) {{ 'selected' }} @endif>Yes</option>
                                                                                                <option value="0">No</option>
                                                                                            </select>
                                                                                            <span id="errStateId" class="text-danger"></span>
                                                                                        </div> <!-- /.form-group -->
                                                                                    </div>
                                                                                
                                                                                @endforeach
                                                                            <!--</div>-->
                                                                       
                                                           </div>
                                                  
                                            <div class="row">
                                                <div class="col-md-6 offset-md-6 d-flex justify-content-end">
                                                    <button type="button" class="btn btn-success text-uppercase mt-4 mr-2" name="submit" id="submit">
                                                        Save
                                                    </button>
                                                    <button type="submit" class="btn btn-success text-uppercase mt-4" id="savesubmit">
                                                        Save & Exit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--buttons start-->
                                        <!-- <div class="row">
                                                <div class="col-md-6 offset-md-6 d-flex justify-content-end">
                                                    <button type="button" class="btn btn-success text-uppercase mt-4 mr-2">
                                                        Save
                                                    </button>
                                                    <button type="button" class="btn btn-success text-uppercase mt-4">
                                                        Save & Exit
                                                    </button>
                                                </div>
                                            </div> -->
                                        <!--/.buttons end-->
                                    </div>
                                    <!--/#support type end-->
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
            url: "{{route('company.allowmodulestore')}}",
            data: $('#frmparameter').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').css("display", "none");
                if (response > 0) {
                    $('#loading').css("display", "none");
                    $("#successalert").show();
                    $("#msgdata").html("<strong>Success !</strong> Modules Allowed Successfully.");
                    $('#company_id').val(response);
                    var company_id = response;
                    $('#save').val('0');
                    // var url = "{{route('company.componetcreate',":company_id")}}";
                    // url = url.replace(':company_id', company_id);
                    // url = url.replace('?', '/');
                    var url = "{{route('company.allow-module',':company_id')}}";
                    url = url.replace(':company_id', company_id);
                    url = url.replace('?', '/');

                    window.location.href = url;
                    return true;
                } else {
                    $('#loading').css("display", "none");
                    $("#erroralert").show();
                    $("#msgdata").html("<strong>Error !</strong> Somthing want wrong.");
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
            '<div class="form-group d-flex"><input type="hidden" name="iSuppotTypeId[]" value="0"><input type="text" class="form-control" name="field_name[]" value=""/><a href="javascript:void(0);" class="btn btn-danger remove_button pull-right add-more" title="Remove Option"><i class="mas-minus-circle lh-normal"></i></a></div>'; //New input field html
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
