@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main Content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    
                        <div class="custom-add-new-button" style="display: flex; justify-content: flex-end;">
                            {{ Form::open(array('url' => route('hardcover_kp.import'), 'enctype' => 'multipart/form-data', 'style' => 'margin-right: 5px;')) }}
                                @if(Gate::allows('is-library-admin') || Gate::allows('is-library-user'))
                                    {!! Html::decode(Form::label('hardcover_kp_import', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-success'])) !!}                           
                                    {{ Form::file('hardcover_kp_import', ['id' => 'hardcover_kp_import', 'class' => 'hide', 'onchange' => 'this.form.submit()', 'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])}}                                                        
                                @endif
                            {{ Form::close() }}

                            {{ Form::open(array('url' => route('library.hardcoverkp.download'))) }}
                                @if(Gate::allows('is-library-admin') || Gate::allows('is-library-user'))
                                    {{ Form::button('<i class="fa fa-download"></i> Ekspor Melalui Excel', ['type' => 'submit', 'class' => 'btn btn-primary', 'style' => 'display: inline-block;']) }}                                    
                                @endif
                            {{  Form::close() }}
                        </div>
                    
                    <br>
                    <div class="table-responsive">
                        <table id="hardcover_kp_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Nama Mahasiswa</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">NPM</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Prodi</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Nama Pembimbing</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Tanggal Submit</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Tanggal Validasi</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Diupload oleh</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px"></th>
                                </tr>

                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::text('nama_mahasiswa', null, ['id' => 'nama_mahasiswa', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('npm', null, ['id' => 'npm', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'placeholder' => '', 'data-column' => '1']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('prodi', null, ['id' => 'prodi', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'placeholder' => '', 'data-column' => '2']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('nama_pembimbing', null, ['id' => 'nama_pembimbing', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'placeholder' => '', 'data-column' => '3']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('tanggal_submit', null, ['id' => 'tanggal_submit', 'class' => 'width-100p form-control form-filter input-sm date-input', 'placeholder' => '', 'data-column' => '4', 'readonly' => true]) }}
                                    </td>

                                    <td>
                                        {{ Form::text('tanggal_validasi', null, ['id' => 'tanggal_validasi', 'class' => 'width-100p form-control form-filter input-sm date-input', 'placeholder' => '', 'data-column' => '5', 'readonly' => true]) }}
                                    </td>

                                    <td>
                                        {{ Form::text('created_by_user', null, ['id' => 'created_by_user', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'placeholder' => '', 'data-column' => '6'])}}
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
        $(document).ready(function() 
        {
            var hardcover_kp_datatable = $('#hardcover_kp_datatable').DataTable(
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
                    url: '{{ route('hardcover_kp.list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    {data: 'nama_mahasiswa', name: 'nama_mahasiswa'},
                    {data: 'npm', name: 'npm'},
                    {data: 'prodi', name: 'prodi'},
                    {data: 'nama_pembimbing', name: 'nama_pembimbing'},
                    {data: 'tanggal_submit', type: 'num', render: {
                            _: 'display',
                            sort: 'date'
                        }
                    },
                    {data: 'tanggal_validasi', type: 'num', render: {
                            _: 'display',
                            sort: 'date'
                        }
                    },
                    {data: 'created_by_user', name: 'created_by_user'},
                    {data: 'actions', name: 'actions', "width": "85px", orderable: false, searchable: false},
                ]
            });

            $('.search-input-text').on('keyup click', function() { // for text boxes
                var i = $(this).attr('data-column'); // getting column index
                var v = $(this).val(); // getting search input value
                hardcover_kp_datatable.columns(i).search(v).draw();
            });  
            
            // initialize date picker
            $('.date-input').datepicker({
                format: 'd-M-yyyy',
            }).on('keyup', function(e) {
                if(e.keyCode == 8 || e.keyCode == 46) {
                    $(this).datepicker('setDate', null);
                }
            }).on('change', function() {
                var i = $(this).attr('data-column');
                var v= $(this).val();
                hardcover_kp_datatable.columns(i).search(v).draw();
            });       
        });
    </script>
@endpush