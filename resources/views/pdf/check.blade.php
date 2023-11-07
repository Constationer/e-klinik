<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> {{ $title }}</title>
    <style>
        .footer {
            position: absolute;
            bottom: 10mm;
            /* Adjust the distance from the bottom as needed */
            right: 10mm;
            /* Adjust the distance from the right as needed */
            text-align: right;
        }
    </style>
</head>

@if ($type === 'check')
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
                        <tr>
                            <td style="font-weight:bold">NO HANDPHONE</td>
                            <td>:</td>
                            <td>{{ $employee->handphone }}</td>
                        </tr>
                    </tbody>
                </table>

            </td>
        </tr>
    </table>


    {{-- table content  --}}
    <br />
    <table width="100%" style="padding 1px;" border="1">
        <tr style="background-color: #cce0ff; color: black;">
            <th colspan="2">DATA PEMERIKSAAN</th>
        </tr>
        <tr>
            <td width="50%">
                <table border="0">
                    <tr>
                        <td><b>Tanggal Periksa</b></td>
                        <td>: {{ $check->date }}</td>
                    </tr>
                    <tr>
                        <td><b>Tanggal Periksa Selanjutnya</b></td>
                        <td>: {{ $check->nextdate }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table border="0">
                    <tr>
                        <td><b>Nama Dokter</b></td>
                        <td>: {{ $check->doctors_name }}</td>
                    </tr>
                    <tr>
                        <td><b>Jenis Periksa</b></td>
                        <td>: {{ $check->check_type }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" style="padding: 1px;" border="1">
        <tr style="background-color: #cce0ff; color: black;">
            <th colspan="2">PEMERIKSAAN DASAR</th>
        </tr>
        <tr>
            <td width="50%">
                <table border="0">
                    <tr>
                        <td><b>Tinggi Badan</b></td>
                        <td>: {{ $check->tinggi }}</td>
                    </tr>
                    <tr>
                        <td><b>Suhu Tubuh</b></td>
                        <td>: {{ $check->suhu }}</td>
                    </tr>
                    <tr>
                        <td><b>Asam Urat</b></td>
                        <td>: {{ $check->asam_urat }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table border="0">
                    <tr>
                        <td><b>Berat Badan</b></td>
                        <td>: {{ $check->berat }}</td>
                    </tr>
                    <tr>
                        <td><b>Tekanan Darah</b></td>
                        <td>: {{ $check->tekanan }}</td>
                    </tr>
                    <tr>
                        <td><b>Kolesterol</b></td>
                        <td>: {{ $check->kolesterol }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>Hasil Pemeriksaan</b>: {{ $check->hasil }}</td>
        </tr>
    </table>
    <table width="100%" style="padding: 1px;" border="1">
        <tr style="background-color: #cce0ff; color: black;">
            <th>PEMERIKSAAN LANJUTAN</th>
        </tr>
        <tr>
            <td><b>Pemeriksaan Fisik</b>: <br>{{ $check->description }}</td>
        </tr>
        <tr>
            <td><b>Diagnosis</b>: <br>{{ $check->diagnosis }}</td>
        </tr>
        <tr>
            <td><b>Terapi</b>: <br>{{ $check->therapy }}</td>
        </tr>
    </table>
    <div class="footer">
        Page 1 of 1
    </div>
@else
    @foreach ($check as $key => $detail)
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
                            <tr>
                                <td style="font-weight:bold">NO HANDPHONE</td>
                                <td>:</td>
                                <td>{{ $employee->handphone }}</td>
                            </tr>
                        </tbody>
                    </table>

                </td>
            </tr>
        </table>


        {{-- table content  --}}
        <br />
        <table width="100%" style="padding 1px;" border="1">
            <tr style="background-color: #cce0ff; color: black;">
                <th colspan="2">DATA PEMERIKSAAN</th>
            </tr>
            <tr>
                <td width="50%">
                    <table border="0">
                        <tr>
                            <td><b>Tanggal Periksa</b></td>
                            <td>: {{ $detail->date }}</td>
                        </tr>
                        <tr>
                            <td><b>Tanggal Periksa Selanjutnya</b></td>
                            <td>: {{ $detail->nextdate }}</td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table border="0">
                        <tr>
                            <td><b>Nama Dokter</b></td>
                            <td>: {{ $detail->doctors_name }}</td>
                        </tr>
                        <tr>
                            <td><b>Jenis Periksa</b></td>
                            <td>: {{ $detail->check_type }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" style="padding: 1px;" border="1">
            <tr style="background-color: #cce0ff; color: black;">
                <th colspan="2">PEMERIKSAAN DASAR</th>
            </tr>
            <tr>
                <td width="50%">
                    <table border="0">
                        <tr>
                            <td><b>Tinggi Badan</b></td>
                            <td>: {{ $detail->tinggi }}</td>
                        </tr>
                        <tr>
                            <td><b>Suhu Tubuh</b></td>
                            <td>: {{ $detail->suhu }}</td>
                        </tr>
                        <tr>
                            <td><b>Asam Urat</b></td>
                            <td>: {{ $detail->asam_urat }}</td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table border="0">
                        <tr>
                            <td><b>Berat Badan</b></td>
                            <td>: {{ $detail->berat }}</td>
                        </tr>
                        <tr>
                            <td><b>Tekanan Darah</b></td>
                            <td>: {{ $detail->tekanan }}</td>
                        </tr>
                        <tr>
                            <td><b>Kolesterol</b></td>
                            <td>: {{ $detail->kolesterol }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2"><b>Hasil Pemeriksaan</b>: {{ $detail->hasil }}</td>
            </tr>
        </table>
        <table width="100%" style="padding: 1px;" border="1">
            <tr style="background-color: #cce0ff; color: black;">
                <th>PEMERIKSAAN LANJUTAN</th>
            </tr>
            <tr>
                <td><b>Pemeriksaan Fisik</b>: <br>{{ $detail->description }}</td>
            </tr>
            <tr>
                <td><b>Diagnosis</b>: <br>{{ $detail->diagnosis }}</td>
            </tr>
            <tr>
                <td><b>Terapi</b>: <br>{{ $detail->therapy }}</td>
            </tr>
        </table>
        <div class="footer">
            Page {{ $loop->iteration }} of {{ $loop->count }}
        </div>
        @if (!$loop->last)
            <div style="page-break-before: always;"></div>
        @endif
    @endforeach
@endif
