@extends('index')

@section('content')
    <section class="section">

        <div class="row">

            <!-- easyui datagrid table -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body mt-2">
                        <table id="dg" class="easyui-datagrid mt-2" style="width:100%;height:500px" singleSelect="true">
                            <thead frozen="true">
                                <tr>
                                    <th data-options="field:'name', sortable:true" width="15%">Nama</th>
                                    <th data-options="field:'nip', sortable:true" width="15%">NIP</th>
                                    <th data-options="field:'data_type', sortable:true" width="10%">Tipe Data</th>
                                    <th data-options="field:'main_data_name', sortable:true" width="15%">Data Utama</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th data-options="field:'place_of_birth', sortable:true" width="10%">Tempat Lahir
                                    </th>
                                    <th data-options="field:'date_of_birth', sortable:true" width="15%">Tanggal Lahir
                                    </th>
                                    <th data-options="field:'religion', sortable:true" width="10%">Agama</th>
                                    <th data-options="field:'gender', sortable:true" width="10%">Jenis Kelamin</th>
                                    <th data-options="field:'address', sortable:true" width="15%">Alamat</th>
                                    <th data-options="field:'blood_type', sortable:true" width="10%">Golongan Darah</th>
                                    <th data-options="field:'marital_status', sortable:true" width="10%">Status
                                        Pernikahan</th>
                                    <th data-options="field:'education', sortable:true" width="10%">Pendidikan</th>
                                    <th data-options="field:'rank_class', sortable:true" width="15%">Pangkat/Golongan
                                    </th>
                                    <th data-options="field:'position', sortable:true" width="15%">Jabatan</th>
                                    <th data-options="field:'work_unit', sortable:true" width="15%">Unit Kerja</th>
                                    <th data-options="field:'handphone', sortable:true" width="15%">Handphone</th>
                                    <th data-options="field:'option'">Opsi</th>

                                </tr>
                            </thead>
                        </table>
                        <div id="toolbar">
                            @if (in_array(1, $user_roles))
                                <a class="btn btn-info btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modal_add">Tambah</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- modal add --}}
        <form method="POST" action="{{ route('master_pegawai_submit') }}" autocomplete="off" id="form_add">
            <div class="modal fade" id="modal_add" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data</h5>
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

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Tipe Data*</label>
                                        <select name="data_type" id="data_type" class="form-control" required>
                                            @php
                                                $enum_data_type = \App\Helper\General::get_enum_values('employees', 'data_type');

                                                foreach ($enum_data_type as $data_type) {
                                                    echo '<option value="' . $data_type . '">' . $data_type . '</option>';
                                                }

                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Data Utama</label><br>
                                        <select name="data_parent" id="data_parent" class="select2 form-control" required>
                                            <option value="0"> Pilih </option>
                                            @php
                                                foreach ($employee as $key => $emp) {
                                                    echo '<option value="' . $emp->id . '">' . $emp->name . '</option>';
                                                }
                                            @endphp
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">NIP*</label>
                                        <input id="nip" class="form-control" type="text" name="nip" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Nama*</label>
                                        <input id="name" class="form-control" type="text" name="name" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Tempat Lahir*</label>
                                        <input id="place_of_birth" class="form-control" type="text"
                                            name="place_of_birth" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Tanggal Lahir*</label>
                                        <input id="date_of_birth" class="form-control" type="date"
                                            name="date_of_birth" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Agama*</label>
                                        <select name="religion" id="religion" class="form-control" required>
                                            <option value="">-- Pilih Agama --</option>
                                            @php
                                                $enum_religion = \App\Helper\General::get_enum_values('employees', 'religion');

                                                foreach ($enum_religion as $religion) {
                                                    echo '<option value="' . $religion . '">' . $religion . '</option>';
                                                }

                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Jenis Kelamin*</label>
                                        <select name="gender" id="gender" class="form-control" required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            @php
                                                $enum_gender = \App\Helper\General::get_enum_values('employees', 'gender');

                                                foreach ($enum_gender as $gender) {
                                                    echo '<option value="' . $gender . '">' . $gender . '</option>';
                                                }

                                            @endphp
                                        </select>

                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="">Alamat*</label>
                                        <textarea name="address" id="address" cols="3" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Golongan Darah*</label>
                                        <select name="blood_type" id="blood_type" class="form-control" required>
                                            <option value="">-- Pilih Golongan Darah --</option>
                                            @php
                                                $blood_type = \App\Helper\General::blood_type();

                                                foreach ($blood_type as $blood) {
                                                    echo '<option value="' . $blood . '">' . $blood . '</option>';
                                                }

                                            @endphp
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Status Pernikahan*</label>
                                        <select name="marital_status" id="marital_status" class="form-control" required>
                                            <option value="">-- Pilih Status Pernikahan --</option>
                                            @php
                                                $enum_marital_status = \App\Helper\General::get_enum_values('employees', 'marital_status');

                                                foreach ($enum_marital_status as $marital_status) {
                                                    echo '<option value="' . $marital_status . '">' . $marital_status . '</option>';
                                                }

                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Pendidikan*</label>
                                        <input id="education" class="form-control" type="text" name="education"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Pangkat*</label>
                                        <input id="rank" class="form-control" type="text" name="rank"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Golongan*</label>
                                        <input id="class" class="form-control" type="text" name="class"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Jabatan*</label>
                                        <input id="position" class="form-control" type="text" name="position"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Unit Kerja*</label>
                                        <input id="work_unit" class="form-control" type="text" name="work_unit"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">No Handphone*</label>
                                        <input id="handphone" class="form-control" type="text" name="handphone"
                                            required>
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

        {{-- modal edit  --}}
        <form method="POST" action="{{ route('master_pegawai_update') }}" autocomplete="off" id="form_edit">
            <div class="modal fade" id="modal_edit" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data</h5>
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

                            <input id="id" class="form-control" type="hidden" name="id" required>
                            @csrf

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Tipe Data*</label>
                                        <select name="data_type" id="data_type" class="form-control" required>
                                            @php
                                                $enum_data_type = \App\Helper\General::get_enum_values('employees', 'data_type');

                                                foreach ($enum_data_type as $data_type) {
                                                    echo '<option value="' . $data_type . '">' . $data_type . '</option>';
                                                }

                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Data Utama</label>
                                        <select name="data_parent" id="data_parent" class="select2 form-control"
                                            required>
                                            <option value="0"> Pilih </option>
                                            @php
                                                foreach ($employee as $key => $emp) {
                                                    echo '<option value="' . $emp->id . '">' . $emp->name . '</option>';
                                                }
                                            @endphp
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Tempat Lahir*</label>
                                        <input id="place_of_birth" class="form-control" type="text"
                                            name="place_of_birth" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Tanggal Lahir*</label>
                                        <input id="date_of_birth" class="form-control" type="date"
                                            name="date_of_birth" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">NIP*</label>
                                        <input id="nip" class="form-control" type="text" name="nip"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Nama*</label>
                                        <input id="name" class="form-control" type="text" name="name"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Agama*</label>
                                        <select name="religion" id="religion" class="form-control" required>
                                            <option value="">-- Pilih Agama --</option>
                                            @php
                                                $enum_religion = \App\Helper\General::get_enum_values('employees', 'religion');

                                                foreach ($enum_religion as $religion) {
                                                    echo '<option value="' . $religion . '">' . $religion . '</option>';
                                                }

                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Jenis Kelamin*</label>
                                        <select name="gender" id="gender" class="form-control" required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            @php
                                                $enum_gender = \App\Helper\General::get_enum_values('employees', 'gender');

                                                foreach ($enum_gender as $gender) {
                                                    echo '<option value="' . $gender . '">' . $gender . '</option>';
                                                }

                                            @endphp
                                        </select>

                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="">Alamat*</label>
                                        <textarea name="address" id="address" cols="3" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Golongan Darah*</label>
                                        <select name="blood_type" id="blood_type" class="form-control" required>
                                            <option value="">-- Pilih Golongan Darah --</option>
                                            @php
                                                $enum_blood_type = \App\Helper\General::blood_type();

                                                foreach ($blood_type as $blood) {
                                                    echo '<option value="' . $blood . '">' . $blood . '</option>';
                                                }

                                            @endphp
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Status Pernikahan*</label>
                                        <select name="marital_status" id="marital_status" class="form-control" required>
                                            <option value="">-- Pilih Status Pernikahan --</option>
                                            @php
                                                $enum_marital_status = \App\Helper\General::get_enum_values('employees', 'marital_status');

                                                foreach ($enum_marital_status as $marital_status) {
                                                    echo '<option value="' . $marital_status . '">' . $marital_status . '</option>';
                                                }

                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Pendidikan*</label>
                                        <input id="education" class="form-control" type="text" name="education"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Pangkat*</label>
                                        <input id="rank" class="form-control" type="text" name="rank"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Golongan*</label>
                                        <input id="class" class="form-control" type="text" name="class"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Jabatan*</label>
                                        <input id="position" class="form-control" type="text" name="position"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Unit Kerja*</label>
                                        <input id="work_unit" class="form-control" type="text" name="work_unit"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">No Handphone*</label>
                                        <input id="handphone" class="form-control" type="text" name="handphone"
                                            required>
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

        <!-- easyui js datagrid -->
        <script type="text/javascript">
            // datagrid
            $(document).ready(function() {
                get_datagrid();
            });

            function get_datagrid() {
                $('#dg').datagrid({
                    url: "{{ route('master_pegawai_data') }}",
                    method: 'get',
                    rownumbers: true,
                    singleSelect: true,
                    pagination: true,
                    fitColumns: true,
                    multiSort: false,
                    toolbar: '#toolbar',
                    pageSize: 10,
                    remoteFilter: true,
                    defaultFilterType: 'text'
                });

                $('#dg').datagrid('enableFilter', [{
                    field: 'option',
                    type: 'label'
                }]);

            }

            // submit tambah data
            $('#form_add').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('master_pegawai_submit') }}",
                    method: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {

                        if (response.status == false) {

                            var message_error = '';
                            $.each(response.message, function(key, value) {
                                message_error += value + '<br>';
                            });

                            $('#modal_add').find('.div-error').show()
                            $('#modal_add').find('.message-error').html('')
                            $('#modal_add').find('.message-error').html(message_error);

                        } else {

                            alert(response.message);

                            $('#modal_add').modal('hide');
                            $('#dg').datagrid('reload');
                            $('#modal_add').find('form')[0].reset();
                        }

                    }
                });
            });

            // open modal update
            function edit(id) {

                $('#modal_edit').find('.div-error').hide()

                $.ajax({
                    url: "master-pegawai-edit/" + id,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        $('#modal_edit').modal('show');
                        $('#modal_edit').find('#id').val(response.id);
                        $('#modal_edit').find('#data_type').val(response.data_type).change();
                        $('#modal_edit').find('#data_parent').val(response.data_parent).change();
                        $('#modal_edit').find('#nip').val(response.nip);
                        $('#modal_edit').find('#name').val(response.name);
                        $('#modal_edit').find('#place_of_birth').val(response.place_of_birth);
                        $('#modal_edit').find('#date_of_birth').val(response.date_of_birth);
                        $('#modal_edit').find('#religion').val(response.religion).change();
                        $('#modal_edit').find('#gender').val(response.gender).change();
                        $('#modal_edit').find('#address').text(response.address);
                        $('#modal_edit').find('#blood_type').val(response.blood_type);
                        $('#modal_edit').find('#marital_status').val(response.marital_status).change();
                        $('#modal_edit').find('#education').val(response.education);
                        $('#modal_edit').find('#rank').val(response.rank);
                        $('#modal_edit').find('#class').val(response.class);
                        $('#modal_edit').find('#position').val(response.position);
                        $('#modal_edit').find('#work_unit').val(response.work_unit);
                        $('#modal_edit').find('#handphone').val(response.handphone);
                    }
                });
            }


            // submit edit data
            $('#form_edit').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('master_pegawai_update') }}",
                    method: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {

                        if (response.status == false) {

                            var message_error = '';
                            $.each(response.message, function(key, value) {
                                message_error += value + '<br>';
                            });

                            $('#modal_edit').find('.div-error').show()
                            $('#modal_edit').find('.message-error').html('')
                            $('#modal_edit').find('.message-error').html(message_error);

                        } else {

                            alert(response.message);

                            $('#modal_edit').modal('hide');
                            $('#dg').datagrid('reload');
                            $('#modal_edit').find('form')[0].reset();
                        }

                    }
                });
            });

            // delete data
            function remove(id) {
                $.messager.confirm('Confirm', 'Anda yakin hapus data ini?', function(r) {
                    if (r) {
                        $.ajax({
                            url: "master-pegawai-delete/" + id,
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

            // change status
            function change_status(id, last_status) {
                var to_status = (last_status == 'Aktif') ? 'Tidak Aktif' : 'Aktif';
                $.messager.confirm('Confirm', 'Anda yakin ubah status menjadi ' + to_status + ' ?', function(r) {
                    if (r) {
                        $.ajax({
                            url: "master-pegawai-change-status/" + id,
                            method: 'get',
                            dataType: 'json',
                            data: {
                                last_status: last_status,
                            },
                            success: function(response) {
                                alert(response.message);
                                $('#dg').datagrid('reload');
                            }
                        });
                    }
                });
            }
        </script>
    @endsection
