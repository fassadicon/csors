@props(['customerName', 'dateCreated', 'read' => false, 'message'])

<div class="w-full px-6 py-4 mt-2 bg-white rounded-lg shadow">
    <div class="inline-flex items-center justify-between w-full ">
        <div class="inline-flex items-center">
            <img src="https://cdn-icons-png.flaticon.com/512/893/893257.png" alt="Training Icon" class="w-6 h-6 mr-3">
            <h3 class="text-base font-bold text-gray-800">{{$customerName}}</h3>
        </div>
        <p class="text-xs text-gray-500">
            {{-- date time here --}}
            {{$dateCreated}}
        </p>
    </div>
    <p class="mt-1 text-sm">
        {{$message}}
    </p>
</div>