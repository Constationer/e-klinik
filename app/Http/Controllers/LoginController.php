<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        // echo '<pre>';
        // print_r($request);
        // echo '</pre>';

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // echo '<pre>';
        // print_r($credentials);
        // echo '</pre>';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password tidak ditemukan !',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

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

    public function recovery(Request $request)
    {
        $akses = $this->generateUniqueCode();

        $user = Employee::where('handphone', $request->handphone)->first();
        if (!$user) {
            return back()->with('error', 'No Handphone tidak terdaftar.');
        } else {
            $user->akses            = $akses;
        }

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
                    'target' => $request->handphone . '|' . $user->name . '|' . $akses,
                    'message' => "*E-Klinik BPK Sulteng*\n\n{name},\nAnda telah melakukan reset Akses Kode, berikut ini adalah Akses Kode terbaru Anda.\n\n*{var1}*\n\nTerima kasih",
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

            return back()->with('success', 'Akses Kode anda berhasil direset.');
        }
    }
}
