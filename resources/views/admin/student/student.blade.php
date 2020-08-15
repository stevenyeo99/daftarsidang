@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    {{ Form::open(array('url' => route('student.download'))) }}
                        <div class="custom-add-new-button">
                            @if (Gate::allows('is-admin')) 
                                <div class="button-top-upload-excel">
                                    {{ Form::button('<i class="fa fa-download"></i> Ekspor Melalui Excel', ['type' => 'submit', 'class' => 'btn btn-primary'] )  }}
                                </div>
                            @endif
                        </div>
                    {{ Form::close() }}

                    <div class="table-responsive">
                        <table id="student_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">NPM</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Angkatan</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Semester Pendaftaran Sidang</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Email</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nomor Telefon</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Program Studi</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Aksi</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::text('npm', null, ['id' => 'npm', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('name', null, ['id' => 'name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '1']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('generation', null, ['id' => 'generation', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '2']) }}
                                    </td>
                                    <td>
                                        {{ Form::select('semester', $semesters, null , ['id' => 'semester', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '3']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('email', null, ['id' => 'email', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '4']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('phone_number', null, ['id' => 'phone_number', 'class' => 'numeric-field width-100p form-control form-filter input-sm search-input-text', 'data-column' => '5']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('program_study', null, ['id' => 'program_study', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '6']) }}
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
            var studentDatatable = $('#student_datatable').DataTable(
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
                    url:  '{{ route('student.list') }}',
                    data: function(data) {
                         data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'npm', name: 'npm', "width": "120px" },
                    { data: 'name', name: 'name', "width": "120px" },
                    { data: 'generation', name: 'generation', "width": "120px" },
                    { data: 'semester', name: 'semester', "width": "120px" },
                    { data: 'email', name: 'email', "width": "160px" },
                    { data: 'phone_number', name: 'phone_number', "width": "120px" },
                    { data: 'study_program_name', name: 'study_program_name', "width": "120px" },
                    { data: 'actions', name: 'actions', "width": "160px" , orderable: false, searchable: false },
                ],
                createdRow: function (row, data, dataIndex) {
                    $( row ).find('td:eq(2)').addClass('break-word');
                }
            });

            $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v =$(this).val();  // getting search input value
                studentDatatable.columns(i).search(v).draw();
            } );
            $('.search-input-select').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                var i =$(this).attr('data-column');
                var v =$(this).val();
                studentDatatable.columns(i).search(v).draw();
            } );
        });

    </script>
@endpush
