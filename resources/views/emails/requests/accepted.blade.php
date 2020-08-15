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
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>

        <!-- <link rel="shortcut icon" href="favicon.ico" />  -->
    </head>
    <!-- END HEAD -->

    <body>
        <div class="wrapper">
            @if ($role == 'student')
                Dear {{ $student->name }},
                <br>
                <br>
                Pendaftaran sidang <b><i> {{ $creation_type }} </i></b> anda dengan judul "<b>{{ $customRequest->title }}</b>" telah diterima oleh Admin Sidang BAAK dan akan diproses.
                <br>
                <br>

                Regards,
                <br>
                Admin Sidang BAAK
                <br>
                Universitas Internasional Batam
            @elseif($role == 'finance_validation_success')
                Dear Admin BAAK,
                <br>
                <br>
                Pendaftaran Sidang <b><i> {{ $creation_type }} </i></b> dari Mahasiswa {{ $student->npm }} / {{$student->name }} telah tervalidasi hasil tagihan keuangan dan dapat untuk melanjutkan ke proses selanjutnya.
                <br>
                Terima kasih.
            @else
                Dear Admin BAAK,
                <br>
                <br>
                Status pendaftaran sidang <b><i> {{ $creation_type }} </i></b> dari Mahasiswa {{ $student->npm }} / {{$student->name }} telah diterima oleh Admin Sidang BAAK dan menunggu proses selanjutnya oleh pihak Prodi.
                <br>
                Terima kasih.
            @endif
        </div>
    </body>

</html>
{{-- @endsection --}}
