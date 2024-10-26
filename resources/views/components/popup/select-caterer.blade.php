<div class="fixed top-0 left-0 z-50 flex items-center justify-center w-full h-screen bg-black/25">
    <div class="w-[90%] md:w-[25%] h-auto p-6 bg-white rounded-lg shadow-md">
        <h3 class="mb-4 text-xl font-semibold">Select a Caterer</h3>
        <p class="mb-6 text-gray-600">Please choose a caterer before adding your order, or continue browsing.</p>

        <div class="flex flex-col justify-between gap-y-2">
            <!-- Button to select a caterer -->
            <a href="{{ route('caterers') }}">
                <button type="button"
                    class="w-full px-4 py-2 text-white transition rounded-md bg-jt-primary hover:bg-blue-600">
                    Select Caterer
                </button>
            </a>

            <!-- Button for browsing -->
            <button @click='showPopup=false'
                class="w-full px-4 py-2 text-gray-700 transition bg-gray-300 rounded-md hover:bg-gray-400">
                Just Browsing
            </button>
        </div>
    </div>
</div>