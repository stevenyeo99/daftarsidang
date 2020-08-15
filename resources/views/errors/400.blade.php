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
        <link rel="shortcut icon" href="/assets/img/propeller-icon.png" type="image/png">
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
        <!-- CSS -->
        @include('layouts.css')
    </head>
    <!-- END HEAD -->
    <body>
      <div class="error-page">
        <h2 class="headline text-red"> 400</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-red"></i> Oops! Bad Request.</h3>

          <p>
            You are entering bad parameters for this request.
            Meanwhile, you may <a href="javascript:window.location.reload();">reload the page</a> or <a href="/">return to dashboard</a>.
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </body>
</html>