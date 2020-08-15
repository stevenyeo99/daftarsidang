@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulir</h3>

                    <div class="box-tools pull-right">
                        <a class="btn btn-box-tool blue-col" href="{{ route('baak.prodi_users') }}">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                @if(!empty($studyProgramUser))
                    {{ Form::open(['id' => 'frm', 'url' => route('baak_prodi.update', $studyProgramUser->id)]) }}
                @else
                    {{ Form::open(['id' => 'frm', 'url' => route('baak_prodi.create')]) }}
                @endif

                <div class="box-body">
                    <div class="form-group">
                        <div class="col-xs-6 p-l-0">
                            {{ Form::label('nip', 'NIP') }}
                            <span class="text-red">*</span>
                            @if(!empty($studyProgramUser))
                                {{ Form::text('nip', $studyProgramUser->nip, ['id' => 'nip', 'class' => 'form-control', 'placeholder' => 'Kosong...', 'maxlength' => 10, 'minlength' => 8]) }}
                            @else
                                {{ Form::text('nip', Input::old('nip'), ['id' => 'nip', 'class' => 'form-control', 'placeholder' => 'Kosong...', 'maxlength' => 10, 'minlength' => 8]) }}
                            @endif

                            <br>

                            {{ Form::label('first_name', 'First Name') }}
                            <span class="text-red">*</span>
                            @if(!empty($studyProgramUser))
                                {{ Form::text('first_name', $studyProgramUser->first_name, ['id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @else
                                {{ Form::text('first_name', Input::old('first_name'), ['id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @endif

                            <br>

                            {{ Form::label('last_name', 'Last Name') }}
                            @if(!empty($studyProgramUser))
                                {{ Form::text('last_name', $studyProgramUser->last_name, ['id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @else
                                {{ Form::text('last_name', Input::old('last_name'), ['id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @endif

                            <br>

                            {{ Form::label('password', 'Password') }}
                            <span class="text-red">*</span>
                            @if(!empty($studyProgramUser))
                                {{ Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @else
                                {{ Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @endif

                            <br>

                            {{ Form::label('password_confirmation', 'Konfirmasi Password') }}
                            <span class="text-red">*</span>
                            {{ Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}

                            <br>                          
                        </div>

                        <div class="col-xs-6 p-l-0">
                            {{ Form::label('username', 'User Name') }}
                            <span class="text-red">*</span>
                            @if(!empty($studyProgramUser))
                                {{ Form::text('username', $studyProgramUser->username, ['id' => 'username', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @else
                                {{ Form::text('username', Input::old('username'), ['id' => 'username', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @endif

                            <br>

                            {{ Form::label('middle_name', 'Middle Name') }}
                            @if(!empty($studyProgramUser))
                                {{ Form::text('middle_name', $studyProgramUser->middle_name, ['id' => 'middle_name', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @else
                                {{ Form::text('middle_name', Input::old('middle_name'), ['id' => 'middle_name', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @endif

                            <br>

                            {{ Form::label('email', 'Email') }}
                            <span class="text-red">*</span>
                            @if(!empty($studyProgramUser))
                                {{ Form::text('email', $studyProgramUser->email, ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @else
                                {{ Form::text('email', Input::old('email'), ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @endif

                            <br>

                            {{ Form::label('gender', 'Jenis Kelamin') }}
                            <span class="text-red">*</span>
                            @if(!empty($studyProgramUser))
                                {{ Form::select('gender', [1 => 'Pria', 2 => 'Wanita'], $studyProgramUser->gender, ['id' => 'gender', 'class' => 'form-control', 'placeholder' => 'Kosong...']) }}
                            @else
                                {{ Form::select('gender', [1 => 'Pria', 2 => 'Wanita'], Input::old('gender'), ['id' => 'gender', 'class' => 'form-control select2', 'placeholder' => 'Kosong...']) }}
                            @endif

                            <br><br>

                            {{ Form::label('ddlStudyProgram', 'Program Studi') }}
                            <span class="text-red">*</span>
                            <input type="hidden" id="is_admin" name="is_admin" value="{{ $is_admin }}" readonly>
                            @if($is_admin == 0)
                                @if(!empty($listOfStudyPrograms))

                                @else
                                    <select id="listOfProdis" name="listOfProdis" multiple="true" class="form-control select2">
                                        @foreach($listOfStudyProgramsDropDowns as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            @else
                                @if(!empty($listOfStudyPrograms))

                                @else
                                    <select id="listOfProdis" name="listOfProdis" class="form-control select2">
                                        @foreach($listOfStudyProgramsDropDowns as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            @endif
                            
                            
                            <!-- <br><br>

                            <div class="custom-control custom-checkbox">
                                @if(!empty($studyProgramUser))
                                    <input type="checkbox" class="custom-control-input" id="is_admin" name="is_admin" value="1" style="cursor: pointer;">
                                    <label class="custom-control-label" for="is_admin" style="cursor: pointer;"> Admin</label>
                                @else
                                    <input type="checkbox" class="custom-control-input" id="is_admin" name="is_admin" value="1" style="cursor: pointer;" @if(old('is_admin') == 1) checked @endif>
                                    <label class="custom-control-label" for="is_admin" style="cursor: pointer;"> Admin</label>
                                @endif
                                &nbsp;
                                @if(!empty($studyProgramUser))
                                    <input type="checkbox" class="custom-control-input" id="is_participant" name="is_participant" style="cursor: pointer;" value="1">
                                    <label class="custom-control-label" for="is_participant" style="cursor: pointer;"> Penguji</label>
                                @else
                                    <input type="checkbox" class="custom-control-input" id="is_participant" name="is_participant" style="cursor: pointer;" value="1" @if(old('is_participant') == 1) checked @endif>
                                    <label class="custom-control-label" for="is_participant" style="cursor: pointer;"> Penguji</label>
                                @endif
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    {{ Form::button($btn_label, ['id' => 'btnSubmit', 'class' => 'btn btn-primary']) }}
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
            bindValidationBeforeSubmit();
        });

        function bindValidationBeforeSubmit() {
            $('#btnSubmit').click(function() {
                var submitType = $('#btnSubmit').text();

                var nip = $('#nip');
                if(nip.val() === '') {
                    alert('NIP tidak boleh kosong!');
                    nip.focus();
                    return false;
                }

                if(nip.val().length < 8) {
                    alert('NIP tidak boleh kurang dari 8 angka');
                    nip.focus();
                    return false;
                }

                var userName = $('#username');
                if(userName.val() === '') {
                    alert('User Name tidak boleh kosong!');
                    userName.focus();
                    return false;
                }

                var firstName = $('#first_name');
                if(firstName.val() === '') {
                    alert('First Name tidak boleh kosong!');
                    firstName.focus();
                    return false;
                }

                var email = $('#email');
                if(email.val() === '') {
                    alert('Email tidak boleh kosong!');
                    email.focus();
                    return false;
                }

                var gender = $('#gender');
                if(gender.children('option:selected').val() === '') {
                    alert('Harap pilih jenis kelamin!');
                    gender.focus();
                    return false;
                }

                var password = $('#password');
                if(password.val() === '') {
                    alert('Password tidak boleh kosong!');
                    password.focus();
                    return false;
                }

                var password_confirmation = $('#password_confirmation');
                if(password_confirmation.val() === '') {
                    alert('Konfirmasi Password tidak boleh kosong!');
                    password_confirmation.focus();
                    return false;
                }

                if(password.val() !== password_confirmation.val()) {
                    alert('Password konfirmasi harus sama dengan password yang diisi!');
                    password_confirmation.focus();
                    return false;
                }

                // var is_admin = $('#is_admin');
                // var is_participant = $('#is_participant');
                // if(is_admin.is(':checked') === false && is_participant.is(':checked') === false) {
                //     alert('Harap check pengguna adalah admin ataupun penguji');
                //     return false;
                // }

                var program_studi = $('#listOfProdis');
                var is_admin = $('#is_admin');
                var gotSelected = false;
                if(is_admin.val() == 1) {
                    $(program_studi).children('option').each(function() {
                        var elementOption = $(this);
                        if(elementOption.is(':selected') === true) {
                            gotSelected = true;
                        }
                    });
                } else {
                    if($(program_studi).children('option:selected').val() !== '') {
                        gotSelected = true;
                    }
                }

                if(gotSelected === false) {
                    alert('Harap pilih role studi program yang akan disimpan');
                    program_studi.focus();
                    return false;
                }

                var confirmation = window.confirm('Apakah anda sudah yakin dengan data yang akan disimpan ?');
                if(!confirmation) {
                    return false;
                }

                $('#frm').submit();
            });
        }
    </script>
@endpush