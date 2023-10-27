<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    // use HasFactory;

    public static function menuHead($section = 'back')
    {
        $menu = DB::table('menus')
            ->where('status', '=', '1')
            ->where('section', '=', $section)
            ->whereNull('parent')
            ->orderBy('order', 'asc')
            ->get();
        
        return $menu;
    }

    public static function menuSub($parent_id)
    {
        $menu = DB::table('menus')
            ->where('status', '=', '1')
            ->where('parent', '=', $parent_id)
            ->orderBy('order', 'asc');
        
        return $menu;
    }


}