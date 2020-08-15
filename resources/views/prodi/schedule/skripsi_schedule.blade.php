@extends('layouts.master')

@section('content')
@include('shared.page_header')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Penjadwalan</h3>

                    <div class="box-tools pull-right">
                         {{-- <button type="button" class="btn btn-box-tool"> --}}
                            <a class="btn btn-box-tool blue-col" href="{{ route('prodi.penjadwalan.skripsi') }}">
                                <i class="fa fa-arrow-left"></i>
                                Kembali
                            </a>
                        {{-- </button> --}}
                    </div>
                </div>

                {{ Form::open(['url' => route('prodi.penjadwalan.assign.skripsi', $penjadwalan->id), 'id' => 'frm']) }}
                    <div class="box-body">
                        <div class="form-group">
                            {{ Form::label('Dosen Pembimbing') }}
                            <span class="text-red">*</span>
                            {{ Form::text('dosen_pembimbing_id', $pembimbingValue, array('class' => 'width-100p form-control control', 'readonly' => true)) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Dosen Penguji') }}
                            <span class="text-red">*</span>
                            @if($penjadwalan->dosen_penguji_id != null && $penjadwalan->dosen_penguji_id != 0)
                                {{ Form::select('dosen_penguji_id', $listOfProdiDDL, $penjadwalan->dosen_penguji_id, array('id' => 'dosen_penguji_id', 'class' => 'width-100p form-control select2', 'placeholder' => 'Kosong...'))}}
                            @else
                                {{ Form::select('dosen_penguji_id', $listOfProdiDDL, null, array('id' => 'dosen_penguji_id', 'class' => 'width-100p form-control select2', 'placeholder' => 'Kosong...'))}}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('Tanggal-Waktu Sidang') }}
                            <div style="position: relative;">
                                @if($penjadwalan->tanggal_sidang != null)
                                     <input type="text" value="{{ $penjadwalan->tanggal_sidang }}" id="tanggal_sidang" name="tanggal_sidang" class="width-100p form-control" readonly>
                                @else
                                    <input type="text" value="{{ Input::old('tanggal_sidang') }}" id="tanggal_sidang" name="tanggal_sidang" class="width-100p form-control" readonly>
                                @endif
                            </div>
                        </div>
                        
                        @if($penjadwalan->dosen_pembimbing_backup == null || $penjadwalan->dosen_pembimbing_backup == 0)
                            <div class="form-group" id="backUpPlan" style="display: none;">
                                {{ Form::label('Dosen Pembimbing Backup') }}
                                {{ Form::select('dosen_pembimbing_backup_id', $listOfProdiDDL, null, array('id' => 'dosen_pembimbing_backup_id', 'class' => 'width-100p form-control select2', 'placeholder' => 'kosong...'))  }}
                            </div>
                        @else
                            <div class="form-group" id="backUpPlan">
                                {{ Form::label('Dosen Pembimbing Backup') }}
                                {{ Form::select('dosen_pembimbing_backup_id', $listOfProdiDDL, $penjadwalan->dosen_pembimbing_backup, array('id' => 'dosen_pembimbing_backup_id', 'class' => 'width-100p form-control select2', 'placeholder' => 'kosong...'))  }}
                            </div>
                        @endif
                    </div>

                    <div class="box-footer">
                        {{ Form::button('Jadwalkan', array('id' => 'btnSubmit', 'class' => 'btn btn-primary'))}}
                        &nbsp;
                        @if($penjadwalan->dosen_pembimbing_backup != null && $penjadwalan->dosen_pembimbing_backup != 0)
                            <input type="checkbox" style="cursor: pointer;" id="dospem_backup_required" checked name="dospem_backup_required" value="YES">
                        @else
                            <input type="checkbox" style="cursor: pointer;" id="dospem_backup_required" name="dospem_backup_required" value="YES">
                        @endif
                        <label for="dospem_backup_required" style="vertical-align: middle; cursor: pointer;">
                            Peganti Dosen Pembimbing
                        </label>
                    </div>
                    <input type="hidden" readonly id="txtIsRevision" value="{{ $isRevision }}">
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom_js')
    <script type="text/javascript">
        var oldDosenPengujiId;
        var oldTanggalWaktuSidang;
        var oldDosenPembimbingBackUpId;
        var isRevision;
        var isBAK;
        $(document).ready(function() {
            setDatetimePicker();
            bindCheckNeedDospemBackupPlan();
            triggerBtnJadwal();
            setGlobalAttributeForLaterWhenValidateGotChangesOrNot();
        });

        function setGlobalAttributeForLaterWhenValidateGotChangesOrNot() {
            isRevision = $('#txtIsRevision').val();

            if(isRevision === 'YES') {
                oldDosenPengujiId = $('#dosen_penguji_id').children('option:selected').val();
                oldTanggalWaktuSidang = $('#tanggal_sidang').val();
                oldDosenPembimbingBackUpId = $('#dosen_pembimbing_backup_id').children('option:selected').val();
                isBAK = $('#dospem_backup_required').is(':checked');
            }
        }

        // initialize tanggal sidang date time picker
        function setDatetimePicker() {
            var defaultValue = $('#tanggal_sidang').val();
            var date = new Date();
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            var dates = ''+year+'-'+month+'-'+day+'';
            var minDate = new Date(dates).setDate(new Date(dates).getDate() + 5);
            if(defaultValue !== '') {
                minDate = new Date(dates).setDate(new Date(dates).getDate() + 3);
            }
            $('#tanggal_sidang').datetimepicker(
                {
                ignoreReadonly: true,
                format: 'DD-MM-YYYY hh:mm A',
                minDate: minDate,
            }
            );
            $('#tanggal_sidang').val(defaultValue);
        }

        // toggle check box for asign dospem backup
        function bindCheckNeedDospemBackupPlan() {
            $('#dospem_backup_required').click(function() {
                var check = $(this);
                if(check.prop('checked') === true) {
                    $('#backUpPlan').show();
                } else {
                    $('#backUpPlan').hide();
                }
            });
        }

        function triggerBtnJadwal() {
            $('#btnSubmit').click(function() {
                // do validation here before submit

                // do check dospem is select same with dospenguji
                var dospengujiId = $('#dosen_penguji_id').children('option:selected').val();
                if(dospengujiId === '0' || dospengujiId === '') {
                    alert('Harap pilih dosen penguji yang akan dijadwalkan!');
                    return false;
                }

                var tanggal_sidang = $('#tanggal_sidang');
                if(tanggal_sidang.val() === '') {
                    alert('Harap pilih waktu sidang yang akan dijadwalkan!');
                    return false;
                }

                var check = $('#dospem_backup_required');
                var dospem_backup_id;      
                if(check.prop('checked')) {
                    dospem_backup_id = $('#dosen_pembimbing_backup_id').children('option:selected').val();

                    if(dospem_backup_id === '0' || dospem_backup_id === '') {
                        alert('Harap pilih dosen pembimbing yang akan digantikan!');
                        return false;
                    }
                }

                if(dospem_backup_id !== '0') {
                    if(dospengujiId === dospem_backup_id) {
                        alert('Dosen penguji dan dosen pembimbing pegganti tidak boleh sama!');
                        return false;
                    }
                } 

                var changeCount = 0;
                if(isRevision === 'YES') {
                    // validate dosen penguji same as previous or not
                    if(oldDosenPengujiId !== dospengujiId) {
                        changeCount++;
                    }

                    if(oldTanggalWaktuSidang !== tanggal_sidang.val()) {
                        changeCount++;
                    }

                   if(isBAK === false && check.is(':checked') === true) { // when beggining is not use then change to use dospem backup
                        changeCount++;
                    } else if(isBAK === true && check.is(':checked') == false) { // when beggining is use but change to not use dospem backup
                        changeCount++;
                    }                    
                }
                
                if(changeCount === 0 && isRevision === 'YES') {
                    alert('Jika tidak ada data yang terubah saat melakukan penjadwalan ulang, tidak perlu untuk memproses form berikut.');
                    return false;
                }

                var confirm = window.confirm('Apakah penjadwalan sudah pasti betul sebelum dijadwalkan ?');
                if(!confirm) {
                    return false;
                }

                // do submit the form
                $('#frm').submit();
            });
        }
    </script>
@endpush