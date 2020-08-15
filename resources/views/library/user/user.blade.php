@extends('layouts.master')

@section('content')
@include('shared.page_header')
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="custom-add-new-button">
                        @if(Gate::allows('is-library-admin'))
                            <a href="{{ route('library_staff.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Staff Baru</a>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table id="user_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter">Nama pengguna</th>
                                    <th class="text-center vcenter">Email</th>
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
        var libaryUserDatatable = $('#user_datatable').DataTable({
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
                    url: '{{ route('library_staff.list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'username', name: 'username', "width": "120px" },
                    { data: 'email', name: 'email', "width": "120px" },
                    { data: 'actions', name: 'actions', "width": "160px", orderable: false, searchable: false },
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(1)').addClass('break-word');
                }
        });

         $('.search-input-text').on('keyup click', function() {
            var i = $(this).attr('data-column');
            var v = $(this).val();
            libaryUserDatatable.columns(i).search(v).draw();
        });
    });
</script>
@endpush