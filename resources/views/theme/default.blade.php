<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE Dashboard</title>

    <!-- Font -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">
</head>

<body class="layout-fixed sidebar-expand-lg">

<!-- Page Wrapper -->
<div class="app-wrapper">

    <!-- Sidebar -->
    @include('theme.sidebar')
    <!-- End Sidebar -->

    <!-- Content Wrapper -->
    <div class="app-main d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            @include('theme.header')
            <!-- End Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid mt-3">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0">Dashboard</h1>

                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="bi bi-download"></i> Generate Report
                    </a>
                </div>

                <!-- Content Row -->
                @yield('content')

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End Main Content -->

        <!-- Footer -->
        <footer class="app-footer text-center">
            <strong>Copyright &copy; 2026</strong>
        </footer>
        <!-- End Footer -->

    </div>
    <!-- End Content Wrapper -->

</div>
<!-- End Page Wrapper -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE JS -->
<script src="{{ asset('js/adminlte.js') }}"></script>

</body>
</html>
