@extends('layouts.master')

@section('content')
@include('shared.page_header')
<section classs="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="custom-add-new-button">
                        @if (Gate::allows('is-admin'))
                            <a 
                            href="{{ route('ruangan.create') }}"
                            class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </a>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table id="dtRuanganSidang" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Gedung</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Ruangan</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Dibuat oleh</th>
                                    <th class="text-center vcenter">Aksi</th>
                                </tr>

                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::select('gedung', ['A' => 'Gedung A UIB', 'B' => 'Gedung B UIB'], null, ['class' => 'width-100p form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '0'])}}
                                    </td>

                                    <td>
                                        {{ Form::text('ruangan', null, ['class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '1'])}}
                                    </td>

                                    <td>
                                        {{ Form::text('created_by_user', null, ['class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '2'])}}
                                    </td>

                                    <td></td>
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
            var dtRuanganSidang = $('#dtRuanganSidang').DataTable({
                'autoWidth': false,
                orderCellsTop: true,
                responsive: false,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                language: {
                    'url': '/assets/json/datatable-id-lang.json'
                }, 
                ajax: {
                    url: '{{ route('ruanganSidang.list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'gedung', name: 'gedung', 'width': '120px' },
                    { data: 'ruangan', name: 'ruangan', 'width': '120px'},
                    { data: 'created_by_user', name: 'created_by_user', 'width': '120px'},
                    { data: 'actions', name: 'actions', 'width': '160px', orderable: false, searchable: false},
                ],
                searchCols: [
                    null,
                    null,
                    null,
                    null,
                ]
            });

            $('.search-input-text').on('keyup click', function() {
                var i = $(this).attr('data-column');
                var v = $(this).val();
                dtRuanganSidang.columns(i).search(v).draw();
            });

            $('.search-input-select').on('change', function() {
                var i = $(this).attr('data-column');
                var v = $(this).val();
                dtRuanganSidang.columns(i).search(v).draw();
            });
        });
    </script>
@endpush
