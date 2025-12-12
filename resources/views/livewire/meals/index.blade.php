<div>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Modern Header --}}
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                    Meals
                </flux:heading>
                <flux:subheading class="mt-2 text-gray-600 dark:text-gray-400">
                    Track your daily food intake and calories
                </flux:subheading>
            </div>
            <flux:modal.trigger name="create-meal">
                <flux:button
                    icon="plus"
                    variant="primary"
                    class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 shadow-lg shadow-amber-500/25 transform hover:scale-105 transition-all duration-200"
                >
                    New Meal
                </flux:button>
            </flux:modal.trigger>
        </div>

        <flux:separator class="opacity-50" />

        {{-- Filters --}}
        <div class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[250px]">
                <flux:date-picker size="sm" label="Filter by date" mode="range" wire:model.live="filterDateRange" clearable="true" class="focus:ring-amber-500 focus:border-amber-500"/>
            </div>
        </div>

        <flux:separator class="opacity-50" />

        {{-- Mobile Cards View --}}
        <div class="sm:hidden">
            {{-- Mobile Header --}}
            <div class="mb-4 px-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">
                        Your Meals
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $meals->total() }} {{ Str::plural('meal', $meals->total()) }}
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                @foreach ($meals as $meal)
                    <div class="relative overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-lg border border-amber-200/50 dark:border-amber-900/30 p-5 card-hover">
                        {{-- Gradient Accent --}}
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-600"></div>

                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 space-y-2">
                                <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($meal->created_at)->format('d M Y H:i') }}
                                </div>
                                <flux:badge icon="utensils" size="sm" color="amber" class="font-bold shadow-sm">
                                    {{ $meal->calories }} cal
                                </flux:badge>
                            </div>
                            <div class="flex items-center gap-1.5 ml-3 shrink-0">
                                <flux:button
                                    size="xs"
                                    wire:click="edit({{ $meal->id }})"
                                    icon="pencil"
                                    class="!bg-gradient-to-r !from-amber-500 !to-orange-600 hover:!from-amber-600 hover:!to-orange-700 !text-white !shadow-md hover:!shadow-lg !shadow-amber-500/25 hover:!shadow-amber-500/40 transition-all duration-200 !border-0"
                                />
                                <flux:button
                                    size="xs"
                                    wire:click="copy({{ $meal->id }})"
                                    variant="filled"
                                    icon="copy"
                                    class="!shadow-md hover:!shadow-lg transition-all duration-200"
                                />
                                <flux:button
                                    size="xs"
                                    wire:click="delete({{ $meal->id }})"
                                    variant="danger"
                                    icon="trash"
                                    class="!shadow-md hover:!shadow-lg !shadow-rose-500/25 hover:!shadow-rose-500/40 transition-all duration-200"
                                />
                            </div>
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ $meal->ingredients }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex items-center justify-between px-1">
                <span class="text-xs text-gray-600 dark:text-gray-400">
                    Showing {{ $meals->firstItem() ?? 0 }} to {{ $meals->lastItem() ?? 0 }} of {{ $meals->total() }} results
                </span>
                <div class="flex-1 flex justify-end">
                    {{ $meals->links() }}
                </div>
            </div>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden sm:block">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-amber-100/50 dark:border-amber-900/30 overflow-hidden backdrop-blur-sm">
                {{-- Table Header with Gradient --}}
                <div class="bg-gradient-to-r from-amber-50 via-orange-50 to-amber-50 dark:from-amber-950/30 dark:via-orange-950/30 dark:to-amber-950/30 border-b-2 border-amber-200 dark:border-amber-800">
                    <flux:table :paginate="$meals" class="w-full">
                        <flux:table.columns>
                            <flux:table.column
                                sortable
                                :sorted="$sortBy === 'created_at'"
                                :direction="$sortDirection"
                                wire:click="sort('created_at')"
                                class="!font-bold !text-amber-700 dark:!text-amber-400 uppercase text-xs tracking-wider hover:text-amber-600 dark:hover:text-amber-300 transition-colors cursor-pointer"
                            >
                                Created
                            </flux:table.column>
                            <flux:table.column class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider">
                                Ingredients
                            </flux:table.column>
                            <flux:table.column
                                sortable
                                :sorted="$sortBy === 'calories'"
                                :direction="$sortDirection"
                                wire:click="sort('calories')"
                                class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider hover:text-amber-600 dark:hover:text-amber-400 transition-colors cursor-pointer"
                            >
                                Calories
                            </flux:table.column>
                            <flux:table.column class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider text-center">
                                Actions
                            </flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach ($meals as $meal)
                                <flux:table.row
                                    :key="$meal->id"
                                    class="group hover:bg-gradient-to-r hover:from-amber-50 hover:via-orange-50/30 hover:to-transparent dark:hover:from-amber-950/30 dark:hover:via-orange-950/20 dark:hover:to-transparent transition-all duration-300 border-b border-gray-100 dark:border-gray-700/50 hover:border-amber-200 dark:hover:border-amber-800 hover:shadow-sm"
                                >
                                    <flux:table.cell class="!py-3">
                                        <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                            <flux:icon.calendar variant="micro" class="text-gray-500 dark:text-gray-500" />
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($meal->created_at)->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell class="!py-3 !text-gray-600 dark:!text-gray-400">
                                        {{ $meal->ingredients }}
                                    </flux:table.cell>
                                    <flux:table.cell class="!py-3">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-gradient-to-r from-amber-100 to-amber-50 dark:from-amber-900/40 dark:to-amber-900/20 text-amber-700 dark:text-amber-400 rounded-lg font-bold text-sm shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200 border border-amber-200/50 dark:border-amber-800/30">
                                            <flux:icon.utensils variant="micro" class="text-amber-600 dark:text-amber-400 shrink-0" />
                                            <span>{{ $meal->calories }}cal</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell class="text-center !py-3">
                                        <div class="inline-flex items-center justify-center gap-1.5 opacity-60 group-hover:opacity-100 transition-opacity duration-200">
                                            <flux:button
                                                size="xs"
                                                wire:click="edit({{ $meal->id }})"
                                                icon="pencil"
                                                class="!bg-gradient-to-r !from-amber-500 !to-orange-600 hover:!from-amber-600 hover:!to-orange-700 !text-white hover:!scale-110 !shadow-md hover:!shadow-lg !shadow-amber-500/25 hover:!shadow-amber-500/40 transition-all duration-200 !border-0"
                                            />
                                            <flux:button
                                                size="xs"
                                                wire:click="copy({{ $meal->id }})"
                                                variant="filled"
                                                icon="copy"
                                                class="hover:!scale-110 !shadow-md hover:!shadow-lg transition-all duration-200"
                                            />
                                            <flux:button
                                                size="xs"
                                                wire:click="delete({{ $meal->id }})"
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
    <livewire:meals.create/>
    <livewire:meals.edit/>
</div>
