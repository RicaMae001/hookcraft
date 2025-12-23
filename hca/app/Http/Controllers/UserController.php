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

            // Try direct DB insert with role
            Log::info('Attempting to insert user...');
            
            $userId = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'User', // Set role as User
                'created_at' => now(),
            ]);

            Log::info('✅ User created successfully!', ['user_id' => $userId, 'role' => 'User']);
            
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

    // Login with role-based redirection
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Check if user exists in users table
        $user = DB::table('users')->where('email', $credentials['email'])->first();
        
        if ($user) {
            // Verify it's a User role
            if ($user->role !== 'User') {
                Log::warning('Invalid user role attempting login', [
                    'email' => $credentials['email'],
                    'role' => $user->role
                ]);
                return back()->withErrors(['email' => 'Invalid credentials']);
            }

            // Attempt authentication for User
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                Log::info('User logged in successfully', [
                    'user_id' => Auth::id(),
                    'role' => 'User'
                ]);
                return redirect()->route('home');
            }
        }

        // Check if it's a delivery coordinator trying to login here
        $deliveryCoordinator = DB::table('delivery_coordinator')
            ->where('email', $credentials['email'])
            ->first();
        
        if ($deliveryCoordinator) {
            Log::warning('Delivery coordinator attempted user login', [
                'email' => $credentials['email']
            ]);
            return back()->withErrors([
                'email' => 'Please use the delivery coordinator login page.'
            ]);
        }

        Log::warning('Failed login attempt', ['email' => $credentials['email']]);
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Logout
    public function logout(Request $request)
    {
        $userId = Auth::id();
        $userRole = Auth::user()->role ?? 'Unknown';
        
        Log::info('User logging out', [
            'user_id' => $userId,
            'role' => $userRole
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully');
    }

    // Middleware helper: Check if user has 'User' role
    public function checkUserRole()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        if (Auth::user()->role !== 'User') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        return null; // Continue
    }
}