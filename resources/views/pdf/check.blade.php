<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> {{ $title }}</title>
</head>

<table border="0">
    <tbody>
        <tr>
            <td>
                <img src="{{ public_path() . '/assets/img/logo.png' }}" width="100px" height="100px">
            </td>
            <td>
                Klinik Umum<br />
                Badan Pemeriksa Keuangan Republik Indonesia<br />
                Perwakilan Provinsi Sulawesi Tengah<br /><br />
            </td>
        </tr>
    </tbody>
</table>

<table width="100%">
    <tbody>
        <tr>
            <th width="33%"></th>
            <th width="33%" align="center">
                <u>KARTU BEROBAT PEGAWAI</u> <br />
                <small> {{ $number }} </small>
            </th>
            <th width="33%"></th>
        </tr>
    </tbody>
</table>

{{-- table header  --}}
<table width="100%" border="0">
    <tr>
        <td width="70%">

            <table border="0">
                <tbody>
                    <tr>
                        <td width="50%" style="font-weight:bold">NAMA PASIEN/PEGAWAI</td>
                        <td width="5%">:</td>
                        <td width="40%">{{ $employee->name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">GOLONGAN</td>
                        <td>:</td>
                        <td>{{ $employee->class }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">UNIT KERJA</td>
                        <td>:</td>
                        <td>{{ $employee->work_unit }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">ALAMAT</td>
                        <td>:</td>
                        <td>{{ $employee->address }}</td>
                    </tr>
                </tbody>
            </table>

        </td>
        <td width="40%">

            <table border="0">
                <tbody>
                    <tr>
                        <td width="30%" style="font-weight:bold">UMUR</td>
                        <td width="5%">:</td>
                        <td width="50%">{{ Carbon\Carbon::parse($employee->date_of_birth)->age }} Thn</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">JENIS KELAMIN</td>
                        <td>:</td>
                        <td>{{ $employee->gender }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">GOL. DARAH</td>
                        <td>:</td>
                        <td>{{ $employee->blood_type }}</td>
                    </tr>
                </tbody>
            </table>

        </td>
    </tr>
</table>


{{-- table content  --}}
<br />
<table width="100%" style="padding: 1px;" border="1">
    <tr style="background-color:rgb(217, 214, 214)">
        <th> TANGGAL </th>
        <th> TINGGI BADAN </th>
        <th> BERAT BADAN </th>
        <th> SUHU TUBUH </th>
        <th> TEKANAN DARAH </th>
        <th> HASIL PEMERIKSAAN DASAR</th>
        <th> PEMERIKSAAN FISIK </th>
        <th> DIAGNOSA </th>
        <th> TERAPI </th>
        <th> TANGGAL PERIKSA SELANJUTNYA </th>
    </tr>


    @if ($type === 'check')
        <tr>
            <td> {{ $check->date }} </td>
            <td> {{ $check->tinggi }} </td>
            <td> {{ $check->berat }} </td>
            <td> {{ $check->suhu }} </td>
            <td> {{ $check->tekanan }} </td>
            <td> {{ $check->hasil }} </td>
            <td> {{ $check->description }} </td>
            <td> {{ $check->diagnosis }} </td>
            <td> {{ $check->therapy }} </td>
            <td> {{ $check->nextdate }}</td>
        </tr>
    @else
        @foreach ($check as $key => $detail)
            <tr>
                <td> {{ $detail->date }} </td>
                <td> {{ $detail->tinggi }} </td>
                <td> {{ $detail->berat }} </td>
                <td> {{ $detail->suhu }} </td>
                <td> {{ $detail->tekanan }} </td>
                <td> {{ $detail->hasil }} </td>
                <td> {{ $detail->description }} </td>
                <td> {{ $detail->diagnosis }}</td>
                <td> {{ $detail->therapy }}</td>
                <td> {{ $detail->nextdate }}</td>
            </tr>
        @endforeach
    @endif
</table>
