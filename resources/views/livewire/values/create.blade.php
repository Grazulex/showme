<div>
    <flux:modal name="create-value" class="md:w-200">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create value</flux:heading>
                <flux:text class="mt-2">Create a new value</flux:text>
            </div>


            <flux:select
                label="Topic"
                wire:model="topic_id"
                placeholder="Select a topic"
            >
                @foreach ($topics as $topic)
                    <flux:select.option value="{{ $topic->id }}">
                        {{ $topic->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:input.group>
                <flux:input wire:model="value" placeholder="The value" />
                <flux:input.group.suffix>t</flux:input.group.suffix>
            </flux:input.group>

            <flux:date-picker label="Date" wire:model="created_at" placeholder="Select a date" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" wire:click="submit" variant="primary">Create</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

