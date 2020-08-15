<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>UIB | Pendaftaran sidang KP dan Skripsi dan Tesis online</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <meta content="" name="description">
        <meta content="" name="author">
        <!-- CSRF TOKEN -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            window.laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body>
        <div class="wrapper">
            Dear {{ $student->name }},
            <br>
            <br>
            @if($isLulus == 'YES')
            <b>Selamat anda telah dinyatakan lulus dalam melakukan sidang {{ $type }}!!!</b>    
            <br>
            Diharapkan untuk melakukan persiapan untuk menyerahkan berkas berkas kepada pihak perpus dan prodi.
            @else
            <b>Mohon maaf anda telah gagal dalam melakukan sidang {{ $type }}</b>
            <br>
            Silahkan melakukan pendaftaran ulang untuk melakukan sidang ulang.
            @endif
            <br>
            <br>

            Regards,
            <br>
            Admin Sidang BAAK
            <br>
            Universitas Internasional Batam
        </div>
    </body>
</html>