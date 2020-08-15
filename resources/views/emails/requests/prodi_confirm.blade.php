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
        Dear Admin BAAK,
        <br>
        <br>
        Pendaftaran sidang <b><i> {{ $creation_type }} </i></b> dari Mahasiswa {{ $student->npm }} / {{ $student->name }} Telah divalidasi oleh pihak Prodi, dan akan segera melakukan proses penjadwalan sidang. 
        <br>
        <br>
        
        Regards, 
        <br>
        Admin Prodi {{ $prodi }}
        <br>
        Universitas Internasional Batam
    </div>
</body>
</html>