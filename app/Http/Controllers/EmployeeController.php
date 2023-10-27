<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Helper\General;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{

    public function generateUniqueCode()
    {
        $code = '';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeLength = 6;

        do {
            $code = '';
            for ($i = 0; $i < $codeLength; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (Employee::where('akses', $code)->exists()); // Check if code exists in the database

        return $code;
    }

    // master_pegawai_submit
    public function master_pegawai_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_type'         => 'required',
            'data_parent'       => 'required',
            'nip'               => [
                'required',
                'unique:employees,nip'
            ],
            'place_of_birth'    => 'required',
            'date_of_birth'     => 'required|date_format:Y-m-d',
            'name'              => 'required',
            'religion'          => 'required',
            'gender'            => 'required',
            'address'           => 'required',
            'blood_type'        => 'required',
            'marital_status'    => 'required',
            'education'         => 'required',
            'rank'              => 'required',
            'class'             => 'required',
            'position'          => 'required',
            'work_unit'         => 'required',
            'handphone'         => ['required', 'regex:/^08\d{8,12}$/', 'unique:employees,handphone'],
        ], [
            'handphone.regex' => 'No Handphone harus terdiri dari 10-14 digit, contoh: 08123456789.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);
        }

        $akses = $this->generateUniqueCode();
        // do insert         
        $user = new Employee;
        $user->data_type        = $request->data_type;
        $user->data_parent      = $request->data_parent;
        $user->nip              = $request->nip;
        $user->place_of_birth   = $request->place_of_birth;
        $user->date_of_birth    = $request->date_of_birth;
        $user->name             = $request->name;
        $user->religion         = $request->religion;
        $user->gender           = $request->gender;
        $user->address          = $request->address;
        $user->blood_type       = $request->blood_type;
        $user->marital_status   = $request->marital_status;
        $user->education        = $request->education;
        $user->rank             = $request->rank;
        $user->class            = $request->class;
        $user->position         = $request->position;
        $user->work_unit        = $request->work_unit;
        $user->handphone        = $request->handphone;
        $user->akses            = $akses;
        $user->created_by       = auth()->user()->id;
        $user->updated_by       = auth()->user()->id;


        // return 
        if ($user->save()) {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $request->handphone . '|' . $request->name . '|' . $akses,
                    'message' => "*E-Klinik BPK Sulteng*\n\n{name},\nPendaftaran anda telah berhasil, silahkan gunakan Kode Akses berikut untuk melakukan pengecekan Riwayat Medis.\n\n*{var1}*\n\nTerima kasih\n*Semoga lekas sembuh*",
                    'typing' => false,
                    'delay' => '2',
                    'countryCode' => '62',
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: VyFngE4ZBU0IR6nhpXS@'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            // logs
            Logs::insert_logs('master_pegawai', 'Menambahkan data ' . $user->name);

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

    // master_pegawai_data datagrid easyui
    public function master_pegawai_data(Request $request)
    {
        // dd($request->all());
        $page   = $request->page;
        $rows   = $request->rows;
        $sort   = (isset($request->sort)) ? $request->sort : 'employees.name';
        $order  = (isset($request->order)) ? $request->order : 'asc';
        $filter = (isset($request->filterRules)) ? json_decode($request->filterRules) : '';

        $offset = ($page - 1) * $rows;

        // query 
        $data = Employee::select('employees.*')
            ->addSelect('employees2.name as main_data_name')
            ->selectRaw(" CONCAT(employees.rank,'/', employees.class) as rank_class")
            ->leftJoin('employees as employees2', 'employees2.id', '=', 'employees.data_parent');

        // query - filter
        if (!empty($filter)) {
            foreach ($filter as $f) {
                if ($f->field == 'main_data_name') {
                    $data->where('employees2.name', 'like', '%' . $f->value . '%');
                } elseif ($f->field == 'rank_class') {
                    $data->having('rank_class', 'like', '%' . $f->value . '%');
                } else {
                    $data->where('employees.' . $f->field, 'like', '%' . $f->value . '%');
                }
            }
        }

        // query - order
        $data->orderBy($sort, $order);

        // query - do
        $total = $data->count();
        $data->take($rows)->skip($offset);
        $data = $data->get();

        // add button to user
        if (in_array(1, UserRole::check_user_group())) {
            foreach ($data as $d) {

                if (($d->status == 1)) {
                    $status_info = 'Aktif';
                } else {
                    $status_info = 'Tidak Aktif';
                }

                $status_btn = '<a href="#" class="btn btn-outline-warning btn-sm m-1" onclick="change_status(`' . $d->id . '`, `' . $status_info . '`)">' . $status_info . '</a>';


                $d->option = '
                    <a href="#" class="btn btn-outline-info btn-sm m-1" onclick="edit(`' . $d->id . '`)">Edit</a> 
                    ' . $status_btn . '
                ';
            }
        }

        $return = array(
            'total' => $total,
            'rows'  => $data
        );

        return response()->json($return);
    }

    // master_pegawai_edit
    public function master_pegawai_edit(Request $request)
    {
        $id = $request->id;

        $data = Employee::find($id);

        return response()->json($data);
    }

    // master_pegawai_update update pegawai
    public function master_pegawai_update(Request $request)
    {
        // dd($request->all());

        // make validator
        $validator = Validator::make($request->all(), [
            'data_type'         => 'required',
            'data_parent'       => 'required',
            'nip'               => [
                'required',
                'unique:employees,nip,' . $request->id . ',id,status,1'
            ],
            'name'              => 'required',
            'place_of_birth'    => 'required',
            'date_of_birth'     => 'required|date_format:Y-m-d',
            'religion'          => 'required',
            'gender'            => 'required',
            'address'           => 'required',
            'blood_type'        => 'required',
            'marital_status'    => 'required',
            'education'         => 'required',
            'rank'              => 'required',
            'class'             => 'required',
            'position'          => 'required',
            'work_unit'         => 'required',
            'handphone'         => ['required', 'regex:/^08\d{8,12}$/'],
        ], [
            'handphone.regex' => 'No Handphone harus terdiri dari 10-14 digit, contoh: 08123456789.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);
        }

        // update data 
        $user = Employee::find(request()->id);
        $user->data_type        = request()->data_type;
        $user->data_parent      = request()->data_parent;
        $user->nip              = request()->nip;
        $user->name             = request()->name;
        $user->place_of_birth   = request()->place_of_birth;
        $user->date_of_birth    = request()->date_of_birth;
        $user->religion         = request()->religion;
        $user->gender           = request()->gender;
        $user->address          = request()->address;
        $user->blood_type       = request()->blood_type;
        $user->marital_status   = request()->marital_status;
        $user->education        = request()->education;
        $user->rank             = request()->rank;
        $user->class            = request()->class;
        $user->position         = request()->position;
        $user->work_unit        = request()->work_unit;
        $user->handphone        = request()->handphone;
        $user->updated_by       = auth()->user()->id;

        // return
        if ($user->save()) {

            // logs
            Logs::insert_logs('master_pegawai', 'Mengubah data ' . $user->name);

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

    // master_pegawai_delete
    public function master_pegawai_delete(Request $request)
    {
        $id = $request->id;

        $user = Employee::find($id);
        $user->status = 0;
        $user->updated_by = auth()->user()->id;
        $user->deleted_at = date('Y-m-d H:i:s');
        $user->deleted_by = auth()->user()->id;

        // logs
        Logs::insert_logs('master_pegawai', 'Menghapus data ' . $user->name);

        if ($user->save()) {

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


    public function master_pegawai_change_status(Request $request)
    {
        $id = $request->id;

        $user = Employee::find($id);

        if ($user->status == 1) {
            $user->status = 0;
            $status_info = 'Tidak Aktif';
        } else {
            $user->status = 1;
            $status_info = 'Aktif';
        }

        $user->updated_by = auth()->user()->id;

        // logs
        Logs::insert_logs('master_pegawai', 'Mengubah status data ' . $user->name . ' menjadi ' . $status_info);

        if ($user->save()) {

            return response()->json([
                'status'    => true,
                'message'   => 'Status berhasil diubah'
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Status gagal diubah'
            ]);
        }
    }
}
