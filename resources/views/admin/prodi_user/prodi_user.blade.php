@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main Content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="custom-add-new-button">
                        <a href="{{ route('baak_prodi.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> User</a>
                        <a href="{{ route('baak_prodi.create_admin') }}" class="btn btn-success"><i class="fa fa-plus"></i> Admin</a>
                    </div>

                    <div class="table-responsive">
                        <table id="user_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter">NIP</th>
                                    <th class="text-center vcenter">Nama Pengguna</th>
                                    <th class="text-center vcenter">Nama</th>
                                    <th class="text-center vcenter">Email</th>
                                    <th class="text-center vcenter">Program Studi</th>
                                    <th class="text-center vcenter">Tipe Pengguna</th>
                                    <th class="text-center vcenter">Aksi</th>
                                </tr>

                                <tr>
                                    <td>
                                        {{ Form::text('nip', null, ['id' => 'nip', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('username', null, ['id' => 'username', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '1']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('nama', null, ['id' => 'nama', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '2']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('email', null, ['id' => 'email', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '3']) }}
                                    </td>

                                    <td>
                                        {{ Form::select('study_program', $listOfStudyProgramsDropDowns, null, ['id' => 'study_program', 'class' => 'width-100p form-control form-filter select2 search-input-select', 'data-column' => '4', 'placeholder' => 'all']) }}
                                    </td>

                                    <td>
                                        {{ Form::select('type', ['0' => 'Dosen', '1' => 'Admin'], null, ['id' => 'type', 'class' => 'width-100p form-control form-filter select2 search-input-select', 'data-column' => '5', 'placeholder' => 'all']) }}
                                    </td>

                                    <td>

                                    </td>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function() {
            var dosenDatatable = $('#user_datatable').DataTable(
            {
                'autoWidth': false,
                orderCellsTop: true,
                responsive: true,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                language: {
                    'url': "/assets/json/datatable-id-lang.json"
                },
                ajax: {
                    url: '{{ route('admin.study_program.user.list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'nip', name: 'nip', "width": "100px" },
                    { data: 'username', name: 'username', "width": "120px" },
                    { data: 'nama', name: 'nama', "width": "120px" },
                    { data: 'email', name: 'email', "width": "120px" },
                    { data: 'study_program', name: 'study_program', "width": "120px" },
                    { data: 'type', name: 'type', "width": "100px" },
                    { data: 'actions', name: 'actions', "width": "160px", orderable: false, searchable: false },
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(1)').addClass('break-word');
                }
            });

            $('.search-input-text').on('keyup click', function() {
                var i = $(this).attr('data-column');
                var v = $(this).val();
                dosenDatatable.columns(i).search(v).draw();
            });

            $('.search-input-select').on('change', function() {
                var i = $(this).attr('data-column');
                var v = $(this).val();
                dosenDatatable.columns(i).search(v).draw();
            });
        });
    </script>
@endpush