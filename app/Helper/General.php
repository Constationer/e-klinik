<?php

namespace App\Helper;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class General {

    public static function get_enum_values( $table, $field )
    {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'");
        $type = array_shift($type);
        
        $type = $type->Type;

        $enum = explode(",", str_replace(array("enum(", ")", "'"), "", $type));
        return $enum;
    }

    public static function get_date_diff($date_start, $date_end) {
        $toDate = Carbon::parse($date_start);
        $fromDate = Carbon::parse($date_end);
  
        $days   = $toDate->diffInDays($fromDate);
        $months = $toDate->diffInMonths($fromDate);
        $years  = $toDate->diffInYears($fromDate);

        $return = array(
            'days' => $days,
            'months' => $months,
            'years' => $years
        );

        return $return;

    }

    public static function blood_type() {
        return array('O','A','B','AB');
    }

}