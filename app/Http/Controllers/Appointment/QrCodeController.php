<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function index()
    {
        // dd(request()->url() . '/' . time());
        // $route = 

        $valor_criptografado = base64_encode("08574165877");
        dd($valor_criptografado);
        $valor_descriptografado = base64_decode($valor_criptografado);
        dd($valor_descriptografado);

        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        return view('qr-code.index', compact('user', 'role'));
    }
}
