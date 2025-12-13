<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('shop.index');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        try {
            $request->session()->regenerate();
        } catch (\Exception $e) {
            logger()->error('Session regeneration failed during register: ' . $e->getMessage());
        }

        return redirect()->route('shop.index')->with('success', 'Account created and logged in.');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('shop.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        logger()->info('Login attempt', ['email' => $credentials['email'], 'remember' => $request->boolean('remember'), 'path' => $request->path()]);
        try {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                logger()->info('Auth::attempt succeeded', ['email' => $credentials['email'], 'id' => Auth::id()]);
                try {
                    $request->session()->regenerate();
                } catch (\Exception $e) {
                    // Log the session regeneration error but continue (do not expose internal error to users)
                    logger()->error('Session regeneration failed during login: ' . $e->getMessage());
                }
                return redirect()->intended(route('shop.index'))->with('success', 'Logged in.');
            }
        } catch (\RuntimeException $e) {
            logger()->warning('Auth::attempt runtimeException', ['msg' => $e->getMessage(), 'email' => $credentials['email']]);
            // Fallback: some user passwords may be hashed with a different algorithm (e.g., Argon2).
            // If the default hasher is strict (bcrypt verify enabled), Auth::attempt will throw a RuntimeException.
            // Try a tolerant verification with PHP's password_verify and, on success, re-hash with the app hasher and proceed.
            if (str_contains($e->getMessage(), 'Bcrypt') || str_contains($e->getMessage(), 'bcrypt')) {
                $user = \App\Models\User::where('email', $credentials['email'])->first();
                if ($user && is_string($user->password)) {
                    logger()->info('Fallback password_verify check', ['email' => $user->email]);
                    try {
                        if (password_verify($credentials['password'], $user->password)) {
                            // Log a note and re-hash the password with the application's hasher.
                            logger()->warning('Password verified using PHP fallback for user ' . $user->email . '. Re-hashing to current hasher.');
                            $user->password = \Illuminate\Support\Facades\Hash::make($credentials['password']);
                            $user->save();

                            // Log the user in and continue
                            Auth::login($user, $request->boolean('remember'));
                            logger()->info('Auth::login via fallback succeeded', ['email' => $user->email, 'id' => $user->id]);
                            try {
                                $request->session()->regenerate();
                            } catch (\Exception $e) {
                                logger()->error('Session regeneration failed during fallback login: ' . $e->getMessage());
                            }
                            return redirect()->intended(route('shop.index'))->with('success', 'Logged in.');
                        }
                    } catch (\Exception $inner) {
                        logger()->error('Fallback password_verify failed: ' . $inner->getMessage());
                    }
                }
            }
        }

        return back()->with('error', 'Login failed — check your credentials.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        try {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } catch (\Exception $e) {
            logger()->error('Session invalidation failed during logout: ' . $e->getMessage());
        }
        return redirect()->route('shop.index')->with('success', 'Logged out.');
    }
}
