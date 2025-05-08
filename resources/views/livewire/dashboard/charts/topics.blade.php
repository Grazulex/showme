<div>
    <div class="m-5">
        @if($this->topic)
            <flux:heading>{{ $this->topic->name }}</flux:heading>
            <flux:text class="mt-2">{{ $this->topic->description }}</flux:text>
            <flux:chart wire:model="data" class="aspect-3/1">
                <flux:chart.viewport class="aspect-3/1">
                    <flux:chart.svg>
                        @if ($slope > 0.05)
                            <flux:chart.line class="text-green-500 dark:text-green-400" />
                            <flux:chart.point field="value" class="text-green-400" />
                        @elseif ($slope < -0.05)
                            <flux:chart.line class="text-red-500 dark:text-red-400" />
                            <flux:chart.point field="value" class="text-red-400" />
                        @else
                            <flux:chart.line class="text-blue-500 dark:text-blue-400" />
                            <flux:chart.point field="value" class="text-blue-400" />
                        @endif

                        <flux:chart.axis axis="x" field="date" scale="time">
                            <flux:chart.axis.grid />
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
                        <flux:chart.tooltip.value field="value" label="{{ $this->topic->unit->value }}" />
                    </flux:chart.tooltip>
                </flux:chart.viewport>
                <div class="flex justify-center gap-4 pt-4">
                    <flux:chart.legend label="{{ $this->topic->unit->label() }}">
                        @if($slope > 0.05)
                            <flux:chart.legend.indicator class="bg-green-500" />
                        @elseif($slope < -0.05)
                            <flux:chart.legend.indicator class="bg-red-500" />
                        @else
                            <flux:chart.legend.indicator class="bg-blue-500" />
                        @endif
                    </flux:chart.legend>
                </div>
            </flux:chart>
        @endif
    </div>
</div>
