
<div id="role_information" class="panel-collapse collapse in">
    <div class="panel-body">
        <ul class="accordion-tab nav nav-tabs">
            <li class="@if (request()->routeIs('topanalytic.index')) {{ 'active' }} @endif">
                <a href="{{ route('topanalytic.index') }}">Top Cities</a>
            </li>
            <li class="@if (request()->routeIs('topanalytic.topCompanies')) {{ 'active' }} @endif">
                <a href="{{ route('topanalytic.topCompanies') }}">Top Companies</a>
            </li>

            <li class="@if (request()->routeIs('topanalytic.topSystems')) {{ 'active' }} @endif">
                <a href="{{ route('topanalytic.topSystems') }}">Top Systems</a>
            </li>
            <li class="@if (request()->routeIs('topanalytic.topCustomers')) {{ 'active' }} @endif">
                <a href=" {{ route('topanalytic.topCustomers') }}">Top Customers</a>
            </li>
            <!-- <li class="@if (request()->routeIs('topanalytic.index')) {{ 'active' }} @endif">
                <a href="{{ route('topanalytic.index') }}">Support
                    Type</a>
            </li> -->
        </ul>
    </div>
</div>

