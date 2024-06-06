<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class loginController extends Controller
{
    public function loginView(){
        return view('login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'The email field is required..',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.'
        ]);
            if (Auth::attempt($credentials)) {
            return redirect('/');
        } else {
            return redirect()->back()->withInput()->withErrors(['email' => 'Invalid Email OR Password.']); // Modify this line to handle failed login attempts
        }
    }
    public function logout(){
        if (Auth::check()) {
            Auth::logout();
            return redirect('/login');
        }
    }
}
