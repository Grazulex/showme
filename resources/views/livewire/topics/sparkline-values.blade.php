<div>
            <flux:chart wire:model="data" class="w-[5rem] aspect-[3/1]">
                <flux:chart.svg gutter="0">
                    @if ($slope > 0.05)
                        <flux:chart.line class="text-green-500 dark:text-green-400" />
                    @elseif ($slope < -0.05)
                        <flux:chart.line class="text-red-500 dark:text-red-400" />
                    @else
                        <flux:chart.line class="text-blue-500 dark:text-blue-400" />
                    @endif
                </flux:chart.svg>
            </flux:chart>
</div>
