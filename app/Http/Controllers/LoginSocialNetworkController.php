<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginSocialNetworkController extends Controller
{
    public function login($driver)
    {
        return Socialite::driver($driver)->redirect();
    }
    public function handleCallback($driver, Request $request)
    {
        $user = Socialite::driver($driver)->stateless()->user();
        return response()->json(['postulante' => $user], 200);
    }
}
