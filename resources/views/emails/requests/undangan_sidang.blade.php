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
        @if($template == 'student')
            Dear {{ $student->name }},
            <br>
            <br>
            Anda telah diundang untuk melakukan Sidang {{ $creation_type }}
            <br>
            Pada Tanggal/Waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan}}
            <br>
            <b>Note:</b>
            <br>            
            <b>1. Kemeja Putih Lengan Panjang</b>
            <br>
            <b>2. Celana Hitam (bahan kain)</b>
            <br>
            <b>3. Sepatu Hitam (tidak boleh sport)</b>
            <br>
            <b>4. Dasi Hitam Panjang</b>                
            <br>
            Harap datang lebih awal untuk melakukan persiapan, dan mohon untuk tidak terlambat karena memilki efek kepada penilaian.
            <br>
            <br>

            Regards,
            <br>
            Admin Sidang BAAK
            <br>
            Universitas Internasional Batam
        @elseif($template == 'student_new')
            Dear {{ $student->name }},
            <br>
            <br>
            Telah terjadi perubahan pada penjadwalan sidang {{ $type }}, berikut dibawah ini merupakan perubahan dari pihak prodi:
            <br>
            Pada tanggal/waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}
            <br>
            <br>

            Regards,
            <br>
            Admin Sidang BAAK
            <br>
            Universitas Internasional Batam
        @elseif($template == 'admin_baak')
            Dear Admin Sidang BAAK,
            <br>
            <br>
            Mahasiswa {{ $student->name }} ber-NPM {{ $student->npm }} akan melakukan sidang <b><i>{{ $creation_type }}</i></b> pada:
            <br>
            Tanggal/Waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}
            <br>
            Terima kasih.
        @elseif($template == 'admin_baak_new')
            Dear Admin Sidang BAAK,
            <br>
            <br>
            Pihak prodi telah melakukan penjadwalan ulang untuk Mahasiswa {{ $student->name }} ber-NPM {{ $student->npm }} yang akan melakukan sidang <b><i>{{ $creation_type }}</i></b> pada:
            <br>
            Tanggal/waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}
            <br>
            Terima Kasih.
        @elseif($template == 'admin_prodi')
            Dear Admin Prodi {{ $prodi }},
            <br>
            <br>
            Mahasiswa bernama {{ $student->name }} dan ber-NPM {{ $student->npm }} akan melakukan sidang pada gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}.
            <br>
            <br>

            Regards,
            <br>
            Admin Sidang BAAK
            <br>
            Universitas Internasional Batam
        @elseif($template == 'admin_prodi_new')
            Dear Admin Prodi {{ $prodi }},
            <br>
            <br>
            Berikut setelah ulang melakukan penjadwalan ulang mahasiswa bernama {{ $student->name }} dan ber-NPM {{ $student->npm }} akan melakukan sidang pada gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}.
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
            Mahasiswa Bimbingan anda dengan bernama {{ $student->name }} dan ber-NPM {{ $student->npm }} telah dijadwalkan untuk melakukan sidang {{ $type }}nya pada:
            <br>
            Tanggal/Waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}
            <br>
            <br>

            Regards,
            <br>
            Admin Sidang BAAK
            <br>
            Universitas Internasional Batam
        @elseif($template == 'dospem_new')
            Dear {{ $jadwal->dospem->initial_name }},
            <br>
            <br>
            Telah terjadi perubahan jadwal dari pihak prodi pada penjadwalan sidang mahasiswa bimbingan anda bernama {{ $student->name }} / {{ $student->npm }} dalam melakukan
            {{ $type }}nya, berikut adalah perubahan jadwal:
            <br>
            Tanggal/Waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}
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
            Anda telah dijadwalkan untuk melakukan sidang kepada mahasiswa bernama {{ $student->name }} dan ber-NPM {{ $student->npm }},
            dalam {{ $type }}nya yang berjudul <b>"{{ $customRequest->title }}"</b>.
            <br>
            Tanggal/Waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}
            <br>
            <br>

            Regards,
            <br>
            Admin Sidang BAAK
            <br>
            Universitas Internasional Batam
        @elseif($template == 'dospenguji_new')
            Dear {{ $jadwal->dospenguji->initial_name }},
            <br>
            <br>
            Telah terjadi perubahan jadwal yang sebelumnya beliau dijadwalkan untuk melakukan sidang kepada mahasiswa bernama {{ $student->name }} / {{ $student->npm }} dalam {{ $type }}nya 
            yang berjudul <b>"{{ $customRequest->title }}"</b>", 
            <br>
            Berikut adalah perubahan jadwalnya:
            <br>
            Tanggal/Waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}
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
            Anda telah dijadwalkan untuk menggantikan {{ $jadwal->dospem->initial_name }}, dikarenakan beliau tidak bisa hadir.
            <br>
            Mahasiswa bimbingannya:
            <br>
            Nama: {{ $student->name }}
            <br>
            NPM: {{ $student->npm }}
            <br>
            Tipe: {{ $type }}
            <br>
            Judul: <b>{{ $customRequest->title }}</b>
            <br>
            Tanggal/Waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}
            <br>
            <br>

            Regards,
            <br>
            Admin Sidang BAAK
            <br>
            Universitas Internasional batam
        @elseif($template == 'dospemBAK_new')
            Dear {{ $jadwal->dospemBAK->initial_name }},
            <br>
            <br>
            Telah terjadi perubahan penjadwalan dari pihak prodi, yang sebelumnya beliau dijadwalkan untuk menjadi dosen pembimbing peganti untuk 
            mahasiswa yang bernama {{ $student->name }} / {{ $student->npm }} dalam melakukan sidang {{ $type }}nya
            berikut adalah jadwal yang telah diulang pengaturan oleh pihak prodi:
            <br>
            Tanggal/Waktu: {{ $jadwal->tanggal_sidang }}
            <br>
            Ruangan: Gedung {{ $ruangan->gedung }} - {{ $ruangan->ruangan }}
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