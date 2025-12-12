<div>
    <flux:modal name="create-meal" class="md:w-200">
        <div class="space-y-6">
            {{-- Modern Header --}}
            <div class="border-b border-amber-100 dark:border-amber-900/30 pb-4">
                <flux:heading size="lg" class="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                    Create Meal
                </flux:heading>
                <flux:text class="mt-2 text-gray-600 dark:text-gray-400">
                    Log a new meal and track your calories
                </flux:text>
            </div>

            {{-- Form Fields --}}
            <div class="space-y-5">
                <flux:input
                    label="Ingredients"
                    wire:model="ingredients"
                    placeholder="List your ingredients"
                    type="text"
                    class="focus:ring-amber-500 focus:border-amber-500"
                />

                <flux:input.group>
                    <flux:input
                        label="Calories"
                        wire:model="calories"
                        placeholder="Calorie count"
                        type="number"
                        step="0.01"
                        pattern="[0-9]+([\.,][0-9]+)?"
                        class="focus:ring-amber-500 focus:border-amber-500"
                    />
                    <flux:input.group.suffix>cal</flux:input.group.suffix>
                </flux:input.group>

                <flux:date-picker
                    label="Date"
                    wire:model="created_at"
                    placeholder="Select a date"
                    class="focus:ring-amber-500 focus:border-amber-500"
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
                    class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 shadow-lg shadow-amber-500/25 transform hover:scale-105 transition-all duration-200"
                >
                    Create Meal
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
