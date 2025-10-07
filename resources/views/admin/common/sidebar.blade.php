
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item pt-3 mb-5">
                <a class="nav-link d-block" href="{{ route('home') }}">
                    <img class="sidebar-brand-logo" src="{{ asset('global/assets/images/logo.png') }}" height="55" alt="" />
                    <img class="sidebar-brand-logomini" src="{{ asset('global/assets/images/logo-mini.png') }}" alt="" />
                </a>
            </li>
            <li class="nav-item @if(Request::routeIs('home')) {{ 'active' }} @endif">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="mas-dashboard menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item @if(Request::routeIs('company.create') || Request::routeIs('company.index')) {{ 'active' }} @endif">
                <a class="nav-link" data-bs-toggle="collapse" href="#company" aria-expanded="false" aria-controls="Company">
                    <i class="mas-company menu-icon"></i>
                    <span class="menu-title">Company</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="company">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item @if(Request::routeIs('company.create')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('company.create', 0) }}">Add New Company</a>
                        </li>
                        <li class="nav-item @if(Request::routeIs('company.index')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('company.index') }}">Companies</a>
                        </li>
                        <li class="nav-item @if(Request::routeIs('company.componentslist')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('company.componentslist') }}">Components</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item @if(Request::routeIs('call_attendant.create') || Request::routeIs('call_attendant.index')) {{ 'active' }} @endif">
                <a class="nav-link" data-bs-toggle="collapse" href="#users" aria-expanded="false" aria-controls="users">
                    <i class="mas-users menu-icon"></i>
                    <span class="menu-title">Users</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="users">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item @if(Request::routeIs('call_attendant.create')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('call_attendant.create') }}">Add New Call Attendant</a>
                        </li>
                        <li class="nav-item @if(Request::routeIs('call_attendant.index')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('call_attendant.index') }}">Call Attendants</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item  @if(Request::routeIs('call_report.index') || Request::routeIs('attendance.index') || Request::routeIs('customer_list.index') || Request::routeIs('customer_company_list.index') || Request::routeIs('distributor_list.index')) {{ 'active' }} @endif">
                <a class="nav-link" data-bs-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="reports">
                    <i class="mas-reports menu-icon"></i>
                    <span class="menu-title">Reports</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="reports">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item  @if(Request::routeIs('call_report.index')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('call_report.index') }}">Call List</a>
                        </li>

                        <li class="nav-item  @if(Request::routeIs('customer_list.index')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('customer_list.index') }}">Customer List</a>
                        </li>
                        <li class="nav-item  @if(Request::routeIs('customer_company_list.index')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('customer_company_list.index') }}">Customer Company List</a>
                        </li>
                        <li class="nav-item  @if(Request::routeIs('distributor_list.index')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('distributor_list.index') }}">Distributor List</a>
                        </li>
                        <li class="nav-item  @if(Request::routeIs('attendance.index')) {{ 'active' }} @endif">
                            <a class="nav-link" href="{{ route('attendance.index') }}">Attendance</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item @if(Request::routeIs('admincity.cityDetail')) {{ 'active' }} @endif">
                <a class="nav-link" href="{{ route('admincity.cityDetail') }}">
                    <i class="mas-map-marker menu-icon"></i>
                    <span class="menu-title">City</span>
                </a>
            </li>
            <li class="nav-item @if(Request::routeIs('companyinfo.index')) {{ 'active' }} @endif">
                <a class="nav-link" href="{{ route('companyinfo.index') }}">
                    <i class="mas-settings menu-icon"></i>
                    <span class="menu-title">Settings</span>
                </a>
            </li>
        </ul>
    </nav>

