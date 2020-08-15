@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="custom-add-new-button">
                        @if (Gate::allows('is-admin')) 
                            <a
                            href="{{ route('prodis.create') }}"
                            class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="study_program_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Kode</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Fakultas</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Dibuat oleh</th>
                                    <th class="text-center vcenter">Aksi</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::text('code', null, ['id' => 'code', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('name', null, ['id' => 'name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '1']) }}
                                    </td>
                                    <td>
                                        {{ Form::select('faculty', $faculties , null , ['id' => 'faculty', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '2']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('created_by_user', null, ['id' => 'created_by_user', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '3']) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
  <!-- /.row -->
@endsection

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function() {
            var studyProgramDatatable = $('#study_program_datatable').DataTable(
            {
                'autoWidth' : false,
                orderCellsTop: true,
                responsive: false,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                language: {
                    'url': "/assets/json/datatable-id-lang.json"
                },
                ajax: {
                    url:  '{{ route('studyProgram.list') }}',
                    data: function(data) {
                         data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'code', name: 'code', "width": "120px" },
                    { data: 'name', name: 'name', "width": "120px" },
                    { data: 'faculty', name: 'faculty', "width": "120px" },
                    { data: 'created_by_user', name: 'created_by_user', "width": "120px" },
                    { data: 'actions', name: 'actions', "width": "160px", orderable: false, searchable: false },
                ],
            });

            $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v =$(this).val();  // getting search input value
                studyProgramDatatable.columns(i).search(v).draw();
            } );
            $('.search-input-select').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                var i =$(this).attr('data-column');
                var v =$(this).val();
                studyProgramDatatable.columns(i).search(v).draw();
            } );
        });

    </script>
@endpush
