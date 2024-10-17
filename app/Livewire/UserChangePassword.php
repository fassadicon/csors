<?php

namespace App\Livewire;

use App\Mail\ForgotPassword; // Ensure this is imported
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class UserChangePassword extends Component
{
    public $email;
    public $customerName;
    public function mount()
    {
        $user = Auth::user();
        $this->email = $user->email; // Get the authenticated user's email
        $this->customerName = $user->name;
    }

    public function generatePasswordResetLink()
    {
        // Get the user by email
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            return "User not found.";
        }

        // Generate the password reset token
        $token = Password::createToken($user);

        // Generate the password reset URL
        $resetLink = URL::to('/reset-password/' . $token . '?email=' . urlencode($user->email));

        return $resetLink;
    }

    public function sendPasswordReset()
    {
        $resetLink = $this->generatePasswordResetLink();

        $this->dispatch('feedback');
        // Send the password reset email
        Mail::to($this->email)->send(new ForgotPassword($resetLink, $this->customerName)); // Pass the reset link to the email
        // Show a popup message for user feedback
        // session()->flash('status', 'A password reset link has been sent to your email.');

        // Logout the user
        Auth::logout();
        // dd($resetLink);

        // Redirect to the landing page
        return redirect('/'); // Adjust this to your actual landing page route
    }

    public function render()
    {
        return view('livewire.user-change-password');
    }
}
