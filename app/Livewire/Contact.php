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
        // $this->caterer->email
        Mail::to('audreysgv@gmail.com')->send(new InquiryMail(
            $this->name,
            $this->subject,
            $this->content,
            $this->email,
            $this->caterer->id,
        ));

        redirect()->route('about', ['caterer' => $this->caterer]);
    }

    public function render()
    {
        return view('livewire.contact');
    }
}
