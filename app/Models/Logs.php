<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Logs extends Model
{
    use HasFactory;


    public static function list_logs($limit = null)
    {
        $logs = Logs::select('logs.*', 'users.name as user_name')
            ->join('users', 'users.id', '=', 'logs.user_id')
            ->orderBy('logs.id', 'desc');
        
        if ($limit) {
            $logs = $logs->limit($limit);
        }

        $logs = $logs->get();
    

        if ($logs) {
            return $logs;
        } else {
            return false;
        }
    }

    public static function insert_logs($source, $description) {

        $logs = Logs::insert([
            'source'        => $source,
            'description'   => $description,
            'user_id'       => auth()->user()->id,
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

    }
}
