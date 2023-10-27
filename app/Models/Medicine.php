<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    // function total quantity per obat
    public static function minimum_stock() {
        
        $medicine = Medicine::select('id', 'name')
        ->where('status', '1')
        ->whereRaw("
            ((
                SELECT SUM(qty)
                FROM medicine_purchases AS stock_in
                WHERE
                    stock_in.medicine_id = medicines.id
                    AND stock_in.status = '1'
            ) - 
            (
                SELECT SUM(qty)
                FROM check_medicines AS stock_out
                WHERE
                    stock_out.medicine_id = medicines.id
                    AND stock_out.status = '1'
            )) 
            <= 10
        ")
        ->count();

        return $medicine;


    }

    public static function minimum_expired_date() {

        $medicine = Medicine::select('id', 'name')
        ->selectRaw("
            (
                SELECT MIN(expired_date)
                FROM medicine_purchases AS stock_in
                WHERE
                    stock_in.medicine_id = medicines.id
                    AND stock_in.status = '1'
            ) as expired_date
        ")
        ->where('status', '1')
        ->having('expired_date', '<=', date('Y-m-d', strtotime('+3 month')) )
        ->count();

        return $medicine;


    }

}
