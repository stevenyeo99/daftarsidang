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
        @if($userType == 0)
            Dear {{ $student->name }},
            <br>
            <br>
            Diingatkan bahwa hari ini akan melakukan sidang {{ $creation_type }} dengan berjudul <b>"{{ $customRequest->title }}"</b>            
        @elseif($userType == 2)
            Dear Admin Prodi {{ $prodiUser->studyprogram()->first()->name }},
            <br>
            <br>
            Diingatkan bahwa hari ini Mahasiswa bernama {{ $student->name }} dan berNPM {{ $student->npm }} akan melakukan sidang {{ $creation_type }} dengan berjudul <b>"{{ $customRequest->title }}"</b>
            <br>
            yang akan disidang oleh:
            <br>
            <b>Ketua Penguji</b>: {{ $beritaAcaraReport->ketua_penguji()->first()->username }}
            <br>
            <b>Penguji</b>: {{ $beritaAcaraReport->penguji()->first()->username }}            
        @elseif($userType == 3)
            Dear {{ $prodiUser->initial_name }},
            <br>
            <br>
            Diingatkan bahwa hari ini beliau akan melakukan sidang {{ $creation_type }} kepada mahasiswa {{ $student->name }}/{{ $student->npm }} dengan berjudul <b>"{{ $customRequest->title }}"</b>
        @elseif($userType == 4)
            Dear {{ $prodiUser->initial_name }},
            <br>
            <br>
            Diingatkan bahwa hari ini beliau akan melakukan sidang {{ $creation_type }} kepada mahasiswa {{ $student->name }}/{{ $student->npm }} dengan berjudul <b>"{{ $customRequest->title }}"</b>
        @endif
        <br>
        <b>Jam</b>: {{ $penjadwalanSidang->tanggal_sidang }}
        <br>
        <b>Ruangan<b>: Gedung {{ $ruanganSidang->gedung }} - {{ $ruanganSidang->ruangan }}
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