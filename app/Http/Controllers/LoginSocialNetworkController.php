<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginSocialNetworkController extends Controller
{
    public function login()
    {
            return Socialite::driver('google')->redirect();
    }
    public function handleCallback($driver){
        $postulante = $driver($driver)->user();
        return response()->json(['postulante' =>  $postulante], 200);
    }
}
