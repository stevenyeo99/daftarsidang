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
                        {{-- <div class="button-top-upload-excel">
                            {{ Form::open(array('url' => route('suppliers.upload.excel'), 'files' => true)) }}
                                {!! Html::decode(Form::label('excelUploader', '<i class="fa fa-upload"></i> Impor Melalui Excel', ['class' => 'btn btn-warning'])) !!}
                                {{ Form::file('excelUploader', array('id'=>'excelUploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                            {{ Form::close() }}
                        </div> --}}
                        @if (Gate::allows('is-superadmin')) 
                            <a
                            href="{{ route('users.create') }}"
                            class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="user_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter">Nama pengguna</th>
                                    <th class="text-center vcenter">Email</th>
                                    <th class="text-center vcenter">Peran</th>
                                    <th class="text-center vcenter">Aksi</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::text('username', null, ['id' => 'username', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('email', null, ['id' => 'email', 'class' => 'form-control form-filter input-sm width-100p search-input-text', 'data-column' => '1']) }}
                                    </td>
                                    <td>
                                        {{ Form::select('role_name', $roles , null , ['id' => 'role_name', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '2']) }}
                                    </td>
                                    <td></td>
                                </tr>

                            </thead>
                            <tbody>
                            </tbody>
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
</section>
@endsection

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function() {
            var supplierDatatable = $('#user_datatable').DataTable(
            {
                'autoWidth' : false,
                orderCellsTop: true,
                responsive: true,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                language: {
                    'url': "/assets/json/datatable-id-lang.json"
                },
                ajax: {
                    url:  '{{ route('users.list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'username', name: 'username', "width": "120px" },
                    { data: 'email', name: 'email', "width": "120px" },
                    { data: 'role_name', name: 'role_name', "width": "120px" },
                    { data: 'actions', name: 'actions', "width": "160px", orderable: false, searchable: false },
                ],
                createdRow: function (row, data, dataIndex) {
                    $( row ).find('td:eq(1)').addClass('break-word');
                }
            });

            $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v =$(this).val();  // getting search input value
                supplierDatatable.columns(i).search(v).draw();
            } );
            $('.search-input-select').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                var i =$(this).attr('data-column');
                var v =$(this).val();
                supplierDatatable.columns(i).search(v).draw();
            } );
        });

    </script>
@endpush
