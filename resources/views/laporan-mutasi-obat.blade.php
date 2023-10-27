@extends('index')

@section('content') 

<section class="section"> 
	<div class="card">
        <div class="card-body">

            <div class="row mt-2">	
                <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="start_date" class="form-label">Tanggal Awal</label>
                        <input type="date" class="form-control" id="start_date"  value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="medicine_id" class="form-label">Nama Obat</label>
                        <select class="form-select select2" aria-label="Default select example" id="medicine_id">
                            <option value="">Semua</option>
                            @foreach ($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-2 mt-4">
                        <button type="button" class="btn btn-info" id="btn-search" onclick="get_datagrid()">Cari</button>
                        @if (in_array(1, $user_roles))
                            <button type="button" class="btn btn-success" id="btn-export">Export</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    
                    {{-- table datagrid easyui datagrid --}}
                    <table id="dg" class="easyui-datagrid mt-2" style="width:100%;height:500px" singleSelect="true">
                        <thead>
                            <tr>
                                <th field="name" sortable="true" width="50">Nama Obat</th>
                                <th field="unit" width="50"> Satuan</th>
                                <th field="expired_date" sortable="true" width="50"> Tgl Kadaluarsa</th>
                                <th field="stock_awal" width="50">Saldo Awal</th>
                                <th field="stock_in" width="50">Stock Masuk</th>
                                <th field="stock_out" width="50">Stock Keluar</th>
                                <th field="stock_akhir" width="50">Saldo Akhir</th>
                            </tr>
                        </thead>
                    </table>
                    <div id="toolbar">
                    </div>
                        
                </div>
            </div>   

        </div>
    </div>
</section>

<script type="text/javascript">

    // document ready
    $(document).ready(function() {
        get_datagrid();
    });


    function get_datagrid() {
        $('#dg').datagrid({
            url: "{{ route('laporan_data') }}",
            method: 'get',
            rownumbers: true,
            singleSelect: true,
            pagination: true,
            fitColumns: true,
            toolbar: '#toolbar',
            queryParams: {
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                medicine_id: $('#medicine_id').val(),
            },
        });
    }

    // function onclick button export
    $('#btn-export').click(function() {
        var start_date      = $('#start_date').val();
        var end_date        = $('#end_date').val();
        var medicine_id     = $('#medicine_id').val();
        window.open("{{ route('laporan_export') }}?start_date="+start_date+"&end_date="+end_date+"&medicine_id="+medicine_id, "_blank");
    });

</script>

@endsection