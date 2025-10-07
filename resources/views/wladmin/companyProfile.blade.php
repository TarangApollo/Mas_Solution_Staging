@extends('layouts.wladmin')

@section('title', 'User Profile')

@section('content')
    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Company Profile</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active"> Company Profile </li>
                </ol>
            </nav>
        </div>

        {{-- Alert Messages --}}
        @include('wladmin.wlcommon.alert')

        <!--/. page header ends-->
        <!-- first row starts here -->
        <div class="row">
            <div class="col-xl-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">Company Name: {{ $Company->strOEMCompanyName }}</h4>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped" data-role="content" data-plugin="selectable"
                                        data-row-selectable="true">
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Label</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>company Id</td>
                                                <td>{{ $Company->strOEMCompanyId }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Contact Person</td>
                                                <td>{{ $Company->ContactPerson }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Mail ID</td>
                                                <td>{{ $Company->EmailId }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Contact No</td>
                                                <td>{{ $Company->ContactNo }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Address 1</td>
                                                <td>{{ $Company->Address1 }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Address 2</td>
                                                <td>{{ $Company->Address2 }}</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Address 3</td>
                                                <td>{{ $Company->Address3 }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Postcode</td>
                                                <td>{{ $Company->Pincode }}</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>City</td>
                                                <td>{{ $Company->strCityName }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>State</td>
                                                <td>{{ $Company->strStateName }}</td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>GST</td>
                                                <td>{{ $Company->strGSTNo }}</td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>System</td>
                                                <td>
                                                    @foreach ($systems as $system)
                                                        <span class="badge badge-secondary">
                                                            {{ $system->strSystem }} </span>
                                                    @endforeach
                                                    <!-- <span class="badge badge-success">Refrigerator</span>
                                                                <span class="badge badge-secondary">Tablet</span> -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Components</td>
                                                <td>
                                                    @foreach ($components as $component)
                                                        <span class="badge badge-success">
                                                            {{ $component->strComponent }} </span><br>
                                                        @foreach ($component['subcomponent'] as $subcompo)
                                                            <span class="badge badge-secondary">
                                                                {{ $subcompo->strSubComponent }} </span>
                                                        @endforeach
                                                        <br>
                                                    @endforeach
                                                    <!-- <span class="badge badge-success">Refrigerator</span>
                                                                <span class="badge badge-secondary">Tablet</span> -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>Solution Type</td>
                                                <td>
                                                    @foreach ($resolutionCategories as $resCat)
                                                        <span class="badge badge-secondary">
                                                            {{ $resCat->strResolutionCategory }} </span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td>Issue Type</td>
                                                <td>
                                                    @foreach ($issuetypes as $issuetype)
                                                        <span class="badge badge-secondary">
                                                            {{ $issuetype->strIssueType }} </span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>16</td>
                                                <td>Call Competency</td>
                                                <td>
                                                    @foreach ($callcompetencies as $callcomp)
                                                        <span class="badge badge-secondary">
                                                            {{ $callcomp->strCallCompetency }} </span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>17</td>
                                                <td>Support Type</td>
                                                <td>
                                                    @foreach ($supporttypes as $supporttype)
                                                        <span class="badge badge-secondary">
                                                            {{ $supporttype->strSupportType }} </span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                                                                        <tr>
                                                <td>18</td>
                                                <td>Module</td>
                                                <td>
                                                    @foreach ($moduleList as $module)
                                                        <span class="badge badge-secondary">
                                                            {{ $module->module_name }} </span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--/. col 8-->
                        </div>

                    </div>
                    <!--card body end-->
                </div>
                <!--card end-->
            </div>
        </div>
        <!--row-->
    </div>
@endsection
