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
                Pendaftaran sidang <b><i> {{ $creation_type }} </i></b> anda dengan judul "<b>{{ $customRequest->title }}</b>" telah berhasil diserahkan kepada Admin Sidang BAAK.
                <br>
                <br>
                Silahkan melengkapi berkas hardcopy yang harus diserahkan ke Admin Sidang BAAK.
                <br>
                Paling lambat H+2.
                <br>
                <br>

                Regards,
                <br>
                Admin Sidang BAAK
                <br>
                Universitas Internasional Batam
            @elseif($role == 'mahasiswa_akhir')
                Dear {{ $student->name }},
                <br>
                <br>
                Pendaftaran sidang <b><i> {{ $creation_type }} </i></b> anda dengan judul "<b>{{ $customRequest->title }}</b>" akan dilakukan validasi terlebih dahulu oleh pihak biro keuangan.
                <br>
                <br>
                Silahkan melakukan persiapan berkas hardcover yang akan diserahkan ke Admin Sidang BAAK, Setelah tervalidasi sukses keuangan yang dilakukan oleh pihak biro keuangan.
                <br>
                <br>

                Regards,
                <br>
                Admin Sidang BAAK
                <br>
                Universitas Internasional Batam
            @elseif($role == 'finance')
                Dear Biro Keuangan,
                <br>
                <br>
                Pendaftaran Sidang <b><i> {{ $creation_type }} </i></b> telah dilakukan oleh Mahasiswa {{ $student->npm }} / {{ $student->name }}.
                <br>
                Diharapkan untuk melakukan validasi tagihan keuangan pada mahasiswa tersebut, dikarenakan tagihan keuangan mahasiswa merupakan syarat untuk melakukan Sidang <b><i> {{ $creation_type }} </i></b>.
                <br>
                <br>

                Regards,
                <br>
                Admin Sidang BAAK
                <br>
                Universitas Internasional Batam
            @elseif($role == 'finance_validation')
                Dear Admin BAAK,
                <br>
                <br>
                Pendaftaran sidang <b><i> {{ $creation_type }} </i></b> dari Mahasiswa {{ $student->npm }} / {{$student->name }} akan divalidasi terlebih dahulu oleh pihak biro keuangan sebelum melakukan tindakan selanjutnya.
                <br>
                Terima kasih.
            @else
                Dear Admin BAAK,
                <br>
                <br>
                Pendaftaran sidang <b><i> {{ $creation_type }} </i></b> dari Mahasiswa {{ $student->npm }} / {{$student->name }} sudah diterima dan menunggu proses selanjutnya.
                <br>
                Terima kasih.
            @endif
        </div>
    </body>

</html>
{{-- @endsection --}}
