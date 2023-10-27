@extends('index')

@section('content') 

<section class="section dashboard"> 
	
	<div class="row">

		<div class="col-lg-12">
			<div class="row">

				<!-- jumlah obat -->
				<div class="col-xxl-6 col-md-12">
					<div class="card info-card sales-card">

						<div class="card-body">
							<h5 class="card-title">Jumlah Data Obat
								<span>| saat ini</span>
							</h5>

							<div class="d-flex align-items-center">
								<div
									class="card-icon rounded-circle d-flex align-items-center justify-content-center">
									<i class="bi bi-hdd-stack"></i>
								</div>
								<div class="ps-3">
									<h6> {{ $total_obat }} </h6>

								</div>
							</div>
						</div>

					</div>
				</div>
				<!-- End jumlah obat -->

				<!-- akan kadaluarsa -->
				<div class="col-xxl-6 col-md-12">
					<div class="card info-card revenue-card">

						<div class="card-body">
							<h5 class="card-title">Akan Kadaluarsa
								<span>| 90 hari kedepan</span>
							</h5>

							<div class="d-flex align-items-center">
								<div
									class="card-icon rounded-circle d-flex align-items-center justify-content-center">
									<i class="bi bi-calendar2-x"></i>
								</div>
								<div class="ps-3">
									<h6> {{$total_expired}} </h6>
								</div>
							</div>
						</div>

					</div>
				</div>
				<!-- End akan kadaluarsa -->

				<!-- stok sedikit -->
				<div class="col-xxl-6 col-xl-12">

					<div class="card info-card customers-card">

						<div class="card-body">
							<h5 class="card-title">Stok Sedikit
								<span>| kurang dari 10 </span>
							</h5>

							<div class="d-flex align-items-center">
								<div
									class="card-icon rounded-circle d-flex align-items-center justify-content-center">
									<i class="bi bi-cart"></i>
								</div>
								<div class="ps-3">
									<h6>{{$total_minimum}}</h6>
								</div>
							</div>

						</div>
					</div>

				</div>
				<!-- End stok sedikit -->

				<!-- data pegawai -->
				<div class="col-xxl-6 col-xl-12">

					<div class="card info-card customers-card">

						<div class="card-body">
							<h5 class="card-title">Data Pegawai
								<span></span>
							</h5>

							<div class="d-flex align-items-center">
								<div
									class="card-icon rounded-circle d-flex align-items-center justify-content-center">
									<i class="bi bi-people"></i>
								</div>
								<div class="ps-3">
									<h6> {{ $total_pegawai }} </h6>

								</div>
							</div>

						</div>
					</div>

				</div>
				<!-- End data pegawai -->
			</div>
		</div>

		<div class="col-lg-12">

			{{-- Activity --}}
			<div class="card">

				<div class="card-body">
					<h5 class="card-title">Aktivitas Terbaru
					</h5>

					<div class="activity">
						
						@if ( count($activity) == 0)

							<div class="d-flex">
								<div class="activite-label"> Tidak ada aktivitas </div>
							</div>

						@else

							@php
								$i_logs = 1;
								$color_theme = array('primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark');
							@endphp

							@foreach ($activity as $act)

								<div class="activity-item d-flex">
									<div class="activite-label"> {{ $act->updated_at->diffForHumans() }} </div>
									<i class='bi bi-circle-fill activity-badge text-{{$color_theme[rand(0,6)]}} align-self-start'></i>
									<div class="activity-content">
										[{{$act->source}}] {{ $act->description }} oleh {{ $act->user_name }}
									</div>
								</div>
								
								@php
									$i_logs++;
								@endphp

							@endforeach

							@if ( $i_logs >= 1 )

								<div class="d-flex">
									<a href="{{ route('logs') }}" class="btn btn-outline-secondary btn-sm"> Lihat Semua </a>
								</div>
								
							@endif

						@endif

					</div>

				</div>
			</div>
			<!-- End Activity -->
			
		</div>

	</div>
            
                

                    


@endsection