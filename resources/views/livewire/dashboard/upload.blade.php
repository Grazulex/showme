<div>
    <div class="flex items-center gap-4">
        <!-- Input caché AVANT le label pour être bien détecté -->
        <input
            id="upload-picture"
            type="file"
            wire:model="picture"
            accept="image/*"
            capture="environment"
            class="hidden"
        >

        <!-- Le label entoure directement le bouton -->
        <label for="upload-picture" class="cursor-pointer">
            <flux:button as="div" color="blue" icon="camera">
                Take a photo
            </flux:button>
        </label>

        <!-- Preview -->
        @if ($picture)
            <img
                src="{{ $picture->temporaryUrl() }}"
                alt="Preview"
                class="w-16 h-16 object-cover rounded border border-gray-300 shadow"
            >
        @endif
    </div>

    @error('picture')
    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
