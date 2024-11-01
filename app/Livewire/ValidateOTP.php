<?php

namespace App\Livewire;

use Livewire\Component;

class ValidateOTP extends Component
{
    public $otp;

    protected $rules = [
        'otp' => 'required|string|size:4',
    ];

    public function validateOTP()
    {
        $this->validate();
        $user = auth()->user();
        if ($user && $user->otp === $this->otp) {
            $user->email_verified_at = now();
            $user->save();

            session()->flash('message', 'OTP verified successfully!');
            return redirect()->route('landing');
        }

        $this->addError('otp', 'The provided OTP is incorrect.');
    }

    public function render()
    {
        return view('livewire.validate-o-t-p');
    }
}
