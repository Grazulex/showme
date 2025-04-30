<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">
                Goals
                <flux:modal.trigger name="create-goal" class="absolute right-0 top-0">
                    <flux:button size="sm" icon="plus" />
                </flux:modal.trigger>
            </flux:heading>
            <flux:subheading size="lg" class="mb-6">Manage your Goals</flux:subheading>
        </div>

        <livewire:goals.create />

        <flux:separator />
        <flux:table :paginate="$goals" class="w-full">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Topic</flux:table.column>
                <flux:table.column>Type</flux:table.column>
                <flux:table.column>Target</flux:table.column>
                <flux:table.column>Started</flux:table.column>
                <flux:table.column>Ended</flux:table.column>
                <flux:table.column>Values</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($goals as $goal)
                    <flux:table.row :key="$goal->id">
                        <flux:table.cell variant="strong">{{ $goal->name }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge icon="{{$goal->topic->unit->icon()}}" size="sm" :color="$goal->topic->unit->color()" inset="top bottom">{{ $goal->topic->name }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge icon="{{$goal->type->icon()}}" size="sm" inset="top bottom">{{ $goal->type->label() }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>{{ $goal->target }} {{ $goal->topic->unit->value }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge icon="calendar" size="sm" inset="top bottom">{{ $goal->started_at->format('d/m/Y') }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge icon="calendar" size="sm" inset="top bottom">{{ $goal->ended_at->format('d/m/Y') }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button.group>
                                <flux:button size="xs" wire:click="edit({{ $goal->id }})" icon="pencil" />
                                <flux:button size="xs" wire:click="delete({{ $goal->id }})" variant="danger" icon="trash" />
                            </flux:button.group>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
