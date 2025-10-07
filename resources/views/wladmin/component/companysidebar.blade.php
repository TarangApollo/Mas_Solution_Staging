<?php
$companyId= request()->id;
//$2y$10$qBez5fUsSJ9mVoAkdAsgu.vVJkf8GjCyHLh0N1w0V5Dve8H08q1hy
?>
<div id="role_information" class="panel-collapse collapse in">
    <div class="panel-body">
        <ul class="accordion-tab nav nav-tabs">
            <li class="@if (request()->routeIs('component.general')) {{ 'active' }} @endif" id="generalLi">
                <a onClick="generalLia();" href="{{ route('component.general') }}#general" @if (request()->routeIs('component.create')) {{ 'data-toggle="tab" '}} @endif>General</a>
            </li>
            <li class="@if (request()->routeIs('component.general')) {{ 'active' }} @endif" id="permissionsLi">
                <a id="permissionsLia" href="#permissions"  @if (request()->routeIs('component.create')) {{ 'data-toggle="tab" aria-expanded="true"' }} @endif>Advance
                    Information</a>
            </li>
            <li class="@if (request()->routeIs('component.create')) {{ 'active' }} @endif">
                <a href="{{ route('component.create') }}">Add
                    Component / Sub
                    Component</a>
            </li>
            <li class="@if (request()->routeIs('component.resolution-category')) {{ 'active' }} @endif">
                <a href="{{ route('component.resolution-category') }}">Resolution
                    Category</a>
            </li>

            <li class="@if (request()->routeIs('component.issue')) {{ 'active' }} @endif">
                <a href="{{ route('component.issue') }}">Issue Type</a>
            </li>
            <li class="@if (request()->routeIs('component.CallCompetency')) {{ 'active' }} @endif">
                <a href=" {{ route('component.CallCompetency') }}">Call
                    Competency</a>
            </li>
            <li class="@if (request()->routeIs('component.Support-Type')) {{ 'active' }} @endif">
                <a href="{{ route('component.Support-Type') }}">Support
                    Type</a>
            </li>
        </ul>
    </div>
</div>
<script>
    function generalLia(){
        window.location.href = "<?= route('component.create', $companyId) ?>";
        // $("#generalLia").attr("aria-expanded","true");
        // $("#general").css("display", "block");
        // $("#general").attr("class", 'active');
        // $("#generalLi").attr("class", 'active');
    }
</script>
