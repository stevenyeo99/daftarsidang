<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>UIB | Pendaftaran sidang KP dan Skripsi dan Tesis online</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        
        {!! Html::style('assets/plugins/bootstrapOld/css/bootstrap.min.css') !!}
        {!! Html::style('assets/plugins/font-awesome/css/font-awesome.min.css') !!}
        {!! Html::style('assets/css/AdminLTE.min.css') !!}
        {!! Html::style('assets/css/custom.css') !!}
        {!! Html::style('assets/plugins/Ionicons/css/ionicons.min.css') !!}
        <!-- iCheck -->
        {!! Html::style('assets/plugins/iCheck/square/blue.css') !!}
        <link rel="shortcut icon" href="./assets/img/Icon.png" />
    </head>

    <body>
        <div style="padding: 30px 50px;">
            <div style="border-radius: 8px; padding: 10px 30px; border: 1px solid #b7b7b7;">
                <h1 style="text-align: center;"><u>Transkrip Nilai</u></h1>
                <br>
                <div class="table-responsive">
                    <table style="width: 80%;" align="center">
                        <tr>
                            <td style="width: 20%;">Nama</td>
                            <td><b>{{ $student->name }}</b></td>
                        </tr>

                        <tr>
                            <td>NIM</td>
                            <td><b>{{ $student->npm }}</b></td>
                        </tr>

                        <tr>
                            <td>Program Studi</td>
                            <td><b>{{ $student->studyprogram()->first()->name }}</b></td>
                        </td>
                    </table>

                    <br>

                    <table class="table table-bordered" style="width: 80%;" align="center">
                        <thead>
                            <tr>
                                <th style="text-align: center;" class="bg-primary">NO</th>
                                <th style="text-align: center;" class="bg-primary">SEMESTER</th>
                                <th style="text-align: center;" class="bg-primary">KODE</th>
                                <th style="text-align: center;" class="bg-primary">MATA KULIAH</th>
                                <th style="text-align: center;" class="bg-primary">SKS</th>
                                <th style="text-align: center;" class="bg-primary">NILAI</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(count($listOfTranskrip) > 0)
                                @php $i = 1; @endphp
                                @foreach($listOfTranskrip as $transkrip)
                                    <tr>
                                        <td style="text-align: center;"><b>{{ $i }}</b></td>
                                        <td>Semester {{ $transkrip->traNamaSemesterAmbil }} {{ $transkrip->traTahunAmbil }}</td>
                                        <td>{{ $transkrip->traKodeMatakuliah }}</td>
                                        <td>{{ $transkrip->traNamaResmi }}</td>
                                        <td style="text-align: center;">
                                            {{ $transkrip->traJumlahSks }}
                                        </td>
                                        <td style="text-align: center;">
                                            @if(strlen($transkrip->traKodeNilai) > 1)
                                                {{ substr($transkrip->traKodeNilai, 0, 1) }}
                                            @else
                                                {{ $transkrip->traKodeNilai }}
                                            @endif
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" style="text-align: center;"><b>Transkrip Nilai Kosong...</b></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <br>

                    <h1 style="text-align: center;"><u>Prestasi Akademik</u></h1>

                    <table class="table table-bordered" style="width: 80%;" align="center">
                        <tr>
                            <td class="bg-primary" style="width: 30%;">Jumlah SKS diambil</td>
                            <td>{{ $jumlahSKS }}</td>
                        </tr>

                        <tr>
                            <td class="bg-primary">Jumlah mata kuliah diambil</td>
                            <td>{{ $jumlahMataKuliah }}</td>
                        </tr>

                        <tr>
                            <td class="bg-primary">IP Kumulatif</td>
                            <td>{{ $ip }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>        
    </body>
</html>