<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">
                Topics
                <flux:modal.trigger name="create-topic" class="absolute right-0 top-0">
                    <flux:button size="sm" icon="plus" />
                </flux:modal.trigger>
            </flux:heading>
            <flux:subheading size="lg" class="mb-6">Manage your Topics</flux:subheading>
        </div>

        <livewire:topics.create />
        <livewire:topics.edit />

        <flux:separator />
        <flux:table :paginate="$topics" class="w-full">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Desciption</flux:table.column>
                <flux:table.column>Unit</flux:table.column>
                <flux:table.column>Goals</flux:table.column>
                <flux:table.column>Values</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($topics as $topic)
                    <flux:table.row :key="$topic->id">
                        <flux:table.cell variant="strong">{{ $topic->name }}</flux:table.cell>
                        <flux:table.cell class="text-wrap">{{ $topic->description }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge icon="{{$topic->unit->icon()}}" size="sm" :color="$topic->unit->color()" inset="top bottom">{{ $topic->unit->label() }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge icon="academic-cap" size="sm" color="success" inset="top bottom">{{ $topic->goals->count() }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button.group>
                                <flux:button size="xs" wire:click="edit({{ $topic->id }})" icon="pencil" />
                                <flux:button size="xs" wire:click="delete({{ $topic->id }})" variant="danger" icon="trash" />
                            </flux:button.group>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
