<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user_social = Socialite::driver($driver)->stateless()->user();
        $user = User::updateOrCreate([
            'github_id' =>  $user_social->id,
        ], [
            'name' =>  $user_social->name,
            'email' =>  $user_social->email,
        ]);
        Auth::login($user);
        return redirect('/');
    }
}
