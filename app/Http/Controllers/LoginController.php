<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{


    public function login_check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);
    
        $userCredentials = $request->only('email', 'password');
    
        if (Auth::attempt($userCredentials)) {
            $user = Auth::user();
    
            // Redirect based on role
            switch ($user->role) {
                case 1:
                    return redirect()->route('admin.dash'); // Make sure this route exists
                case 2:
                    return redirect()->route('trainer.dashboard');
                case 3:
                    return redirect()->route('student.dashboard');
                default:
                    Auth::logout(); // Prevents infinite loop
                    return to_route('login')->withErrors(['error' => 'Invalid role assigned.']);
            }
        } else {
            return back()->withErrors(['error' => 'Username and password are incorrect.']);
        }
    }
    


public function register(Request $request)
{
    //dd($request->all());
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email', // Unique email validation
        'password' => 'required|min:4', // Requires password confirmation
        'role' => 'required|in:1,2,3', // Role must be 1 (Admin), 2 (Trainer), or 3 (Student)
    ], [
        'email.unique' => 'This email is already taken. Please use another one.',
       // 'password.confirmed' => 'Passwords do not match.',
        'role.in' => 'Invalid role selected.',
    ]);

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    return to_route('login')->with('success', 'You have been registered successfully. Please login to continue.');

}
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect()->route('login')->with('success', 'Logged out successfully.');
}


}
