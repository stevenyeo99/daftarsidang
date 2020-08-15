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
                  <a class="btn btn-box-tool blue-col" href="{{ route('faculties') }}">
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if (!empty($faculty))
              {{ Form::open(array('url' => route('faculties.update', $faculty->id))) }}
            @else
              {{ Form::open(array('url' => route('faculties.create'))) }}
            @endif
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('name', 'Nama') }}
                  <span class="text-red">*</span>
                  @if (!empty($faculty))
                    {{ Form::text('name', $faculty->name, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
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