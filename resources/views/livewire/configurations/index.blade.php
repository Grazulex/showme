<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">
                Configurations
            </flux:heading>
            <flux:subheading size="lg">Manage your configuration</flux:subheading>
        </div>

        <flux:separator />

        <flux:select label="Topic Weight" wire:model="topicWeight" placeholder="Select a topic" searchable="true">
            @foreach ($topics as $topic)
                <flux:select.option value="{{ $topic->id }}">
                    {{ $topic->name }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <flux:select label="Topic Calorie IN" wire:model="topicCalorieIn" placeholder="Select a topic" searchable="true">
            @foreach ($topics as $topic)
                <flux:select.option value="{{ $topic->id }}">
                    {{ $topic->name }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <flux:select label="Topic Calorie OUT" wire:model="topicCalorieOut" placeholder="Select a topic" searchable="true">
            @foreach ($topics as $topic)
                <flux:select.option value="{{ $topic->id }}">
                    {{ $topic->name }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" wire:click="submit" variant="primary">Update</flux:button>
        </div>

    </div>
</div>
