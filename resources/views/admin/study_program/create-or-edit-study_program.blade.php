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
                  <a class="btn btn-box-tool blue-col" href="{{ route('prodis') }}">
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if (!empty($studyProgram))
              {{ Form::open(array('url' => route('prodis.update', $studyProgram->id))) }}
            @else
              {{ Form::open(array('url' => route('prodis.create'))) }}
            @endif
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('code', 'Kode') }}
                  <span class="text-red">*</span>
                  @if (!empty($studyProgram))
                    {{ Form::text('code', $studyProgram->code, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('code', Input::old('code'), array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  {{ Form::label('name', 'Nama') }}
                  <span class="text-red">*</span>
                  @if (!empty($studyProgram))
                    {{ Form::text('name', $studyProgram->name, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  {{ Form::label('faculty', 'Fakultas') }}
                  <span class="text-red">*</span>
                  @if (!empty($studyProgram))
                    {{ Form::select('faculty', $faculties, $selected_faculty->id, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                  @else
                    {{ Form::select('faculty', $faculties, null, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
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