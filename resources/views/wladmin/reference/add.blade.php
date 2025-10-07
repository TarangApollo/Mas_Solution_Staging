@extends('layouts.wladmin')

@section('title', 'Add Reference')

@section('content')
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper pb-0">
    <div class="page-header">
        <h3>Add Reference Documents</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item"><a href="{{ route('reference.index') }}">Reference Documents</a></li>
                <li class="breadcrumb-item active"> Add Reference Documents </li>
            </ol>
        </nav>
    </div>
    <!--/. page header ends-->
    <!-- first row starts here -->
    @include('wladmin.wlcommon.alert')
    <div class="row">
        <div class="col-xl-12 stretch-card grid-margin">
            <div class="card">
                <div class="card-body p-0">
                    <h4 class="card-title mt-0">Add Document</h4>
                    <form class="was-validated p-4 pb-3" id="frmReference" action="{{ route('reference.store') }}" method="post" enctype='multipart/form-data'>
                        <input type="hidden" name="iCompanyId" id="iCompanyId" value="{{ $CompanyMaster->iCompanyId }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Reference Title*</label>
                                    <input type="text" class="form-control" name="strRefTitle" id="strRefTitle" required="">
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select system*</label>
                                    <select class="js-example-basic-single" name="iSystemId" id="iSystemId" style="width: 100%;" required>
                                        <option label="Please Select" value="">-- Select --</option>
                                        @foreach($systemLists as $system)
                                        <option value="{{ $system->iSystemId }}">{{ $system->strSystem }}</option>
                                        @endforeach
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Component*</label>
                                    <select class="js-example-basic-single" name="iComponentId" id="iComponentId" style="width: 100%;" required>
                                        <option label="Please Select" value="">-- Select --</option>
                                        @foreach($componentLists as $component)
                                        <option value="{{ $component->iComponentId }}">{{ $component->strComponent }}</option>
                                        @endforeach
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Sub Component</label>
                                    <select class="js-example-basic-single" name="iSubComponentId" id="iSubComponentId" style="width: 100%;" >
                                        <option label="Please Select" value="">-- Select --</option>
                                        @foreach($subcomponents as $subcomponent)
                                        <option value="{{ $subcomponent->iSubComponentId }}">{{ $subcomponent->strSubComponent }}</option>
                                        @endforeach
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Content Type</label>
                                    <select multiple="multiple" name="strContentType[]" id="strContentType" class="js-example-basic-single" style="width: 100%;" required>
                                        <option data-tokens="All" value="1,2,3">All</option>
                                        <option data-tokens="Document" value="1">Document</option>
                                        <option data-tokens="Image" value="2">Image</option>
                                        <option data-tokens="Video" value="3">Video</option>
                                    </select>
                                </div> <!-- /.form-group -->
                            </div> <!-- /.col -->
                            <div class="col-md-4 Document">
                                <div class="form-group">
                                    <label>Upload Document (pdf, excel, word below 10 MB)</label>
                                    <input class="form-control" name="strDocument[]" id="strDocument" type="file" accept=".pdf,.doc,.docx,.xlsx,.xls" multiple/>
                                </div>
                            </div> <!-- /.col -->
                            <div class="col-md-4 Image">
                                <div class="form-group">
                                    <label>Upload Images (max 5, each below 10 MB)</label>
                                    <input class="form-control" name="strImages[]" id="strImages" onchange="checkImageFiles(this.files)" type="file" id="formFileMultiple" multiple="" accept="image/jpeg,image/gif,image/png"/>
                                </div>
                            </div> <!-- /.col -->
                            <div class="col-md-4 Video">
                                <div class="form-group">
                                    <label>Upload Video (max 2, each below 200 MB)</label>
                                    <input class="form-control" name="strVideo[]" id="strVideo" onchange="checkVideoFiles(this.files)" type="file" id="formFileMultiple" multiple="" accept="video/*"/>
                                </div>
                            </div> <!-- /.col -->
                        </div>

                        <input type="submit" class="btn btn-success text-uppercase mt-3 mr-2" value="Submit">
                        <input type="button" class="btn btn-default text-uppercase mt-3" onclick="clearData();" value="Clear">
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
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->

<script>
    $(document).ready(function(){
        $('.Document').hide();
        $('.Image').hide();
        $('.Video').hide();
        $("#strContentType").change(function (){
            var strContentType = $(this).val();
            $('.Document').hide();
            $('.Image').hide();
            $('.Video').hide();
            $.each($(this).find('option:selected'), function (index, item) {
                var selected = $(item).data('tokens');
                if (selected == 'All') {
                    $('.Document').show();
                    $('.Image').show();
                    $('.Video').show();
                    return true;
                } else {
                    $("." + selected).show();
                }
            });
        });
    });
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
                        $("#iComponentId").html(response);
                    } else {

                    }
                }
            });
        });

    $("#iComponentId").change(function() {
        var iComponentId = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{route('faq.getsubcomponent')}}",
            data: {
                iComponentId: iComponentId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#iSubComponentId").html(response);
            }
        });
    });

    function checkImageFiles(files) {
        if (files.length > 5) {
            alert("length exceeded; Image have been truncated");

            let list = new DataTransfer;
            for (let i = 0; i < 5; i++)
                list.items.add(files[i])

            document.getElementById('strImages').files = list.files
        }
    }

    function checkVideoFiles(files) {
        if (files.length > 2) {
            alert("length exceeded; Video have been truncated");

            let list = new DataTransfer;
            for (let i = 0; i < 2; i++)
                list.items.add(files[i])

            document.getElementById('strVideo').files = list.files
        }
    }

    function clearData() {
        window.location.href = "";
    }
    </script>
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
    <script>
    // $('#frmReference').validate({
    //     rules: {
    //         "strDocument": {
    //             //extension: "jpg|jpeg|png",
    //             filesize: 10485760
    //         },
    //         "strImages[]": {
    //             //extension: "jpg|jpeg|png",
    //             filesize: 10485760,
    //         },
    //         "strVideo[]": {
    //             //extension: "jpg|jpeg|png",
    //             filesize: 209715200,
    //         }
    //     }
    // });
</script>
@endsection
