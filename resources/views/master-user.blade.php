@extends('index')

@section('content') 

<section class="section"> 
	
	<div class="row">	

		<!-- easyui datagrid table -->
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body mt-2">
					<table id="dg" class="easyui-datagrid mt-2" style="width:100%;height:600px" singleSelect="true">
						<thead>
							<tr>
								<th data-options="field:'name', sortable:true" width="20%">Nama</th>
								<th data-options="field:'email', sortable:true" width="20%">Email</th>
								<th data-options="field:'phone', sortable:true" width="20%">No. Telepon</th>
								<th data-options="field:'user_group', sortable:true" width="20%">Group</th>
								<th data-options="field:'name', sortable:false" field="option" width="20%">Opsi</th>
							</tr>
						</thead>
					</table>
					<div id="toolbar">
						@if (in_array(1, $user_roles))
							<a class="btn btn-info btn-sm m-1" data-bs-toggle="modal" data-bs-target="#modal_add">Tambah</a>
						@endif
					</div>
				</div>
			</div>
		</div>

	</div>

	{{-- modal add --}}
	<form method="POST" action="{{ route('master_user_submit') }}" autocomplete="off" id="form_add">
		<div
			class="modal fade"
			id="modal_add"
			tabindex="-1"
			aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Tambah Data</h5>
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
							<input id="name" class="form-control" type="text" name="name" required autocomplete="off">
						</div>
						<div class="mb-3">
							<label for="">Email*</label>
							<input id="email" class="form-control" type="email" name="email" required autocomplete="off">
						</div>
						<div class="mb-3">
							<label for="">Password*</label>
							<input id="password" class="form-control" type="password" name="password" required autocomplete="off">
						</div>
						<div class="mb-3">
							<label for="">No. Telepon*</label>
							<input id="phone" class="form-control" type="text" name="phone" required autocomplete="off">
						</div>
						<div class="mb-3">
							<label for="">Group*</label>
							<select class="form-control" name="user_group" id="user_group" required>
								<option value="">Pilih Group</option>
								@foreach ($user_group as $item)
									<option value="{{ $item->id }}">{{ $item->name }}</option>
								@endforeach
							</select>
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
	<form method="POST" action="{{ route('master_user_update') }}" autocomplete="off" id="form_edit">
		<div
			class="modal fade"
			id="modal_edit"
			tabindex="-1"
			aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit Data</h5>
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
						<input id="id" class="form-control" type="hidden" name="id" required autocomplete="off">
						<div class="mb-3">
							<label for="">Nama*</label>
							<input id="name" class="form-control" type="text" name="name" required autocomplete="off">
						</div>
						<div class="mb-3">
							<label for="">Email*</label>
							<input id="email" class="form-control" type="email" name="email" required autocomplete="off">
						</div>
						<div class="mb-3">
							<label for="">Password*</label>
							<input id="password" class="form-control" type="password" name="password" autocomplete="off" placeholder="isi jika ganti password">
						</div>
						<div class="mb-3">
							<label for="">No. Telepon*</label>
							<input id="phone" class="form-control" type="text" name="phone" required autocomplete="off">
						</div>
						<div class="mb-3">
							<label for="">Group*</label>
							<select class="form-control" name="user_group" id="user_group" required>
								<option value="">Pilih Group</option>
								@foreach ($user_group as $item)
									<option value="{{ $item->id }}">{{ $item->name }}</option>
								@endforeach
							</select>
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
		
		// datagrid
		$(document).ready(function() {
			get_datagrid();
		});

		function get_datagrid() {
			$('#dg').datagrid({
				url: "{{ route('master_user_data') }}",
				method: 'get',
				rownumbers: true,
				singleSelect: true,
				pagination: true,
				fitColumns: true,
				toolbar: '#toolbar',
				pageSize: 10,
				remoteFilter: true,
				defaultFilterType: 'label'
			});
			
			$('#dg').datagrid('enableFilter', [
				{field:'name',type:'text'},
				{field:'email',type:'text'},
				{field:'phone',type:'text'},
				{field:'user_group',type:'text'}
			]);
		}

		// submit tambah data
		$('#form_add').submit(function(e) {
			e.preventDefault();
			$.ajax({
				url: "{{ route('master_user_submit') }}",
				method: 'post',
				dataType: 'json',
				data: $(this).serialize(),
				success: function(response) {

					if(response.status == false) {

						var message_error = '';
						$.each(response.message, function(key, value) {
							message_error += value + '<br>';
						});
						
						$('#modal_add').find('.div-error').show()
						$('#modal_add').find('.message-error').html('')
						$('#modal_add').find('.message-error').html(message_error);

					} else {

						alert(response.message);

						$('#modal_add').modal('hide');
						$('#dg').datagrid('reload');
						$('#modal_add').find('form')[0].reset();
					}

				}
			});
		});

		// open modal update
		function edit(id) {

			$('#modal_edit').find('.div-error').hide()

			$.ajax({
				url: "master-user-edit/"+id,
				method: 'post',
				dataType: 'json',
				data: {
					_token: "{{ csrf_token() }}",
				},
				success: function(response) {
					$('#modal_edit').modal('show');
					$('#modal_edit').find('#id').val(response.id);
					$('#modal_edit').find('#name').val(response.name);
					$('#modal_edit').find('#email').val(response.email);
					$('#modal_edit').find('#phone').val(response.phone);
					$('#modal_edit').find('#user_group').val(response.user_group);
				}
			});
		}

		// submit edit data
		$('#form_edit').submit(function(e) {
			e.preventDefault();
			$.ajax({
				url: "{{ route('master_user_update') }}",
				method: 'post',
				dataType: 'json',
				data: $(this).serialize(),
				success: function(response) {

					if(response.status == false) {

						var message_error = '';
						$.each(response.message, function(key, value) {
							message_error += value + '<br>';
						});
						
						$('#modal_edit').find('.div-error').show()
						$('#modal_edit').find('.message-error').html('')
						$('#modal_edit').find('.message-error').html(message_error);

					} else {

						alert(response.message);

						$('#modal_edit').modal('hide');
						$('#dg').datagrid('reload');
						$('#modal_edit').find('form')[0].reset();
					}

				}
			});
		});

		// delete data
		function remove(id) {
			$.messager.confirm('Confirm', 'Anda yakin hapus data ini?', function(r) {
				if (r) {
					$.ajax({
						url: "master-user-delete/" + id,
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



	</script>


@endsection