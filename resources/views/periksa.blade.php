@extends('index')

@section('content')

    <section class="section">

        {{-- laravel show error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong></strong> Terjadi Error : <br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="row">

            <!-- easyui datagrid table -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table id="dg" class="easyui-datagrid mt-2" style="width:99%;height:500px" singleSelect="true">
                            <thead frozen="true">
                                <tr>
                                    <th data-options='field:"code", sortable:true' width="15%">Nomor</th>
                                    <th data-options='field:"employees.name", sortable:true' width="15%">Nama Pegawai
                                    </th>
                                    <th data-options='field:"doctors.name", sortable:true' width="10%">Nama Dokter</th>
                                    <th data-options='field:"check_type", sortable:true' width="10%">Jenis Periksa</th>
                                    <th data-options='field:"date", sortable:true' width="10%">Tgl Periksa</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th data-options='field:"tinggi", sortable:true' width="10%">Tinggi Badan(cm)</th>
                                    <th data-options='field:"berat", sortable:true' width="10%">Berat Badan(kg)</th>
                                    <th data-options='field:"suhu", sortable:true' width="10%">Suhu Tubuh(c)</th>
                                    <th data-options='field:"tekanan", sortable:true' width="10%">Tekanan Darah</th>
                                    <th data-options='field:"asam_urat", sortable:true' width="10%">Asam Urat</th>
                                    <th data-options='field:"kolesterol", sortable:true' width="10%">Kolesterol</th>
                                    <th data-options='field:"hasil", sortable:true' width="20%">Hasil Pemeriksaan Dasar
                                    </th>
                                    <th data-options='field:"description", sortable:true' width="20%">Pemeriksaan Fisik
                                    </th>
                                    <th data-options='field:"diagnosis", sortable:true' width="20%">Diagnosis</th>
                                    <th data-options='field:"therapy", sortable:true' width="20%">Terapi</th>
                                    <th data-options='field:"nextdate", sortable:true' width="20%">Tanggal Periksa
                                        Selanjutnya</th>
                                    <th data-options='field:"option"' width="20%">Opsi</th>
                                </tr>
                            </thead>
                        </table>
                        <div id="toolbar">
                            @if (in_array(1, $user_roles))
                                <a class="btn btn-info btn-sm m-1" data-bs-toggle="modal" data-bs-target="#modal_add">Tambah
                                    Data Periksa</a>
                                <a class="btn btn-outline-info btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modal_rm">Rekam Medis</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- modal add --}}
        <form method="POST" action="{{ route('periksa_submit') }}" autocomplete="off" id="form_add">
            <div class="modal fade" id="modal_add" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data Pemeriksaan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-1 div-error" style="display: none">
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                        aria-label="Danger:">
                                        <use xlink:href="#exclamation-triangle-fill" />
                                    </svg>
                                    <div class="message-error"></div>
                                </div>
                            </div>

                            @csrf

                            <div class="border mb-3 p-3">
                                <h5 class="modal-title text-bold"><b>DATA PEGAWAI</b></h5>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Nama Pegawai*</label><br>
                                            <select class="form-control" name="employee_id" id="employee_id" required>
                                                <option value="">Pilih Pegawai</option>
                                                @foreach ($employee as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Nama Dokter*</label>
                                            <select class="form-control" name="doctor_id" id="doctor_id" required>
                                                <option value="">Pilih Dokter</option>
                                                @foreach ($doctor as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Jenis Periksa*</label>
                                            <select class="form-control" name="check_type" id="check_type" required>
                                                <option value="">Pilih Jenis Periksa</option>
                                                <option value="Periksa">Periksa</option>
                                                <option value="Non Periksa">Non Periksa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Tanggal Periksa*</label>
                                            <input id="date" class="form-control" type="date" name="date"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border mb-3 p-3">
                                <h5 class="modal-title text-bold"><b>PEMERIKSAAN DASAR</b></h5>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Tinggi Badan*(cm)</label>
                                            <input id="tinggi" class="form-control" type="input" name="tinggi"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Berat Badan*(kg)</label>
                                            <input id="berat" class="form-control" type="input" name="berat"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Suhu Badan*(c)</label>
                                            <input id="suhu" class="form-control" type="input" name="suhu"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Tekanan Darah*(mmHg)</label>
                                            <input id="tekanan" class="form-control" type="input" name="tekanan"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Asam Urat*((mg/dL))</label>
                                            <input id="asam_urat" class="form-control" type="input" name="asam_urat"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Kolesterol*</label>
                                            <input id="kolesterol" class="form-control" type="input" name="kolesterol"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Hasil Pemeriksaan Dasar*</label>
                                            <input id="hasil" class="form-control" type="input" name="hasil"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border mb-3 p-3">
                                <h5 class="modal-title text-bold"><b>PEMERIKSAAN LANJUTAN</b></h5>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Pemeriksaan Fisik</label>
                                            <textarea id="description" class="form-control" name="description"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Diagnosis</label>
                                            <textarea id="diagnosis" class="form-control" name="diagnosis"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Terapi</label>
                                            <textarea id="therapy" class="form-control" name="therapy"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Tanggal Periksa Selanjutnya</label>
                                            <input id="nextdate" class="form-control" type="date" name="nextdate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-info" id="btn_save">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- modal edit --}}
        <form method="POST" action="{{ route('periksa_update') }}" autocomplete="off" id="form_edit">
            <div class="modal fade" id="modal_edit" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Pemeriksaan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-1 div-error" style="display: none">
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                        aria-label="Danger:">
                                        <use xlink:href="#exclamation-triangle-fill" />
                                    </svg>
                                    <div class="message-error"></div>
                                </div>
                            </div>

                            <input type="hidden" name="id" id="id">

                            @csrf

                            <div class="border mb-3 p-3">
                                <h5 class="modal-title text-bold"><b>DATA PEGAWAI</b></h5>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Nama Pegawai*</label>
                                            <select class="form-control" name="employee_id" id="employee_id" required>
                                                <option value="">Pilih Pegawai</option>
                                                @foreach ($employee as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Nama Dokter*</label>
                                            <select class="form-control" name="doctor_id" id="doctor_id" required>
                                                <option value="">Pilih Dokter</option>
                                                @foreach ($doctor as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Jenis Periksa*</label>
                                            <select class="form-control" name="check_type" id="check_type" required>
                                                <option value="">Pilih Jenis Periksa</option>
                                                <option value="Periksa">Periksa</option>
                                                <option value="Non Periksa">Non Periksa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Tanggal Periksa*</label>
                                            <input id="date" class="form-control" type="date" name="date"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border mb-3 p-3">
                                <h5 class="modal-title text-bold"><b>PEMERIKSAAN DASAR</b></h5>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Tinggi Badan*(cm)</label>
                                            <input id="tinggi" class="form-control" type="input" name="tinggi"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Berat Badan*(kg)</label>
                                            <input id="berat" class="form-control" type="input" name="berat"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Suhu Badan*(c)</label>
                                            <input id="suhu" class="form-control" type="input" name="suhu"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Tekanan Darah*(mmHg)</label>
                                            <input id="tekanan" class="form-control" type="input" name="tekanan"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Asam Urat*(mg/dL)</label>
                                            <input id="asam_urat" class="form-control" type="input" name="asam_urat"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="">Kolesterol*</label>
                                            <input id="kolesterol" class="form-control" type="input" name="kolesterol"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Hasil Pemeriksaan Dasar*</label>
                                            <input id="hasil" class="form-control" type="input" name="hasil"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border mb-3 p-3">
                                <h5 class="modal-title text-bold"><b>PEMERIKSAAN LANJUTAN</b></h5>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Pemeriksaan Fisik</label>
                                            <textarea id="description" class="form-control" name="description"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Diagnosis</label>
                                            <textarea id="diagnosis" class="form-control" name="diagnosis"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Terapi</label>
                                            <textarea id="therapy" class="form-control" name="therapy"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="">Tanggal Periksa Selanjutnya*</label>
                                            <input id="nextdate" class="form-control" type="date" name="nextdate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-info" id="btn_save">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- modal rekam medis  --}}
        <div class="modal" tabindex="-1" id="modal_rm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rekam Medis Pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="">Nama Pegawai*</label>
                            <select class="form-control" id="pdf_employee_id" required>
                                <option value="">Pilih</option>
                                @foreach ($employee as $item)
                                    <option value="{{ $item->akses }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-info" onclick="pdf_rm()">Lihat PDF</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- easyui js datagrid -->
        <script type="text/javascript">
            // js window onload
            $(function() {
                get_datagrid();
            });

            function get_datagrid() {

                $('#dg').datagrid({
                    url: "{{ route('periksa_data') }}",
                    method: 'get',
                    rownumbers: true,
                    singleSelect: true,
                    pagination: true,
                    fitColumns: true,
                    toolbar: '#toolbar',
                    pageSize: 10,
                    remoteFilter: true,
                    defaultFilterType: 'label'
                });

                // datagrid filter 
                $('#dg').datagrid('enableFilter', [{
                        field: 'code',
                        type: 'textbox',
                        'placeholder': 'Kode'
                    },
                    {
                        field: 'employees.name',
                        type: 'textbox'
                    },
                    {
                        field: 'doctors.name',
                        type: 'textbox'
                    },
                    {
                        field: 'check_type',
                        type: 'textbox'
                    },
                    {
                        field: 'date',
                        type: 'textbox'
                    },
                    {
                        field: 'tinggi',
                        type: 'textbox'
                    },
                    {
                        field: 'berat',
                        type: 'textbox'
                    },
                    {
                        field: 'suhu',
                        type: 'textbox'
                    },
                    {
                        field: 'tekanan',
                        type: 'textbox'
                    },
                    {
                        field: 'asam_urat',
                        type: 'textbox'
                    },
                    {
                        field: 'kolesterol',
                        type: 'textbox'
                    },
                    {
                        field: 'hasil',
                        type: 'textbox'
                    },
                    {
                        field: 'description',
                        type: 'textbox'
                    },
                    {
                        field: 'diagnosis',
                        type: 'textbox'
                    },
                    {
                        field: 'therapy',
                        type: 'textbox'
                    },
                    {
                        field: 'nextdate',
                        type: 'textbox'
                    }
                ]);

            }

            // js submit form add
            $('#form_add').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('periksa_submit') }}",
                    method: "POST",
                    data: $('#form_add').serialize(),
                    dataType: "JSON",
                    success: function(data) {

                        if (data.status == false) {

                            var message_error = '';
                            $.each(data.message, function(key, value) {
                                message_error += value + '<br>';
                            });

                            $('#modal_add').find('.div-error').show()
                            $('#modal_add').find('.message-error').html('')
                            $('#modal_add').find('.message-error').html(message_error);

                        } else {

                            alert(data.message);

                            $('#modal_add').modal('hide');
                            $('#dg').datagrid('reload');
                            $('#form_add').trigger('reset');
                        }
                    }
                });
            });

            // delete data
            function remove(id) {
                $.messager.confirm('Confirm', 'Anda yakin hapus data ini?', function(r) {
                    if (r) {
                        $.ajax({
                            url: "master-obat-delete/" + id,
                            method: 'get',
                            dataType: 'json',
                            data: {},
                            success: function(response) {
                                alert(response.message);
                                $('#dg').datagrid('reload');
                            }
                        });
                    }
                });
            }

            // edit data
            function edit(id) {
                $.ajax({
                    url: "periksa-edit/" + id,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {

                        var modal = $('#modal_edit');

                        modal.find('.div-error').hide();

                        modal.modal('show');

                        modal.find('#id').val(response.id);
                        modal.find('#employee_id').val(response.employee_id);
                        modal.find('#doctor_id').val(response.doctor_id);
                        modal.find('#check_type').val(response.check_type);
                        modal.find('#date').val(response.date);
                        modal.find('#tinggi').val(response.tinggi);
                        modal.find('#berat').val(response.berat);
                        modal.find('#suhu').val(response.suhu);
                        modal.find('#tekanan').val(response.tekanan);
                        modal.find('#asam_urat').val(response.asam_urat);
                        modal.find('#kolesterol').val(response.kolesterol);
                        modal.find('#hasil').val(response.hasil);
                        modal.find('#description').val(response.description);
                        modal.find('#diagnosis').val(response.diagnosis);
                        modal.find('#therapy').val(response.therapy);
                        modal.find('#nextdate').val(response.nextdate);
                    }
                });
            }

            // js submit form edit
            $('#form_edit').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('periksa_update') }}",
                    method: "POST",
                    data: $('#form_edit').serialize(),
                    dataType: "JSON",
                    success: function(data) {

                        if (data.status == false) {

                            var message_error = '';
                            $.each(data.message, function(key, value) {
                                message_error += value + '<br>';
                            });

                            $('#modal_edit').find('.div-error').show()
                            $('#modal_edit').find('.message-error').html('')
                            $('#modal_edit').find('.message-error').html(message_error);

                        } else {

                            alert(data.message);

                            $('#modal_edit').modal('hide');
                            $('#dg').datagrid('reload');
                            $('#form_edit').trigger("reset");
                        }
                    }
                });
            });

            function pdf_rm() {
                var employee_id = $('#pdf_employee_id').val();

                if (employee_id == '') {
                    alert('Pilih nama pegawai');
                    return false;
                } else {
                    var url = @json(url('/pdf-check/rm/')) + '/' + employee_id;
                    // Now, you can use the 'url' variable in your JavaScript code.
                    // For example, you can use it in a JavaScript function or for opening a new window.
                    // Here's how to open a new window with the URL:
                    window.open(url);
                }
            }
        </script>

        @push('script')
            <style>
                /* Smaller height, padding, and font size */
                .select2-container--default .select2-selection--single {
                    height: 34px !important;
                    padding: 6px 6px;
                    font-size: 16px;
                    line-height: 1.5;
                    border-radius: 4px;
                    border: 1px solid #ccc;
                }

                /* Adjust the arrow position */
                .select2-container--default .select2-selection--single .select2-selection__arrow b {
                    top: 40% !important;
                }

                /* Adjust the line height of the rendered value */
                .select2-container--default .select2-selection--single .select2-selection__rendered {
                    line-height: 20px !important;
                }

                /* Adjust focus border */
                .select2-container--default.select2-container--focus .select2-selection--single {
                    border-color: #007bff;
                }
            </style>

            <script>
                $(function() {
                    $('#employee_id').select2({
                        dropdownParent: $('#modal_add'),
                        dropdownAutoWidth: true,
                        width: "100%"
                    });
                });
            </script>
        @endpush
    @endsection
