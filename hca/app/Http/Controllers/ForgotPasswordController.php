<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password page.
     */
    public function show()
    {
        return view('pages.forgot-password');
    }

    /**
     * Step 1 — Send OTP to the email (must exist in users table).
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No account found with that email address.',
        ]);

        $otp     = rand(100000, 999999);
        $expires = Carbon::now()->addMinutes(5);

        // Store in session under a separate namespace from registration OTP
        session([
            'reset_otp'         => (string) $otp,
            'reset_otp_email'   => $request->email,
            'reset_otp_expires' => $expires,
            'reset_otp_verified'=> false,
        ]);

        try {
            Mail::to($request->email)->send(new ResetPasswordMail($otp));
        } catch (\Exception $e) {
            Log::error('Failed to send password reset OTP', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
            ], 500);
        }

        Log::info('Password reset OTP sent', ['email' => $request->email]);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to ' . $request->email,
        ]);
    }

    /**
     * Step 2 — Verify the OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $sessionOtp     = session('reset_otp');
        $sessionEmail   = session('reset_otp_email');
        $sessionExpires = session('reset_otp_expires');

        if (!$sessionOtp) {
            return response()->json([
                'success' => false,
                'message' => 'No OTP found. Please request a new one.',
            ], 422);
        }

        if ($request->email !== $sessionEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Email does not match. Please request a new OTP.',
            ], 422);
        }

        if (Carbon::now()->gt($sessionExpires)) {
            session()->forget(['reset_otp', 'reset_otp_email', 'reset_otp_expires', 'reset_otp_verified']);
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 422);
        }

        if ($request->otp !== $sessionOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.',
            ], 422);
        }

        session(['reset_otp_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified! You can now reset your password.',
        ]);
    }

    /**
     * Step 3 — Reset the password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        // Guard: OTP must have been verified in this session
        if (!session('reset_otp_verified') || session('reset_otp_email') !== $request->email) {
            return response()->json([
                'success' => false,
                'message' => 'OTP not verified. Please complete the verification step first.',
            ], 403);
        }

        DB::table('users')
            ->where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Clear reset session data
        session()->forget(['reset_otp', 'reset_otp_email', 'reset_otp_expires', 'reset_otp_verified']);

        Log::info('Password reset successfully', ['email' => $request->email]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully! You can now log in.',
        ]);
    }
}