<?php

namespace App\Exports;

use App\Models\Check;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CheckExport implements FromView
{
    private $start_date;
    private $end_date;
    private $doctor_id;
    private $employee_id;

    public function __construct ($start_date,$end_date, $doctor_id, $employee_id)
    {
        $this->start_date   = $start_date;
        $this->end_date     = $end_date;
        $this->doctor_id    = $doctor_id;
        $this->employee_id  = $employee_id;

    }

    public function view(): View
    {

        $get_data = Check::select( 'checks.code', 'checks.check_type', 'checks.date')
            // join ke employees
            ->join('employees', 'employees.id', '=', 'checks.employee_id')
            ->selectRaw('employees.name AS employee_name')
            // join ke doctors
            ->join('doctors', 'doctors.id', '=', 'checks.doctor_id')
            ->selectRaw('doctors.name AS doctor_name')

            // filter - date
            ->whereBetween('checks.date', [$this->start_date, $this->end_date]) 

            // filter - doctor
            ->when($this->doctor_id, function ($query, $doctor_id) {
                return $query->where('checks.doctor_id', $doctor_id);
            })

            // filter - employee
            ->when($this->employee_id, function ($query, $employee_id) {
                return $query->where('checks.employee_id', $employee_id);
            })

            ->get();

        return view('exports.checks', [
            'title'         => 'Laporan Kunjungan Pasien',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'doctor_id'     => $this->doctor_id,
            'employee_id'   => $this->employee_id,
            'data'          => $get_data
        ]);
    }
}