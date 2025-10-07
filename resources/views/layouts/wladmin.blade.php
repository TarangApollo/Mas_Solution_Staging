<!DOCTYPE html>
<html lang="en">


@include('wladmin.wlcommon.head')

<body>

    <div class="container-scroller">

        <!-- Siderbar -->

        @include('wladmin.wlcommon.sidebar')
        <div class="container-fluid page-body-wrapper">
            <!-- partial hearder -->
            @include('wladmin.wlcommon.header')
            <div class="main-panel">
                @yield('content')
                @include('wladmin.wlcommon.footer')
            </div>
        </div>
        @include('wladmin.wlcommon.loader')
    </div>
    <!-- Footer -->
    @include('wladmin.wlcommon.footerjs')

    @yield('script')
</body>

</html>
