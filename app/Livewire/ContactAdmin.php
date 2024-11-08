<?php

namespace App\Livewire;

use App\Mail\InquiryMail;
use App\Models\Caterer;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactAdmin extends Component
{
    public User $admin;
    public $name;
    public $subject;
    public $content;
    public $email;

    public function mount()
    {
        // $this->admin = Caterer::find(session()->get('caterer'))->first();
        foreach (User::all() as $user) {
            if ($user->hasRole('superadmin')) {
                $this->admin = $user;
                session(['admin_phone_number' => $this->admin->phone_number]);
                break;
            }
        }

        // if ($admin) {
        //     // Found a superadmin, process accordingly
        //     echo $admin->name;
        // }
        if (auth()->check()) {
            $this->name = auth()->user()->full_name;
            $this->email = auth()->user()->email;
        }
    }

    public function send()
    {
        // dd($this->caterer);
        // validate
        $inputs = $this->validate([
            'name' => ['required', 'min:3', 'max:50'],
            'subject' => ['required', 'min:3', 'max:50'],
            'content' => ['required', 'min:3', 'max:150'],
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // $this->caterer->email
        // dd($this->admin->email);
        // dd('personal email:'.session('caterer_email_personal'). ' business email: '. session('caterer_email_business'));
        Mail::to($this->admin->email)->send(new InquiryMail(
            $this->name,
            $this->subject,
            $this->content,
            $this->email,
            $this->admin->id,
        ));

        return redirect()->route('contact-admin')
            ->success('Inquiry sent! Please wait for an email response.');
    }

    public function render()
    {
        return view('livewire.contact');
    }
}
