<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function login()
    {
        return view('front.account.login');
    }
    public function loginProcess(Request $request)
    {
        $request->validate([
            'login'    => 'required',
            'password' => 'required',
        ]);

        $login = $request->login;
        // Detect email or phone
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$field => $login, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('front.home'));
        }

        return back()
            ->withErrors(['login' => 'Invalid email/phone or password'])
            ->withInput();
    }


    public function register()
    {
        return view('front.account.register');
    }


    public function processRegister(Request $request)
    {
        $validated = $request->validate([
            'name'          =>  'required|string|max:255',
            'email'         =>  'required|email|unique:users,email',
            'phone'         =>  'required|digits:10|unique:users,phone',
            'password'      =>  'required|confirmed',

        ]);

        $validated['password'] = Hash::make($request->password);

        $user = User::create($validated);

        Auth::login($user);

        return redirect()->intended(route('front.home'));
    }

    public function profile()
    {

        echo 'You are logged in';
    }

    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You successfully logged out');
    }
}
