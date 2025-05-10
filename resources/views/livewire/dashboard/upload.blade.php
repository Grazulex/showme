<div>
    <form wire:submit.prevent>
        <input
            type="file"
            wire:model="picture"
            accept="image/*"
            capture="environment"
            class="mb-4"
        >

        @error('picture') <span class="text-red-500">{{ $message }}</span> @enderror

        @if ($picture)
            <p class="text-green-600">Uploading preview:</p>
            <img src="{{ $picture->temporaryUrl() }}" class="w-48 rounded" alt="Preview">
        @endif
    </form>
</div>
