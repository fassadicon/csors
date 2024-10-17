<div class="flex justify-center flex-col  bottom-0 w-full text-white p-10 gap-y-4 md:gap-y-0 !pb-4 h-fit bg-slate-900">
    <div class="flex flex-col justify-between pt-24 mx-auto max-w-7xl sm:px-2 lg:px-4 md:py-0 md:flex-row gap-y-4 md:gap-y-0 md:gap-x-10">
        <div class="flex flex-col items-center justify-center gap-y-4">
            <h3>CSORS</h3>
            <x-application-logo class="block w-auto fill-current text-jt-white h-9 dark:text-gray-200" />
        </div>
        <div class="flex items-center">
            <p>
                For additional information or inquiries or if you encounter any issues, please feel free to contact us.
            </p>
        </div>
    </div>
    <div class="flex items-center justify-center gap-x-4">
        <p>© CSORS 2024</p>
        @if (session('adminInfo'))
            <p> | </p>
            <p>Phone: {{session('adminInfo')->phone_number}}</p>
            <p> | </p>
            <p>Email: {{session('adminInfo')->email}}</p>
        @endif
    </div>
</div>