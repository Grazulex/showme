<div>
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Modern Header --}}
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    Topics
                </flux:heading>
                <flux:subheading class="mt-2 text-gray-600 dark:text-gray-400">
                    Manage your health metrics and tracking topics
                </flux:subheading>
            </div>
            <flux:modal.trigger name="create-topic">
                <flux:button
                    icon="plus"
                    variant="primary"
                    class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-500/25 transform hover:scale-105 transition-all duration-200"
                >
                    New Topic
                </flux:button>
            </flux:modal.trigger>
        </div>

        <flux:separator class="opacity-50" />

        {{-- Mobile Cards View --}}
        <div class="sm:hidden">
            {{-- Mobile Header --}}
            <div class="mb-4 px-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                        Your Topics
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $topics->total() }} {{ Str::plural('topic', $topics->total()) }}
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($topics as $topic)
                    <div class="relative overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-lg border border-emerald-200/50 dark:border-emerald-900/30 p-5 card-hover">
                        {{-- Gradient Accent --}}
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-600"></div>

                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1">
                                    {{ $topic->name }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $topic->description }}
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 ml-3 shrink-0">
                                <flux:button
                                    size="xs"
                                    wire:click="edit({{ $topic->id }})"
                                    icon="pencil"
                                    class="!bg-gradient-to-r !from-emerald-500 !to-teal-600 hover:!from-emerald-600 hover:!to-teal-700 !text-white !shadow-md hover:!shadow-lg !shadow-emerald-500/25 hover:!shadow-emerald-500/40 transition-all duration-200 !border-0"
                                />
                                <flux:button
                                    size="xs"
                                    wire:click="delete({{ $topic->id }})"
                                    variant="danger"
                                    icon="trash"
                                    class="!shadow-md hover:!shadow-lg !shadow-rose-500/25 hover:!shadow-rose-500/40 transition-all duration-200"
                                />
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <flux:badge icon="{{ $topic->unit->icon() }}" size="sm" :color="$topic->unit->color()" class="font-semibold shadow-sm">
                                {{ $topic->unit->label() }}
                            </flux:badge>
                            <flux:badge icon="medal" size="sm" class="bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-700 dark:from-emerald-900/40 dark:to-emerald-900/20 dark:text-emerald-400 font-semibold shadow-sm border border-emerald-200/50 dark:border-emerald-800/30">
                                {{ $topic->goals->count() }} goals
                            </flux:badge>
                            <flux:badge icon="chart-bar" size="sm" class="bg-gradient-to-r from-sky-100 to-sky-50 text-sky-700 dark:from-sky-900/40 dark:to-sky-900/20 dark:text-sky-400 font-semibold shadow-sm border border-sky-200/50 dark:border-sky-800/30">
                                {{ $topic->values->count() }} values
                            </flux:badge>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex items-center justify-between px-1">
                <span class="text-xs text-gray-600 dark:text-gray-400">
                    Showing {{ $topics->firstItem() ?? 0 }} to {{ $topics->lastItem() ?? 0 }} of {{ $topics->total() }} results
                </span>
                <div class="flex-1 flex justify-end">
                    {{ $topics->links() }}
                </div>
            </div>
        </div>


        {{-- Desktop Table View --}}
        <div class="hidden sm:block">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-emerald-100/50 dark:border-emerald-900/30 overflow-hidden backdrop-blur-sm">
                {{-- Table Header with Gradient --}}
                <div class="bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 dark:from-emerald-950/30 dark:via-teal-950/30 dark:to-emerald-950/30 border-b-2 border-emerald-200 dark:border-emerald-800">
                    <flux:table :paginate="$topics" class="w-full">
                        <flux:table.columns>
                            <flux:table.column
                                sortable
                                :sorted="$sortBy === 'name'"
                                :direction="$sortDirection"
                                wire:click="sort('name')"
                                class="!font-bold !text-emerald-700 dark:!text-emerald-400 uppercase text-xs tracking-wider hover:text-emerald-600 dark:hover:text-emerald-300 transition-colors cursor-pointer"
                            >
                                Name
                            </flux:table.column>
                            <flux:table.column class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider">
                                Description
                            </flux:table.column>
                            <flux:table.column
                                sortable
                                :sorted="$sortBy === 'unit'"
                                :direction="$sortDirection"
                                wire:click="sort('unit')"
                                class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer"
                            >
                                Unit
                            </flux:table.column>
                            <flux:table.column class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider text-center">
                                Goals
                            </flux:table.column>
                            <flux:table.column class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider text-center">
                                Values
                            </flux:table.column>
                            <flux:table.column class="!font-bold !text-gray-700 dark:!text-gray-300 uppercase text-xs tracking-wider text-center">
                                Actions
                            </flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach ($topics as $topic)
                                <flux:table.row
                                    :key="$topic->id"
                                    class="group hover:bg-gradient-to-r hover:from-emerald-50 hover:via-teal-50/30 hover:to-transparent dark:hover:from-emerald-950/30 dark:hover:via-teal-950/20 dark:hover:to-transparent transition-all duration-300 border-b border-gray-100 dark:border-gray-700/50 hover:border-emerald-200 dark:hover:border-emerald-800 hover:shadow-sm"
                                >
                                    <flux:table.cell variant="strong" class="!text-gray-900 dark:!text-gray-100 !font-semibold group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors !py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1 h-6 bg-gradient-to-b from-emerald-500 to-teal-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            <span>{{ $topic->name }}</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell class="!text-gray-600 dark:!text-gray-400 text-sm !py-3">
                                        {{ $topic->description }}
                                    </flux:table.cell>
                                    <flux:table.cell class="!py-3">
                                        <flux:badge
                                            icon="{{$topic->unit->icon()}}"
                                            size="sm"
                                            :color="$topic->unit->color()"
                                            inset="top bottom"
                                            class="font-semibold shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200"
                                        >
                                            {{ $topic->unit->label() }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell class="text-center !py-3">
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 bg-gradient-to-r from-emerald-100 to-emerald-50 dark:from-emerald-900/40 dark:to-emerald-900/20 text-emerald-700 dark:text-emerald-400 rounded-lg font-bold text-sm shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200 border border-emerald-200/50 dark:border-emerald-800/30">
                                            <flux:icon.medal variant="micro" class="text-emerald-600 dark:text-emerald-400 shrink-0" />
                                            <span>{{ $topic->goals->count() }}</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell class="text-center !py-3">
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 bg-gradient-to-r from-sky-100 to-sky-50 dark:from-sky-900/40 dark:to-sky-900/20 text-sky-700 dark:text-sky-400 rounded-lg font-bold text-sm shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200 border border-sky-200/50 dark:border-sky-800/30">
                                            <flux:icon.chart-bar variant="micro" class="text-sky-600 dark:text-sky-400 shrink-0" />
                                            <span>{{ $topic->values->count() }}</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell class="text-center !py-3">
                                        <div class="inline-flex items-center justify-center gap-1.5 opacity-60 group-hover:opacity-100 transition-opacity duration-200">
                                            <flux:button
                                                size="xs"
                                                wire:click="edit({{ $topic->id }})"
                                                icon="pencil"
                                                class="!bg-gradient-to-r !from-emerald-500 !to-teal-600 hover:!from-emerald-600 hover:!to-teal-700 !text-white hover:!scale-110 !shadow-md hover:!shadow-lg !shadow-emerald-500/25 hover:!shadow-emerald-500/40 transition-all duration-200 !border-0"
                                            />
                                            <flux:button
                                                size="xs"
                                                wire:click="delete({{ $topic->id }})"
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
    <livewire:topics.create />
    <livewire:topics.edit />
</div>
