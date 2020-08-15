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
            @if($role == 'student')
                Dear {{ $student->name }},
    			<br>
    			<br>
    			Pendaftaran sidang <b><i> {{ $creation_type }} </i></b> anda dengan judul "<b>{{ $customRequest->title }}</b>" telah tervalidasi gagal oleh pihak Finance dikarenakan alasan berikut :
                <br>
                <br>
                " {{ $customRequest->reject_reason != null ? $customRequest->reject_reason : 'ALASAN KOSONG...' }} "
    			<br>
    			<br>

                Regards,
                <br>
                Admin Sidang BAAK
                <br>
                Universitas Internasional Batam
            @else
                Dear Admin BAAK,
                <br>
                <br>
                Status pendaftaran sidang <b><i> {{ $creation_type }} </i></b> dari Mahasiswa {{ $student->npm }} / {{$student->name }} telah tervalidasi gagal oleh pihak Finance, dikarenakan alasan berikut :
                <br>
                <br>
                " {{ $customRequest->reject_reason != null ? $customRequest->reject_reason : 'ALASAN KOSONG...' }} "
                <br>
                <br>

                Warm Regard,
                <br>
                Biro Administrasi Keuangan
                <br>
                Universitas Internasional Batam
                <br>
                Phone : 0778-743-7111 | EXT : 121
            @endif
        </div>
    </body>
</html>