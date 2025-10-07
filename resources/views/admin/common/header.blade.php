
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mas-angle-double-left"></span>
                </button>
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo-mini" href="index.html"><img
                            src="../global/assets/images/logo-mini-white.png" alt="logo" /></a>
                </div>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <?php $photo = auth()->user()->photo ?? ''; ?>
                            <div class="nav-profile-img">
                                <?php if(isset($photo) && $photo != "") { ?>
                                <img src="{{ (auth()->user()->photo) ? asset('/ProfilePhoto/' . $photo) : asset('global/assets/images/user_icon.jpeg'); }}" alt="image" />
                                <?php } ?>
                            </div>
                            <div class="nav-profile-text">

                                <p class="text-black font-weight-normal m-0">
                                    {{ (auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? '') }} </p>
                                <span class="font-13">online <i class="mas-arrow-down"></i></span>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ route('resetpassword.index') }}">
                                <i class="mas-refresh-thin mr-2 font-13"></i> Reset Password
                            </a>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="mas-user mr-2 font-13"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            {{-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a> --}}
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="mas-log-out mr-2 font-13"></i> Signout </a>
                        </div>
                    </li>
                </ul>
                <!--/. profile ends-->
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mas-menu-bars"></span>
                </button>
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

