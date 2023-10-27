<?php

namespace App\Http\Controllers;

use App\Models\SettingSite;
use Illuminate\Http\Request;

class SettingSiteController extends Controller
{
    //

        // function pengaturan_update
        public function pengaturan_update(Request $request)
        {
            $list_setting = array('web_name', 'web_desc');
            $count_success = 0;

            foreach ($list_setting as $setting) {
                $data = SettingSite::where('note', $setting)->first();
                $data->value = $request->$setting;
                $data->save();

                if($data) {
                    $count_success++;
                }

            }

            if( $count_success == count($list_setting) ) {
                return redirect()->route('pengaturan')->with('success', 'Pengaturan berhasil diubah');
            } else {
                return redirect()->route('pengaturan')->with('failed', 'Pengaturan gagal diubah');
            }

            
        }

}
