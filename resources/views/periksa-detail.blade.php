@extends('index')

@section('content') 

<h5>Dokter : {{ $check->doctor_name }} | Pegawai : {{ $check->employee_name }}</h5>

<section class="section"> 
	
	<div class="row">

		<!-- easyui datagrid table -->
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<table id="dg" class="easyui-datagrid mt-2" style="width:100%;height:500px" singleSelect="true">
						<thead>
							<tr>
								<th field="medicine_name" width="50">Nama Obat</th>
								<th field="qty" width="50">Jumlah</th>
								<th field="expired_date" width="50">Tgl Kadaluarsa</th>
								<th field="description" width="50">Catatan</th>
								<th field="option" width="50">Opsi</th>
							</tr>
						</thead>
					</table>
					<div id="toolbar">
						<a class="btn btn-info btn-sm m-1" data-bs-toggle="modal" data-bs-target="#modal_add">Tambah Obat yang Diberikan</a>
					</div>
				</div>
			</div>
		</div>

		{{-- btn back  --}}
		<div class="d-grid gap-3">
			<a href="{{ route('periksa') }}" class="btn btn-info">Kembali</a>
		</div>
		

	</div>

	{{-- modal add --}}
	<form method="POST" action="{{ route('periksa_obat_submit') }}" autocomplete="off" id="form_add">
		<div
			class="modal fade"
			id="modal_add"
			tabindex="-1"
			aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Tambah Detail Obat</h5>
						<button
							type="button"
							class="btn-close"
							data-bs-dismiss="modal"
							aria-label="Close"></button>
					</div>
					<div class="modal-body">

						<div class="mb-1 div-error" style="display: none">	
							<div class="alert alert-danger d-flex align-items-center" role="alert">
								<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
								<div class="message-error"></div>
							  </div>				
						</div>

						@csrf

						<input type="hidden" name="check_id" value="{{ $check->id }}">

						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="">Nama Obat*</label>
									<select class="form-control select2" name="medicine_id" id="medicine_id" required onchange="list_expired_date()">
										<option value="">Pilih </option>
										@foreach ($medicine as $item)
											<option value="{{ $item->id }}">{{ $item->name }}</option>
										@endforeach
									</select>
								</div>
							</div>							

							<div class="col-lg-6">
								<div class="mb-3">
									<label for="">Tgl Expired Obat*</label>
									<select class="form-control" name="medicine_purchase_id" id="medicine_purchase_id" required onchange="set_qty()">
										<option value="">Pilih Obat Dahulu </option>
									</select>
								</div>
							</div>

							<div class="col-lg-6">
								<div class="mb-3">
									<label for="">Jumlah*</label>
									<input id="qty" class="form-control" type="number" name="qty" required>
									<small for="" class="text-muted">hanya bisa edit lebih kecil dari stock</small>
								</div>
							</div>

							<div class="col-lg-12">
								<div class="mb-3">
									<label for="">Catatan*</label>
									<textarea id="description" class="form-control" name="description" required rows="4"></textarea>
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

		// js window onload
		$(function(){
			get_datagrid();
		});

		function get_datagrid() {
			$('#dg').datagrid({
				url: "/periksa-obat-data/{{ $check->id }}",
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
				url: "{{ route('periksa_obat_submit') }}",
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
						url: "/periksa-obat-delete/" + id,
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

		// edit data
		function edit(id) {
			$.ajax({
				url: "master-obat-edit/" + id,
				method: 'get',
				dataType: 'json',
				data: {},
				success: function(response) {
					$('#modal_edit').find('.div-error').hide();
					$('#modal_edit').modal('show');
					$('#id_edit').val(response.data.id);
					$('#name_edit').val(response.data.name);
					$('#unit_edit').val(response.data.unit);
				}
			});
		}

		// js submit form edit
		$('#form_edit').submit(function(e){
			e.preventDefault();
			$.ajax({
				url: "{{ route('periksa_update') }}",
				method: "POST",
				data: $('#form_edit').serialize(),
				dataType: "JSON",
				success: function(data){
					
					if(data.status == false) {

						var message_error = '';
						$.each(data.message, function(key, value) {
							message_error += value + '<br>';
						});

						$('#modal_edit').find('.div-error').show()
						$('#modal_edit').find('.message-error').html('')
						$('#modal_edit').find('.message-error').html(message_error);

						} else {

						alert(data.message);

						$('#modal_edit').modal('hide');
						$('#dg').datagrid('reload');
						$('#form_edit').trigger("reset");
					}
				}
			});
		});

		// list expired date
		function list_expired_date() {
			var medicine_id = $('#medicine_id').val();
			$.ajax({
				url: "/master-obat-expired-data/" + medicine_id,
				method: 'get',
				dataType: 'json',
				data: {},
				success: function(response) {

					var list_options = '';
					length_data = response.length;

					if( length_data > 0 ) {
						list_options +='<option value="">Pilih Data</option>';
						for (var i = 0; i < response.length ; i++) {
							list_options +='<option value="' + response[i].id + '" data-stock="'+response[i].stock+'">' + response[i].expired_date + '</option>';
						}
					} else {
						list_options +='<option value="">Tidak Ada Data</option>';
					}

					$('#medicine_purchase_id').html(list_options);

				}
			});						
		}

		function set_qty() {
			var stock = $('#medicine_purchase_id').find(':selected').data('stock');
			$('#qty').attr('max', stock);	
			$('#qty').val(stock);		
		}

	</script>

@endsection