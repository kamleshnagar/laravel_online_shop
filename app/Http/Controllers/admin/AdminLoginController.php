<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    
    public function index()
    {
        //$email = admin@gmail.com
        // $pass = '123456';
        // $pass  = bcrypt($pass);
        // $role  = 2
        // $bycrypted_pass = $2y$10$AYK0Mtd6f/3fpBzx39Sb0u5A9DwuVAvmGDZ35csMUWa.9Gti2/QBS

        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            if (Auth::guard('admin')->attempt(
                [
                    'email' => $request->email,
                    'password' => $request->password
                ],

                $request->get('remember')
            )) {

                $admin = Auth::guard('admin')->user();

                if ($admin->role == 2) {
                    return redirect()->route('admin.login');
                } else {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'You are not authorized to access');
                }

                return redirect()->route('admin.dashboard');
                
            } else {
                
                return redirect()->route('admin.login')
                    ->with('error', 'Invalid Email or Password');
            }
        } else {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
