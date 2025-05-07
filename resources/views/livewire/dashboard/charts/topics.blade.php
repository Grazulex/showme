<div>
    <flux:chart wire:model="data" class="aspect-3/1">
        <flux:chart.svg>
            <flux:chart.line field="value" class="text-pink-500 dark:text-pink-400" />

            <flux:chart.axis axis="x" field="date">
                <flux:chart.axis.line />
                <flux:chart.axis.tick />
            </flux:chart.axis>

            <flux:chart.axis axis="y">
                <flux:chart.axis.grid />
                <flux:chart.axis.tick />
            </flux:chart.axis>

            <flux:chart.cursor />
        </flux:chart.svg>

        <flux:chart.tooltip>
            <flux:chart.tooltip.heading field="date" :format="['year' => 'numeric', 'month' => 'numeric', 'day' => 'numeric']" />
            <flux:chart.tooltip.value field="value" label="Value" />
        </flux:chart.tooltip>
    </flux:chart>
</div>
