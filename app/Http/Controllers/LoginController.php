<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits_between:10,15',
            'password'     => 'required|min:6',
        ]);

        // Phone number vechu user-a find pannurom
        $user = User::where('phone_number', $request->phone_number)
            ->whereNull('deleted_at')  // ✅ soft delete check
            ->first();

        // User illana or password thappu na error
       if (!$user || !Hash::check($request->password, $user->password)) {
    throw ValidationException::withMessages([
        'phone_number' => 'Phone number or password thappu. Please check pannunga.',
    ]);
}

        // Account active-a check pannurom
        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'phone_number' => 'Your account has been deactivated.',
            ]);
        }

        // Manual login + remember me
        Auth::login($user, $request->boolean('remember'));

        $user->update(['last_login_at' => now()]);

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}