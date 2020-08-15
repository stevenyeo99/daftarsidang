<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
</head>
<body>
    <div class="wrapper">
        @if($role == 'prodi')
            Dear Admin Prodi {{ $studyProgram }},
            <br>
            <br>
            Mahasiswa dengan bernama {{ $student->name }} dan ber-NPM {{ $student->npm }} telah melakukan Pendaftaran Sidang <b><i> {{ $creation_type }} </i></b> dengan berjudul "<b>{{ $customRequest->title }}</b>", Diharapkan untuk melakukan tindakan selanjutnya.
            <br>
            <br>
            Regards,
            <br>
            Admin Sidang BAAK
            <br>
            Universitas Internasional Batam
        @endif
    </div>
</body>
</html>