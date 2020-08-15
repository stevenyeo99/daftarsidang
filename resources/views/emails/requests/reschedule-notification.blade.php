<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UIB | Pendaftaran sidang KP dan Skripsi dan Tesis online</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <script>
        window.laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div class="wrapper">
        Dear Admin Prodi {{ $prodi }},
        <br>
        <br>
        Hari ini adalah hari H-3 untuk melakukan penjadwalan ulang sidang {{ $type }}, jika tidak terdapat penjadwalan ulang harap hiraukan saja, jika ada mohon cek di list penjadwalan yang butuh dijadwalkan.
        <br>
        <br>

        Regards,
        <br>
        Admin Sidang Baak,
        <br>
        Universitas Internasional Batam
    </div>
</body>
</html>