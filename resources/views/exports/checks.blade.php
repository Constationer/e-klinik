<h1>{{$title}}</h1>
<h3>Periode : {{ $start_date }} s/d {{ $end_date }}</h3>
<h3>Klinik Perwakilan Provinsi Sulawesi Tengah</h3>

<br/><br/>
<table border="1" width="100%">
    <thead>
        <tr style="background-color: beige">
            <th>No</th>
            <th>Nomor</th>
            <th>Nama Pegawai</th>
            <th> Nama Dokter </th>
            <th> Jenis Periksa </th>
            <th> Tgl Periksa </th>
        </tr>
    </thead>
        @php
            $i = 1;
        @endphp 

        @foreach ($data as $check)

            <tr>
                <td>{{$i}}</td>
                <td>{{ $check->code }}</td>
                <td>{{ $check->employee_name }}</td>
                <td>{{ $check->doctor_name }}</td>
                <td>{{ $check->check_type }}</td>
                <td>{{ $check->date }}</td>
            </tr>

            @php
                $i++;
            @endphp
        @endforeach 
        
    <tbody>

    </tbody>
</table>