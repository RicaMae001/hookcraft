<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        // Remove dd() and let it proceed
        Log::info('Registration started', $request->except('password', 'password_confirmation'));

        try {
            // Step 1: Validate
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
            ]);

            Log::info('✅ Validation passed', ['name' => $data['name'], 'email' => $data['email']]);
            
            // Step 2: Check if email already exists (double check)
            $existingUser = DB::table('users')->where('email', $data['email'])->first();
            if ($existingUser) {
                Log::warning('Email already exists', ['email' => $data['email']]);
                return redirect()->back()
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->withErrors(['email' => 'This email is already registered.']);
            }
            
            Log::info('✅ Email is unique');

            // Try direct DB insert
            Log::info('Attempting to insert user...');
            
            $userId = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'created_at' => now(),
            ]);

            Log::info('✅ User created successfully!', ['user_id' => $userId]);
            
            // Verify the user was actually inserted
            $insertedUser = DB::table('users')->where('id', $userId)->first();
            Log::info('✅ User verified in database', ['user' => $insertedUser]);

            // Get the user and login
            $user = User::find($userId);
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Registration successful!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors($e->errors());
                
        } catch (\Exception $e) {
            Log::error('Registration error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}