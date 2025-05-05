<div>
    <div class="flex items-center gap-0.5">
        @if ($label)
            <div>
                @if ($slope > 0.05)
                    <flux:badge color="green" size="xs">{{$label}}%</flux:badge>
                @elseif ($slope < -0.05)
                    <flux:badge color="red" size="xs">{{$label}}%</flux:badge>
                @else
                    <flux:badge color="blue" size="xs">{{$label}}%</flux:badge>
                @endif
            </div>
        @endif
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
    </div>
</div>
