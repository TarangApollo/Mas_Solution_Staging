
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item pt-3 mb-5">
                <a class="nav-link d-block" href="#">
                    <img class="sidebar-brand-logo" src="{{ \Session::get('CompanyLogo') }}"  alt="" />
                    <!--<img class="sidebar-brand-logomini" src="{{ asset('global/assets/images/logo-mini.png') }}" alt="" />-->
                </a>
            </li>
            <?php if (in_array('13', \Session::get('menuList'))) { ?>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('wladmin.dashboard') }}">
                    <i class="mas-dashboard menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <?php } ?>
            <?php if (in_array('15', \Session::get('menuList')) || in_array('16', \Session::get('menuList'))) { ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#component" aria-expanded="false" aria-controls="component">
                        <i class="mas-package menu-icon"></i>
                        <span class="menu-title">My Components</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="component">
                        <ul class="nav flex-column sub-menu">
                            <?php if (in_array('15', \Session::get('menuList'))) { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('component.create') }}">Add Components</a>
                                </li>
                            <?php }
                            if (in_array('16', \Session::get('menuList'))) { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('component.index') }}">Components</a>
                                </li>
                            <?php  } ?>
                        </ul>
                    </div>
                </li>
            <?php } ?>
            <?php if (in_array('18', \Session::get('menuList')) || in_array('19', \Session::get('menuList'))) { ?>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#role" aria-expanded="false" aria-controls="role">
                    <i class="mas-role menu-icon"></i>
                    <span class="menu-title">Role</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="role">
                    <ul class="nav flex-column sub-menu">
                    <?php if (in_array('18', \Session::get('menuList')) ) {?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('role.create') }}">Add New Role</a>
                        </li> <?php }  if (in_array('19', \Session::get('menuList')) ) {?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('role.index') }}">List of Roles</a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </li> <?php } ?>
            <?php if (in_array('21', \Session::get('menuList')) || in_array('22', \Session::get('menuList'))) { ?>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#users" aria-expanded="false" aria-controls="users">
                    <i class="mas-users menu-icon"></i>
                    <span class="menu-title">Users</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="users">
                    <ul class="nav flex-column sub-menu">
                    <?php if (in_array('21', \Session::get('menuList'))){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.create') }}">Add New User</a>
                        </li> <?php } if (in_array('22', \Session::get('menuList'))){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.index') }}">List of Users</a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </li> <?php } ?>
            <?php if (in_array('39', \Session::get('menuList')) ) { ?>
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('companyclient.create') }}">
                    <i class="mas-company menu-icon"></i>
                    <span class="menu-title">Distributor & Cus-Company Profile</span>
                </a>
            </li>
            <?php } ?>
            <?php if (in_array('25', \Session::get('menuList')) || in_array('26', \Session::get('menuList')) ) { ?>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#distributor" aria-expanded="false" aria-controls="distributor">
                    <i class="mas-distributor menu-icon"></i>
                    <span class="menu-title">Distributors</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="distributor">
                    <ul class="nav flex-column sub-menu">
                    <?php if (in_array('25', \Session::get('menuList')) ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('distributor.create',0) }}">Add New Distributor</a>
                        </li> <?php  } if (in_array('26', \Session::get('menuList')) ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('distributor.index') }}">List of Distributors</a>
                        </li> <?php } ?>
                    </ul>
                </div>
            </li> <?php } ?>
            <?php if (in_array('28', \Session::get('menuList')) || in_array('29', \Session::get('menuList')) ) { ?>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#company" aria-expanded="false" aria-controls="company">
                    <i class="mas-company-1 menu-icon"></i>
                    <span class="menu-title">Customer Company</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="company">
                    <ul class="nav flex-column sub-menu">
                    <?php if (in_array('28', \Session::get('menuList')) ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('companyclient.basicinfocreate',0) }}">Add New Company</a>
                        </li>  <?php } if (in_array('29', \Session::get('menuList')) ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('companyclient.index') }}">List of Companies</a>
                        </li><?php } ?>
                    </ul>
                </div>
            </li><?php } ?>
            <?php if (in_array('31', \Session::get('menuList')) || in_array('32', \Session::get('menuList')) ) { ?>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#faq" aria-expanded="false" aria-controls="faq">
                    <i class="mas-faq menu-icon"></i>
                    <span class="menu-title">Faq's</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="faq">
                    <ul class="nav flex-column sub-menu">
                    <?php if (in_array('31', \Session::get('menuList')) ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('faq.create') }}">Add New Faq</a>
                        </li> <?php } if (in_array('32', \Session::get('menuList')) ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('faq.index') }}">List of Faq's</a>
                        </li><?php } ?>
                    </ul>
                </div>
            </li><?php } ?>
            <?php if (in_array('34', \Session::get('menuList')) || in_array('35', \Session::get('menuList')) ) { ?>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ref" aria-expanded="false" aria-controls="ref">
                    <i class="mas-reference menu-icon"></i>
                    <span class="menu-title">Reference Docs</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ref">
                    <ul class="nav flex-column sub-menu">
                    <?php if (in_array('34', \Session::get('menuList')) ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reference.create') }}">Add New Reference</a>
                        </li>  <?php } if (in_array('35', \Session::get('menuList')) ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reference.index') }}">List of Reference</a>
                        </li><?php } ?>
                    </ul>
                </div>
            </li><?php } ?>
            <?php if (in_array('37', \Session::get('menuList')) ) { ?>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="reports">
                    <i class="mas-reports menu-icon"></i>
                    <span class="menu-title">Reports</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="reports">
                    <ul class="nav flex-column sub-menu">
                    <?php if (in_array('37', \Session::get('menuList')) ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('callList.index') }}">Call List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('systemsummary.index') }}">System Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('citysummary.index') }}">City Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('companysummary.index') }}">Company Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customersummary.index') }}">Customer Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('topanalytic.index') }}">Top Analytic Report</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('downloadCall.index') }}">Download Call Report</a>
                        </li>
                        <?php } ?>
                        <!-- <li class="nav-item">
                    <a class="nav-link" href="reference.html">List of Reference</a>
                  </li>                                  -->
                    </ul>
                </div>
            </li><?php } ?>
            <?php if (in_array('40', \Session::get('menuList')) ) { ?>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('setting.index') }}">
                    <i class="mas-settings menu-icon"></i>
                    <span class="menu-title">Settings</span>
                </a>
            </li> <?php } ?>
        </ul>
    </nav>

