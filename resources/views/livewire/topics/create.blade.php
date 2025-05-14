<div>
    <flux:modal name="create-topic" class="md:w-200">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create topic</flux:heading>
                <flux:text class="mt-2">Create a new topic and goal(s)</flux:text>
            </div>

            <flux:input label="Name" wire:model="name" placeholder="Name of the topic" />

            <flux:textarea label="Description" wire:model="description" placeholder="Description of the topic" />

            <flux:radio.group label="Unit" wire:model="unit" class="mt-4">
                @foreach ($units as $unit)
                    <flux:radio value="{{ $unit->value }}" label="{{ $unit->label() }}" />
                @endforeach
            </flux:radio.group>

            <flux:field variant="inline">
                <flux:label>Is weight ?</flux:label>

                <flux:switch wire:model.live="is_weight" />

                <flux:error name="is_weight" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" wire:click="submit" variant="primary">Create</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
