<div>
    <flux:modal name="create-value" class="md:w-200">
        <div class="space-y-6">
            {{-- Modern Header --}}
            <div class="border-b border-emerald-100 dark:border-emerald-900/30 pb-4">
                <flux:heading size="lg" class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    Create Value
                </flux:heading>
                <flux:text class="mt-2 text-gray-600 dark:text-gray-400">
                    Record a new measurement for your health metrics
                </flux:text>
            </div>

            {{-- Form Fields --}}
            <div class="space-y-5">
                <flux:select
                    label="Topic"
                    wire:model="topic_id"
                    placeholder="Select a topic"
                    class="focus:ring-emerald-500 focus:border-emerald-500"
                >
                    @foreach ($topics as $topic)
                        <flux:select.option value="{{ $topic->id }}">
                            {{ $topic->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input.group>
                    <flux:input
                        label="Value"
                        wire:model="value"
                        placeholder="The value"
                        type="number"
                        step="0.01"
                        pattern="[0-9]+([\.,][0-9]+)?"
                        class="focus:ring-emerald-500 focus:border-emerald-500"
                    />
                    <flux:input.group.suffix>t</flux:input.group.suffix>
                </flux:input.group>

                <flux:date-picker
                    label="Date"
                    wire:model="created_at"
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
                    Create Value
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
