<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    // check user group 
    public static function check_user_group()
    {
        $user_role = UserRole::select('user_group_id')
            ->where('user_id', auth()->user()->id)
            ->get();

        if ($user_role) {
            foreach ($user_role as $key => $value) {
                $list[] = $value->user_group_id;
            }

            return $list;
        } else {
            return false;
        }
    }
    
}
