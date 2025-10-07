<nav class="navbar top-navbar col-lg-12 col-12 p-0">
    <div class="container">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo" href="{{ route('callattendantAdmin.dashboard') }}">

                <img class="sidebar-brand-logo" src="{{ \Session::get('CompanyLogo') }}" alt="" />
            </a>
            {{-- <a class="navbar-brand brand-logo-mini" href="{{ route('callattendantAdmin.dashboard') }}"><img
                    src="{{ asset('global/assets/images/logo.png') }}" alt="logo" /></a> --}}
            <a class="navbar-brand brand-logo-mini" href="{{ route('callattendantAdmin.dashboard') }}"><img
                    src="{{ \Session::get('CompanyLogo') }}" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?php $photo = auth()->user()->photo; ?>
                        <div class="nav-profile-img">
                            <img src="{{ auth()->user()->photo ? asset('/UserProfilePhoto/' . $photo) : asset('global/assets/images/user_icon.jpeg') }}"
                                alt="image" />
                        </div>
                        <div class="nav-profile-text">
                            <p class="text-black font-weight-semibold m-0">
                                {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }} </p>
                            <span class="font-13 online-color">online <i class="mas-arrow-down"></i></span>
                        </div>
                    </a>
                    <?php
                    $userid = auth()->user()->id;
                    $data = DB::table('users')
                        ->where(['id' => $userid])
                        ->first();
                    ?>
                    <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">

                        @if ($data->isCanSwitchProfile == 1)
                            <a class="dropdown-item" href="{{ route('User.switchsubmit') }}">
                                <i class="mas-change text-orange"></i> Switch User
                            </a>
                        @endif

                        <a class="dropdown-item" href="{{ route('callattendantresetpassword.index') }}">
                            <i class="mas-refresh-thin me-2 text-orange"></i> Reset Password </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="mas-log-out text-orange"></i> Signout </a>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="horizontal-menu-toggle">
                <span class="mas-menu-bars"></span>
            </button>
        </div>
    </div>
</nav>
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-success" href="{{ route('profile.logoutlog') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('profile.logoutlog') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
</div>
