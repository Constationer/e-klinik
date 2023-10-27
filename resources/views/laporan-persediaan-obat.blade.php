@extends('index')

@section('content') 

<section class="section"> 
	<div class="card">
        <div class="card-body">

            <div class="row mt-2">	
                <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="start_date" class="form-label">Tahun</label>
                        <input type="number" min="1900" max="2099" step="1" value="{{ date('Y') }}" id="year" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="medicine_id" class="form-label">Nama Obat</label>
                        <select class="form-select select2" aria-label="Default select example" id="medicine_code">
                            <option value="">-- Pilih Obat --</option>
                            @foreach ($medicines as $medicine)
                                <option value="{{ $medicine->code }}">{{ $medicine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-2 mt-4">
                        @if (in_array(1, $user_roles))
                            <button type="button" class="btn btn-success" id="btn-export">Lihat PDF</button>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script type="text/javascript">

    // function onclick button export
    $('#btn-export').click(function() {
        var year        = $('#year').val();
        var medicine_code = $('#medicine_code').val();

        if ( medicine_code == '' ) {
            alert('Pilih obat terlebih dahulu');
            return false;
        }

        window.open("/pdf-medicine-per-item/"+year+'/'+medicine_code, "_blank");
    });

</script>

@endsection