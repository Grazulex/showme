<div>
    @if($topic)
        <div class="space-y-6">
            {{-- Header --}}
            <div class="flex items-start justify-between">
                <div>
                    <flux:heading size="lg" class="text-gray-800 dark:text-gray-100">
                        {{ $topic->name }}
                    </flux:heading>
                    @if($goal)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Goal: {{ $goal->target }}{{ $topic->unit->value }} in {{ number_format(now()->diffInDays(\Carbon\Carbon::parse($goal->ended_at), false), 0) }} days
                        </p>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">No active goal</p>
                    @endif
                </div>
                @if($score !== null)
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Score</p>
                        <p class="text-3xl font-bold {{ $trendState === 'good' ? 'text-emerald-600' : ($trendState === 'bad' ? 'text-rose-600' : 'text-blue-600') }}">
                            {{ $score }}%
                        </p>
                    </div>
                @endif
            </div>

            {{-- Summary Stats --}}
            @if($values->count() >= 2)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {{-- Latest Value --}}
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-lg p-4 border border-emerald-200 dark:border-emerald-700">
                        <div class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mb-1">Latest</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($values->last()->value, 1) }}<span class="text-sm text-gray-600 dark:text-gray-400">{{ $topic->unit->value }}</span></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $values->last()->created_at->format('d/m/Y') }}</div>
                    </div>

                    {{-- Best Value --}}
                    @if($bestValue !== null)
                        <div class="bg-gradient-to-br from-sky-50 to-blue-50 dark:from-sky-900/20 dark:to-blue-900/20 rounded-lg p-4 border border-sky-200 dark:border-sky-700">
                            <div class="text-xs font-medium text-sky-600 dark:text-sky-400 mb-1">Best</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($bestValue, 1) }}<span class="text-sm text-gray-600 dark:text-gray-400">{{ $topic->unit->value }}</span></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $bestDate }}</div>
                        </div>
                    @endif

                    {{-- Worst Value --}}
                    @if($worstValue !== null)
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg p-4 border border-amber-200 dark:border-amber-700">
                            <div class="text-xs font-medium text-amber-600 dark:text-amber-400 mb-1">Worst</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($worstValue, 1) }}<span class="text-sm text-gray-600 dark:text-gray-400">{{ $topic->unit->value }}</span></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $worstDate }}</div>
                        </div>
                    @endif

                    {{-- Total Change --}}
                    @if($totalChange !== null)
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
                            <div class="text-xs font-medium text-purple-600 dark:text-purple-400 mb-1">Total Change</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalChange > 0 ? '+' : '' }}{{ number_format($totalChange, 1) }}<span class="text-sm text-gray-600 dark:text-gray-400">{{ $topic->unit->value }}</span></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Since {{ $values->first()->created_at->format('d/m/Y') }}</div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Chart --}}
            <flux:chart wire:model="chartData" class="aspect-video">
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

            {{-- Progress Info --}}
            <div class="text-sm text-gray-600 dark:text-gray-400 flex flex-wrap justify-between gap-4">
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

            {{-- Projection --}}
            @if($projection !== null && $willReachTarget !== null)
                <div class="mt-4 p-4 rounded-lg {{ $willReachTarget ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700' : 'bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700' }}">
                    <div class="flex items-start gap-3">
                        <div class="text-2xl">{{ $willReachTarget ? '🎯' : '⚠️' }}</div>
                        <div class="flex-1">
                            <p class="font-semibold {{ $willReachTarget ? 'text-emerald-700 dark:text-emerald-300' : 'text-amber-700 dark:text-amber-300' }}">
                                Final estimation: {{ number_format($projection, 1) }} {{ $topic->unit->value }}
                            </p>
                            <p class="text-sm {{ $willReachTarget ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }} mt-1">
                                @if($willReachTarget)
                                    You are on track to achieve your goal on time.
                                @else
                                    At this pace, you might not reach your goal before {{ $estimatedTargetDate?->format('d/m/Y') ?? '???' }}.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
