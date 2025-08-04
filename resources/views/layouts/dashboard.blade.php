<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Pengajuan Cuti Netafarm</title>
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('logo.png') }}">

    <!-- Scripts -->
    @include('partials._style')
    @stack('style')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('partials._navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('partials._sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('partials._footer')
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    @include('partials._script')
    @stack('script')
</body>

</html>
