@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main Content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="dtBeritaAcaraSkripsi" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">NPM</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Judul</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Ruangan Sidang</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Aksi</th>
                                </tr>

                                <tr role="row">
                                    <td>
                                        {{ Form::text('npm', null, array('id' => 'npm', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0')) }}
                                    </td>

                                    <td>
                                        {{ Form::text('name', null, array('id' => 'name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '1')) }}
                                    </td>

                                    <td>
                                        {{ Form::text('judul', null, array('id' => 'title', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '2') )}}
                                    </td>

                                    <td>
                                        {{ Form::select('ruangan_sidang', $ruanganSidangArr, null, array('id' => 'ruangan_sidang', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'data-column' => '3', 'placeholder' => 'All')) }}
                                    </td>

                                    <td>
                                        <!-- prototype berita acara form -->
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end main content -->
@endsection

@push('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        var dtBeritaAcaraSkripsi = $('#dtBeritaAcaraSkripsi').DataTable({
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
                url: '{{ route('prodi.berita_acara_skripsi_list') }}',
                data: function(data) {
                    data._token = '{{ csrf_token() }}';
                },
                type: 'POST',
            }, 
            columns: [
                { data: 'npm', name: 'npm', "width": "80px" },
                { data: 'name', name: 'name', "width": "120px" },
                { data: 'title', name: 'title', "width": "150px" },
                { data: 'ruangan_sidang', name: 'ruangan_sidang', "width": "120px" },
                { data: 'actions', name: 'actions', "width": "50px", orderable: false, searchable: false }
            ]
        });

        $('.search-input-text').on('keyup click', function() {
            var i = $(this).attr('data-column');
            var v = $(this).val();
            dtBeritaAcaraSkripsi.columns(i).search(v).draw();
        });

        $('.search-input-select').change(function() {
            var i = $(this).attr('data-column');
            var v = $(this).val();
            dtBeritaAcaraSkripsi.columns(i).search(v).draw();
        });
    });
</script>
@endpush