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
        <!-- mahasiswa batal sidang -->
        @if($template == 'student')
        Dear {{ $student->name }},
        <br>
        <br>
        Sidang {{ $type }} anda dengan berjudul <b><i>{{ $customRequest->title }}</i></b> telah dibatalkan untuk tidak melakukan sidang.
        <br>
        Berikut merupakan alasan dari pembatalan sidang :
        <br>
        " {{ $customRequest->reject_reason }} "
        <br>
        <br>

        Regards,
        <br>
        Admin Sidang BAAK
        <br>
        Universitas Internasional Batam
        @elseif($template == 'admin_baak')
        Dear Admin BAAK,
        <br>
        <br>
        Sidang {{ $type }} dengan berjudul <b><i>{{ $customRequest->title }}</i></b> dari mahasiswa {{ $student->npm }} / {{ $student->name }} telah dilakukan pembatalan sidang.
        <br>
        Berikut merupakan alasan dari pembatalan sidang :
        <br>
        " {{ $customRequest->reject_reason }} "
        <br>
        <br>

        Regards,
        <br>
        Admin Sidang BAAK
        <br>
        Universitas Internasional Batam
        @elseif($template == 'admin_prodi')
        Dear admin prodi {{ $prodi }},
        <br>
        <br>
        Sidang {{ $type }} dengan berjudul <b><i>{{ $customRequest->title }}</i></b> dari mahasiswa {{ $student->npm }} / {{ $student->name }} telah dibatalkan oleh pihak prodi.
        <br>
        Berikut merupakan alasan dari pembatalan sidang :
        <br>
        " {{ $customRequest->reject_reason }} "
        <br>
        <br>

        Regards,
        <br>
        Admin Sidang BAAK
        <br>
        Universitas Internasional Batam
        @elseif($template == 'old_dospem')
        Dear {{ $jadwal->dospemOld->initial_name }},
        <br>
        <br>
        Anda akan digantikan oleh {{ $jadwal->dospemBAK->initial_name }} dalam melakukan sidang pada mahasiswa bimbingan anda dengan bernama {{ $student->name }} ber-NPM {{ $student->npm }},
        yang telah dijadwalkan sebelumnya pada {{ $jadwal->history->tanggal_waktu_sidang }}
        <br>
        <br>

        Regards,
        <br>
        Admin Sidang BAAK
        <br>
        Universitas Internasional Batam
        @elseif($template == 'dospem')
        Dear {{ $jadwal->dospem->initial_name }},
        <br>
        <br>
        Penjadwalan sidang {{ $type }} dengan Mahasiswa {{ $student->name }} / ber-NPM {{ $student->npm }} yang berjudul {{ $customRequest->title }}, yang sebelumnya telah dijadwalkan pada:
        <br>
        Tanggal/waktu: {{ $jadwal->tanggal_sidang }}
        <br>
        Telah dilakukan pembatalan, berikut adalah alasan dari pembatalannya sidang :
        <br>
        " {{ $customRequest->reject_reason }} "
        <br>
        <br>

        Regards,
        <br>
        Admin Prodi {{ $prodi }}
        <br>
        Universitas Internasional Batam
        @elseif($template == 'old_dospembak')
        Dear {{ $jadwal->dospemBAKOld->initial_name }},
        <br>
        <br>
        Anda telah digantikan oleh dosen lain dalam melakukan sidang pada mahasiswa yang bernama {{ $student->name }} ber-NPM {{ $student->npm }},
        yang sebelumnya dijadwalkan pada {{ $jadwal->history->tanggal_waktu_sidang }} untuk menggantikan {{ $jadwal->dospem->username }} sebagai dosen pembimbing mahasiswanya saat sidang.
        <br>
        <br>

        Regards,
        <br>
        Admin Sidang BAAK
        <br>
        Universitas Internasional Batam
        @elseif($template == 'dospemBAK')
        Dear {{ $jadwal->dospemBAK->initial_name }},
        <br>
        <br>
        Penjadwalan sidang {{ $type }} dengan Mahasiswa {{ $student->name }} / ber-NPM {{ $student->npm }} yang berjudul <b><i>{{ $customRequest->title }}</i></b>, yang sebelumnya telah dijadwalkan pada:
        <br>
        Tanggal/waktu: {{ $jadwal->tanggal_sidang }}
        <br>
        Telah dilakukan pembatalan, berikut adalah alasan dari pembatalannya sidang :
        <br>
        " {{ $customRequest->reject_reason }} "
        <br>
        <br>

        Regards,
        <br>
        Admin Prodi {{ $prodi }}
        <br>
        Universitas Internasional Batam
        @elseif($template == 'old_dospenguji')
        Dear {{ $jadwal->dospengujiOld->initial_name }},
        <br>
        <br>
        Anda akan digantikan oleh {{ $jadwal->dospenguji->initial_name }} dalam melakukan sidang pada mahasiswa yang bernama {{ $student->name }} ber-NPM {{ $student->npm }},
        yang sebelumnya dijadwalkan pada {{ $jadwal->history->tanggal_waktu_sidang }}.
        <br>
        <br>

        Regards,
        <br>
        Admin Sidang BAAK
        <br>
        Universitas Internasional Batam
        @elseif($template == 'dospenguji')
        Dear {{ $jadwal->dospenguji->initial_name }},
        <br>
        <br>
        Penjadwalan sidang {{ $type }} dengan Mahasiswa {{ $student->name }} / ber-NPM {{ $student->npm }} yang berjudul <b><i>{{ $customRequest->title }}</i></b>, yang sebelumnya telah dijadwalkan pada:
        <br>
        Tanggal/waktu: {{ $jadwal->tanggal_sidang }}
        <br>
        Telah dilakukan pembatalan, berikut adalah alasan dari pembatalannya sidang :
        <br>
        " {{ $customRequest->reject_reason }} "
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