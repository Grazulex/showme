<div>
    <flux:modal name="edit-meal" class="md:w-200">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit meal</flux:heading>
                <flux:text class="mt-2">Edit a meal</flux:text>
            </div>

            <flux:input wire:model="ingredients" placeholder="Ingredients" type="text" />

            <flux:input.group>
                <flux:input wire:model="calories" placeholder="The calorie" type="number" step="0.01" pattern="[0-9]+([\.,][0-9]+)?" />
                <flux:input.group.suffix>cal</flux:input.group.suffix>
            </flux:input.group>

            <flux:date-picker label="Date" wire:model="created_at" placeholder="Select a date" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" wire:click="submit" variant="primary">Update</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
