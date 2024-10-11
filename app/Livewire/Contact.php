<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;
use App\Mail\InquiryMail;
use Illuminate\Support\Facades\Mail;

class Contact extends Component
{
    public Caterer $caterer;
    public $name;
    public $subject;
    public $content;
    public $email;

    public function mount()
    {
        $this->caterer = Caterer::find(session()->get('caterer'))->first();

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
        Mail::to('audreysgv@gmail.com')->send(new InquiryMail(
            $this->name,
            $this->subject,
            $this->content,
            $this->email,
            $this->caterer->id,
        ));


        return redirect()->route('about', ['caterer' => $this->caterer])
            ->success('Inquiry sent! Please wait for an email response.');
    }

    public function render()
    {
        return view('livewire.contact');
    }
}
