<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">
                Values
                <flux:modal.trigger name="create-value" class="absolute right-0 top-0">
                    <flux:button size="sm" icon="plus" />
                </flux:modal.trigger>
            </flux:heading>
            <flux:subheading size="lg">Manage your Values</flux:subheading>
        </div>
        <flux:separator />

        <div class="flex items-center gap-4 m-2">
            <flux:select size="sm" wire:model.live="filterTopicId" label="Filter by topic" placeholder="Select a topic">
                <flux:select.option value="0">All</flux:select.option>
                @foreach ($topics as $topic)
                    <flux:select.option :value="$topic->id">{{ $topic->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:date-picker size="sm" label="Filter by date" mode="range" wire:model.live="filterDateRange" clearable="true"/>
        </div>

        <flux:separator />

        <div class="sm:hidden space-y-4">
            @foreach ($values as $value)
                <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md p-4 border border-gray-100 dark:border-gray-700 space-y-3">
                    <div class="flex items-start justify-between">
                        <div class="space-y-1">
                            <div class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                {{ $value->topic->name }}
                            </div>
                            <flux:badge icon="{{ $value->topic->unit->icon() }}" size="xs" :color="$value->topic->unit->color()">
                                {{ $value->topic->unit->label() }}
                            </flux:badge>
                        </div>

                        <flux:button.group>
                            <flux:button size="xs" wire:click="edit({{ $value->id }})" icon="pencil" />
                            <flux:button size="xs" wire:click="delete({{ $value->id }})" variant="danger" icon="trash" />
                        </flux:button.group>
                    </div>

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center justify-between">
                            <span>Created on</span>
                            <flux:badge icon="calendar" size="xs">
                                {{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}
                            </flux:badge>
                        </div>

                        <div class="flex items-center justify-between mt-1">
                            <span>Value</span>
                            <strong>{{ $value->value }} {{ $value->topic->unit->value }}</strong>
                        </div>

                        <div class="flex items-center justify-between mt-1">
                            <span>Diff. with last</span>
                            <flux:badge
                                icon="{{ $value->diff_with_last == 0.00 ? 'equal' : ($value->diff_with_last > 0.00 ? 'arrow-up' : 'arrow-down') }}"
                                :color="$value->color"
                                size="xs"
                            >
                                {{ $value->diff_with_last }} {{ $value->topic->unit->value }}
                            </flux:badge>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="hidden sm:block">
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
                                <flux:badge icon="calendar" size="sm" inset="top bottom">{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $value->value }} {{ $value->topic->unit->value }}
                            </flux:table.cell>
                            <flux:table.cell>

                                <flux:badge icon="{{ $value->diff_with_last == 0.00 ? 'equal' : ($value->diff_with_last > 0.00 ? 'arrow-up' : 'arrow-down') }}" :color="$value->color" size="sm" inset="top bottom">
                                    {{ $value->diff_with_last }} {{ $value->topic->unit->value }}
                                </flux:badge>

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
    <livewire:values.create />
    <livewire:values.edit />
</div>
