<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">
                Meals
                <flux:modal.trigger name="create-meal" class="absolute right-0 top-0">
                    <flux:button size="sm" icon="plus" />
                </flux:modal.trigger>
            </flux:heading>
            <flux:subheading size="lg">Manage your Meals</flux:subheading>
        </div>
        <flux:separator />

        <div class="flex items-center gap-4 m-2">
            <flux:date-picker size="sm" label="Filter by date" mode="range" wire:model.live="filterDateRange" clearable="true"/>
        </div>

        <flux:separator />

        <div class="hidden sm:block">
            <flux:table :paginate="$meals" class="w-full">
                <flux:table.columns>
                    <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Created</flux:table.column>
                    <flux:table.column>Ingredients</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy === 'calories'" :direction="$sortDirection" wire:click="sort('calories')">Calories</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($meals as $meal)
                        <flux:table.row :key="$meal->id">
                            <flux:table.cell>
                                <flux:badge icon="calendar" size="sm" inset="top bottom">{{ \Carbon\Carbon::parse($meal->created_at)->format('d/m/Y') }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $meal->ingredients }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge icon="fire" size="sm" inset="top bottom">{{ $meal->calories }} kcal</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:button.group>
                                    <flux:button size="xs" wire:click="edit({{ $meal->id }})" icon="pencil" />
                                    <flux:button size="xs" wire:click="delete({{ $meal->id }})" variant="danger" icon="trash" />
                                </flux:button.group>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
    <livewire:meals.create />
    <livewire:meals.edit />
</div>
