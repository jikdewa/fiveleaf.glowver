<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Page
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        if (Auth::check()) {

            return $this->redirectByRole(
                Auth::user()->role
            );
        }

        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | Register Page
    |--------------------------------------------------------------------------
    */
    public function register()
    {
        if (Auth::check()) {

            return $this->redirectByRole(
                Auth::user()->role
            );
        }

        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | Register Process
    |--------------------------------------------------------------------------
    */
    public function registerProcess(Request $request){
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|max:20',
            'password' => 'required|min:6|confirmed',
        ]);

        $name = explode('@', $request->email)[0];

        $name = str_replace(
            ['.', '_', '-'],
            ' ',
            $name
        );

        $name = ucwords($name);
        

        User::create([
            'name'      => $name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role'      => 'staff',
            'is_active' => true,
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Register berhasil');
    }

    /*
    |--------------------------------------------------------------------------
    | Login Process
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' => 'required|email',

            'password' => 'required',

        ]);

        $remember =
            $request->boolean('remember');

        if (
            !Auth::attempt(
                $credentials,
                $remember
            )
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Email atau password salah'
                );
        }

        $request
            ->session()
            ->regenerate();

        $user = Auth::user();

        if (!$user->is_active) {

            Auth::logout();

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Akun Anda dinonaktifkan.'
                );
        }

        return $this->redirectByRole(
            $user->role
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();

        return redirect()
            ->route('login');
    }

    /*
    |--------------------------------------------------------------------------
    | Redirect By Role
    |--------------------------------------------------------------------------
    */
    private function redirectByRole($role)
    {
        switch ($role) {

            case 'owner':
            case 'admin':
            case 'staff':

                return redirect()
                    ->route('dashboard');

            case 'user':

                return redirect('/');

            default:

                Auth::logout();

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Role tidak valid.'
                    );
        }
    }
}