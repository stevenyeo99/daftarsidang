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
                        <a class="btn btn-box-tool blue-col" href="{{ route('ruangan') }}">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </a>
                        {{-- </button> --}}
                    </div>
                </div>

                @if(!empty($ruangan))
                    {{ Form::open(['id' => 'frm', 'url' => route('ruangan.update', $ruangan->id)]) }}
                @else
                    {{ Form::open(['id' => 'frm', 'url' => route('ruangan.create')]) }}
                @endif

                <div class="box-body">
                    <div class="form-group">
                        {{ Form::label('Gedung') }}
                        <span class="text-danger">*</span>
                        @if(!empty($ruangan))
                            {{ Form::select('gedung', ['A' => 'Gedung A UIB', 'B' => 'Gedung B UIB'], $ruangan->gedung, array('id' => 'gedung', 'class' => 'width-100p form-control select2', 'placeholder' => 'Kosong...')) }}
                        @else
                            {{ Form::select('gedung', ['A' => 'Gedung A UIB', 'B' => 'Gedung B UIB'], null, array('id' => 'gedung', 'class' => 'width-100p form-control select2', 'placeholder' => 'Kosong ...'))}}
                        @endif

                        <br><br>
                        
                        {{ Form::label('ruangan') }}
                        <span class="text-danger">*</span>
                        @if(!empty($ruangan))
                            {{ Form::text('ruangan', $ruangan->ruangan, array('id' => 'ruangan', 'class' => 'width-100p form-control control', 'placeholder' => 'Kosong...'))}}
                        @else
                            {{ Form::text('ruangan', Input::old('ruangan'), array('id' => 'ruangan', 'class' => 'width-100p form-control control', 'placeholder' => 'Kosong...')) }}
                        @endif
                    </div>
                </div>

                <div class="box-footer">
                    {{ Form::button($btn_label, array('id' => 'btnSubmit', 'class' => 'btn btn-primary')) }}
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
            bindBtnSubmitClick();
        });

        function bindBtnSubmitClick() {
            $('#btnSubmit').click(function() {
                
                var gedungSelector = $('#gedung');
                var ruanganInput = $('#ruangan');

                if((typeof gedungSelector.children('option:selected').val() === 'undefined') || (gedungSelector.children('option:selected').val() === '')) {
                    alert('Harap pilih gedung yang akan dibuat');
                    gedungSelector.focus();
                    return false;
                }

                if(ruanganInput.val() === '') {
                    alert('Harap isi ruangan yang akan dibuat!');
                    ruanganInput.focus();
                    return false;
                }

                var confirmation = window.confirm('Apakah anda yakin dengan pengisian data berikut ?');
                if(!confirmation) {
                    return false;
                }

                $('#frm').submit();
            });
        }
    </script>
@endpush