<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\User;
use App\Models\Check;
use App\Models\Employee;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;


class PDFController extends Controller
{
    public function generatePDF()
    {
        $users = User::get();

        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
            'users' => $users
        ];

        $pdf = PDF::loadView('welcome', $data);

        return $pdf->download('itsolutionstuff.pdf');
    }

    public function check(Request $request)
    {

        // file options
        if ($request->type == 'check') { // pemeriksaan

            $check = Check::select(
                'checks.*',
                'doctors.name as doctors_name',
                'employees.name as employee_name',
                'gender',
                'address',
                'blood_type',
                'class',
                'work_unit'
            )
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->join('doctors', 'doctors.id', '=', 'checks.doctor_id')
                ->where('checks.code', $request->code)
                ->first();

            $file_type  = 'Pemeriksaan - ' . $check->code;
            $filename   = 'Pemeriksaan - ' . $check->code . '.pdf';

            $employee_id = $check->employee_id;
            $number      = $check->code;
        } else { // rekam medis

            // check
            $check = Check::select(
                'checks.*',
                'doctors.name as doctors_name',
                'employees.name as employee_name',
                'employees.nip as employee_nip',
                'gender',
                'address',
                'blood_type',
                'class',
                'work_unit'
            )
                ->join('employees', 'employees.id', '=', 'checks.employee_id')
                ->join('doctors', 'doctors.id', '=', 'checks.doctor_id')
                ->where('employees.akses', $request->code)
                ->where('checks.check_type', 'Periksa')
                ->get();

            if ($check->isEmpty() || !isset($check[0]->employee_name)) {
                // Handle the case where the array is empty or the key doesn't exist
                return back()->with('error', 'Data rekam medis belum tersedia / Kode Akses tidak valid.');
            }

            $temp = Employee::where('akses', $request->code)->first();
            $employee_id = $temp->id;
            $number      = 'RM/' . $check[0]->employee_nip;

            // RM
            $file_type  = 'Rekam Medis';
            $filename   = 'Rekam Medis - ' . $check[0]->employee_name . '.pdf';
        }

        // info pegawai
        $employee = Employee::find($employee_id);

        // check detail
        $check_detail = array(
            ['a', 'b'],
            ['c', 'd']
        );

        $data = [
            'title'         => $file_type,
            'number'        => $number,
            'employee'      => $employee,
            'check'         => $check,
            'type'          => $request->type
        ];

        $pdf = PDF::loadView('pdf.check', $data);

        // dom PDF preview in browser
        return $pdf->stream($filename);
    }

    public function medicine_per_item(Request $request)
    {

        // data obat
        $get_medicine = Medicine::where('code', $request->code)->firstOrFail();

        // data obat STOCK AWAL
        // query ke medicine_purchases 
        $medicine_purchases_stock = DB::table('medicine_purchases')
            ->selectRaw(' COALESCE(SUM(`qty`),0) as qty')
            ->where('medicine_purchases.medicine_id', $get_medicine->id)
            ->where('medicine_purchases.status', '1')
            ->where('medicine_purchases.purchase_date', '<', $request->year . '-01-01')
            ->first();

        // query ke check_medicines
        $check_medicines_stock = DB::table('check_medicines')
            ->selectRaw(' COALESCE(SUM(`qty`),0) as qty')
            ->join('checks', 'checks.id', '=', 'check_medicines.check_id')
            ->where('check_medicines.medicine_id', $get_medicine->id)
            ->where('checks.status', '1')
            ->where('checks.date', '<', $request->year . '-01-01')
            ->first();

        // data obat IN
        $medicine_purchases = DB::table('medicine_purchases')
            ->select(
                'invoice_number as data_number',
                'purchase_date as data_date'
            )
            ->selectRaw("
                '-' as data_desc,
                `qty` as data_in,
                '0' as data_out
            ")
            ->where('medicine_purchases.medicine_id', $get_medicine->id)
            ->where('medicine_purchases.status', '1')
            ->where('medicine_purchases.purchase_date', 'like', '%' . $request->year . '%');

        // data obat OUT
        $check_medicines = DB::table('check_medicines')
            ->select(
                'checks.code as data_number',
                'checks.date as data_date',
                'check_medicines.description as data_desc'
            )
            ->selectRaw(" 
                '0' as data_in,
                check_medicines.qty as data_out
            ")
            ->join('checks', 'checks.id', '=', 'check_medicines.check_id')
            ->where('check_medicines.medicine_id', $get_medicine->id)
            ->where('check_medicines.status', 1)
            ->where('checks.status', '1')
            ->where('checks.date', 'like', '%' . $request->year . '%')
            ->union($medicine_purchases)
            ->orderBy('data_date', 'asc')
            ->get();
        // ->toSql();

        // die($check_medicines);    

        $filename = 'KARTU PERSEDIAAN OBAT - ' . $request->year . ' - ' . $get_medicine->name . '.pdf';

        $data = [
            'title'         => $filename,
            'year'          => $request->year,
            'medicine'      => $get_medicine,
            'last_stock'    => $medicine_purchases_stock->qty - $check_medicines_stock->qty,
            'mutation'      => $check_medicines
        ];

        $pdf = PDF::loadView('pdf.medicine-per-item', $data);

        // dom PDF preview in browser
        return $pdf->stream($filename);
    }

    public function akses(Request $request)
    {
        return redirect()->route('pdf_check', ['type' => 'rm', 'code' => $request->akses]);
    }
}
