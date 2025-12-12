<div>
    <flux:modal name="create-goal" class="md:w-200">
        <div class="space-y-6">
            {{-- Modern Header --}}
            <div class="border-b border-emerald-100 dark:border-emerald-900/30 pb-4">
                <flux:heading size="lg" class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    Create Goal
                </flux:heading>
                <flux:text class="mt-2 text-gray-600 dark:text-gray-400">
                    Set a new health objective to track your progress
                </flux:text>
            </div>

            {{-- Form Fields --}}
            <div class="space-y-5">
                <flux:select
                    label="Topic"
                    wire:model="topic_id"
                    placeholder="Select a topic"
                    searchable="true"
                    class="focus:ring-emerald-500 focus:border-emerald-500"
                >
                    @foreach ($topics as $topic)
                        <flux:select.option value="{{ $topic->id }}">
                            {{ $topic->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input
                    label="Name"
                    wire:model="name"
                    placeholder="Name of the goal"
                    class="focus:ring-emerald-500 focus:border-emerald-500"
                />

                <flux:radio.group label="Type" wire:model="type" class="space-y-1">
                    @foreach ($types as $type)
                        <flux:radio
                            value="{{ $type->value }}"
                            label="{{ $type->label() }}"
                            class="!items-center hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 transition-colors rounded-lg p-2.5"
                        />
                    @endforeach
                </flux:radio.group>

                <flux:input
                    label="Target"
                    wire:model="target"
                    placeholder="Target value"
                    class="focus:ring-emerald-500 focus:border-emerald-500"
                />

                <flux:date-picker
                    label="Started at"
                    wire:model="started_at"
                    placeholder="Select a date"
                    class="focus:ring-emerald-500 focus:border-emerald-500"
                />

                <flux:date-picker
                    label="Ended at"
                    wire:model="ended_at"
                    placeholder="Select a date"
                    class="focus:ring-emerald-500 focus:border-emerald-500"
                />
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" class="hover:bg-gray-100 dark:hover:bg-gray-800">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    wire:click="submit"
                    variant="primary"
                    class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-500/25 transform hover:scale-105 transition-all duration-200"
                >
                    Create Goal
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
