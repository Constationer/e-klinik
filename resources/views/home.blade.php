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
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
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
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar2-x"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6> {{ $total_expired }} </h6>
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
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $total_minimum }}</h6>
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
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
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
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Statistik Kesehatan Pegawai</h5>
                        <div class="chart-container"
                            style="display: flex;
						justify-content: center;
						align-items: center;
						max-width: 300px; /* Adjust this value as needed */
						margin: 0 auto;">
                            <canvas id="myPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">

                {{-- Activity --}}
                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title">Data Pegawai Kurang Sehat
                        </h5>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header px-3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                        aria-controls="flush-collapseOne">
                                        Berat Badan Tidak Ideal
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="acoordion-body px-3 my-3">
                                        <table id="example1" class="table display table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Berat</th>
                                                    <th>Check Terakhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data_bb as $key)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $key->employee_name }}</td>
                                                        <td>{{ $key->berat }}Kg</td>
                                                        <td>{{ $key->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="acoordion-header px-3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                        aria-controls="flush-collapseTwo">
                                        Suhu Tubuh Tidak Normal
                                    </button>
                                </h2>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="acoordion-body px-3">
                                        <table id="example2" class="table display table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Suhu Tubuh</th>
                                                    <th>Check Terakhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data_suhu as $key)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $key->employee_name }}</td>
                                                        <td>{{ $key->suhu }}c</td>
                                                        <td>{{ $key->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="acoordion-header px-3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseThree" aria-expanded="false"
                                        aria-controls="flush-collapseThree">
                                        Asam Urat
                                    </button>
                                </h2>
                                <div id="flush-collapseThree" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="acoordion-body px-3">
                                        <table id="example3" class="table display table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Asam Urat</th>
                                                    <th>Check Terakhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data_asamurat as $key)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $key->employee_name }}</td>
                                                        <td>{{ $key->asam_urat }}mg/dL</td>
                                                        <td>{{ $key->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="acoordion-header px-3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseFour" aria-expanded="false"
                                        aria-controls="flush-collapseFour">
                                        Kolesterol
                                    </button>
                                </h2>
                                <div id="flush-collapseFour" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body px-3 my-2">
                                        <table id="example4" class="table display table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Kolesterol</th>
                                                    <th>Check Terakhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data_kolesterol as $key)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $key->employee_name }}</td>
                                                        <td>{{ $key->kolesterol }}</td>
                                                        <td>{{ $key->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="acoordion-header px-3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseFive" aria-expanded="false"
                                        aria-controls="flush-collapseFive">
                                        Tekanan Darah Tidak Normal
                                    </button>
                                </h2>
                                <div id="flush-collapseFive" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="acoordion-body px-3">
                                        <table id="example5" class="table display bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Tekanan</th>
                                                    <th>Check Terakhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data_tekanan as $key)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $key->employee_name }}</td>
                                                        <td>{{ $key->tekanan }}mmHg</td>
                                                        <td>{{ $key->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End Activity -->

                <div class="col-lg-12">

                    {{-- Activity --}}
                    <div class="card">

                        <div class="card-body">
                            <h5 class="card-title">Aktivitas Terbaru
                            </h5>

                            <div class="activity">

                                @if (count($activity) == 0)
                                    <div class="d-flex">
                                        <div class="activite-label"> Tidak ada aktivitas </div>
                                    </div>
                                @else
                                    @php
                                        $i_logs = 1;
                                        $color_theme = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
                                    @endphp

                                    @foreach ($activity as $act)
                                        <div class="activity-item d-flex">
                                            <div class="activite-label"> {{ $act->updated_at->diffForHumans() }} </div>
                                            <i
                                                class='bi bi-circle-fill activity-badge text-{{ $color_theme[rand(0, 6)] }} align-self-start'></i>
                                            <div class="activity-content">
                                                [{{ $act->source }}] {{ $act->description }} oleh {{ $act->user_name }}
                                            </div>
                                        </div>

                                        @php
                                            $i_logs++;
                                        @endphp
                                    @endforeach

                                    @if ($i_logs >= 1)
                                        <div class="d-flex">
                                            <a href="{{ route('logs') }}" class="btn btn-outline-secondary btn-sm"> Lihat
                                                Semua </a>
                                        </div>
                                    @endif
                                @endif

                            </div>

                        </div>
                    </div>
                    <!-- End Activity -->

                </div>

            </div>




            @push('script')
                <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
                <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

                <script>
                    $(document).ready(function() {
                        $('#example1').DataTable();
                        $('#example2').DataTable();
                        $('#example3').DataTable();
                        $('#example4').DataTable();
                        $('#example5').DataTable();
                    });
                </script>
                <script>
                    var ctx = document.getElementById('myPieChart').getContext('2d');

                    var data1 = <?php echo json_encode($data1); ?>;
                    var data2 = <?php echo json_encode($data2); ?>;
                    var data3 = <?php echo json_encode($data3); ?>;
                    var data4 = <?php echo json_encode($data4); ?>;
                    var data5 = <?php echo json_encode($data5); ?>;

                    var data = {
                        labels: ['Berat Badan', 'Suhu Tubuh', 'Asam Urat', 'Kolesterol', 'Tekanan Darah'],
                        datasets: [{
                            barPercentage: 0.9,
                            data: [data1, data2, data3, data4, data5],
                            backgroundColor: [
                                'red',
                                'blue',
                                'green',
                                'yellow',
                                'orange'
                            ]
                        }]
                    };

                    var myPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: data,
                        options: {
                            responsive: false, // Prevent chart from resizing to fit container
                            maintainAspectRatio: true // Keep the specified width and height
                        }
                    });
                </script>
            @endpush
        @endsection
