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
                        <a class="btn btn-box-tool blue-col" href="{{ route('finance_user') }}">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                @if(!empty($financeUser))
                    {{ Form::open(array('id' => 'frm', 'url' => route('finance_user.update', $financeUser->id), 'method' => 'POST')) }}
                @else
                    {{ Form::open(array('id' => 'frm', 'url' => route('finance_user.create'), 'method' => 'POST')) }}
                @endif
                
                <div class="box-body">
                    <div class="form-group">
                        {{ Form::label('username', 'Nama pengguna') }}
                        <span class="text-red">*</span>
                        @if(!empty($financeUser))
                            {{ Form::text('username', $financeUser->username, array('id' => 'username', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @else
                            {{ Form::text('username', null, array('id' => 'username', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @endif

                        <br>

                        {{ Form::label('email', 'Email') }}
                        <span class="text-red">*</span>
                        @if(!empty($financeUser))
                            {{ Form::text('email', $financeUser->email, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @else
                            {{ Form::text('email', null, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @endif

                        <br>

                        {{ Form::label('password', 'Password') }}
                        @if(empty($financeUser))
                            <span class="text-red">*</span>
                        @endif

                        @if(empty($financeUser))
                            {{ Form::password('password', array('id' => 'password', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @else
                            {{ Form::password('password', array('id' => 'password', 'class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @endif

                        <br>

                        {{ Form::label('password_confirmation', 'Konfirmasi Password') }}
                        @if(empty($financeUser))
                            <span class="text-red">*</span>
                        @endif

                        @if(!empty($financeUser))
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

            var confirmation = window.confirm('Apakah data pengguna finance sudah sesuai ?');
            if(!confirmation) {
                return false;
            }

            $('#frm').submit();
        });
    }
</script>
@endpush