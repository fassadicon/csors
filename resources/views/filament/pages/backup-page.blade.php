<x-filament-panels::page>
    <!-- Status Message -->
    @if ($backupStatus)
        <div
            class="p-4 mb-4 text-sm {{ str_contains($backupStatus, 'failed') ? 'text-red-700 bg-red-100' : 'text-green-700 bg-green-100' }} rounded-md">
            {{ $backupStatus }}
        </div>
    @endif
    <x-filament::button wire:click="runBackup"
        color="primary"
        class="px-4 py-2">
        Run Backup
    </x-filament::button>

    <!-- Backup Files List -->
    <h2 class="text-lg font-semibold mt-8 mb-4">Backup Files</h2>
    <ul class="list-disc list-inside space-y-2">
        @forelse ($backupFiles as $file)
            <li>
                <a href="{{ route('download.backup', ['filename' => basename($file)]) }}"
                    style="color:blue;">
                    {{ basename($file) }}
                </a>
            </li>
        @empty
            <li class="text-gray-500">No backups available.</li>
        @endforelse
    </ul>
</x-filament-panels::page>
