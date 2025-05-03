<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">
                Values
                <flux:modal.trigger name="create-value" class="absolute right-0 top-0">
                    <flux:button size="sm" icon="plus" />
                </flux:modal.trigger>
            </flux:heading>
            <flux:subheading size="lg" class="mb-6">Manage your Values</flux:subheading>
        </div>

        <livewire:values.create />
        <livewire:values.edit />


        <flux:separator />
        <flux:table :paginate="$values" class="w-full">
            <flux:table.columns>
                <flux:table.column>Topic</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Created</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'value'" :direction="$sortDirection" wire:click="sort('value')">Value</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'diff_with_last'" :direction="$sortDirection" wire:click="sort('diff_with_last')">Difference with last</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($values as $value)
                    <flux:table.row :key="$value->id">
                        <flux:table.cell>
                            <flux:badge icon="{{$value->topic->unit->icon()}}" size="sm" :color="$value->topic->unit->color()" inset="top bottom">{{ $value->topic->name }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge icon="calendar" size="sm" inset="top bottom">{{ \Carbon\Carbon::parse($goal->created_at)->diffForHumans() }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $value->value }} {{ $value->topic->unit->value }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $value->diff_with_last }} {{ $value->topic->unit->value }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button.group>
                                <flux:button size="xs" wire:click="edit({{ $value->id }})" icon="pencil" />
                                <flux:button size="xs" wire:click="delete({{ $value->id }})" variant="danger" icon="trash" />
                            </flux:button.group>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
