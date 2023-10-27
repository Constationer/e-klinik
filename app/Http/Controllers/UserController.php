<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    // master_user_data easyui datagrid
    public function master_user_data(Request $request)
    {
        // parameter 
        $page   = $request->page;
        $rows   = $request->rows;
        $sort   = ( isset($request->sort) )? $request->sort : 'name';
        $order  = ( isset($request->order) )? $request->order : 'asc';
        $filter = ( isset($request->filterRules) )? json_decode($request->filterRules) : '';

        $offset = ($page-1)*$rows;

        // query
        $user = User::select('id', 'name', 'email', 'phone')
                    ->selectRaw('
                        (
                            SELECT 
                                user_groups.name
                            FROM user_roles as roles
                                LEFT JOIN user_groups ON user_groups.id = roles.user_group_id
                            WHERE
                                roles.user_id = users.id
                            LIMIT 1
                        ) as user_group
                    ')
                    ->where('status',1);
        
        // query -> filter
        if( $filter ) {
            foreach($filter as $f) {
                if($f->field == 'user_group') {
                    $user->having($f->field, 'like', '%'.$f->value.'%');
                } else {
                    $user->where($f->field, 'like', '%'.$f->value.'%');
                }  
            }
        }

        // query -> sort
        $user->orderBy($sort, $order);

        // query -> do
        $total  = $user->count();
        $user   = $user->skip($offset)->take($rows)->get();

        // add button to user
        if ( in_array(1, UserRole::check_user_group()) ) {
            foreach($user as $u) {
                $u->option = '
                    <a href="#" class="btn btn-outline-info btn-sm m-1" onclick="edit(`'.$u->id.'`)">Edit</a> 
                    <a href="#" class="btn btn-outline-danger btn-sm m-1" onclick="remove(`'.$u->id.'`)">Hapus</a>
                ';
            }   
        }

        // return
        return response()->json([
            'total' => $total,
            'rows'  => $user
        ]);
    }

    // master_user_submit
    public function master_user_submit(Request $request)
    {
        // dd($request->all());
        
        // validate
        $validator = Validator::make($request->all(), [
            'name'          => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('status', 1);
                }),
            ],
            'email'         => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where([
                        'status' => 1,
                        'email'  => request()->email
                    ]);
                }),
            ],
            'password'      => 'required',
            'phone'         => 'required',
            'user_group'    => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);

        }

        // do insert : table user
        $user = new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone    = $request->phone;

        $user->created_by = auth()->user()->id;
        $user->updated_by = auth()->user()->id;

        $do_insert = $user->save();

        // do insert : table user_roles
        UserRole::insert([
            'user_id'       => $user->id,
            'user_group_id' => $request->user_group
        ]);

        // insert logs
        Logs::insert_logs('master_user', 'Menambahkan user '.$request->name);

        // return 
        if( $do_insert ) {
            $return = array(
                'status'    => true,
                'message'   => 'Data berhasil ditambahkan'
            );
        } else {            
            $return = array(
                'status'    => false,
                'message'   => 'Data gagal ditambahkan'
            );
        }
        
        return response()->json($return);
        
    }

    // master_user_edit
    public function master_user_edit(Request $request)
    {
        // dd($request->all());
        $user = User::select('id', 'name', 'email', 'phone')
            ->selectRaw('
                (
                    SELECT 
                        user_groups.id
                    FROM user_roles as roles
                        LEFT JOIN user_groups ON user_groups.id = roles.user_group_id
                    WHERE
                        roles.user_id = users.id
                    LIMIT 1
                ) as user_group
            ')
            ->find($request->id);

        // return
        return response()->json([
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'phone'     => $user->phone,
            'user_group'=> $user->user_group
        ]);
    }

    // master_user_update
    public function master_user_update(Request $request)
    {
        // dd($request->all());
        // die();

        // validate
        $validator = Validator::make($request->all(), [
            'name'          => [
                'required',
                Rule::unique('users')->ignore($request->id),    
            ],
            'phone'         => 'required',
            'email'         => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->id),    
            ],
            'user_group'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->all()
            ]);
        }

        // die();
        // do update         
        $user = User::find($request->id);
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;

        if( $request->password != '' ) {
            $user->password = bcrypt($request->password);
        }

        $user->updated_by = auth()->user()->id;

        // return 
        if( $user->save() ) {
            $return = array(
                'status'    => true,
                'message'   => 'Data berhasil diupdate'
            );

            // delete user roles
            $delete_userRoles = UserRole::where('user_id', $request->id)
                ->delete();
            
            // insert user roles
            $insert_userRoles = UserRole::insert([
                'user_id'       => $request->id,
                'user_group_id' => $request->user_group
            ]);

            // logs
            Logs::insert_logs('master_user', 'Mengubah user menjadi '.$request->name);

        } else {            
            $return = array(
                'status'    => false,
                'message'   => 'Data gagal diupdate'
            );
        }
        
        return response()->json($return);

        
    }

    // master_user_delete
    public function master_user_delete(Request $request)
    {
        // dd($request->all());
        $user = User::find($request->id);
        $user->status = 0;
        $user->updated_by = auth()->user()->id;
        $user->deleted_at = date('Y-m-d H:i:s'); 
        $user->deleted_by = auth()->user()->id;
        
        // logs
        Logs::insert_logs('master_user', 'Menghapus user '.$user->name);

        if( $user->save() ) {
            $return = array(
                'status'    => true,
                'message'   => 'Data berhasil dihapus'
            );

        } else {            
            $return = array(
                'status'    => false,
                'message'   => 'Data gagal dihapus'
            );
        }

        return response()->json($return);
    }

    // function pengaturan_user
    public function pengaturan_user_update( Request $request )
    {
        // dd($request->all());

        // validate
        $request->validate(
            [
                'name'      => 'required',
                'email'     => 'required|email',
                'phone'     => 'required'
            ],
            [
                'name.required'     => 'Nama tidak boleh kosong',
                'email.required'    => 'Email tidak boleh kosong',
                'email.email'       => 'Email tidak valid',
                'phone.required'    => 'No. HP tidak boleh kosong',
                'password.required' => 'Password tidak boleh kosong'
            ]
        );

        $user = User::find(auth()->user()->id);
        
        if ( $request->password != '' ) {
            $user->password = bcrypt($request->password);
        }

        // do update
        
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;

        $user->updated_by = auth()->user()->id;

        // return
        if( $user->save() ) {
            // logs
            Logs::insert_logs('pengaturan_user', 'Mengubah pengaturan user '.$request->name);
            
            return back()->with('success','berhasil update data');

        } else {
            return back()->with('error','gagal update data');
        }

    }


}
