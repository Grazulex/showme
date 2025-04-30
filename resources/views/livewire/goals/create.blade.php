<div>
    <flux:modal name="create-goal" class="md:w-200">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create goal</flux:heading>
                <flux:text class="mt-2">Create a new goal</flux:text>
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

            <flux:input label="Name" wire:model="name" placeholder="Name of the topic" />

            <flux:radio.group label="Type" wire:model="type" class="mt-4">
                @foreach ($types as $type)
                    <flux:radio value="{{ $type->value }}" label="{{ $type->label() }}" />
                @endforeach
            </flux:radio.group>

            <flux:input label="target" wire:model="target" placeholder="Target value" />

            <flux:date-picker label="Ended at" wire:model="ended_at" placeholder="Select a date" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" wire:click="submit" variant="primary">Create</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
