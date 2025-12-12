<div>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Modern Header --}}
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    Values
                </flux:heading>
                <flux:subheading class="mt-2 text-gray-600 dark:text-gray-400">
                    Record and track your health measurements
                </flux:subheading>
            </div>
            <flux:modal.trigger name="create-value">
                <flux:button
                    icon="plus"
                    variant="primary"
                    class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-500/25 transform hover:scale-105 transition-all duration-200"
                >
                    New Value
                </flux:button>
            </flux:modal.trigger>
        </div>

        <flux:separator class="opacity-50" />

        {{-- Filters --}}
        <div class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <flux:select size="sm" wire:model.live="filterTopicId" label="Filter by topic" placeholder="Select a topic" class="focus:ring-emerald-500 focus:border-emerald-500">
                    <flux:select.option value="0">All Topics</flux:select.option>
                    @foreach ($topics as $topic)
                        <flux:select.option :value="$topic->id">{{ $topic->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="flex-1 min-w-[250px]">
                <flux:date-picker size="sm" label="Filter by date" mode="range" wire:model.live="filterDateRange" clearable="true" class="focus:ring-emerald-500 focus:border-emerald-500"/>
            </div>
        </div>

        <flux:separator class="opacity-50" />

        {{-- Mobile Cards View --}}
        <div class="sm:hidden">
            {{-- Mobile Header --}}
            <div class="mb-4 px-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                        Your Values
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $values->total() }} {{ Str::plural('value', $values->total()) }}
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                @foreach ($values as $value)
                    <div class="relative overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-lg border border-emerald-200/50 dark:border-emerald-900/30 p-5 card-hover">
                        {{-- Gradient Accent --}}
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-600"></div>

                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 space-y-2">
                                <div class="text-lg font-bold text-gray-800 dark:text-gray-100">
                                    {{ $value->topic->name }}
                                </div>
                                <flux:badge icon="{{ $value->topic->unit->icon() }}" size="sm" :color="$value->topic->unit->color()" class="font-semibold shadow-sm">
                                    {{ $value->topic->unit->label() }}
                                </flux:badge>
                            </div>
                            <div class="flex items-center gap-1.5 ml-3 shrink-0">
                                <flux:button
                                    size="xs"
                                    wire:click="edit({{ $value->id }})"
                                    icon="pencil"
                                    class="!bg-gradient-to-r !from-emerald-500 !to-teal-600 hover:!from-emerald-600 hover:!to-teal-700 !text-white !shadow-md hover:!shadow-lg !shadow-emerald-500/25 hover:!shadow-emerald-500/40 transition-all duration-200 !border-0"
                                />
                                <flux:button
                                    size="xs"
                                    wire:click="delete({{ $value->id }})"
                                    variant="danger"
                                    icon="trash"
                                    class="!shadow-md hover:!shadow-lg !shadow-rose-500/25 hover:!shadow-rose-500/40 transition-all duration-200"
                                />
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Date</span>
                                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Value</span>
                                    <span class="font-bold text-emerald-700 dark:text-emerald-400">
                                        {{ $value->value }} {{ $value->topic->unit->value }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Diff. with last</span>
                                    <flux:badge
                                        icon="{{ $value->diff_with_last == 0.00 ? 'equal' : ($value->diff_with_last > 0.00 ? 'arrow-up' : 'arrow-down') }}"
                                        :color="$value->color"
                                        size="sm"
                                        class="font-semibold shadow-sm"
                                    >
                                        {{ $value->diff_with_last }} {{ $value->topic->unit->value }}
                                    </flux:badge>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex items-center justify-between px-1">
                <span class="text-xs text-gray-600 dark:text-gray-400">
                    Showing {{ $values->firstItem() ?? 0 }} to {{ $values->lastItem() ?? 0 }} of {{ $values->total() }} results
                </span>
                <div class="flex-1 flex justify-end">
                    {{ $values->links() }}
                </div>
            </div>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden sm:block">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-emerald-100/50 dark:border-emerald-900/30 overflow-hidden backdrop-blur-sm">
                {{-- Table Header with Gradient --}}
                <div class="bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 dark:from-emerald-950/30 dark:via-teal-950/30 dark:to-emerald-950/30 border-b-2 border-emerald-200 dark:border-emerald-800">
                    <flux:table :paginate="$values" class="w-full">
                        <flux:table.columns>
                            <flux:table.column class="!font-bold !text-emerald-700 dark:!text-emerald-400 uppercase text-xs tracking-wider">
                                Topic
                            </flux:table.column>
                            <flux:table.column
                                sortable
                                :sorted="$sortBy === 'created_at'"
                                :direction="$sortDirection"
                                wire:click="sort('created_at')"
                                class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer"
                            >
                                Created
                            </flux:table.column>
                            <flux:table.column
                                sortable
                                :sorted="$sortBy === 'value'"
                                :direction="$sortDirection"
                                wire:click="sort('value')"
                                class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer"
                            >
                                Value
                            </flux:table.column>
                            <flux:table.column
                                sortable
                                :sorted="$sortBy === 'diff_with_last'"
                                :direction="$sortDirection"
                                wire:click="sort('diff_with_last')"
                                class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer"
                            >
                                Difference
                            </flux:table.column>
                            <flux:table.column class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider text-center">
                                Actions
                            </flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach ($values as $value)
                                <flux:table.row
                                    :key="$value->id"
                                    class="group hover:bg-gradient-to-r hover:from-emerald-50 hover:via-teal-50/30 hover:to-transparent dark:hover:from-emerald-950/30 dark:hover:via-teal-950/20 dark:hover:to-transparent transition-all duration-300 border-b border-gray-100 dark:border-gray-700/50 hover:border-emerald-200 dark:hover:border-emerald-800 hover:shadow-sm"
                                >
                                    <flux:table.cell class="!py-3">
                                        <flux:badge
                                            icon="{{$value->topic->unit->icon()}}"
                                            size="sm"
                                            :color="$value->topic->unit->color()"
                                            inset="top bottom"
                                            class="font-semibold shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200"
                                        >
                                            {{ $value->topic->name }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell class="!py-3">
                                        <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                            <flux:icon.calendar variant="micro" class="text-gray-500 dark:text-gray-500" />
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell class="!py-3 !font-bold !text-emerald-700 dark:!text-emerald-400">
                                        {{ $value->value }} <span class="text-xs text-gray-500">{{ $value->topic->unit->value }}</span>
                                    </flux:table.cell>
                                    <flux:table.cell class="!py-3">
                                        <flux:badge
                                            icon="{{ $value->diff_with_last == 0.00 ? 'equal' : ($value->diff_with_last > 0.00 ? 'arrow-up' : 'arrow-down') }}"
                                            :color="$value->color"
                                            size="sm"
                                            inset="top bottom"
                                            class="font-semibold shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200"
                                        >
                                            {{ $value->diff_with_last }} {{ $value->topic->unit->value }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell class="text-center !py-3">
                                        <div class="inline-flex items-center justify-center gap-1.5 opacity-60 group-hover:opacity-100 transition-opacity duration-200">
                                            <flux:button
                                                size="xs"
                                                wire:click="edit({{ $value->id }})"
                                                icon="pencil"
                                                class="!bg-gradient-to-r !from-emerald-500 !to-teal-600 hover:!from-emerald-600 hover:!to-teal-700 !text-white hover:!scale-110 !shadow-md hover:!shadow-lg !shadow-emerald-500/25 hover:!shadow-emerald-500/40 transition-all duration-200 !border-0"
                                            />
                                            <flux:button
                                                size="xs"
                                                wire:click="delete({{ $value->id }})"
                                                variant="danger"
                                                icon="trash"
                                                class="hover:!scale-110 !shadow-md hover:!shadow-lg !shadow-rose-500/25 hover:!shadow-rose-500/40 transition-all duration-200"
                                            />
                                        </div>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </div>
            </div>
        </div>
    </div>
    <livewire:values.create />
    <livewire:values.edit />
</div>
