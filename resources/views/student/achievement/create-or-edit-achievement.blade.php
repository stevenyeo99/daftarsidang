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
                  <a class="btn btn-box-tool blue-col" href="{{ route('student.achievement') }}">
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if (!empty($achievement))
              {{ Form::open(array('url' => route('student.achievement.update', $achievement->id))) }}
            @else
              {{ Form::open(array('url' => route('student.achievement.create'))) }}
            @endif
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('name', 'Nama') }}
                  <span class="text-red">*</span>
                  @if (!empty($achievement))
                    {{ Form::text('name', $achievement->name, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  {{ Form::label('place', 'Tempat') }}
                  {{-- <span class="text-red">*</span> --}}
                  @if (!empty($achievement))
                    {{ Form::text('place', $achievement->place, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('place', Input::old('place'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  {{ Form::label('year', 'Tahun') }}
                  <span class="text-red">*</span>
                  @if (!empty($achievement))
                    {{ Form::text('year', $achievement->year, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('year', Input::old('year'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
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