@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <!-- general form elements -->
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

                @if(!empty($prodiUser))
                    {{ Form::open(array('id' => 'frm', 'url' => route('dosen.update', $prodiUser->id))) }}
                @else
                    {{ Form::open(array('id' => 'frm', 'url' => route('dosen.create'))) }}
                @endif

                <div class="box-body">
                    <div class="form-group">
                        {{ Form::label('username', 'Nama pengguna') }}
                        <span class="text-red">*</span>
                        @if(!empty($prodiUser))
                            {{ Form::text('username', $prodiUser->username, array('id' => 'username', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @else
                            {{ Form::text('username', Input::old('username'), array('id' => 'username', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @endif
                        
                        <br>

                        {{ Form::label('initial_name', 'Nama panggilan') }}
                        <span class="text-red">*</span>
                        @if(!empty($prodiUser))
                            {{ Form::text('initial_name', $prodiUser->initial_name, array('id' => 'initial_name', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @else
                            {{ Form::text('initial_name', Input::old('initial_name'), array('id' => 'initial_name', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @endif

                        <br>

                        {{ Form::label('email', 'Email') }}
                        <span class="text-red">*</span>
                        @if(!empty($prodiUser))
                            {{ Form::text('email', $prodiUser->email, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Kosong...'))}}
                        @else
                            {{ Form::text('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @endif

                        <br>

                        {{ Form::label('prodi', 'Prodi') }}
                        {{ Form::text('prodi', $studyProgramName, array('class' => 'form-control', 'readonly' => true))}}
                        {{ Form::text('prodi_id', $studyProgramId, array('readonly' => true, 'hidden' => true))}}

                        <br>

                        {{ Form::label('password', 'Password') }}
                        @if(empty($prodiUser))
                            <span class="text-red">*</span>
                        @endif
                        @if(!empty($prodiUser))
                            {{ Form::password('password', array('id' => 'password', 'class' => 'form-control', 'placeholder' => 'Kosong...') )}}
                        @else
                            {{ Form::password('password', array('id' => 'password', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @endif

                        <br>

                        {{ Form::label('password_confirmation', 'Konfirmasi Password') }}
                        @if(empty($prodiUser))
                            <span class="text-red">*</span>
                        @endif
                        @if(!empty($prodiUser))
                            {{ Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @else
                            {{ Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
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
            bindSubmitBtnFormOnClick();
        });
        
        function bindSubmitBtnFormOnClick() {
            $('#btnSubmit').click(function() {
                // validate username first
                var username = $('#username');
                if(username.val() === '') {
                    alert('Harap isi username dosen!');
                    username.focus();
                    return false;
                }

                var initial_name = $('#initial_name');
                if(initial_name.val() === '') {
                    alert('Harap isi nama panggilan dosen!');
                    initial_name.focus();
                    return false;
                }

                var email = $('#email');
                if(email.val() === '') {
                    alert('Harap isi email dosen!');
                    email.focus();
                    return false;
                }

                var password = $('#password');
                if(password.val() === '') {
                    alert('Harap isi password dosen');
                    password.focus();
                    return false;
                }


                var confirmation = window.confirm('Apakah data pengguna dosen sudah sesuai ?');
                if(!confirmation) {
                    return false;
                }

                $('#frm').submit();
            });
        }
    </script>
@endpush