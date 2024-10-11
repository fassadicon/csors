<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $first_name = '';
    public string $last_name = '';
    public string $middle_name = '';
    public string $ext_name = '';
    public string $phone_number = '';
    public string $email = '';
    public ?string $verification_image_path = null;
    public $verification_image; // For file upload

    public function mount(): void
    {
        $this->first_name = Auth::user()->first_name;
        $this->last_name = Auth::user()->last_name;
        $this->middle_name = Auth::user()->middle_name ?? '';
        $this->ext_name = Auth::user()->ext_name ?? '';
        $this->phone_number = Auth::user()->phone_number;
        $this->email = Auth::user()->email;
        $this->verification_image_path = Auth::user()->verification_image_path;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'ext_name' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'verification_image' => ['image', 'mimes:jpg,jpeg,png', 'max:10240', 'nullable'], // Specific file types
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle image upload
        if ($this->verification_image) {
            if ($user->verification_image_path) {
                // Optionally delete the old image
                \Storage::delete($user->verification_image_path);
            }
            $user->verification_image_path = $this->verification_image->store('customer-verification-images', 'public');
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->full_name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('landing', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation"
        class="mt-6 space-y-6">
        <div>
            <x-input-label for="first_name"
                :value="__('First Name')" />
            <x-text-input wire:model="first_name"
                id="first_name"
                name="first_name"
                type="text"
                class="mt-1 block w-full"
                required
                autofocus
                autocomplete="first_name" />
            <x-input-error class="mt-2"
                :messages="$errors->get('first_name')" />
        </div>
        <div>
            <x-input-label for="last_name"
                :value="__('Last Name')" />
            <x-text-input wire:model="last_name"
                id="last_name"
                name="last_name"
                type="text"
                class="mt-1 block w-full"
                required
                autofocus
                autocomplete="last_name" />
            <x-input-error class="mt-2"
                :messages="$errors->get('last_name')" />
        </div>
        <div>
            <x-input-label for="middle_name"
                :value="__('Middle Name')" />
            <x-text-input wire:model="middle_name"
                id="middle_name"
                name="middle_name"
                type="text"
                class="mt-1 block w-full"
                autofocus
                autocomplete="middle_name" />
            <x-input-error class="mt-2"
                :messages="$errors->get('middle_name')" />
        </div>
        <div>
            <x-input-label for="ext_name"
                :value="__('Extension Name')" />
            <x-text-input wire:model="ext_name"
                id="ext_name"
                name="ext_name"
                type="text"
                class="mt-1 block w-full"
                autofocus
                autocomplete="ext_name" />
            <x-input-error class="mt-2"
                :messages="$errors->get('ext_name')" />
        </div>
        <div>
            <x-input-label for="phone_number"
                :value="__('Phone Number')" />
            <x-text-input wire:model="phone_number"
                id="phone_number"
                name="phone_number"
                type="text"
                class="mt-1 block w-full"
                autofocus
                autocomplete="phone_number" />
            <x-input-error class="mt-2"
                :messages="$errors->get('phone_number')" />
        </div>
        <div>
            <x-input-label for="verification_image"
                :value="__('Upload Valid ID')" />

            <!-- Show existing verification image if available -->
            @if ($verification_image_path)
                <div class="mb-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Current Valid ID:') }}</p>
                    <img src="{{ asset('storage/' . $verification_image_path) }}"
                        alt="Current Verification Image"
                        class="mt-2 border rounded"
                        style="max-width: 200px; max-height: 200px;" />
                </div>
            @endif

            <!-- File input for new upload -->
            <input type="file"
                wire:model.change="verification_image"
                id="verification_image"
                class="mt-1 block w-full" />

            <x-input-error class="mt-2"
                :messages="$errors->get('verification_image')" />

            {{-- @if ($verification_image)
                <div class="mt-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Preview:') }}</p>
                    <img src="{{ $verification_image->temporaryUrl() }}"
                        alt="Verification Image Preview"
                        class="mt-2 border rounded"
                        style="max-width: 200px; max-height: 200px;" />
                </div>
            @endif --}}
        </div>


        <div>
            <x-input-label for="email"
                :value="__('Email')" />
            <x-text-input wire:model="email"
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                required
                autocomplete="username" />
            <x-input-error class="mt-2"
                :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3"
                on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
