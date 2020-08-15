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
                {{-- <button type="button" class="btn btn-box-tool"> --}}
                  <a class="btn btn-box-tool blue-col" href="{{ route('users') }}">
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if (!empty($user))
              {{ Form::open(array('url' => route('users.update', $user->id))) }}
            @else
              {{ Form::open(array('url' => route('users.create'))) }}
            @endif
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('username', 'Nama pengguna') }}
                  <span class="text-red">*</span>
                  @if (!empty($user))
                    {{ Form::text('username', $user->username, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('username', Input::old('username'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  {{ Form::label('email', 'Email') }}
                  <span class="text-red">*</span>
                  @if (!empty($user))
                    {{ Form::text('email', $user->email, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('email', Input::old('email'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  {{ Form::label('role', 'Peran') }}
                  <span class="text-red">*</span>
                  @if (!empty($user))
                    @if ($user->id == \Auth::user()->id)
                      {{ Form::select('role', $roles, $selected_role->id, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                    @else
                      {{ Form::select('role', $roles, $selected_role->id, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                    @endif
                  @else
                      {{ Form::select('role', $roles, null, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                  @endif
                  <br><br>
                  {{ Form::label('password', 'Password') }}
                  @if (empty($user))
                    <span class="text-red">*</span>
                  @endif
                  @if (!empty($user))
                    {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  {{ Form::label('password_confirmation', 'Konfirmasi Password') }}
                  @if (empty($user))
                    <span class="text-red">*</span>
                  @endif
                  @if (!empty($user))
                    {{ Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                 {{ Form::submit($btn_label, array('class' => 'btn btn-primary')) }}
              </div>
              {{ Form::close() }}
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
  <!-- /.row -->
</section>
@endsection