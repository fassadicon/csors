<div x-data="{
        showPopup: false, disableButton: false,
        showLoading: false,
        showFeedback() {
            this.showPopup = false;
            this.showLoading = true;
        }
    }" 
    x-on:feedback.window="showFeedback()"
    class="max-w-xl space-y-4">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>
    <button @click="showPopup=true" class="px-4 py-2 btn-primary">Change Password</button>

    <template x-if="showPopup">
        <div class="fixed left-0 z-50 flex items-center justify-center w-full h-screen -top-4 bg-black/50">
            <div class="p-10 bg-white rounded-xl">
                <form wire:submit='sendPasswordReset' class="items-center space-y-4">
                    <center>
                        <h3>Request to Change Password</h3>
                        <p class="mt-2">For this process you need to logout first.</p>
                        <div class="flex flex-col mt-4 gap-y-2">
                            <button type="submit" class="px-5 py-2 text-white bg-jt-primary">LOGOUT</button>
                            <button @click="showPopup=false"  class="px-5 py-2 bg-transparent ">Cancel</button>
                        </div>
                    </center>
                </form>
            </div>
        </div>
    </template>

    <template x-if="showLoading">
        <div class="fixed left-0 z-50 flex items-center justify-center w-full h-screen -top-4 bg-black/50">
            <div class="p-10 bg-white rounded-xl">
                <form wire:submit='sendPasswordReset' class="items-center space-y-4">
                    <center>
                        <h3>Sending email...</h3>
                        <hr class="my-4">
                        <img src="{{asset('images/icons/mail.gif')}}" alt="">         
                    </center>
                </form>
            </div>
        </div>
    </template>
</div>
