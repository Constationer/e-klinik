<?php

namespace App\Exports;

use App\Models\Medicine;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MedicineExport implements FromView
{
    private $start_date;
    private $end_date;
    private $medicine_id;

    public function __construct ($start_date,$end_date, $medicine_id)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->medicine_id = $medicine_id;

    }

    public function view(): View
    {

        $medicines = Medicine::select('name', 'unit')
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
                    @expired_date := `medicine_purchases`.`expired_date`
                FROM `medicine_purchases`
                WHERE
                    `medicine_purchases`.`medicine_id` = `medicines`.`id`
                    AND `medicine_purchases`.`status` = 1
                    AND `medicine_purchases`.`qty` > (SELECT COALESCE(SUM(`qty`),0) FROM `check_medicines` WHERE `check_medicines`.`medicine_purchase_id` = `medicine_purchases`.`id` AND `check_medicines`.`status` = 1 )
                ORDER BY `medicine_purchases`.`expired_date` ASC
                LIMIT 1
            ) AS `expired_date`
        ')
        
        ->selectRaw('
            CASE WHEN DATEDIFF(@expired_date, NOW()) <= 0 THEN "EXPIRED"
            WHEN DATEDIFF(@expired_date, NOW()) <= 90 THEN "WARNING"
            ELSE "OK"
            END AS `status_stock`
        ')
        
        // query medicines_purchases and check_medicines | last stock
        ->selectRaw("
            COALESCE((
                SELECT SUM(`qty`)
                FROM `medicine_purchases` AS `add`
                WHERE
                    `add`.medicine_id = medicines.id 
                    AND `add`.status = 1
                    AND `add`.purchase_date < '".$this->start_date."'
            ),0) - COALESCE((
                SELECT SUM(`qty`)
                FROM `check_medicines` AS `usage`
                    LEFT JOIN `checks` ON `usage`.`check_id` = `checks`.`id`
                WHERE
                    `usage`.medicine_id = medicines.id 
                    AND `usage`.status = 1
                    AND `checks`.`status` = 1
                    AND `checks`.`date` < '".$this->start_date."'
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
                    AND `add`.`purchase_date` BETWEEN '".$this->start_date."' AND '".$this->end_date."'
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
                    AND `checks`.`date` BETWEEN '".$this->start_date."' AND '".$this->end_date."'
            ),0) AS `stock_out`
        ");

        $medicines->where('status', 1);

        // filter - nama obat
        if( !empty($this->medicine_id) ) {
            $medicines->where('medicines.id', '=', $this->medicine_id);
        }
        $medicines = $medicines->get();

        return view('exports.medicines', [
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'medicines'     => $medicines
        ]);
    }
}