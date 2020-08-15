<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>UIB | Pendaftaran sidang KP dan Skripsi dan Tesis online</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Scripts -->
        <link rel="shortcut icon" href="../../../../../../assets/img/Icon.png" type="image/png">
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
        
        <!-- CSS -->
        @include('layouts.css')
        
        @stack('custom_css')

        <!-- <link rel="shortcut icon" href="favicon.ico" />  -->
    </head>
    <!-- END HEAD -->

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            @include('layouts.header')

            <!-- Left side column. contains the logo and sidebar -->
            @include('layouts.sidebar')

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- /.content-wrapper -->

            @include('layouts.footer')
            <!-- END FOOTER -->
        </div>

        <!-- JavaScript-->
        @include('layouts.js')
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        
        @stack('custom_js')
    </body>

</html>