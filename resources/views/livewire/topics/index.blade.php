<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">
                Topics
                <flux:modal.trigger name="create-topic" class="absolute right-0 top-0">
                    <flux:button size="sm" icon="plus" />
                </flux:modal.trigger>
            </flux:heading>
            <flux:subheading size="lg">Manage your Topics</flux:subheading>
        </div>

        <flux:separator />
        <div class="sm:hidden space-y-4">
            @foreach($topics as $topic)
                <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md p-4 border border-gray-100 dark:border-gray-700 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            {{ $topic->name }}
                        </div>
                        <flux:button.group>
                            <flux:button size="xs" wire:click="edit({{ $topic->id }})" icon="pencil" />
                            <flux:button size="xs" wire:click="delete({{ $topic->id }})" variant="danger" icon="trash" />
                        </flux:button.group>
                    </div>

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $topic->description }}
                    </div>

                    <div class="flex flex-wrap gap-2 text-sm">
                        <div class="flex items-center gap-1">
                            <flux:badge icon="{{ $topic->unit->icon() }}" size="sm" :color="$topic->unit->color()">
                                {{ $topic->unit->label() }}
                            </flux:badge>
                        </div>
                        <div class="flex items-center gap-1">
                            <flux:badge icon="medal" size="sm" color="success">
                                {{ $topic->goals->count() }}
                            </flux:badge>
                        </div>
                        <div class="flex items-center gap-1">
                            <flux:badge icon="chart-bar" size="sm" color="info">
                                {{ $topic->values->count() }}
                            </flux:badge>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="hidden sm:block">
            <flux:table :paginate="$topics" class="w-full">
                <flux:table.columns>
                    <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
                    <flux:table.column>Description</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy === 'unit'" :direction="$sortDirection" wire:click="sort('unit')">Unit</flux:table.column>
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
                                <flux:badge icon="medal" size="sm" color="success" inset="top bottom">{{ $topic->goals->count() }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge icon="chart-bar" size="sm" color="info" inset="top bottom">{{ $topic->values->count() }}</flux:badge>
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
    <livewire:topics.create />
    <livewire:topics.edit />
</div>
