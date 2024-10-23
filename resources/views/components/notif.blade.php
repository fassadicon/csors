@props(['notifCount', 'notifications'])

@if (auth()->user())
{{-- Notifications --}}
<div class="w-fit">
    <div x-data="{showNotif: false, notifCount:false}" class="flex items-end sm:-my-px sm:ms-10 sm:flex shrink-0">
        <x-mary-button wire:click='readAllNotif' @click="showNotif = true, notifCount = true" icon="o-bell"
            class="relative btn-circle">
            <template x-if="!notifCount">
                <x-mary-badge value="{{ $notifCount }}" class="absolute badge-primary -right-2 -top-2" />
            </template>
            <template x-if="notifCount">
                <x-mary-badge value="0" class="absolute badge-primary -right-2 -top-2" />
            </template>
        </x-mary-button>
        {{-- notif container --}}
        <template x-if="showNotif">
            <div style="top: 70px;"
                class="fixed max-h-[500px] overflow-y-auto bg-jt-white top-[70px] w-[350px] right-5 min-w-32 p-4 shadow-xl">
                <div class="flex items-center justify-between">
                    <h4>Notifications</h4>
                    <x-mary-button @click="showNotif = false" icon="o-x-mark">
                    </x-mary-button>
                </div>
                <hr class="my-4">
                <div>
                    @foreach ($notifications as $notif)
                    <x-notif-card customerName="{{ $notif['customer_name'] ?? 'System' }}"
                        :read="$notif->read_at ? true : false"
                        message="{{ $notif['data']['title'] ?? 'No message available' }}"
                        dateCreated="{{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}" />
                    @endforeach
                    @if (count($notifications) <= 0) <p>You have 0 notifications yet...</p>
                        @endif
                </div>
            </div>
        </template>
    </div>
</div>
@endif