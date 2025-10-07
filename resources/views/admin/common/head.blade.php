{{-- <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Mas Solutions</title>
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('global/assets/css/style-admin.css') }}" />
<link rel="stylesheet" href="{{ asset('global/assets/css/bootstrap.css') }}" />
<link rel="stylesheet" href="{{ asset('global/assets/fonts/mas-solution/styles.css') }}" />
<link rel="stylesheet" href="{{ asset('global/assets/vendors/css/vendor.bundle.base.css') }}">
<!-- plugins:css -->

<!-- End layout styles -->
<link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />
</head> --}}


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    {{-- <title>Mas Solutions</title> --}}
    <title>{{ config('app.name', 'Mas-Solutions') }} | @yield('title')</title>
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('global/assets/css/style-admin.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/fonts/mas-solution/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/css/vendor.bundle.base.css') }}">



    <link rel="shortcut icon" href="{{ asset('global/assets/images/favicon.png') }}" />


    <!-- css for this page -->
    <!--select 2 form-->
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('global/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <!--bootstrap table-->
    <link href="{{ asset('global/assets/vendors/bootstrap-table/css/fresh-bootstrap-table.css') }}" rel="stylesheet" />
    <!--date range picker-->
    <link rel="stylesheet" type="text/css" href="{{ asset('global/assets/vendors/date-picker/daterangepicker.css') }}" />
    <!--toggle button active/inactive-->
    <link href="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.css') }}" rel="stylesheet">

</head>
