<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">
                Meals
                <flux:modal.trigger name="create-meal" class="absolute right-0 top-0">
                    <flux:button size="sm" icon="plus"/>
                </flux:modal.trigger>
            </flux:heading>
            <flux:subheading size="lg">Manage your Meals</flux:subheading>
        </div>
        <flux:separator/>

        <div class="flex items-center gap-4 m-2">
            <flux:date-picker size="sm" label="Filter by date" mode="range" wire:model.live="filterDateRange"
                              clearable="true"/>
        </div>

        <flux:separator/>

        <div class="hidden sm:block">
            <flux:table :paginate="$meals" class="w-full">
                <flux:table.columns>
                    <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                                       wire:click="sort('created_at')">Created
                    </flux:table.column>
                    <flux:table.column>Ingredients</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy === 'calories'" :direction="$sortDirection"
                                       wire:click="sort('calories')">Calories
                    </flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($meals as $meal)
                        <flux:table.row :key="$meal->id">
                            <flux:table.cell>
                                <flux:badge icon="calendar" size="sm" inset="top bottom">
                                    {{ \Carbon\Carbon::parse($meal->created_at)->format('d/m/Y H:i') }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-wrap">
                                {{ $meal->ingredients }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge icon="utensils" size="sm" inset="top bottom">
                                    {{ $meal->calories }}cal
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:button.group>
                                    <flux:button size="xs" wire:click="edit({{ $meal->id }})" icon="pencil"/>
                                    <flux:button size="xs" wire:click="copy({{ $meal->id }})" variant="filled" icon="copy"/>
                                    <flux:button size="xs" wire:click="delete({{ $meal->id }})" variant="danger"
                                                 icon="trash"/>
                                </flux:button.group>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="sm:hidden space-y-4">
            @foreach ($meals as $meal)
                <div
                    class="rounded-2xl bg-white dark:bg-gray-800 shadow-md p-4 border border-gray-100 dark:border-gray-700 space-y-3">
                    <div class="flex items-start justify-between">
                        <div class="space-y-1">
                            <div class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($meal->created_at)->format('d M Y H:i') }}
                            </div>
                            <flux:badge icon="utensils" size="xs" color="red">
                                {{ $meal->calories }} cal
                            </flux:badge>
                        </div>

                        <flux:button.group>
                            <flux:button size="xs" wire:click="edit({{ $meal->id }})" icon="pencil"/>
                            <flux:button size="xs" wire:click="copy({{ $meal->id }})" variant="filled" icon="copy"/>
                            <flux:button size="xs" wire:click="delete({{ $meal->id }})" variant="danger" icon="trash"/>
                        </flux:button.group>
                    </div>

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $meal->ingredients }}
                    </div>
                </div>
            @endforeach

            <div class="mt-4">
                {{ $meals->links() }}
            </div>
        </div>


    </div>
    <livewire:meals.create/>
    <livewire:meals.edit/>
</div>
