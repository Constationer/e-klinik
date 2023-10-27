<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Doctor;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    // master_dokter_submit
    public function master_dokter_submit(Request $request)
    {
        // make validator 
        $validator = Validator::make($request->all(), [
            'name'          => 'required|unique:doctors,name,status',
            'position'      => 'required',
            'specialist'    => 'required',
            'phone'         => 'required'            
        ]);

        // check validator
        if( $validator->fails() ) {
            $return = array(
                'status' => false,
                'message' => $validator->errors()->all()
            );

            return response()->json($return);
        } 

        // save data 
        $doctor = new Doctor;
        $doctor->name       = $request->name;
        $doctor->position   = $request->position;
        $doctor->specialist = $request->specialist;
        $doctor->phone      = $request->phone;

        $doctor->created_by = auth()->user()->id;
        $doctor->updated_by = auth()->user()->id;
        
        $do_insert = $doctor->save();

        if( $do_insert ) {
            $return = array(
                'status' => true,
                'message' => 'Data berhasil ditambahkan'
            );

            // logs 
            Logs::insert_logs('master_dokter', 'Menambahkan data '.$doctor->name);

        } else {
            $return = array(
                'status' => false,
                'message' => 'Data gagal ditambahkan'
            );
        }

        return response()->json($return);
        
    }

    // master_dokter_data easyui datagrid
    public function master_dokter_data(Request $request)
    {
        $page   = $request->page;
        $rows   = $request->rows;
        $sort   = ( isset($request->sort) )? $request->sort : 'name';
        $order  = ( isset($request->order) )? $request->order : 'desc';
        $filter = ( isset($request->filterRules) )? json_decode($request->filterRules) : '';

        $offset = ($page-1)*$rows;

        // query 
        $doctor = Doctor::select('id', 'name', 'position', 'specialist', 'phone')
                        ->where('status',1);

        // query -> filter
        if( !empty($filter) ) {
            foreach($filter as $f) {
                $doctor->where($f->field, 'like', '%'.$f->value.'%');
            }
        }

        // query -> order
        $doctor->orderBy($sort, $order);

        // query -> get data
        $total = $doctor->count();
        $doctor = $doctor->take($rows)->skip($offset)->get();

        // add button to doctor
        if ( in_array(1, UserRole::check_user_group()) ) {
            foreach($doctor as $d) {
                $d->option = '
                    <a href="#" class="btn btn-outline-info btn-sm m-1" onclick="edit(`'.$d->id.'`)">Edit</a> 
                    <a href="'.route('master_dokter_delete', ['id' => $d->id]).'" class="btn btn-outline-danger btn-sm m-1" onclick="delete_alert()">Delete</a>
                ';
            }   
        }

        $data = [
            'total' => $total,
            'rows'  => $doctor
        ];

        return response()->json($data);
        
    }

    // master_dokter_edit
    public function master_dokter_edit($id)
    {
        $doctor = Doctor::find($id);

        echo json_encode($doctor);

        // return view('master_dokter_edit', compact('doctor'));
    }

    // master_dokter_update
    public function master_dokter_update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'          => 'required|unique:doctors,name,'.$request->id.',id,status,1',
            'position'      => 'required',
            'specialist'    => 'required',
            'phone'         => 'required'            
        ]);

        $doctor = Doctor::find($request->id);
        $doctor->name       = $request->name;
        $doctor->position   = $request->position;
        $doctor->specialist = $request->specialist;
        $doctor->phone      = $request->phone;

        $doctor->updated_by = auth()->user()->id;
        
        $do_update = $doctor->save();

        if( $do_update ) {
            
            $return = array(
                'status' => 'success',
                'message' => 'Data berhasil diupdate'
            );

            // Logs 
            Logs::insert_logs('master_dokter', 'Mengubah data '.$doctor->name);
            
        } else {

            // show error message validation
            $return = array(
                'status' => 'error',
                'message' => $do_update['message']
            );



        }

        echo json_encode($return);
        
    }
    

    // master_dokter_delete update in status
    public function master_dokter_delete($id)
    {
        $doctor = Doctor::find($id);

        $doctor->status     = 0;
        $doctor->deleted_at = date('Y-m-d H:i:s');
        $doctor->deleted_by = auth()->user()->id;

        if( $doctor->save() ) {

            // Logs 
            Logs::insert_logs('master_dokter', 'Menghapus data '.$doctor->name);

            return redirect()->route('master-dokter')->with('success', 'Data berhasil dihapus');
        } else {            
            return redirect()->route('master-dokter')->with('error', 'Data gagal dihapus');
        }

    }







}