@extends('index')

@section('content')

<div class="card">
    <div class="card-body">

        {{-- show error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                Ada masalah dengan data yang di input !<br><br>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        
        <table id="dg" class="easyui-datagrid mt-2" style="width:100%;height:500px" singleSelect="true">
            <thead>
                <tr>
                    <th field="invoice_number" width="50">Nomor Invoice</th>
                    <th field="purchase_date" width="50">Tanggal Pembelian</th>
                    <th field="qty" width="50">Jumlah Pembelian</th>
                    <th field="expired_date" width="50">Tanggal Kadaluarsa</th>
                    <th field="qty_used" width="50">Jumlah Terpakai</th>
                    <th field="option" width="50">Opsi</th>
                </tr>
            </thead>
        </table>
        <div id="toolbar" class="mt-2">
            <a class="btn btn-info btn-sm m-1" data-bs-toggle="modal" data-bs-target="#modal_add">Tambah</a>
        </div>

        {{-- modal add --}}
        <form method="POST" action="{{ route('master_obat_detail_submit') }}" autocomplete="off" id="form_add">
            <div
                class="modal fade"
                id="modal_add"
                tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data</h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf

                            <input id="medicine_id" type="hidden" name="medicine_id" required value="{{$medicine_id}}">
                            <div class="mb-3">
                                <label for="">No. Invoice Pembelian*</label>
                                <input id="invoice_number" class="form-control" type="text" name="invoice_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="">Tanggal Pembelian*</label>
                                <input id="purchase_date" class="form-control" type="date" name="purchase_date" required>
                            </div>
    
                            <div class="mb-3">
                                <label for="">Jumlah*</label>
                                <input id="qty" class="form-control" type="number" name="qty" required>
                            </div>
                            <div class="mb-3">
                                <label for="">Tanggal Kadaluarsa</label>
                                <input id="expired_date" class="form-control" type="date" name="expired_date" >
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

        {{-- button back  --}}        
        <div class="d-grid mt-2">
            <a href="{{ route('master-obat') }}" class="btn btn-info btn-sm">Kembali</a>
        </div>        

    </div>
</div>

<!-- easyui js datagrid -->
<script type="text/javascript">

    // document ready
    $(document).ready(function() {
        get_datagrid();
    });


    function get_datagrid() {
        $('#dg').datagrid({
            url: "{{ route('master_obat_detail_data') }}/?medicine_id="+{{$medicine_id}},
            method: 'get',
            rownumbers: true,
            singleSelect: true,
            pagination: true,
            fitColumns: true,
            toolbar: '#toolbar',
        });
    }

    // js submit form add
    $('#form_add').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('master_obat_detail_submit') }}",
            method: "POST",
            data: $('#form_add').serialize(),
            dataType: "JSON",
            success: function(data){
                
                if(data.status == false) {

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
                    url: "/master-obat-detail-delete/" + id,
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


</script>

@endsection