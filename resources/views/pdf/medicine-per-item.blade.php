<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title> {{ $title }}</title>
</head>

<table border="0" width="100%">
    <tbody>
        <tr>
            <td>
                <img src="{{ public_path() . '/assets/img/logo.png' }}" width="100px" height="100px">
            </td>
            <td align="center" style="font-weight: bold">
                Klinik Umum<br/>
                Badan Pemeriksa Keuangan Republik Indonesia<br/>
                Perwakilan Provinsi Sulawesi Tengah<br/><br/>
            </td>
            <td align="right"> Tahun {{$year}} </td>
        </tr>
    </tbody>
</table>

<table width="100%">
    <tbody>
        <tr>
            <th width="25%"></th>
            <th width="50%" align="center"> 
                <u>KARTU PERSEDIAAN OBAT</u> <br/>
                <small> {{ $medicine->name }} / {{ $medicine->code }} </small>
            </th>
            <th width="25%"></th>
        </tr>
    </tbody>
</table>

{{-- table dengan 2 tr dan 2 td --}}
<table width="100%" border="0">
    <tr>
        <td width="50%">

            <table border="0">
                <tbody>
                    <tr>
                        <td style="font-weight:bold">Kode Obat</td>
                        <td>:</td>
                        <td>{{ $medicine->code }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">Kelompok Obat</td>
                        <td>:</td>
                        <td> {{ $medicine->category }} </td>
                    </tr>
                </tbody>
            </table>

        </td>
        <td width="50%"> 

            <table border="0">
                <tbody>
                    <tr>
                        <td width="50%" style="font-weight:bold">Nama Obat</td>
                        <td width="5%">:</td>
                        <td width="40%">{{ $medicine->name }}</td>
                    </tr>
                    
                    <tr>
                        <td style="font-weight:bold">Satuan</td>
                        <td>:</td>
                        <td>{{ $medicine->unit }}</td>
                    </tr>
                </tbody>
            </table>

        </td>
    </tr>
</table>

{{-- table content  --}}
<br/>
<table width="100%" style="padding: 2px;" border="1">
    <tr style="background-color:rgb(217, 214, 214)">
        <th> No </th>
        <th> BAST/Pemeriksaan </th>
        <th> Tgl Masuk / Keluar </th>
        <th> Keterangan </th>
        <th> Masuk </th>
        <th> Keluar </th>
        <th> Saldo </th>
    </tr>
    
    @php
        // $saldo_awal = $last_stock;
        $saldo = $last_stock;
    @endphp

    <tr>
        <td align="center"> 1 </td>
        <td> </td>
        <td> </td>
        <td> saldo tahun sebelumnya </td>
        <td> </td>
        <td> </td>
        <td align="right"> {{$saldo }} </td>
    </tr>
    
    @foreach ($mutation as $medicine_detail)
        
        @php
            $saldo = $saldo + $medicine_detail->data_in - $medicine_detail->data_out;
        @endphp

        <tr>
            <td align="center"> {{ $loop->iteration+1 }} </td>
            <td> {{ $medicine_detail->data_number }} </td>
            <td> {{ $medicine_detail->data_date }} </td>
            <td> {{ $medicine_detail->data_desc }} </td>
            <td align="right"> {{ $medicine_detail->data_in }} </td>
            <td align="right"> {{ $medicine_detail->data_out }} </td>
            <td align="right"> {{ $saldo }} </td>
        </tr>
    @endforeach
        
</table>