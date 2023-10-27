<h1>Laporan Persediaan Obat-Obatan</h1>
<h3>Periode : {{ $start_date }} s/d {{ $end_date }}</h3>
<h3>Klinik Perwakilan Provinsi Sulawesi Tengah</h3>

<br/><br/>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Obat</th>
            <th>Satuan</th>
            <th> Jumlah Awal </th>
            <th> Masuk </th>
            <th> Keluar </th>
            <th> Jumlah Akhir </th>
            <th> Expired Date </th>
            <th> Umur Obat (dalam bulan) </th>
            <th> Status </th>
        </tr>
    </thead>
        @php
            $i = 1;
        @endphp 

        @foreach ($medicines as $value)
        
        @php
            $jumlah_akhir = $value->stock_awal + $value->stock_in - $value->stock_out;
            $month_diff = \App\Helper\General::get_date_diff($value->expired_date, date('Y-m-d'));        

            $status = $value->status_stock;
        @endphp

            <tr>
                <td>{{$i}}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->unit }}</td>
                <td>{{ $value->stock_awal }}</td>
                <td>{{ $value->stock_in }}</td>
                <td>{{ $value->stock_out }}</td>
                <td>{{ $jumlah_akhir }}</td>
                <td>{{ $value->expired_date }}</td>
                <td>{{ $month_diff['months'] }}</td>
                <td>{{ $status }}</td>
            </tr>

            @php
                $i++;
            @endphp
        @endforeach 
        
    <tbody>

    </tbody>
</table>