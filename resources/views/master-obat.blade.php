@extends('index')

@php
	$categories = \App\Helper\General::get_enum_values('medicines', 'category');
@endphp

@section('content') 

<section class="section"> 
	
	<div class="row">

		<!-- easyui datagrid table -->
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<table id="dg" class="easyui-datagrid mt-2" style="width:100%;height:500px" singleSelect="true">
						<thead frozen="true">
							<tr>
								<th data-options="field:'code', sortable:true" width="15%">Kode Obat</th>
								<th data-options="field:'name', sortable:true" width="15%">Nama Obat</th>
							</tr>
						</thead>
						<thead>
						    <tr>
						        <th data-options="field:'unit', sortable:true" width="10%">Satuan</th>
								<th data-options="field:'category', sortable:true" width="15%">Kategori</th>
								<th data-options="field:'total_qty', sortable:true" width="10%">Jumlah</th>
								<th data-options="field:'expired_date', sortable:true" width="10%">Tgl Kadaluarsa Terdekat</th>
								<th data-options="field:'status_stock', sortable:true" width="10%">Status</th>
								<th data-options="field:'option', sortable:true" width="20%">Opsi</th>
						    </tr>
						</thead>
					</table>
					<div id="toolbar">
						@if (in_array(1, $user_roles))
						<a class="btn btn-info btn-sm m-1" data-bs-toggle="modal" data-bs-target="#modal_add">Tambah Obat Baru</a>
						@endif
					</div>
				</div>
			</div>
		</div>

	</div>

	{{-- modal add --}}
	<form method="POST" action="{{ route('master_obat_submit') }}" autocomplete="off" id="form_add">
		<div
			class="modal fade"
			id="modal_add"
			tabindex="-1"
			aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Tambah Obat</h5>
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
						<div class="mb-3">
							<label for="">Nama*</label>
							<input id="name" class="form-control" type="text" name="name" required>
						</div>
						<div class="mb-3">
							<label for="">Satuan*</label>
							<input id="unit" class="form-control" type="text" name="unit" required>
						</div>
						<div class="mb-5">
							<label for="">Kategori*</label>
							<select id="category" class="form-control" name="category" required>
								<option value="">Pilih Kategori Obat</option>
								@foreach ($categories as $category)
									<option value="{{ $category }}">{{ $category }}</option>
								@endforeach
							</select>
						</div>
						<hr/>
						
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
							<label for="">Tanggal Kadaluarsa*</label>
							<input id="expired_date" class="form-control" type="date" name="expired_date" required>
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

	{{-- modal edit --}}
	<form method="POST" action="{{ route('master_obat_update') }}" autocomplete="off" id="form_edit">
		<div
			class="modal fade"
			id="modal_edit"
			tabindex="-1"
			aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit Obat</h5>
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
						<div class="mb-3">
							<label for="">Nama*</label>
							<input id="name_edit" class="form-control" type="text" name="name" required>
						</div>
						<div class="mb-3">
							<label for="">Satuan*</label>
							<input id="unit_edit" class="form-control" type="text" name="unit" required>
						</div>
						<div class="mb-5">
							<label for="">Kategori*</label>
							<select id="category_edit" class="form-control" name="category" required>
								<option value="">Pilih Kategori Obat</option>
								@foreach ($categories as $category)
									<option value="{{ $category }}">{{ $category }}</option>
								@endforeach
							</select>
						</div>

						<input type="hidden" name="id" id="id_edit">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Tutup</button>
						<button type="submit" class="btn btn-info">Simpan</button>
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
		    
			var dg = $('#dg').datagrid({
				url: "{{ route('master_obat_data') }}",
				method: 'get',
				pageSize: 10,
				rownumbers: true,
				singleSelect: true,
				pagination: true,
				fitColumns: true,
				remoteFilter: true,
				toolbar: '#toolbar',
				defaultFilterType: 'label'
			});

			// easyui datagrid search
			dg.datagrid('enableFilter', [
				{field:'code',type:'text'},
				{field:'name',type:'text'},
				{field:'unit',type:'text'},
				{field:'category',type:'text'},
				{field:'status_stock',type:'text'}
			]);
			
		}

		// js submit form add
		$('#form_add').submit(function(e){
			e.preventDefault();
			$.ajax({
				url: "{{ route('master_obat_submit') }}",
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
						url: "master-obat-delete/" + id,
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
					$('#category_edit').val(response.data.category);
				}
			});
		}

		// js submit form edit
		$('#form_edit').submit(function(e){
			e.preventDefault();
			$.ajax({
				url: "{{ route('master_obat_update') }}",
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


	</script>

@endsection