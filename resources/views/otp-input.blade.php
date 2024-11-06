<!DOCTYPE html>
<html>

<head>
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center w-full h-screen bg-slate-900">
    <div class="max-w-md px-4 py-10 mx-auto text-center bg-white shadow sm:px-8 rounded-xl">
        <header class="mb-8">
            <h1 class="mb-1 text-2xl font-bold">Enter Your OTP</h1>
            <p class="text-[15px] text-slate-500">Enter the 4-digit verification code that was sent to your email.
            </p>
        </header>
    
        @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('message'))
            <p class="text-sm italic text-green-600">{{ session('message') }}</p>
        @endif
        <form id="otp-form" action="{{ route('caterer.otp.verify') }}" method="POST">
            @csrf
            <div class="flex items-center justify-center gap-3">
                <input type="text" name="otp[]"
                    class="p-4 text-2xl font-extrabold text-center border border-transparent rounded outline-none appearance-none w-14 h-14 text-slate-900 bg-slate-100 hover:border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                    pattern="\d*" maxlength="1" autofocus />
                <input type="text" name="otp[]"
                    class="p-4 text-2xl font-extrabold text-center border border-transparent rounded outline-none appearance-none w-14 h-14 text-slate-900 bg-slate-100 hover:border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                    maxlength="1" />
                <input type="text" name="otp[]"
                    class="p-4 text-2xl font-extrabold text-center border border-transparent rounded outline-none appearance-none w-14 h-14 text-slate-900 bg-slate-100 hover:border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                    maxlength="1" />
                <input type="text" name="otp[]"
                    class="p-4 text-2xl font-extrabold text-center border border-transparent rounded outline-none appearance-none w-14 h-14 text-slate-900 bg-slate-100 hover:border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                    maxlength="1" />
            </div>
            <div class="max-w-[260px] mx-auto mt-4">
                <button type="submit"
                    class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-indigo-500 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150">Verify
                    Account</button>
            </div>
        </form>
        <div class="mt-4 text-sm text-slate-500">Didn't receive code? <a href="{{ route('caterer.otp.request') }}"
                class="font-medium text-indigo-500 hover:text-indigo-600" href="#0">Resend</a></div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('otp-form');
            const inputs = [...form.querySelectorAll('input[type=text]')];
            const submit = form.querySelector('button[type=submit]');
    
            const handleKeyDown = (e) => {
                if (!/^[0-9]{1}$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab' && !e.metaKey) {
                    e.preventDefault();
                }
    
                if (e.key === 'Delete' || e.key === 'Backspace') {
                    const index = inputs.indexOf(e.target);
                    if (index > 0) {
                        inputs[index - 1].value = '';
                        inputs[index - 1].focus();
                    }
                }
            }
    
            const handleInput = (e) => {
                const { target } = e;
                const index = inputs.indexOf(target);
                if (target.value) {
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    } else {
                        submit.focus();
                    }
                }
            }
    
            const handleFocus = (e) => {
                e.target.select();
            }
    
            const handlePaste = (e) => {
                e.preventDefault();
                const text = e.clipboardData.getData('text');
                if (!new RegExp(`^[0-9]{${inputs.length}}$`).test(text)) {
                    return;
                }
                const digits = text.split('');
                inputs.forEach((input, index) => input.value = digits[index]);
                submit.focus();
            }
    
            inputs.forEach((input) => {
                input.addEventListener('input', handleInput);
                input.addEventListener('keydown', handleKeyDown);
                input.addEventListener('focus', handleFocus);
                input.addEventListener('paste', handlePaste);
            });
        });
    </script>
</body>

</html>