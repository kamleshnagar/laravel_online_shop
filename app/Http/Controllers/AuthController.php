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
        $user = Auth::user();
        return view('front.profile', compact('user'));
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required',
        ]);

        /* Update profile */
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        return back()->with('success', 'Profile updated successfully');
    }


    public function changePassword()
    {
        return view('front.change-password');
    }


    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $user->password)) {

            return back()
                ->withErrors([
                    'current_password' => 'Current password is incorrect'
                ])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully');
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
