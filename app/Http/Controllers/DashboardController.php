<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public static function index()
    {
        return view('home', [
            'page_title' => 'Dashboard'
        ]);
    }

    // setting
    // public function master_page($param)
    // {
    //     return view('master-'.$param, [
    //         'page_title'   => $param,
    //         // 'articles'     => Article::read('profil')
    //     ]);

    //     // echo 'master-'.$param;
    // }

    public function master_page()
    {
        // laravel uri segment
        // https://stackoverflow.com/questions/2955251/how-to-get-the-last-part-of-a-uri-in-laravel
        // $uri = request()->path();
        // $segments = explode('/', $uri);
        // $last = end($segments);

        $last = 'master-obat';

        return view('master-obat', [
            'page_title'   => $last,
            // 'articles'     => Article::read('profil')
        ]);

        // echo $last;
        


    }

    public function master_page2()
    {
        return view('master-user', [
            'page_title'   => 'user',
            // 'articles'     => Article::read('profil')
        ]);

        echo 'master-user';
    }

}
