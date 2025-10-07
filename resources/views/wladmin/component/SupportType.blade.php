@extends('layouts.wladmin')

@section('title', 'Add New Component')

@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Add New Component</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">My Components</li>
                    <li class="breadcrumb-item active"> Add Component </li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
        <!-- first row starts here -->
        @include('wladmin.wlcommon.alert')
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
                                            @include('wladmin.component.companysidebar')
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-9 col-md-8">
                                <div class="accordion-box-content">
                                    <div class="tab-content clearfix">
                                        <!--support type start-->
                                        <div class="tab-pane fade in active" id="support">
                                            <h3 class="tab-content-title">Support Type</h3>
                                            <form class="was-validated pb-3" name="frmparameter" id="frmparameter"
                                                action="{{ route('component.supporttypestore') }}" method="post">
                                                <input type="hidden" name="company_id" id="company_id"
                                                    value="{{ $CompanyMaster->iCompanyId }}">
                                                <input type="hidden" name="save" id="save" value="0">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-12 ">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Add Support Type</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="col-md-9 field_wrapper">
                                                                                @if ($supporttypes->isEmpty())
                                                                                    <div class="form-group d-flex">
                                                                                        <input type="hidden"
                                                                                            name="iSuppotTypeId[]"
                                                                                            value="0">
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="field_name[]">
                                                                                        <a href="javascript:void(0);"
                                                                                            class="btn btn-success add_button add-more"
                                                                                            title="Add Support Type"><i
                                                                                                class="mas-plus-circle lh-normal"></i></a>
                                                                                    </div>
                                                                                @else
                                                                                    <?php $iCounter = 0; ?>
                                                                                    @foreach ($supporttypes as $supporttype)
                                                                                        @if ($iCounter == 0)
                                                                                            <div class="form-group d-flex">
                                                                                                <input type="hidden"
                                                                                                    name="iSuppotTypeId[]"
                                                                                                    value="{{ $supporttype->iSuppotTypeId ?? 0 }}">
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    name="field_name[]"
                                                                                                    value="{{ $supporttype->strSupportType }}">
                                                                                                <a href="javascript:void(0);"
                                                                                                    class="btn btn-success add_button add-more"
                                                                                                    title="Add Issue"><i
                                                                                                        class="mas-plus-circle lh-normal"></i></a>
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="form-group d-flex">
                                                                                                <input type="hidden"
                                                                                                    name="iSuppotTypeId[]"
                                                                                                    value="{{ $supporttype->iSuppotTypeId ?? 0 }}">
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    name="field_name[]"
                                                                                                    value="{{ $supporttype->strSupportType }}">
                                                                                                <a href="javascript:void(0);"
                                                                                                    class="btn btn-danger remove_button add-more"
                                                                                                    title="Delete Issue"><i
                                                                                                        class="mas-minus-circle lh-normal"></i></a>
                                                                                            </div>
                                                                                        @endif
                                                                                        <?php $iCounter++; ?>
                                                                                    @endforeach
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="row">
                                                <div class="spinner-border" id="loading" style="display: none" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div> --}}
                                                <div class="row">
                                                    <div class="col-md-6 offset-md-6 d-flex justify-content-end">
                                                        <button type="button"
                                                            class="btn btn-success text-uppercase mt-4 mr-2" name="submit"
                                                            id="submit">
                                                            Save
                                                        </button>
                                                        <button type="submit" class="btn btn-success text-uppercase mt-4"
                                                            id="savesubmit">
                                                            Save & Exit
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
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
                url: "{{ route('component.supporttypestore') }}",
                data: $('#frmparameter').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#loading').css("display", "none");
                    if (response > 0) {

                        $('#company_id').val(response);
                        var company_id = response;
                        $('#save').val('0');
                        var url = "{{ route('component.Support-Type') }}";
                        window.location.href = url;
                        return true;
                    } else {

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
