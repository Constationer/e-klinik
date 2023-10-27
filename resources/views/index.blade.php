@php

    if ( Auth::check() == false ) {
        redirect('/login');
    }

    $setting   = App\Models\SettingSite::find(1);
    $menus  = App\Models\Menu::menuHead('back');
    $user   = Auth::user();

	// $user_roles = App\Models\UserRole::check_user_group()

@endphp

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>{{ $page_title }} - {{ $setting->value }} </title>
	<meta content="" name="description">
	<meta content="" name="keywords">

	<!-- Favicons -->
	<link href="/assets/img/favicon.png" rel="icon">
	<link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

	<!-- Google Fonts -->
	<link href="https://fonts.gstatic.com" rel="preconnect">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

	<!-- Vendor CSS Files -->
	<link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
	<link href="/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
	<link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
	<link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">

	<!-- Template Main CSS File -->
	<link href="/assets/css/style.css" rel="stylesheet">

	<!-- js file source from online -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

	{{-- select2 --}}
	<link rel="stylesheet" href="/assets/vendor/select2/dist/css/select2.css">
	<script src="/assets/vendor/select2/dist/js/select2.full.min.js"></script>
	
	{{-- datagrid --}}
	<link href="/assets/eui/css/easyui.css" rel="stylesheet">
	<script src="/assets/eui/js/jquery.easyui.min.js"></script>
	<script src="/assets/eui/js/datagrid-filter.js"></script>

</head>
<body>

	@include('partials.header')

	@include('partials.sidebar')

	{{-- content  --}}
	<main id="main" class="main">
		<div class="pagetitle">
			<h1>{{ $page_title }}</h1>			
		</div><!-- End Page Title -->

		@yield('content')
	</main>

  	@include('partials.footer')

	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>	
	
	
	<!-- Vendor JS Files -->
	<script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
	<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="/assets/vendor/chart.js/chart.umd.js"></script>
	<script src="/assets/vendor/echarts/echarts.min.js"></script>
	<script src="/assets/vendor/quill/quill.min.js"></script>
	<script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
	<script src="/assets/vendor/tinymce/tinymce.min.js"></script>
	<script src="/assets/vendor/php-email-form/validate.js"></script>	

	<!-- Template Main JS File -->
	<script src="/assets/js/main.js"></script>

	

	<script>

		$(document).ready(function() {
			// $('.select2').select2(
			// 	{ dropdownParent: "#modal_add" }
			// );
		});


		function delete_alert() {
			
			if ( confirm("Apakah yakin hapus data ini?") ) {
				return true;
			} else {
				return false;
			}			

		}

		

	</script>


</body>
</html>