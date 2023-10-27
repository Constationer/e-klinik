<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckMedicines;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CheckMedicinesController extends Controller
{
    // periksa_obat_submit
    public function periksa_obat_submit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'check_id'              => 'required',
            'medicine_id'           => 'required',
            'medicine_purchase_id'  => 'required',
            'qty'                   => 'required',
            'description'           => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);
        }

        $check_medicine = new CheckMedicines;
        $check_medicine->check_id               = $request->check_id;
        $check_medicine->medicine_id            = $request->medicine_id;
        $check_medicine->medicine_purchase_id   = $request->medicine_purchase_id;
        $check_medicine->qty                    = $request->qty;
        $check_medicine->description            = $request->description;
        $check_medicine->created_by             = Auth::user()->id;
        $check_medicine->updated_by             = Auth::user()->id;

        if( $check_medicine->save() ){
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

    // function periksa_obat_data easyui datagrid
    public function periksa_obat_data(Request $request)
    {
        $check_id = $request->id;

        $rows = $request->rows;
        $page = $request->page;
        $sort = isset($request->sort) ? $request->sort : 'id';
        $order = isset($request->order) ? $request->order : 'desc';        
        
        $offset = ($page-1) * $rows;

        $data = CheckMedicines::select(
                    'check_medicines.*', 
                    'medicines.name as medicine_name',
                    'medicine_purchases.expired_date as expired_date'
                )
                ->where([
                    'check_medicines.check_id'  => $check_id,
                    'check_medicines.status'    => 1
                ])
                ->join('medicines', 'check_medicines.medicine_id', '=', 'medicines.id')
                ->leftJoin('medicine_purchases', 'check_medicines.medicine_purchase_id', '=', 'medicine_purchases.id')
                ->offset($offset)
                ->limit($rows)
                ->orderBy($sort, $order)
                ->get();
        
        // button delete 
        foreach($data as $key => $value){
            $data[$key]['option'] = '<a href="#" class="btn btn-outline-danger btn-sm m-1" onclick="remove('.$value->id.')">Delete</a>';
        }
        

        return response()->json([
            'total' => count($data),
            'rows'  => $data
        ]);
    }

    // function periksa_obat_delete
    public function periksa_obat_delete(Request $request) 
    {
        $check_medicine = CheckMedicines::find($request->id);
        
        $check_medicine->status = 0;
        $check_medicine->updated_by = Auth::user()->id;

        if( $check_medicine->save() ){
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

}
