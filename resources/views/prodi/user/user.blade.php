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
                        @if (Gate::allows('is-prodi-admin'))
                            <a href="{{ route('dosen.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Dosen Baru</a>
                            <a href="{{ route('dosen.assign') }}" class="btn btn-success"><i class="fa fa-plus"></i> Dosen Prodi Lain</a>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table id="user_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter">Nama pengguna</th>
                                    <th class="text-center vcenter">Email</th>
                                    <th class="text-center vcenter">Prodi</th>
                                    <th class="text-center vcenter">Aksi</th>
                                </tr>

                                <tr>
                                    <td>
                                        {{ Form::text('username', null, ['id' => 'username', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('email', null, ['id' => 'email', 'class' => 'form-control form-filter input-sm width-100p search-input-text', 'data-column' => '1'])}}
                                    </td>

                                    <td>

                                    </td>

                                    <td>

                                    </td>
                                </tr>
                            </thead>

                            <tbody></tbody>
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
                    url: '{{ route('dosen.list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'username', name: 'username', "width": "120px" },
                    { data: 'email', name: 'email', "width": "120px" },
                    { data: 'prodi_name', name: 'prodi_name', "width": "120px", orderable: false, searchable: false  },
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
        });
    </script>
@endpush