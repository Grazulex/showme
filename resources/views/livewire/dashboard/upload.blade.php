<div>
    <div class="flex items-center gap-4">
        <!-- Input caché -->
        <input
            id="upload-picture"
            type="file"
            wire:model="picture"
            accept="image/*"
            capture="environment"
            class="hidden"
        >

        <!-- Bouton stylé FluxUI -->
        <label for="upload-picture" class="cursor-pointer">
            <flux:button as="div" color="blue" icon="camera" wire:loading.attr="disabled">
                Take a photo
            </flux:button>
        </label>

        <!-- Aperçu image -->
        @if ($picture)
            <img
                src="{{ $picture->temporaryUrl() }}"
                alt="Preview"
                class="w-16 h-16 object-cover rounded border border-gray-300 shadow"
            >
        @endif

        <!-- Spinner pendant l'upload ou analyse -->
        <div wire:loading wire:target="picture">
            <div class="w-5 h-5 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>

    @error('picture')
    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
