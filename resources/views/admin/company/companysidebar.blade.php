<?php
$companyId= request()->id;
//$2y$10$qBez5fUsSJ9mVoAkdAsgu.vVJkf8GjCyHLh0N1w0V5Dve8H08q1hy
?>
<div id="role_information" class="panel-collapse collapse in">
    <div class="panel-body">
        <ul class="accordion-tab nav nav-tabs">
            <li class="@if (request()->routeIs('company.create')) {{ 'active' }} @endif" id="generalLi">
                <a onClick="generalLia();" href="{{ route('company.create', $companyId) }}#general" @if (request()->routeIs('company.create')) {{ 'data-toggle="tab" '}} @endif>General</a>
                <!-- #general -->
            </li>
            <li class="@if (request()->routeIs('company.create')) {{ 'active' }} @endif" id="permissionsLi">
                <a id="permissionsLia" href="{{ route('company.create', $companyId) }}#permissions"  @if (request()->routeIs('company.create')) {{ 'data-toggle="tab" aria-expanded="true"' }} @endif>Advance
                    Information</a>
                    <!-- #permissions -->
            </li>
            <li class="@if (request()->routeIs('company.componetcreate')) {{ 'active' }} @endif">
                <a href="@if($companyId == 0) {{ '#' }} @else {{ route('company.componetcreate', $companyId) }} @endif">Add
                    Component / Sub
                    Component</a>
            </li>
            <li class="@if (request()->routeIs('company.res-categorycreate')) {{ 'active' }} @endif">
                <a href="@if($companyId == 0) {{ '#' }} @else {{ route('company.res-categorycreate', $companyId) }}  @endif">Resolution
                    Category</a>
            </li>


            <li class="@if (request()->routeIs('company.issue-type')) {{ 'active' }} @endif">
                <a href="@if($companyId == 0) {{ '#' }} @else {{ route('company.issue-type', $companyId) }}  @endif">Issue Type</a>
            </li>
            <li class="@if (request()->routeIs('company.call-competency')) {{ 'active' }} @endif">
                <a href="@if($companyId == 0) {{ '#' }} @else  {{ route('company.call-competency', $companyId) }}  @endif">Call
                    Competency</a>
            </li>
            <li class="@if (request()->routeIs('company.support-type')) {{ 'active' }} @endif">
                <a href="@if($companyId == 0) {{ '#' }} @else {{ route('company.support-type', $companyId) }}  @endif">Support
                    Type</a>
            </li>
            <li class="@if (request()->routeIs('company.allow-module')) {{ 'active' }} @endif">
                <a href="@if($companyId == 0) {{ '#' }} @else {{ route('company.allow-module', $companyId) }}  @endif">Module Permission</a>
            </li>
        </ul>
    </div>
</div>
<script>
    function generalLia(){
        window.location.href = "<?= route('company.create', $companyId) ?>";
        // $("#generalLia").attr("aria-expanded","true");
        // $("#general").css("display", "block");
        // $("#general").attr("class", 'active');
        // $("#generalLi").attr("class", 'active');
    }
</script>
