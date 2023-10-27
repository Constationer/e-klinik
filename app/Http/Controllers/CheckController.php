<?php

namespace App\Http\Controllers;

use App\Models\Check;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckController extends Controller
{
    //

    // function periksa_submit
    public function periksa_submit(Request $request)
    {
        // dd($request->all());

        // validate 
        $validator = Validator::make($request->all(), [
            'employee_id'       => 'required',
            'doctor_id'         => 'required',
            'check_type'        => 'required',
            'date'              => 'required',
            'tinggi'            => 'required',
            'berat'             => 'required',
            'suhu'              => 'required',
            'tekanan'           => 'required',
            'asam_urat'         => 'required',
            'kolesterol'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);
        }

        // generate number check CHECK-Ymd-xxx
        $check = Check::where('code', 'like', 'CHECK-' . date('Ymd') . '%')->orderBy('code', 'desc')->first();
        if ($check) {
            $code = explode('-', $check->code);
            $code = $code[2] + 1;
            $code = 'CHECK-' . date('Ymd') . '-' . str_pad($code, 3, '0', STR_PAD_LEFT);
        } else {
            $code = 'CHECK-' . date('Ymd') . '-001';
        }

        // do insert
        $check = new Check;
        $check->code            = $code;
        $check->employee_id     = $request->employee_id;
        $check->doctor_id       = $request->doctor_id;
        $check->check_type      = $request->check_type;
        $check->date            = $request->date;
        $check->tinggi          = $request->tinggi;
        $check->berat           = $request->berat;
        $check->suhu            = $request->suhu;
        $check->tekanan         = $request->tekanan;
        $check->asam_urat       = $request->asam_urat;
        $check->kolesterol      = $request->kolesterol;
        $check->hasil           = $request->hasil;
        $check->description     = $request->description;
        $check->diagnosis       = $request->diagnosis;
        $check->therapy         = $request->therapy;
        $check->nextdate        = $request->nextdate;
        $check->created_by      = auth()->user()->id;
        $check->updated_by      = auth()->user()->id;

        if ($check->save()) {
            return response()->json([
                'status'    => true,
                'message'   => 'Data berhasil disimpan'
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Data gagal disimpan'
            ]);
        }
    }

    // function periksa_data datagrid easyui
    public function periksa_data(Request $request)
    {
        $page   = $request->page;
        $rows   = $request->rows;
        $sort   = (isset($request->sort)) ? $request->sort : 'code';
        $order  = (isset($request->order)) ? $request->order : 'desc';
        $filter = (isset($request->filterRules)) ? json_decode($request->filterRules) : '';

        $offset = ($page - 1) * $rows;

        // query
        $check = Check::select('checks.*', 'employees.name as employees.name', 'doctors.name as doctors.name')
            ->join('employees', 'employees.id', '=', 'checks.employee_id')
            ->join('doctors', 'doctors.id', '=', 'checks.doctor_id')
            ->where('checks.status', 1);

        // query -> filter
        if ($filter) {
            foreach ($filter as $f) {
                $check->where($f->field, 'like', '%' . $f->value . '%');
            }
        }

        // query -> sort
        $check->orderBy($sort, $order);

        // query -> do
        $total = $check->count();
        $data  = $check->skip($offset)->take($rows)->get();

        // tombol action bootstrap
        if (in_array(1, UserRole::check_user_group())) {
            foreach ($data as $key => $value) {
                $data[$key]['option'] = '
                    <a href="' . route('periksa_detail', $value->id) . '" class="btn btn-sm btn-outline-warning m-1")">Detail Obat</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-info m-1" onclick="edit(' . $value->id . ')">Edit</a>
                    <a href="/pdf-check/check/' . $value->code . '" class="btn btn-sm btn-outline-danger m-1" target="_blank">PDF</a>
                ';
            }
        }

        return response()->json([
            'total' => $total,
            'rows'  => $data
        ]);
    }

    // function periksa_edit
    public function periksa_edit($id)
    {
        $check = Check::find($id);
        return response()->json($check);
    }

    // function periksa_update
    public function periksa_update(Request $request)
    {
        // validate 
        $validator = Validator::make($request->all(), [
            'employee_id'       => 'required',
            'doctor_id'         => 'required',
            'check_type'        => 'required',
            'date'              => 'required',
            'tinggi'            => 'required',
            'berat'             => 'required',
            'suhu'              => 'required',
            'tekanan'           => 'required',
            'asam_urat'         => 'required',
            'kolesterol'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);
        }

        // do update
        $check = Check::find($request->id);
        $check->employee_id     = $request->employee_id;
        $check->doctor_id       = $request->doctor_id;
        $check->check_type      = $request->check_type;
        $check->date            = $request->date;
        $check->tinggi          = $request->tinggi;
        $check->berat           = $request->berat;
        $check->suhu            = $request->suhu;
        $check->tekanan         = $request->tekanan;
        $check->asam_urat       = $request->asam_urat;
        $check->kolesterol      = $request->kolesterol;
        $check->hasil           = $request->hasil;
        $check->description     = $request->description;
        $check->diagnosis       = $request->diagnosis;
        $check->therapy         = $request->therapy;
        $check->nextdate        = $request->nextdate;
        $check->updated_by      = auth()->user()->id;

        if ($check->save()) {
            return response()->json([
                'status'    => true,
                'message'   => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Data gagal diupdate'
            ]);
        }
    }
}
