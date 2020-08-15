@extends('layouts.master')

@section('content')
@include('shared.page_header')

<!-- Main Content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Lihat Detail Penjadwalan</h3>

                    <div class="box-tools pull-right">
                         {{-- <button type="button" class="btn btn-box-tool"> --}}
                            <a class="btn btn-box-tool blue-col" href="{{ $backRoute }}">
                                <i class="fa fa-arrow-left"></i>
                                Kembali
                            </a>
                        {{-- </button> --}}
                    </div>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        {{ Form::label('Dosen Pembimbing') }}
                        {{ Form::text('dosen_pembimbing_id', $pengujiUserName, array('class' => 'width-100p form-control control', 'readonly' => true)) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Dosen Penguji') }}
                        {{ Form::text('dosen_penguji', $ketuaPengujiUserName, array('class' => 'width-100p form-control control', 'readonly' => true))}}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Tanggal-Waktu Sidang') }}
                        {{ Form::text('tanggal_waktu_sidang', $penjadwalan->tanggal_sidang, array('class' => 'width-100p form-control control', 'readonly' => true)) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Ruangan Sidang') }}
                        {{ Form::text('ruangan_sidang', $ruanganSidangVal, array('id' => 'ruangan_sidang', 'class' => 'width-100p form-control control', 'readonly' => true)) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
