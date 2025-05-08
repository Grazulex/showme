<div>
    @if($topic)
        <div class="space-y-4 m-2">
            <div class="text-lg font-semibold">{{ $topic->name }}</div>

            <flux:chart wire:model="chartData" class="aspect-3/1">
                <flux:chart.svg>
                    <flux:chart.line
                        field="value"
                        class="{{ match($trendState) {
                        'good' => 'text-green-500 dark:text-green-400',
                        'bad' => 'text-red-500 dark:text-red-400',
                        default => 'text-blue-500 dark:text-blue-400',
                    } }}"
                    />

                    <flux:chart.line
                        field="target"
                        class="text-gray-400"
                    />

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
                    <flux:chart.tooltip.value field="value" label="Valeur ({{ $topic->unit->value }})" />
                    <flux:chart.tooltip.value field="target" label="Objectif ({{ $topic->unit->value }})" />
                </flux:chart.tooltip>
            </flux:chart>

            <div class="text-sm text-gray-600 flex justify-between">
                <div>
                    Score : <span class="font-bold">{{ $score }}%</span>
                </div>
                <div>
                    Tendance :
                    <span class="inline-flex items-center font-bold
                        {{ $trendState === 'good' ? 'text-green-600' :
                           ($trendState === 'bad' ? 'text-red-600' : 'text-blue-600') }}">
                        {{ ucfirst($trendState) }}
                        @if ($trend !== null)
                            @if ($trend > 0)
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 15l5-5 5 5H5z" />
                                </svg> <!-- Flèche vers le haut -->
                            @elseif ($trend < 0)
                                <svg class="w-4 h-4 ml-1 rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 15l5-5 5 5H5z" />
                                </svg> <!-- Flèche vers le bas -->
                            @else
                                <svg class="w-4 h-4 ml-1 rotate-90" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 15l5-5 5 5H5z" />
                                </svg> <!-- Flèche horizontale -->
                            @endif
                        @endif
                    </span>
                </div>
            </div>
        </div>
    @endif
</div>
