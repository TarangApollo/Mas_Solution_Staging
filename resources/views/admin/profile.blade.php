@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>User Profile</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active"> Profile </li>
                </ol>
            </nav>
        </div>

        {{-- Alert Messages --}}
        @include('admin.common.alert')

        <!--/. page header ends-->
        <!-- first row starts here -->
        <div class="row">
            <div class="col-xl-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">Edit your Profile</h4>
                        <form class="was-validated p-4 pb-3" action="{{ route('profile.update') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="picture-container">
                                        <div class="picture">
                                            <?php $photo = auth()->user()->photo;
                                            if($photo != ""){ ?>
                                            <img src="{{ asset('/ProfilePhoto/' . $photo) }}" class="picture-src h-100"
                                                id="wizardPicturePreview" title="" alt="Profile Pic">
                                            <?php }else{ ?>
                                            <img src="{{ asset('/images/noimage.jpg') }}" class="picture-src h-100"
                                                id="wizardPicturePreview" title="" alt="Profile Pic">
                                            <?php } ?>
                                            <input type="file" id="wizard-picture" name="photo" class="">
                                            <input type="hidden" name="hiddenPhoto" class="form-control"
                                                id="hiddenPhoto" value="{{ $photo }}">

                                        </div>
                                        <h6 class="">Choose Picture</h6>
                                    </div>
                                </div>
                                <!--/. col 4-->
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="first_name"
                                                    value="{{ old('first_name') ? old('first_name') : auth()->user()->full_name }}"
                                                    required />
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email ID</label>
                                                <input type="email" class="form-control" name="email"
                                                    onkeyup="checkemail(this)" id="email"
                                                    value="{{ old('email') ? old('email') : auth()->user()->email }}"
                                                    required />
                                                <b><span style="color:red;" id="mailerror"></span></b>
                                            </div>
                                        </div> <!-- /.col -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Contact Number</label>
                                                <input type="text" class="form-control" name="mobile_number"
                                                    id="MobileValidate"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    value="{{ old('mobile_number') ? old('mobile_number') : auth()->user()->mobile_number }}"
                                                    maxlength="10" required />
                                            </div>
                                        </div> <!-- /.col -->
                                    </div>
                                    <!--/. row-->
                                </div>
                                <!--/. col 8-->
                            </div>
                            <input type="submit" onclick="return validateData();"
                                class="btn btn-success text-uppercase mt-3 mr-2" value="Save">
                            <a class="btn btn-default text-uppercase mt-3"
                                href="{{ route('profile.detail') }}">Clear</a>
                            {{-- <input type="button" class="btn btn-default text-uppercase mt-3" value="Clear"> --}}
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
