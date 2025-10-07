<!DOCTYPE html>
<html lang="en">


@include('call_attendant.callattendantcommon.head')

<body>
    <div class="container-scroller">
        <div class="horizontal-menu">
            @include('call_attendant.callattendantcommon.header')

            {{--  --}}
            <!-- partial hearder -->

            @include('call_attendant.callattendantcommon.sidebar')
        </div>
        <div class="container-fluid page-body-wrapper">
            @include('call_attendant.callattendantcommon.loader')
            <!-- Begin Page Content -->
            @yield('content')
            <!-- /.container-fluid -->

            {{-- @include('common.footer') --}}
            {{-- </div> --}}
            
        </div>

        <!-- Footer -->
        @include('call_attendant.callattendantcommon.footerjs')
    </div>


</body>

</html>
