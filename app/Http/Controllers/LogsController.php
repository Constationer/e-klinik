<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Logs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class LogsController extends Controller
{
    // function logs_data
    public function logs_data(Request $request)
    {
        // parameter easyui datagrid
        $page   = $request->page;
        $rows   = $request->rows;
        $sort   = ($request->sort) ? $request->sort : 'logs.id';
        $order  = ($request->order) ? $request->order : 'desc';
        $filter = ($request->filterRules) ? json_decode($request->filterRules) : '';
        $year   = ($request->year) ? $request->year : date('Y');

        // query
        $query = Logs::selectRaw('DATE_FORMAT(`logs`.`updated_at`, "%d-%m-%Y %H:%i:%s") AS `updated_at_custom`')
            ->selectRaw("
                CONCAT(
                    '[',`source`,'] ',
                    `description`,
                    ' Oleh ',
                    `users`.`name`
                ) AS `data_desc`
            ")
            ->whereYear('logs.updated_at', $year)
            ->join('users', 'users.id', '=', 'logs.user_id');

        // query -> filter 
        if ($filter) {
            foreach ($filter as $key => $value) {
                if( $value->field == 'data_desc' ) {

                    $query->where('logs.source', 'like', '%'.$value->value.'%')
                        ->orWhere('logs.description', 'like', '%'.$value->value.'%')
                        ->orWhere('users.name', 'like', '%'.$value->value.'%');

                } else {
                    $query->having('updated_at_custom', 'like', '%'.$value->value.'%');
                }

                
            }
        }

        // query -> do 
        $total  = $query->count();
        $logs   = $query->orderBy($sort, $order)
                ->skip(($page-1)*$rows)->take($rows)
                ->get();

        // return
        $return = [
            'total' => $total,
            'rows'  => $logs
        ];

        return response()->json($return);

    }

    // public function submit 
    public function insert_data(Request $request)
    {
        // $logs = Logs::insert([
        //     'source'        => $request->source,
        //     'description'   => $request->description,
        //     'user_id'       => auth()->user()->id,
        //     'updated_at'    => date('Y-m-d H:i:s')
        // ]);

        // logs insert 
        $logs = DB::insert([
            'source'        => $request->source,
            'description'   => $request->description,
            'user_id'       => auth()->user()->id,
            'updated_at'    => date('Y-m-d H:i:s')
        ]);



        // auth user id not found 


        if ($logs) {
            return response()->json([
                'status' => 'success',
                'message' => 'Logs created successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create logs'
            ]);
        }        
    }

    public function testing() {
        echo 'test : '.auth()->user()->id.' | '.date('Y-m-d H:i:s');
    }

}