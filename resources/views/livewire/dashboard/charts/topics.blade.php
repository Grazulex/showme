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
                    Score: <span class="font-bold">{{ $score }}%</span>
                </div>
                <div>
                    Delta:
                    <span class="font-bold">
                        {{ $gap > 0 ? '+' : '' }}{{ number_format($gap, 1) }} {{ $topic->unit->value }}/{{$goal->target}}{{ $topic->unit->value }}
                    </span>
                </div>
                <div>
                    Trend:
                    <span class="inline-flex items-center font-bold
                        {{ $trendState === 'good' ? 'text-green-600' :
                            ($trendState === 'bad' ? 'text-red-600' : 'text-blue-600') }}">
                        {{ ucfirst($trendState) }}
                        @php
                            $arrowRotation = match(true) {
                                $trend === null => 'rotate-90 text-gray-400', // neutre visuel
                                $trend > 0      => '',                        // haut
                                $trend < 0      => 'rotate-180',              // bas
                                default         => 'rotate-90',               // stable
                            };
                        @endphp

                        <svg class="w-4 h-4 ml-1 {{ $arrowRotation }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 15l5-5 5 5H5z" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
   @endif
</div>
