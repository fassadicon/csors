<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        $user = Auth::user();
        // dd('test');
        // If the user is authenticated and email_verified_at is null, redirect
        if ($user && !$user->email_verified_at) {
            return redirect()->route('validateOtp'); // Adjust this to your route for email verification notice
        }

        return $next($request); // Proceed to the next middleware or request
    }
}
