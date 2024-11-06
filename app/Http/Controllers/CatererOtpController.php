<?php

namespace App\Http\Controllers;

use App\Mail\UserOtp;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class CatererOtpController extends Controller
{
    // Show the OTP input page
    public function show()
    {
        return view('otp-input');
    }

    // Handle OTP verification
    public function verify(Request $request)
    {
        // Join the array of OTP digits into a single string
        $otp = implode('', $request->input('otp'));

        // Validate the joined OTP as a numeric string
        $request->merge(['otp' => $otp]);
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        // Retrieve the OTP from the authenticated user
        $user = Auth::user();
        $sessionOtp = session('otp'); // Assuming the OTP was stored in the session
        $email = $user->email; // Get the authenticated user's email

        if ($otp == $user->otp) {
            // Clear the OTP from the session
            // session()->forget('otp');
            $user->email_verified_at = now();
            $user->save();

            // Redirect to the Filament dashboard
            return redirect('admin');
        } else {
            return back()->withErrors(['otp' => 'The OTP is incorrect. Please try again.']);
        }
    }

    public function requestOTP()
    {
        $user = auth()->user();
        // Check if the user has hit the rate limit
        if (RateLimiter::tooManyAttempts('otp-request', $user->id)) {
            $timeLeft = RateLimiter::availableIn('otp-request', $user->id);
            return back()->with('error', "Please wait {$timeLeft} seconds before requesting a new OTP.");
        }
        // Generate a unique 4-digit OTP
        $otp = "";
        do {
            $otp = random_int(1000, 9999);
        } while (DB::table('users')->where('otp', $otp)->exists());

        // Assign the OTP and save it to the user
        $user->otp = $otp;
        $user->save();

        // Record the OTP request attempt
        RateLimiter::hit('otp-request', $user->id);

        Mail::to($user->email)->send(new UserOtp($otp));

        // Redirect to the OTP validation page
        return redirect('otp-input')->with('message', 'A new OTP has been sent!');
    }
}
