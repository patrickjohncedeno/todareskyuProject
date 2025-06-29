<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(){
        return view('auth.sign-up');
    }

    public function store(Request $request){
        $request->validate(
            [
                'name' => 'required|min:5|max:70',
                'create_email' => 'required|unique:users,email|min:5|max:70',
                'createpassword' => 'required|confirmed'
            ]
        );

        User::create(
            [
                'name' => $request['name'],
                'email' => $request['create_email'],
                'password' => Hash::make($request['createpassword'])
            ]
        );

        return redirect()->route('login')->with('success', 'Registered Successfully !');
    }

    public function login(){
        return view('auth.login');
    }

    public function authenticate(){

        $validated = request()->validate(
            [
                'email' => 'required|min:5|max:70',
                'password' => 'required'
            ]
        );


        if(auth()->attempt($validated)){
            request()->session()->regenerate();

            return redirect()->route('index')->with('success', 'Logged in successfully !');
        }

        return redirect()->route('login')->withErrors([
            'email' => 'No matching email and password.'
        ]);
    }

    public function logout(){
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
