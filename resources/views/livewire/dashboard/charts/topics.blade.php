<div>
    @if($topic)
        <div class="space-y-2 m-2">
            <flux:heading>{{ $topic->name }} - Goal: {{$goal->target}}{{ $topic->unit->value }} in {{ number_format(now()->diffInDays(\Carbon\Carbon::parse($goal->ended_at), false),0) }} days</flux:heading>

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
                    <flux:chart.point
                        field="value"
                        class="{{ match($trendState) {
                        'good' => 'text-green-500 dark:text-green-400',
                        'bad' => 'text-red-500 dark:text-red-400',
                        default => 'text-blue-500 dark:text-blue-400',
                    } }}"
                        size="4"
                    />

                    <flux:chart.line
                        field="target"
                        class="text-yellow-100"
                    />

                    <flux:chart.axis axis="x" field="date" scale="time">
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
                    <flux:chart.tooltip.value field="value" label="Value ({{ $topic->unit->value }})" />
                    <flux:chart.tooltip.value field="target" label="Target ({{ $topic->unit->value }})" />
                </flux:chart.tooltip>
            </flux:chart>

            <div class="text-sm text-gray-500 flex justify-between">
                <div>
                    Score: <span class="font-bold">{{ $score }}%</span>
                </div>
                <div>
                    Delta:
                    <span class="font-bold">
                        {{ $gap > 0 ? '+' : '' }}{{ number_format($gap, 1) }} {{ $topic->unit->value }}
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
            @if($projection !== null && $willReachTarget !== null)
                <div class="mt-2 text-sm text-gray-500">
                    <p>
                        Final estimation: <span class="font-bold">{{ number_format($projection, 1) }} {{ $topic->unit->value }}</span><br>
                        @if($willReachTarget)
                            🎯 You are on track to achieve your goal on time.
                        @else
                            ⚠️ At this pace, you might not reach your goal before {{ \Carbon\Carbon::parse($goal->ended_at)->format('d/m/Y') }}.
                        @endif
                    </p>
                </div>
            @endif
        </div>
   @endif
</div>
