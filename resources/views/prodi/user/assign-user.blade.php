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
                        <a class="btn btn-box-tool blue-col" href="{{ route('dosen') }}">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                @if(!empty($prodiAssign))
                    {{ Form::open(array('method' => 'post', 'id' => 'frm', 'url' => route('dosen.reassign', $prodiAssign->id))) }}
                @else
                    {{ Form::open(array('method' => 'post', 'id' => 'frm', 'url' => route('dosen.assign'))) }}
                @endif

                <div class="box-body">
                    <div class="form-group">
                        {{ Form::label('Dosen') }}
                        <span class="text-danger">*</span>
                        @if(!empty($prodiAssign))
                            {{ Form::select('dosen_user_id', $anotherProdiUserArr, $prodiAssign->prodi_user_id, array('id' => 'dosen_user_id', 'class' => 'width-100p form-control select2', 'placeholder' => 'Kosong...')) }}
                        @else
                            {{ Form::select('dosen_user_id', $anotherProdiUserArr, null, array('id' => 'dosen_user_id', 'class' => 'width-100p form-control select2', 'placeholder' => 'Kosong...')) }}
                        @endif                        
                    </div>

                    <div class="form-group">
                        {{ Form::label('Prodi') }}                        
                        {{ Form::text('prodi', $studyProgram->name, array('class' => 'width-100p form-control control', 'readonly' => true)) }}
                        {{ Form::text('study_program_id', $studyProgram->id, array('hidden' => true)) }}
                    </div>
                </div>

                <div class="box-footer">
                    {{ Form::button('Atur', array('id' => 'btnAssign', 'class' => 'btn btn-primary')) }}
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
        bindBtnAssignClick();
    });

    function bindBtnAssignClick() {
        $('#btnAssign').click(function() {
            var ddlProdiUser = $('#dosen_user_id');
            if(ddlProdiUser.children('option:selected').val() === '' || ddlProdiUser.children('option:selected').val() === 0) {
                alert('Harap pilih pengguna yang akan terlibat dalam prodi ini.');
                return false;
            }

            var confirmation = window.confirm('Apakah dosen berikut terlibat dengan prodi ini ?');
            if(!confirmation) {
                return false;
            }

            $('#frm').submit();
        });
    }
</script>
@endpush