<?php

use App\Models\Logs;

use App\Models\User;
use App\Models\Check;

use App\Models\Doctor;
use App\Http\Middleware;
use App\Models\Employee;
use App\Models\Medicine;
use App\Models\UserRole;
use App\Models\SettingSite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingSiteController;
use App\Http\Controllers\CheckMedicinesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!


php artisan cache:clear
php artisan route:cache
|
*/


// TEST ==================================================
Route::get('/generate_password/{post}', [HomeController::class, 'generate_password']);

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('config:clear');
    // return what you want
});


// LOGIN & LOGOUT ============================================
// Route::get('/', function () {
//     return redirect('/login');
// });

Route::get('/', function () {

    $setting = SettingSite::get()->toArray();

    return view(
        'akses',
        [
            'web_name'      => $setting[0]['value'],
            'logo_name'     => $setting[2]['value'],
            'logo_width'    => $setting[3]['value'],
        ]
    );
});

Route::get('/recovery', function () {

    $setting = SettingSite::get()->toArray();

    return view(
        'recovery',
        [
            'web_name'      => $setting[0]['value'],
            'logo_name'     => $setting[2]['value'],
            'logo_width'    => $setting[3]['value'],
        ]
    );
})->name('recovery');

Route::get('/login', function () {

    $setting = SettingSite::get()->toArray();

    return view(
        'login',
        [
            'web_name'      => $setting[0]['value'],
            'logo_name'     => $setting[2]['value'],
            'logo_width'    => $setting[3]['value'],
        ]
    );
})->name('login');


Route::post('/akses-action', [PDFController::class, 'akses'])->name('akses_action');

Route::controller(LoginController::class)->group(function () {
    Route::post('/login_action', 'authenticate')->name('login_action');
    Route::post('/recovery_action', 'recovery')->name('recovery_action');
    Route::get('/logout', 'logout')->middleware('auth')->name('logout');
});

// DASHBOARD =====================================================
// ===============================================================
// route DashboardController::class
Route::controller(DashboardController::class)->group(function () {

    Route::get('/dashboard', function () {
        return view('home', [
            'page_title'    => 'Dashboard',
            'total_obat'    => Medicine::where('status', 1)->count(),
            'total_pegawai' => Employee::where('status', 1)->count(),
            'total_expired' => Medicine::minimum_expired_date(),
            'total_minimum' => Medicine::minimum_stock(),
            'activity'      => Logs::list_logs(10),
            'data_bb' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->whereRaw('checks.berat > (checks.Tinggi - 100)')
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->get(),
            'data_suhu' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->where(function ($query) {
                    $query->where('checks.suhu', '>', 37.5);
                        // ->orWhere('checks.suhu', '<', 36.5);
                })
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->get(),
            'data_kolesterol' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->where(function ($query) {
                    $query->where('checks.kolesterol', '>', 239);
                        // ->orWhere('checks.kolesterol', '<', 200);
                })
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->get(),
            'data_asamurat' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->where(function ($query) {
                    $query->where('checks.asam_urat', '>', 6.0)
                        ->orWhere('checks.asam_urat', '<', 2.4);
                })
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->get(),
            'data_tekanan' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->where(function ($query) {
                    $query->whereRaw("SUBSTRING_INDEX(checks.tekanan, '/', 1) > 120")
                        ->orWhereRaw("SUBSTRING_INDEX(checks.tekanan, '/', 1) < 90");
                })
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->get(),
            'data1' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->whereRaw('checks.berat > (checks.Tinggi - 100)')
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->count(),
            'data2' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->where(function ($query) {
                    $query->where('checks.suhu', '>', 37.5);
                        // ->orWhere('checks.suhu', '<', 36.5);
                })
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->count(),
            'data4' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->where(function ($query) {
                    $query->where('checks.kolesterol', '>', 239);
                        // ->orWhere('checks.kolesterol', '<', 200);
                })
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->count(),
            'data3' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->where(function ($query) {
                    $query->where('checks.asam_urat', '>', 6.0)
                        ->orWhere('checks.asam_urat', '<', 2.4);
                })
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->count(),
            'data5' => Check::select('checks.*', 'employees.name as employee_name')
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->where('checks.check_type', 'Periksa')
                ->where(function ($query) {
                    $query->whereRaw("SUBSTRING_INDEX(checks.tekanan, '/', 1) > 120")
                        ->orWhereRaw("SUBSTRING_INDEX(checks.tekanan, '/', 1) < 90");
                })
                ->join(DB::raw('(SELECT employee_id, MAX(created_at) AS max_created_at FROM checks GROUP BY employee_id) latest_checks'), function ($join) {
                    $join->on('checks.employee_id', '=', 'latest_checks.employee_id');
                })
                ->whereColumn('checks.created_at', '=', 'latest_checks.max_created_at')
                ->count(),
        ]);
    })->middleware('auth')->name('dashboard');
});

// route controller medicine controller
Route::controller(MedicineController::class)->group(function () {

    Route::get('/master-obat', function () {
        return view('master-obat', [
            'page_title'    => 'Master Obat',
            'user_roles'    => UserRole::check_user_group()
        ]);
    })->middleware('auth')->name('master-obat');

    Route::get('/master-obat-detail/{code}', function () {

        $medicine = Medicine::where('code', request()->code)->first();

        return view('master-obat-detail', [
            'page_title'    => 'Detail Obat - ' . $medicine['name'],
            'medicine_id'   => $medicine['id'],
            'user_roles'    => UserRole::check_user_group()
        ]);
    })->middleware('auth')->name('master-obat-detail');

    route::get('/master-obat-data', 'master_obat_data')->middleware('auth')->name('master_obat_data');
    route::post('/master-obat-submit', 'master_obat_submit')->middleware('auth')->name('master_obat_submit');
    route::get('/master-obat-edit/{id}', 'master_obat_edit')->middleware('auth')->name('master_obat_edit');
    route::post('/master-obat-update', 'master_obat_update')->middleware('auth')->name('master_obat_update');
    route::get('/master-obat-delete/{id}', 'master_obat_delete')->middleware('auth')->name('master_obat_delete');

    // detail obat
    route::get('/master-obat-detail-data', 'master_obat_detail_data')->middleware('auth')->name('master_obat_detail_data');
    route::post('/master-obat-detail-submit', 'master_obat_detail_submit')->middleware('auth')->name('master_obat_detail_submit');
    route::get('/master-obat-detail-delete/{id}', 'master_obat_detail_delete')->middleware('auth')->name('master_obat_detail_delete');

    // list expired date available medicine
    route::get('/master-obat-expired-data/{medicine_id}', 'master_obat_expired_data')->middleware('auth')->name('master_obat_expired_data');
});

// route doctor controller
Route::controller(DoctorController::class)->group(function () {

    Route::get('/master-dokter', function () {
        return view('master-dokter', [
            'page_title'   => 'Master Dokter',
            'user_roles'   => UserRole::check_user_group()
        ]);
    })->middleware('auth')->name('master-dokter');


    route::get('/master-dokter-data', 'master_dokter_data')->middleware('auth')->name('master_dokter_data');
    route::post('/master-dokter-submit', 'master_dokter_submit')->middleware('auth')->name('master_dokter_submit');
    route::post('/master-dokter-edit/{id}', 'master_dokter_edit')->middleware('auth')->name('master_dokter_edit');
    route::post('/master-dokter-update', 'master_dokter_update')->middleware('auth')->name('master_dokter_update');
    route::get('/master-dokter-delete/{id}', 'master_dokter_delete')->middleware('auth')->name('master_dokter_delete');
});

// route user controller
Route::controller(UserController::class)->group(function () {

    Route::get('/master-user', function () {
        return view('master-user', [
            'page_title'        => 'Master User',
            'user_group'        => DB::table('user_groups')->where('status', 1)->get()->toArray(),
            'user_roles'        => UserRole::check_user_group()
        ]);
    })->middleware('auth')->name('master-user');


    route::get('/master-user-data', 'master_user_data')->middleware('auth')->name('master_user_data');
    route::post('/master-user-submit', 'master_user_submit')->middleware('auth')->name('master_user_submit');
    route::post('/master-user-edit/{id}', 'master_user_edit')->middleware('auth')->name('master_user_edit');
    route::post('/master-user-update', 'master_user_update')->middleware('auth')->name('master_user_update');
    route::get('/master-user-delete/{id}', 'master_user_delete')->middleware('auth')->name('master_user_delete');

    // pengaturan user 
    route::get('/pengaturan-user', function () {
        return view('pengaturan-user', [
            'page_title'        => 'Pengaturan User',
            'user'              => User::where('id', Auth::user()->id)->first(),
        ]);
    })->middleware('auth')->name('pengaturan_user');

    route::post('/pengaturan-user-update', 'pengaturan_user_update')->middleware('auth')->name('pengaturan_user_update');
});

// route employee controller
Route::controller(EmployeeController::class)->group(function () {

    Route::get('/master-pegawai', function () {
        return view('master-pegawai', [
            'page_title'        => 'Master Pegawai',
            'datagrid_route'    => 'master_pegawai_data',
            'employee'          => Employee::where('status', 1)->get(),
            'user_roles'        => UserRole::check_user_group()
        ]);
    })->middleware('auth')->name('master-pegawai');

    route::get('/master-pegawai-data', 'master_pegawai_data')->middleware('auth')->name('master_pegawai_data');
    route::post('/master-pegawai-submit', 'master_pegawai_submit')->middleware('auth')->name('master_pegawai_submit');
    route::post('/master-pegawai-edit/{id}', 'master_pegawai_edit')->middleware('auth')->name('master_pegawai_edit');
    route::post('/master-pegawai-update', 'master_pegawai_update')->middleware('auth')->name('master_pegawai_update');
    route::get('/master-pegawai-delete/{id}', 'master_pegawai_delete')->middleware('auth')->name('master_pegawai_delete');
    route::get('/master-pegawai-change-status/{id}', 'master_pegawai_change_status')->middleware('auth')->name('master_pegawai_change_status');
});

// route check controller
Route::controller(CheckController::class)->group(function () {

    Route::get('/periksa', function () {
        return view('periksa', [
            'page_title'        => 'Pemeriksaan',
            'employee'          => Employee::where('status', '=', '1')->get(),
            'doctor'            => Doctor::where('status', '=', '1')->get(),
            'user_roles'        => UserRole::check_user_group()
        ]);
    })->middleware('auth')->name('periksa');

    route::get('/periksa-data', 'periksa_data')->middleware('auth')->name('periksa_data');

    route::get('periksa-detail/{id}', function ($id) {

        $check = Check::select('checks.*', 'employees.name as employee_name', 'doctors.name as doctor_name')
            ->where('checks.id', '=', $id)
            ->join('employees', 'employees.id', '=', 'checks.employee_id')
            ->join('doctors', 'doctors.id', '=', 'checks.doctor_id')
            ->first();

        return view('periksa-detail', [
            'page_title'        => 'Pemeriksaan Detail - ' . $check->code,
            'check'             => $check,
            'medicine'          => Medicine::where('status', '=', '1')->get()
        ]);
    })->middleware('auth')->name('periksa_detail');

    route::post('/periksa-submit', 'periksa_submit')->middleware('auth')->name('periksa_submit');
    route::post('/periksa-edit/{id}', 'periksa_edit')->middleware('auth')->name('periksa_edit');
    route::post('/periksa-update', 'periksa_update')->middleware('auth')->name('periksa_update');
    route::get('/periksa-delete/{id}', 'periksa_delete')->middleware('auth')->name('periksa_delete');
});

// route check medicines controller
Route::controller(CheckMedicinesController::class)->group(function () {
    route::get('/periksa-obat-data/{id}', 'periksa_obat_data')->middleware('auth')->name('periksa_obat_data');
    route::post('/periksa-obat-submit', 'periksa_obat_submit')->middleware('auth')->name('periksa_obat_submit');
    route::post('/periksa-obat-edit/{id}', 'periksa_obat_edit')->middleware('auth')->name('periksa_obat_edit');
    route::post('/periksa-obat-update', 'periksa_obat_update')->middleware('auth')->name('periksa_obat_update');
    route::get('/periksa-obat-delete/{id}', 'periksa_obat_delete')->middleware('auth')->name('periksa_obat_delete');
});

// route report controller
Route::controller(ReportController::class)->group(function () {

    Route::get('/laporan-mutasi-obat', function () {
        return view('laporan-mutasi-obat', [
            'page_title'    => 'Laporan Mutasi Obat',
            'medicines'      => Medicine::where('status', '=', '1')->get(),
            'user_roles'    => UserRole::check_user_group()
        ]);
    })->middleware('auth')->name('laporan');

    route::get('/laporan-data', 'laporan_data')->middleware('auth')->name('laporan_data');
    route::get('/laporan-detail/{id}', 'laporan_detail')->middleware('auth')->name('laporan_detail');
    route::get('/laporan-detail-obat/{id}', 'laporan_detail_obat')->middleware('auth')->name('laporan_detail_obat');

    route::get('/laporan-export/', 'export')->middleware('auth')->name('laporan_export');

    // laporan persediaan obat
    route::get('/laporan-persediaan-obat/', function () {
        return view('laporan-persediaan-obat', [
            'page_title'    => 'Laporan Persediaan Obat',
            'medicines'      => Medicine::where('status', '=', '1')->get(),
            'user_roles'    => UserRole::check_user_group()
        ]);
    })->middleware('auth')->name('laporan_persediaan_obat');

    // Laporan Kunjungan Pasien
    route::get('/laporan-kunjungan-pasien/', function () {
        return view('laporan-kunjungan-pasien', [
            'page_title'    => 'Laporan Kunjungan Pasien',
            'user_roles'    => UserRole::check_user_group(),
            'doctors'       => Doctor::where('status', '=', '1')->get(),
            'employees'     => Employee::where('status', '=', '1')->get(),
        ]);
    })->middleware('auth')->name('laporan_kunjungan_pasien');

    route::get('/laporan-kunjungan-pasien-export/{start_date}/{end_date}/{doctor_id}/{employee_id}', 'laporan_kunjungan_pasien_export')->middleware('auth')->name('laporan_kunjungan_pasien_export');
});

// route pengaturan controller 
Route::controller(SettingSiteController::class)->group(function () {

    Route::get('/pengaturan', function () {

        $setting = SettingSite::get()->toArray();

        return view('pengaturan', [
            'page_title'    => 'Pengaturan',
            'web_name'      => $setting[0]['value'],
            'web_desc'      => $setting[1]['value'],
            'user_roles'    => UserRole::check_user_group()
        ]);
    })->middleware('auth')->name('pengaturan');

    route::post('/pengaturan-update', 'pengaturan_update')->middleware('auth')->name('pengaturan_update');
});

// route PDFController controller 
Route::controller(PDFController::class)->group(function () {
    route::get('/pdf-check/{type}/{code}', 'check')->name('pdf_check');
    route::get('/pdf-medicine-per-item/{year}/{code}', 'medicine_per_item')->middleware('auth')->name('pdf_medicine_per_item');
});

// route logs controller 
Route::controller(LogsController::class)->group(function () {

    Route::get('/logs', function () {
        return view('logs', [
            'page_title'    => 'Logs Aktifitas'
        ]);
    })->middleware('auth')->name('logs');

    route::get('/logs-data/{year}', 'logs_data')->middleware('auth')->name('logs_data');
});
