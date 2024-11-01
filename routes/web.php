<?php

use App\Livewire\ValidateOTP;
use App\Mail\ForgotPassword;
use App\Mail\NotifyUser;
use App\Mail\UserOtp;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\FoodDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Livewire\ValidateOTP as LivewireValidateOTP;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

Route::get('test', function () {
    dd('BOOM!');
});

Route::get('clear', function () {
    session()->flush();
});

Route::get('/', App\Livewire\Landing::class)
    ->name('landing');

// General Caterer
Route::get('caterers', App\Livewire\Caterers::class)
    ->name('caterers');
Route::get('caterer/{caterer:name}', App\Livewire\About::class)
    ->name('about');

// Utilities
Route::get('utilities', App\Livewire\Utilities::class)
    ->name('utilities');
Route::get('utility/{utility:name}', App\Livewire\Utility::class)
    ->name('utility');

// Events
Route::get('events', App\Livewire\Events::class)
    ->name('events');
Route::get('event/{event:name}', App\Livewire\Event::class)
    ->name('event');

Route::get('package/{package:name}', App\Livewire\Package::class)
    ->name('package');

Route::get('menu', App\Livewire\Menu::class)
    ->name('menu');
Route::get('menu/{foodDetail}', App\Livewire\Food::class)
    ->name('food');

Route::get('contact', App\Livewire\Contact::class)
    ->name('contact');

Route::get('cart', App\Livewire\Cart::class)
    ->name('cart');



Route::group(['middleware' => ['auth', 'emailVerified']], function () {
    Route::view('profile', 'profile')
        ->name('profile');

    Route::get('order', App\Livewire\Order::class)
        ->name('order');

    Route::get('order-history', App\Livewire\OrderHistory::class)
        ->name('order-history');
    Route::get('view-order/{order}', App\Livewire\ViewOrder::class)
        ->name('view-order');

    Route::prefix('cancellation-request')->group(function () {
        Route::get('create/{order}', App\Livewire\CancellationRequest\Create::class)
            ->name('request-cancellation.create');
        Route::get('edit/{order}', App\Livewire\CancellationRequest\Edit::class)
            ->name('request-cancellation.edit');
        Route::get('{order}', App\Livewire\CancellationRequest\View::class)
            ->name('products.show');
    });

    Route::get('partial-payment-success', [PaymentController::class, 'successPartial'])
        ->name('partial-payment-success');
    Route::get('full-payment-success', [PaymentController::class, 'successFull'])
        ->name('full-payment-success');

    Route::get('partial-payment-existing-success', [PaymentController::class, 'successPartialExisting'])
        ->name('partial-payment-existing-success');
    Route::get('remaining-payment-success', [PaymentController::class, 'successRemaining'])
        ->name('remaining-payment-success');
    Route::get('full-payment-existing-success', [PaymentController::class, 'successFullExisting'])
        ->name('full-payment-existing-success');
});

Route::get('payment-cancelled', [PaymentController::class, 'cancelled'])
    ->name('payment-cancelled');

require __DIR__ . '/auth.php';


// Route::get('emailPreview', function () {
//     // $order = Order::findOrFail(7);
//     return view('mail.caterer-requirement');
//     // Mail::to('jjarts1028@gmail.com')
//     //     ->send(new ForgotPassword());
// });

Route::get('otp', function () {
    return view('validateOTP');
})->name('otp');

Route::get('request-otp', function () {
    $user = auth()->user();

    // Check if the user has hit the rate limit
    if (RateLimiter::tooManyAttempts('otp-request', $user->id)) {
        $timeLeft = RateLimiter::availableIn('otp-request', $user->id);
        return back()->with('error', "Please wait {$timeLeft} seconds before requesting a new OTP.");
    }

    // Generate a unique 4-digit OTP
    $otp;
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
    return redirect('otp')->with('message', 'A new OTP has been sent!');
})->name('request-otp')->middleware('auth');



Route::post('validate-otp', function () {
    // Validate the OTP input
    $validatedData = request()->validate([
        'otp' => ['required', 'min:4', 'max:4']
    ]);

    $otp = $validatedData['otp']; // Get the validated OTP

    // Retrieve the authenticated user
    $user = auth()->user();

    if ($user && $user->otp === $otp) {
        // Mark the email as verified and save the user
        $user->email_verified_at = now();
        $user->save();

        // Flash a success message to the session
        session()->flash('message', 'OTP verified successfully!');
        return redirect()->route('landing'); // Redirect to the landing page
    }

    // If the OTP is incorrect, flash an error message
    return back()->withErrors(['otp' => 'The provided OTP is incorrect.']);
});
