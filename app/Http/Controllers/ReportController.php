<?php

namespace App\Http\Controllers;

use App\Exports\MedicineExport;
use App\Exports\CheckExport;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Helper\General;

class ReportController extends Controller
{
    // function laporan_data easyui datagrid
    public function laporan_data(Request $request)
    {
        $rows = $request->rows;
        $page = $request->page;
        $sort = isset($request->sort) ? $request->sort : 'medicines.name';
        $order = isset($request->order) ? $request->order : 'asc';

        $offset = ($page-1) * $rows;

        // filter
        if( !empty($request->start_date) ) {
            $start_date = date('Y-m-d', strtotime($request->start_date));

            // $data = Medicine::select('name', 'unit')
            //     ->selectRaw('
            //         COALESCE((
            //             SELECT SUM(`qty`)
            //             FROM `medicine_purchases` AS `add`
            //             WHERE
            //                 `add`.medicine_id = medicines.id 
            //                 AND `add`.status = 1
            //                 AND `add`.expired_date >= "'.$start_date.'"
            //         ),0) AS `total_qty`
            //     ')
            //     ->orderBy($sort, $order);

        }

        $data = Medicine::select('name', 'unit')
            ->selectRaw('
                COALESCE((
                    SELECT SUM(`qty`)
                    FROM `medicine_purchases` AS `add`
                    WHERE
                        `add`.medicine_id = medicines.id 
                        AND `add`.status = 1
                ),0) AS `total_qty`
            ')
            ->selectRaw('
                (
                    SELECT 
                        `medicine_purchases`.`expired_date`
                    FROM `medicine_purchases`
                    WHERE
                        `medicine_purchases`.`medicine_id` = `medicines`.`id`
                        AND `medicine_purchases`.`status` = 1
                        AND `medicine_purchases`.`qty` > (SELECT COALESCE(SUM(`qty`),0) FROM `check_medicines` WHERE `check_medicines`.`medicine_purchase_id` = `medicine_purchases`.`id` AND `check_medicines`.`status` = 1 )
                    ORDER BY `medicine_purchases`.`expired_date` ASC
                    LIMIT 1
                ) AS `expired_date`
            ')
            // query medicines_purchases and check_medicines | last stock
            ->selectRaw("
                COALESCE((
                    SELECT SUM(`qty`)
                    FROM `medicine_purchases` AS `add`
                    WHERE
                        `add`.medicine_id = medicines.id 
                        AND `add`.status = 1
                        AND `add`.purchase_date < '".$start_date."'
                ),0) - COALESCE((
                    SELECT SUM(`qty`)
                    FROM `check_medicines` AS `usage`
                        LEFT JOIN `checks` ON `usage`.`check_id` = `checks`.`id`
                    WHERE
                        `usage`.medicine_id = medicines.id 
                        AND `usage`.status = 1
                        AND `checks`.`status` = 1
                        AND `checks`.`date` < '".$start_date."'
                ),0) AS `stock_awal`
            ")
            // query to medicines purchase | in
            ->selectRaw("
                COALESCE((
                    SELECT SUM(`qty`)
                    FROM `medicine_purchases` AS `add`
                    WHERE
                        `add`.medicine_id = medicines.id 
                        AND `add`.status = 1
                        AND `add`.`purchase_date` BETWEEN '".$request->start_date."' AND '".$request->end_date."'
                ),0) AS `stock_in`
            ")
            // query to check medicines | out
            ->selectRaw("
                COALESCE((
                    SELECT SUM(`qty`)
                    FROM `check_medicines` AS `usage`
                        LEFT JOIN `checks` ON `usage`.`check_id` = `checks`.`id`
                    WHERE
                        `usage`.medicine_id = medicines.id 
                        AND `usage`.status = 1
                        AND `checks`.`status` = 1
                        AND `checks`.`date` BETWEEN '".$request->start_date."' AND '".$request->end_date."'
                ),0) AS `stock_out`
            ")
            
            ->where('status', 1)
            ->orderBy($sort, $order);

            // filter - nama obat 
            if( !empty($request->medicine_id) ) {
                $data->where('medicines.id', '=', $request->medicine_id);
            }
            $total  = $data->count();
            $data   = $data->get();

            // show last query 
            // dd($data->toSql());

            foreach($data as $key => $value) {
                $data[$key]['stock_akhir'] = $value['stock_awal'] + $value['stock_in'] - $value['stock_out'];
            }                    

        return response()->json([
            'total' => $total,
            'rows'  => $data,
        ]);
    }

    public function export(Request $request) 
    {

        // dd($request->all());

        // send $request to export class

        $start_date     = $request->start_date;
        $end_date       = $request->end_date;
        $medicine_id    = $request->medicine_id;

        return Excel::download(new MedicineExport(
            $start_date,$end_date,$medicine_id), 
            'MutasiObat_'.$start_date.'_'.$end_date.'.xlsx'
        );

    }
    
    // laporan_kunjungan_pasien_export
    public function laporan_kunjungan_pasien_export(Request $request) 
    {
        $start_date     = $request->start_date;
        $end_date       = $request->end_date;
        $doctor_id      = $request->doctor_id;
        $employee_id    = $request->employee_id;

        return Excel::download(new CheckExport(
            $start_date,$end_date,$doctor_id,$employee_id), 
            'LaporanKunjunganPasien_'.$start_date.'_'.$end_date.'.xlsx'
        );

    }
}
