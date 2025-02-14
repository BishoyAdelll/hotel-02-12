<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Observers\UserObserver;
use App\Traits\HttpResponses;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;
    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());
        if(!Auth::attempt($request->only(['email','password'])))
        {
            return $this->error('' ,'credentials not match',401);
        }
        $user =User::where('email',$request->email)->first();
        return redirect()->route('dashboard');


    }

    public function register(StoreUserRequest $request)
    {
          $request->validated($request->all());

          $user=User::create([
              'name' => $request->name,
              'email' => $request->email,
              'password' => Hash::make($request->password),

          ]);
        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function  logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'you have been  logged out successfully',
        ]);

    }


}
