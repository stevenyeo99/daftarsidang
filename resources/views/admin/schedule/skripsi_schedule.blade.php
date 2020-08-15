@extends('layouts.master')

@section('content')
@include('shared.page_header')

<!-- Main Content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Atur Ruangan Sidang</h3>

                    <div class="box-tools pull-right">
                         {{-- <button type="button" class="btn btn-box-tool"> --}}
                            <a class="btn btn-box-tool blue-col" href="{{ route('baak.penjadwalan.skripsi') }}">
                                <i class="fa fa-arrow-left"></i>
                                Kembali
                            </a>
                        {{-- </button> --}}
                    </div>
                </div>

                {{ Form::open(['url' => route('baak.penjadwalan.assign.skripsi', $penjadwalan->id), 'id' => 'frm']) }}
                    <div class="box-body">
                        <div class="form-group">
                            {{ Form::label('Dosen Pembimbing') }}
                            {{ Form::text('dosen_pembimbing_id', $pembimbingValue, array('class' => 'width-100p form-control control', 'readonly' => true)) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Dosen Penguji') }}
                            {{ Form::text('dosen_penguji', $dosenPengujiValue, array('class' => 'width-100p form-control control', 'readonly' => true))}}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Tanggal-Waktu Sidang') }}
                            {{ Form::text('tanggal_waktu_sidang', $penjadwalan->tanggal_sidang, array('class' => 'width-100p form-control control', 'readonly' => true)) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Ruangan Sidang') }}
                            <span class="text-danger">*</span>
                            @if($penjadwalan->ruangan_sidang_id != '' && $penjadwalan->ruangan_sidang_id != null)
                                {{ Form::select('ruangan_sidang', $ruangan_sidang_arr, $penjadwalan->ruangan_sidang_id, array('id' => 'ruangan_sidang', 'class' => 'width-100p form-control select2', 'placeholder' => 'Kosong...')) }}
                            @else
                                {{ Form::select('ruangan_sidang', $ruangan_sidang_arr, null, array('id' => 'ruangan_sidang', 'class' => 'width-100p form-control select2', 'placeholder' => 'Kosong...')) }}
                            @endif
                        </div>

                        @if($penjadwalan->dosen_pembimbing_backup != 0)
                        <div class="form-group">
                            {{ Form::label('Dosen Pembimbing Backup') }}
                            {{ Form::text('dosen_pembimbing_backup_id', $pembimbingBAKValue, array('class' => 'width-100p form-control control', 'readonly' => true)) }}
                        </div>
                        @endif
                    </div>

                    <div class="box-footer">
                        {{ Form::button('Atur Ruangan', array('id' => 'btnSubmit', 'class' => 'btn btn-primary')) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>

@endsection

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function() {
            BindOnAssignJadwal();
        });

        function BindOnAssignJadwal() {
            $('#btnSubmit').click(function() {
                var ruangan_sidang = $('#ruangan_sidang');
                if((ruangan_sidang.children('option:selected').val() === '') || (typeof ruangan_sidang.children('option:selected').val() === 'undefined')) {
                    alert('Harap pilih ruangan yang akan dijadwalkan!');
                    ruangan_sidang.focus();
                    return false;
                }

                var confirmation = window.confirm('Apakah ruangan sudah sesuai dengan ketentuan ?');
                if(!confirmation) {
                    return false;
                }

                $('#frm').submit();
            });
        }
    </script>
@endpush