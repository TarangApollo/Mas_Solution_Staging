@extends('layouts.admin')

@section('title', 'Company Info')

@section('content')

    <div class="content-wrapper pb-0">
        <div class="page-header">
            <h3>Company Information</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Company</li>
                    <li class="breadcrumb-item"><a href="{{ route('company.index') }}">Company List</a></li>
                    <li class="breadcrumb-item active">Information </li>
                </ol>
            </nav>
        </div>
        <!--/. page header ends-->
        <!-- first row starts here -->
        @include('admin.common.alert')
        <div class="row d-flex justify-content-center mb-5">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title mt-0">Company Name: {{ $Company->strOEMCompanyName }}</h4>
                        <div class="row">
                            <div class="col-md-6">
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
                            <!--/. col 6-->
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped" data-role="content" data-plugin="selectable"
                                        data-row-selectable="true">
                                        <thead class="bg-grey-100">
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Date of Updates</th>
                                                <th>Updated by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!$infoTables->isEmpty())
                                                <?php $jCounter = 1; ?>
                                                @foreach ($infoTables as $infoTable)
                                                    <tr>
                                                        <td>{{ $jCounter }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($infoTable->strEntryDate)) }} <br>
                                                            <small
                                                                class="position-static">{{ date('H:i:s', strtotime($infoTable->strEntryDate)) }}</small>
                                                        </td>
                                                        <td>{{ $infoTable->actionBy }} </td>
                                                    </tr>
                                                    <?php $jCounter++; ?>
                                                @endforeach
                                            @endif
                                            <!-- <tr>
                                                            <td>2</td>
                                                            <td>22-05-2022 <br>
                                                                <small class="position-static">23:18:00</small>
                                                            </td>
                                                            <td>Admin</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>23-05-2022 <br>
                                                                <small class="position-static">10:18:05</small>
                                                            </td>
                                                            <td>Admin</td>
                                                        </tr> -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--/. col 6-->
                        </div>
                    </div>
                </div>
                <!--card end-->
            </div>
        </div><!-- end row -->
    </div>

@endsection

@section('scripts')

@endsection
