<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpController extends Controller
{
    // Called via AJAX when user clicks "Send OTP"
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $otp     = rand(100000, 999999);
        $expires = Carbon::now()->addMinutes(5);

        // Store OTP in session (user not created yet)
        session([
            'pending_otp'         => (string) $otp,
            'pending_otp_email'   => $request->email,
            'pending_otp_expires' => $expires,
            'otp_verified'        => false,
        ]);

        // Send OTP email
        Mail::send('emails.otp', ['otp' => $otp], function ($msg) use ($request) {
            $msg->to($request->email)
                ->subject('Your Hookcraft Avenue OTP Code');
        });

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to ' . $request->email,
        ]);
    }

    // Called via AJAX when user submits the OTP code
    public function verify(Request $request)
    {
        $request->validate([
            'otp'   => 'required|digits:6',
            'email' => 'required|email',
        ]);

        $sessionOtp     = session('pending_otp');
        $sessionEmail   = session('pending_otp_email');
        $sessionExpires = session('pending_otp_expires');

        // Check if OTP session exists
        if (!$sessionOtp) {
            return response()->json([
                'success' => false,
                'message' => 'No OTP found. Please request a new one.',
            ], 422);
        }

        // Check email matches
        if ($request->email !== $sessionEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Email does not match. Please request a new OTP.',
            ], 422);
        }

        // Check if expired
        if (Carbon::now()->gt($sessionExpires)) {
            session()->forget(['pending_otp', 'pending_otp_email', 'pending_otp_expires', 'otp_verified']);
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 422);
        }

        // Check OTP match
        if ($request->otp !== $sessionOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.',
            ], 422);
        }

        // All good — mark as verified in session
        session(['otp_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified! You can now complete registration.',
        ]);
    }
}