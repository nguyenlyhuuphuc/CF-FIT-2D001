<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(){
        $user = Socialite::driver('google')->user();

        $userSystem = User::updateOrCreate([
            'email' => $user->email,
        ], [
            'name' => $user->name,
            'password' => Hash::make('!@Password@123')
        ]);

        // $userSystem = User::where('email', $user->email)->first();

        // if(!$userSystem){
        //     $userSystem = User::create([
        //         'name' => $user->name,
        //         'email' => $user->email,
        //         'password' => Hash::make('!@Password@123')
        //     ]);
        // }else{
        //     $userSystem = User::where('email', $user->email)->update([
        //         'name' => $user->name,
        //         'avatar' => $user->avatar
        //     ]);
        // }

        Auth::login($userSystem);
        return redirect()->route('home');
    }
}
