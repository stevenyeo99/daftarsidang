@extends('layouts.master')

@section('content')
@include('shared.page_header')
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulir</h3>

                    <div class="box-tools pull-right">
                        {{-- <button type="button" class="btn btn-box-tool"> --}}
                        <a class="btn btn-box-tool blue-col" href="{{ route('prodi.berita.acara.tesis') }}">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </a>
                        {{-- </button> --}}
                    </div>
                </div>

                {{ Form::open(array('id' => 'frm', 'url' => route('prodi.berita_acara_isi_form.tesis', $participant->id), 'method' => 'POST')) }}
                    <div class="box-body">
                        <div class="form-group">
                            {{ Form::label('Nama Mahasiswa :') }}
                            {{ Form::text('nama', 'steven', array('class' => 'form-control', 'readonly' => true)) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('NPM :') }}
                            {{ Form::text('npm', '1631095', array('class' => 'form-control', 'readonly' => true)) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Dosen Pembimbing :') }}
                            {{ Form::text('dospem', 'Dr Hendi Sama', array('class' => 'form-control', 'readonly' => true)) }}
                        </div>

                        <br>
                        
                        <table class="table" id="tblNote">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="3">
                                        <img style="cursor: pointer;" id="btnAdd" src="{{ asset('assets/img/add_bizmann.png') }}" width="30" height="30" alt="add logo">
                                        <label for="btnAdd" style="cursor: pointer;">Tambah Keterangan Revisi</label>
                                    </th>
                                </tr>

                                <tr role="row">
                                    <th style="width: 5%; border-right: 2px solid #f4f4f4;" class="text-center vcenter" rowspan="1" colspan="1">No</th>
                                    <th style="width: 90%;" class="text-center vcenter" rowspan="1" colspan="1">Keterangan Revisi</th>
                                    <th style="width: 5%;" class="text-center vcenter" rowspan="1" colspan="1"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr role="row" id="emptyRow">
                                    <td colspan="3" style="text-align: center;">Tidak ada revisi untuk sidang ini.</td>
                                </tr>
                            </tbody>
                        </table>

                        @if($participant->participant_type == 1)    
                            <br>
                            <hr>
                            <h4><u><b>Nilai Tesis</b></u></h4>
                            <div class="form-group row">
                                <div class="col-xs-2">
                                    {{ Form::text('nilai_score', null, array('id' => 'nilai_score', 'class' => 'form-control col-xs-2', 'style' => 'text-align: right;')) }}
                                </div>

                                <div class="col-xs-2">
                                    {{ Form::text('nilai_index', null, array('id' => 'nilai_index', 'class' => 'form-control col-xs-2', 'readonly' => true)) }}
                                    <input type="hidden" id="nilai_ip" name="nilai_ip" readonly>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="box-footer">
                        <input type="button" value="Submit" id="btnSubmit" class="btn btn-primary">
                    </div>

                    <input type="hidden" id="txtNeedScore" name="txtNeedScore" value="{{ $participant->participant_type }}" readonly>
                    <input type="hidden" id="txtNeedRevision" name="txtNeedRevision" readonly>
                    <input type="hidden" id="txtTotalRevision" name="txtTotalRevision" value="0" readonly>
                    <input type="hidden" id="txtStatusLulus" name="txtStatusLulus" value="0" readonly>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom_css')
    <style>
        /* #nilai_score::-webkit-inner-spin-button,
        #nilai_score::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        } */
    </style>
@endpush

@push('custom_js')
<script type="text/javascript">
    var emptyRow = '<tr role="row" id="emptyRow"><td colspan="3" style="text-align: center;">Tidak ada revisi untuk sidang ini.</td></tr>';
    var isDotted = false;
    var dotIndex = false;
    var isOneHundred = false;
    $(document).ready(function() {
        bindAddListOfRevisionSidang();
        bindOnInputScore();
        btnSimpanOnTrigger();
    });

    function btnSimpanOnTrigger() {
        $('#btnSubmit').click(function() {
            var countRevisiRow = $('#tblNote tbody tr').not('#emptyRow').length;
            var needRevision = $('#txtNeedRevision');
            var totalRevision = $('#txtTotalRevision');
            var total = 0;
            if(countRevisiRow !== 0) {
                needRevision.val(1);
                for(var i = 1; i <= countRevisiRow; i++) {
                    var note = $('#txtNoteRevisi_' + i);
                    if(note.val() === '') {
                        alert('Harap isi revisi yang akan diberikan pada barisan no ' + i);
                        scrollTo(note.attr('id'));
                        note.focus();
                        return false;
                    }
                    total++;
                }
            } else {
                needRevision.val(0);
            }

            // validate nilai
            var nilaiAngka = $('#nilai_score');
            var nilaiIndeks = $('#nilai_index');
            if(nilaiAngka.val() === '' || nilaiIndeks.val() === '') {
                alert('Harap mengisi nilai untuk berita acara sidang!');
                scrollTo(nilaiAngka.attr('id'));
                nilaiAngka.focus();
                return false;
            }

            // set total when submit the data
            totalRevision.val(total);

            var confirm = window.confirm('Apakah anda sudah yakin dengan data yang akan disimpan?');
            if(!confirm) {
                return false;
            }

            $('#frm').submit();
        });
    }

    function scrollTo(id) {
        $('html, body').animate({
            scrollTop: $('#'+id).offset().top
        }, 500);
    }

    function bindOnInputScore() {
        $('#nilai_score').change(function() {
            var value = $(this).val();
            if(value !== '') {
                value = getScoreFormattedNumber(value, 2);
            } else {
                value = '';
            }
            $(this).val(value);
        });

        $('#nilai_score').on('input', function() {
            var value = $(this).val();
            // validate first . unable to key in
            if(value.charAt(0) === '.') {
                $(this).val('');
            } else if(value.charAt(value.length - 1) === '.' && isDotted === true) {
                if(value.charAt(value.length - 2) === '.') {
                    $(this).val(value.substring(0, value.length - 1));
                }               
            } else if(isDotted === true && isOneHundred === true && parseInt(value.substring(dotIndex + 1)) > 0) {
                $(this).val(value.substring(0, value.length -1));
            } else if(isDotted === true && value.substring(dotIndex + 1).length > 2) {
                $(this).val(value.substring(0, value.length - 1));
            } else if(value.indexOf('.') === -1) {
                isDotted = false;
                dotIndex = -1;
            } else if(value.indexOf('.') !== -1) {
                isDotted = true;
                dotIndex = value.indexOf('.');
            }

            if(parseInt(value) === 100) {
                isOneHundred = true;
            } else {
                isOneHundred = false;
            }

            if(parseInt(value) > 100) {
                if(parseInt(value.substring(0, 3)) === 100) {
                    $(this).val(value.substring(0, 3));
                } else {
                    $(this).val(value.substring(0, 2));
                }                
            } 

            $(this).val($(this).val().replace(/[^\d.]/g, ""));
            if($(this).val() === '') {                
                $('#nilai_index').val('');
                return false;
            }
            
            var nilai = parseFloat($(this).val());
            var indexs = '';
            var ip = '0.00';
            // logic requirements tesis
            // S2	A	4.00	86.00 - 100.00	A	4.00
            // S2	A-	3.67	80.00 - 85.99	A	4.00
            // S2	B	3.00	72.00 - 75.99	B	3.00
            // S2	B+	3.33	76.00 - 79.99	B	3.00
            // S2	B-	2.67	68.00 - 71.99	B	3.00
            // S2	C	2.00	56.00 - 61.99	C	2.00
            // S2	C+	2.33	62.00 - 67.99	C	2.00
            // S2	E	0.00	0.00 - 55.99	E	0.00
            if(nilai >= 86.00 && nilai <= 100.00) {
                indexs = 'A';
                ip = '4.00';
            } else if(nilai >= 80.00 && nilai <= 85.99) {
                indexs = 'A-';
                ip = '3.67';
            } else if(nilai >= 76.00 && nilai <= 79.99) {
                indexs = 'B+';
                ip = '3.33';
            } else if(nilai >= 72.00 && nilai <= 75.99) {
                indexs = 'B';
                ip = '3.00';
            } else if(nilai >= 68.00 && nilai <= 71.99) {
                indexs = 'B-';
                ip = '2.67';    
            } else if(nilai >= 62.00 && nilai <= 67.99) {
                indexs = 'C+';
                ip = '2.33';
            } else if(nilai >= 56.00 && nilai <= 61.99) {
                indexs = 'C';
                ip = '2.00';
            } else if(nilai >= 0.00 && nilai <= 55.99) {
                indexs = 'E';
                ip = '0.00';
            }

            $('#nilai_index').val(indexs);
            $('#nilai_ip').val(ip);
            if(indexs === 'D' || indexs === 'E') {
                $('#txtStatusLulus').val(13);
            } else {
                $('#txtStatusLulus').val(8);
            }
            
        });
    }

    function bindAddListOfRevisionSidang() {
        $('#btnAdd').click(function() {
            $('#tblNote tbody tr#emptyRow').hide();
            var number = $('#tblNote tbody tr').not('#emptyRow').length + 1;
            var rowDetail = '<tr role="row">';
            rowDetail += '<td style="text-align: center; padding-top: 35px;">' + number + '</td>';
            rowDetail += '<td><textarea class="form-control" id="txtNoteRevisi_' + number + '" name="txtNoteRevisi_' + number + '" style="width: 100%; height: 80px;"></textarea></td>';
            rowDetail += '<td><img onclick="bindBtnDeleteRow($(this))" class="btnDelete" style="padding-top: 20px; cursor: pointer; width: 35px;" src="{{ asset("assets/img/delete_bizmann.png") }}"></td>';
            rowDetail += '</tr>';
            $('#tblNote tbody').append(rowDetail);         
        });
    }

    function bindBtnDeleteRow(element) {
        element.parent().parent().remove();        
        var noteRows = $('#tblNote tbody tr').not('#emptyRow').length;
        if(noteRows === 0) {
            $('#tblNote tbody tr#emptyRow').show();
        } else {
            bindResetAttribute();
        }
    }

    function bindResetAttribute() {
        var number = 1;
        $('#tblNote tbody tr').not('#emptyRow').each(function() {
            var rowElement = $(this);
            rowElement.find('td:eq(0)').text(number);
            rowElement.find('td:eq(1) textarea').attr({id: 'txtNoteRevisi_' + number, name: 'txtNoteRevisi_' + number});
            number++;
        });
    }

    /**
        for formatted decimal value
     */
    function getScoreFormattedNumber(number, numberOfDecimalPlaces) {
        numberOfDecimalPlaces = parseInt(numberOfDecimalPlaces);
        var roundingFactor = Math.pow(10, numberOfDecimalPlaces);
        number = (Math.round((number * roundingFactor) + (0.0001 / roundingFactor)) / roundingFactor).toFixed(numberOfDecimalPlaces);
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
</script>
@endpush