<div>
    <flux:modal name="create-topic" class="md:w-200">
        <div class="space-y-6">
            {{-- Modern Header --}}
            <div class="border-b border-emerald-100 dark:border-emerald-900/30 pb-4">
                <flux:heading size="lg" class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    Create Topic
                </flux:heading>
                <flux:text class="mt-2 text-gray-600 dark:text-gray-400">
                    Create a new health metric to track your progress
                </flux:text>
            </div>

            {{-- Form Fields --}}
            <div class="space-y-5">
                <flux:input
                    label="Name"
                    wire:model="name"
                    placeholder="Name of the topic"
                    class="focus:ring-emerald-500 focus:border-emerald-500"
                />

                <flux:textarea
                    label="Description"
                    wire:model="description"
                    placeholder="Description of the topic"
                    class="focus:ring-emerald-500 focus:border-emerald-500"
                />

                <flux:radio.group label="Unit" wire:model="unit" class="space-y-1">
                    @foreach ($units as $unit)
                        <flux:radio
                            value="{{ $unit->value }}"
                            label="{{ $unit->label() }}"
                            class="!items-center hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 transition-colors rounded-lg p-2.5"
                        />
                    @endforeach
                </flux:radio.group>

                <flux:field variant="inline" class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                    <flux:label class="text-gray-700 dark:text-gray-300">Is weight metric?</flux:label>

                    <flux:switch wire:model.live="is_weight" />

                    <flux:error name="is_weight" />
                </flux:field>
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
                    Create Topic
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
