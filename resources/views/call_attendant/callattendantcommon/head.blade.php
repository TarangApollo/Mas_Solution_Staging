<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    {{-- <title>Mas Solutions</title> --}}
    <title>{{ 'Mas Solutions' }} | @yield('title')</title>

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('global/assets/css/style.css') }}" />
    <?php $string = str_replace(' ', '_', Session::get('CompanyName') . '_fd');
    $fileName = preg_replace('/[^A-Za-z0-9\-]/', '', $string) . '.css'; ?>
    <link rel="stylesheet" href="{{ asset('global/assets/css/' . $fileName) }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/fonts/mas-solution/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/css/vendor.bundle.base.css') }}">

    <!-- css for this page -->
    <!--step wizard-->
    <link href="{{ asset('global/assets/vendors/wizard/css/material-bootstrap-wizard.css') }}" rel="stylesheet" />
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <!--bootstrap table-->
    <link href="{{ asset('global/assets/vendors/bootstrap-table/css/fresh-bootstrap-table.css') }}" rel="stylesheet" />


    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />

</head>
