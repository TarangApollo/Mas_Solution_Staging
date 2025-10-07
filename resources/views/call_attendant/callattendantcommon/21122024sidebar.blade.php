<nav class="bottom-navbar">
    <div class="container">
        <ul class="nav page-navigation">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('callattendantAdmin.home') }}">
                    <i class="mas-home menu-icon"></i>
                    <span class="menu-title">Home</span>
                </a>
              </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('callattendantAdmin.dashboard') }}" >
                    <i class="mas-add-complaints menu-icon"></i>
                    <span class="menu-title">Add Complaint</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('complaint.index') }}">
                    <i class="mas-list menu-icon"></i>
                    <span class="menu-title">Complaint List</span>
                </a>
            </li>
            @if(Session::get('exeLevel') == 2)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('my-tickets.index') }}">
                    <i class="mas-tags menu-icon"></i>
                    <span class="menu-title">
                        My Tickets</span>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a class="nav-link" href="{{ route('callattendantfaq.index') }}">
                    <i class="mas-faq menu-icon"></i>
                    <span class="menu-title">Faq</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('callattendantreference.index') }}">
                    <i class="mas-reference menu-icon"></i>
                    <span class="menu-title">Reference Documents</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('callattendantprofile.index') }}">
                    <i class="mas-user menu-icon"></i>
                    <span class="menu-title">Profile</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
