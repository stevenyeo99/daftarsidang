@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    {{ Form::open(array('url' => route('request.kp.download'))) }}
                        <div class="custom-add-new-button">
                            @if (Gate::allows('is-admin')) 
                                <div class="button-top-upload-excel">
                                        {{ Form::button('<i class="fa fa-download"></i> Ekspor Melalui Excel', ['type' => 'submit', 'class' => 'btn btn-primary'] )  }}
                                </div>
                            @endif
                        </div>
                    {{ Form::close() }}
                        
                    <div class="table-responsive">
                        <table id="kp_request_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">NPM</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Angkatan</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Program Studi</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Judul</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Status Pendaftaran</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Status Sidang</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Email</th>
                                    {{-- <th class="text-center vcenter" rowspan="1" colspan="1">Jenis Karya</th> --}}
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
                                        {{ Form::text('program_study', null, ['id' => 'program_study', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '3']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('title', null, ['id' => 'title', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '4']) }}
                                    </td>
                                    <td>
                                        {{ Form::select('status', $statuses, $defaultStatusSelection, ['id' => 'status', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '5']) }}
                                    </td>
                                    <td>
                                        {{ Form::select('session_status_status', $session_statuses, null, ['id' => 'session_status_status', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '6']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('email', null, ['id' => 'email', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '7']) }}
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
            var defaultStatusSelection = {!! $defaultStatusSelection !!};

            var kpRequestDatatable = $('#kp_request_datatable').DataTable(
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
                    url:  '{{ route('kp_record.list') }}',
                    data: function(data) {
                         data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'npm', name: 'npm', "width": "80px" },
                    { data: 'name', name: 'name', "width": "120px" },
                    { data: 'generation', name: 'generation', "width": "80px" },
                    { data: 'study_program_name', name: 'study_program_name', "width": "120px" },
                    { data: 'title', name: 'title', "width": "120px" },
                    { data: 'status', name: 'status', "width": "80px" },
                    { data: 'session_status_status', name: 'session_status_status', "width": "80px" },
                    { data: 'email', name: 'email', "width": "160px" },
                    // { data: 'type', name: 'type', "width": "80px" },
                    { data: 'actions', name: 'actions', "width": "160px" , orderable: false, searchable: false },
                ],
                searchCols: [ // each row refers to columns' index, and search for value
                    null,
                    null,
                    null,
                    null,
                    null,
                    { 'search': defaultStatusSelection },
                    null,
                    null,
                    // null,
                ],
                createdRow: function (row, data, dataIndex) {
                    $( row ).find('td:eq(6)').addClass('break-word');
                }
            });

            $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v =$(this).val();  // getting search input value
                kpRequestDatatable.columns(i).search(v).draw();
            } );

            $('.search-input-select').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                var i =$(this).attr('data-column');
                var v =$(this).val();
                kpRequestDatatable.columns(i).search(v).draw();
            } );
        });

    </script>
@endpush
