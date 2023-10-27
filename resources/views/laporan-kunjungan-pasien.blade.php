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
                        <label for="" class="form-label">Nama Dokter</label>
                        <select class="form-select" aria-label="Default select example" id="doctor_id">
                            <option value="">Semua</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="medicine_id" class="form-label">Nama Pegawai/Pasien</label>
                        <select class="form-select" aria-label="Default select example" id="employee_id">
                            <option value="">Semua</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 col-2 mx-auto mt-2">
                @if (in_array(1, $user_roles))
                    <button type="button" class="btn btn-success" id="btn-export">Export</button>
                @endif
            </div>

        </div>
    </div>
</section>

<script type="text/javascript">

    // function onclick button export
    $('#btn-export').click(function() {
        var start_date  = $('#start_date').val();
        var end_date    = $('#end_date').val();
        var doctor_id   = $('#doctor_id').val();
        if (doctor_id == '') {
            doctor_id = 0;
        }
        var employee_id = $('#employee_id').val();
        if (employee_id == '') {
            employee_id = 0;
        }

        window.open("/laporan-kunjungan-pasien-export/"+start_date+'/'+end_date+'/'+doctor_id+'/'+employee_id, "_blank");
    });

</script>

@endsection