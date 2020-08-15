<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>UIB | Pendaftaran sidang KP dan Skripsi dan Tesis online</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <meta content="" name="description">
        <meta content="" name="author">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            window.laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body>
        <div class="wrapper">
            Dear {{ $student->name  }},
            <br>
            <br>
            Berikut terlampir daftar daftar yang perlu direvisi saat melakukan penyidangan {{ $type }} dari {{ $beritaAcaraParticipant->username }} :
            <br>        
            @for($i = 1; $i <= count($beritaAcaraNoteRevisi); $i++)
                <b>{{ $i }}.  {{ $beritaAcaraNoteRevisi[$i-1] }}</b>
                <br>
            @endfor                       
            Diharapkan secepatnya untuk melakukan revisi yang diberikan.
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