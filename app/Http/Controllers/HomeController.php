<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Setting;
use Faker\Provider\Lorem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{

    // TEST =================
    public function generate_password($post)
    {
        $password = Hash::make($post);
        return view('test', [
            'data' => $password 
        ]);
    }

    // =================


    public function index()
    {        
    }
}

