<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Medicine;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\MedicinePurchases;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{

    // declare function utama yang bisa digunakan dibanyak function
    public function __construct()
    {
    }

    // function master_obat_submit
    public function master_obat_submit(Request $request)
    {

        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'name'          => 'required|unique:medicines',
            'qty'           => 'required',
            'unit'          => 'required',
            // 'expired_date'  => 'required',
            'category'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);
        }

        $medicine = new Medicine;
        $medicine->code         = 'OBAT-'.date('YmdHis').'-'.rand(100, 999); // 'OBAT-20210803123456-123
        $medicine->name         = $request->name;
        $medicine->unit         = $request->unit;
        $medicine->category     = $request->category;
        $medicine->created_by   = Auth::user()->id;
        $medicine->updated_by   = Auth::user()->id;

        DB::beginTransaction();
        try {
            $medicine->save();

            DB::table('medicine_purchases')->insert([
                'medicine_id'   => $medicine->id,
                'invoice_number'=> $request->invoice_number,
                'purchase_date' => $request->purchase_date,
                'qty'           => $request->qty,
                'expired_date'  => $request->expired_date,
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => Auth::user()->id,
                'updated_at'    => date('Y-m-d H:i:s'),
                'updated_by'    => Auth::user()->id                
            ]);

            DB::commit();

            // logs
            Logs::insert_logs('master_obat', 'Menambahkan data '.$medicine->name);

            return response()->json([
                'status'    => true,
                'message'   => 'Data berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => 'Data gagal ditambahkan'
            ]);
        }

    }

    // master_obat_data datagrid easyui
    public function master_obat_data(Request $request)
    {
        $page   = $request->page;
        $rows   = $request->rows;
        $sort   = ( isset($request->sort)) ? $request->sort : 'id';
        $order  = ( isset($request->order)) ? $request->order : 'asc';
        $search = $request->filterRules;

        $offset = ($page-1) * $rows;        

        $data = Medicine::select('id', 'code', 'name', 'unit', 'category',
            DB::raw('
                COALESCE((
                    SELECT SUM(`qty`)
                    FROM `medicine_purchases` AS `add`
                    WHERE
                        `add`.medicine_id = medicines.id 
                        AND `add`.status = 1
                ),0) AS `total_qty`
            '),
            
            DB::raw('
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
            '),

            DB::raw('
                CASE WHEN DATEDIFF(@expired_date, NOW()) <= 0 THEN "EXPIRED"
                WHEN DATEDIFF(@expired_date, NOW()) <= 90 THEN "WARNING"
                ELSE "OK"
                END AS `status_stock`
            ')

            
        ) 
       
        ->where('status', 1);

        if( !empty($search) ) {            
            $search = json_decode($search, true);
            foreach ($search as $key => $search_value) {

                $op     = $search_value['op'];
                $field  = $search_value['field'];
                $value  = $search_value['value'];

                if ( $field == 'status_stock' ) {
                    $data->having($field, 'like', '%'.$value.'%');
                } else {
                    $data->where($field, 'like', '%'.$value.'%');
                }

            }
        }

        $total = $data->count();

        $data->skip($offset);
        $data->take($rows);
        $data->orderBy($sort, $order);        

        $data = $data->get();        

        // add button to user
        foreach($data as $d) {

            // button 
            if ( in_array(1, UserRole::check_user_group()) ) {
                $d->option = '
                    <a href="#" class="btn btn-outline-info btn-sm m-1" onclick="edit(`'.$d->id.'`)">Edit</a> 
                    <a href="'.route('master-obat-detail', $d->code).'" class="btn btn-outline-info btn-sm m-1">Detail</a> 
                ';
            }
            

            // status
            // $month_diff = \App\Helper\General::get_date_diff($d->expired_date, date('Y-m-d'));        

            // if( $d->total_qty > 0 )  {
            //     if( $month_diff['months'] <= 12 ) {
            //         $d->status_stock = 'WARNING';
            //     } else {
            //         $d->status_stock = 'OK';
            //     }
            // }
        }

        $return = array(
            'total' => $total,
            'rows'  => $data
        );

        return response()->json($return);
    }

    // function master_obat_edit
    public function master_obat_edit(Request $request)
    {
        $id = $request->id;

        $medicine = Medicine::find($id);

        return response()->json([
            'status'    => true,
            'data'      => $medicine
        ]);
    }

    // function master_obat_update
    public function master_obat_update(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'name'          => 'required|unique:medicines,name,'.$request->id,
            'unit'          => 'required',
            'category'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);
        }

        $medicine = Medicine::find($request->id);
        $medicine->name         = $request->name;
        $medicine->unit         = $request->unit;
        $medicine->category     = $request->category;
        $medicine->updated_by   = Auth::user()->id;

        if ( $medicine->save() ) {

            // logs
            Logs::insert_logs('master_obat', 'Mengubah data '.$medicine->name);

            return response()->json([
                'status'    => true,
                'message'   => 'Data berhasil diubah'
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Data gagal diubah'
            ]);
        }

    }

    // function master_obat_delete
    public function master_obat_delete(Request $request)
    {
        $id = $request->id;

        $medicine = Medicine::find($id);
        $medicine->status       = 0;
        $medicine->deleted_by   = Auth::user()->id;
        $medicine->deleted_at   = date('Y-m-d H:i:s');
        
        if( $medicine->save() ) {

            // logs 
            Logs::insert_logs('master_obat', 'Menghapus data '.$medicine->name);

            return response()->json([
                'status'    => true,
                'message'   => 'Data berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Data gagal dihapus'
            ]);
        }

    }


    // DETAIL OBAT

    // funtion master_obat_detail_submit
    public function master_obat_detail_submit(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'invoice_number'    => 'required',
            'purchase_date'     => 'required',
            'qty'               => 'required',
            'expired_date'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);
        }

        
        $insert = DB::table('medicine_purchases')->insert([
            'medicine_id'   => $request->medicine_id,
            'invoice_number'=> $request->invoice_number,
            'purchase_date' => $request->purchase_date,
            'qty'           => $request->qty,
            'expired_date'  => $request->expired_date,
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => Auth::user()->id,
            'updated_at'    => date('Y-m-d H:i:s'),
            'updated_by'    => Auth::user()->id                
        ]);

        if ( $insert ) {
            
            // Medicine Logs
            $medicine_logs = Medicine::find($request->medicine_id);
            Logs::insert_logs('master_obat', 'Menambahkan Detail Obat '.$medicine_logs->name.', qty '.$request->qty.', tgl pembelian '.$request->purchase_date);
            
            return response()->json([
                'status'    => true,
                'message'   => 'Data berhasil ditambahkan'
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Data gagal ditambahkan'
            ]);
        }



    }

    // function master_obat_detail_data
    public function master_obat_detail_data(Request $request)
    {
        // dd($request->all());
        $page   = $request->page;
        $rows   = $request->rows;
        $sort   = ( isset($request->sort)) ? $request->sort : 'id';
        $order  = ( isset($request->order)) ? $request->order : 'asc';
        $search = $request->search;

        $offset = ($page-1) * $rows;
        
        $data = MedicinePurchases::select(
            'id', 'invoice_number', 'purchase_date', 'qty', 'expired_date'
        )
        ->selectRaw('
            (
                SELECT COALESCE( SUM(`qty`), 0 ) 
                FROM check_medicines 
                WHERE 
                    check_medicines.medicine_purchase_id = medicine_purchases.id
                    AND check_medicines.status = 1
            ) AS qty_used
        ')
        ->where('medicine_id', $request->medicine_id)
        ->where('status', 1);

        if( !empty($search) ) {
            $data->where('invoice_number', 'like', '%'.$search.'%');
        }

        $total = $data->count();

        $data->skip($offset);
        $data->take($rows);
        $data->orderBy($sort, $order);

        $data = $data->get();

        // add button to user
        foreach($data as $d) {
            $d->option = '
                <a href="#" class="btn btn-outline-danger btn-sm m-1" onclick="remove(`'.$d->id.'`)">Hapus</a>
            ';
            // <a href="#" class="btn btn-outline-info btn-sm m-1" onclick="edit(`'.$d->id.'`)">Edit</a>
        }

        $return = array(
            'total' => $total,
            'rows'  => $data
        );

        return response()->json($return);
    }

    // function master_obat_detail_delete
    public function master_obat_detail_delete(Request $request)
    {
        $id = $request->id;

        $medicine = MedicinePurchases::find($id);
        $medicine->status       = 0;
        $medicine->deleted_by   = Auth::user()->id;
        $medicine->deleted_at   = date('Y-m-d H:i:s');
        
        // Medicine Logs
        $medicine_logs = Medicine::find($medicine->medicine_id);
        Logs::insert_logs('master_obat', 'Menghapus Detail Obat '.$medicine_logs->name.', qty '.$medicine->qty.', tgl pembelian '.$medicine->purchase_date);
        
        if( $medicine->save() ) {
            
            return response()->json([
                'status'    => true,
                'message'   => 'Data berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Data gagal dihapus'
            ]);
        }

    }
    
    // function master_obat_expired_data
    public function master_obat_expired_data(Request $request)
    {
        // dd($request->all());
        // echo $request->medicine_id;
        $medicine_purchases = MedicinePurchases::select('id', 'expired_date', 'qty');
        $medicine_purchases->selectRaw('
            (
                (medicine_purchases.qty) - 
                (
                    SELECT 
                        COALESCE(SUM(check_medicines.qty),0) 
                    FROM check_medicines 
                    WHERE 
                        check_medicines.medicine_purchase_id = medicine_purchases.id
                        AND check_medicines.status = 1
                ) 
            ) as stock
        ');
        $medicine_purchases->where('medicine_id', $request->medicine_id);
        $medicine_purchases->where('status', 1);
        $medicine_purchases->orderBy('expired_date', 'asc');
        $medicine_purchases->having('stock', '>', 0);
        $medicine_purchases->get();

        $data = array();
        foreach($medicine_purchases->get() as $mp) {
            $data[] = array(
                'id'            => $mp->id,
                'expired_date'  => Carbon::parse($mp->expired_date)->format('d M Y'),
                'stock'         => (int)$mp->stock,
            );
        }

        return response()->json($data);
    }

}
