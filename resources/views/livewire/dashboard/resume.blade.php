<div>
    <div class="hidden sm:block">
        <flux:table>
            <flux:table.columns>
                <flux:table.column class="text-xs">Topic</flux:table.column>
                <flux:table.column class="text-xs">Target</flux:table.column>
                <flux:table.column class="text-xs">High / Low</flux:table.column>
                <flux:table.column class="text-xs">Last</flux:table.column>
                <flux:table.column class="text-xs">Avg</flux:table.column>
                <flux:table.column class="text-xs">Records</flux:table.column>
                <flux:table.column class="text-xs">Progress</flux:table.column>
                <flux:table.column class="text-xs">Status</flux:table.column>
                <flux:table.column class="text-xs">Remaining</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($resumes as $resume)
                    @php
                        $barColor = match ($resume['status']) {
                            'on_track' => 'bg-green-500',
                            'warning' => 'bg-yellow-500',
                            default => 'bg-red-500',
                        };

                        $recordColor = match (true) {
                            $resume['record_frequency'] >= 0.75 => 'green',
                            $resume['record_frequency'] >= 0.25 => 'amber',
                            default => 'red',
                        };

                        $statusBadge = match ($resume['status']) {
                            'on_track' => ['color' => 'emerald', 'label' => 'On track'],
                            'warning' => ['color' => 'yellow', 'label' => 'At risk'],
                            default => ['color' => 'red', 'label' => 'Off track'],
                        };
                    @endphp
                    <flux:table.row :key="$resume['id']">
                        <flux:table.cell variant="strong" class="text-xs">{{ $resume['name'] }}</flux:table.cell>
                        <flux:table.cell class="text-xs">{{ $resume['goal_type'] }} {{ number_format($resume['goal_target'], 1) }} {{ $resume['unit'] }}</flux:table.cell>
                        <flux:table.cell class="text-xs">
                            {{ number_format($resume['higher_value_in_goal_range'], 1) }} / {{ number_format($resume['lower_value_in_goal_range'], 1) }} {{ $resume['unit'] }}
                        </flux:table.cell>
                        <flux:table.cell class="text-xs">{{ number_format($resume['latest_value_in_goal_range'], 1) }} {{ $resume['unit'] }}</flux:table.cell>
                        <flux:table.cell class="text-xs">{{ number_format($resume['avg_value_in_goal_range'], 1) }} {{ $resume['unit'] }}</flux:table.cell>
                        <flux:table.cell class="text-xs">
                            <flux:badge variant="solid" color="{{ $recordColor }}" size="sm">
                                {{ $resume['values_count'] }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-xs w-32">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="{{ $barColor }} h-2.5 rounded-full transition-all"
                                     style="width: {{ $resume['progress_percent'] }}%;"
                                     title="{{ $resume['progress_percent'] }}%"></div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-xs">
                            <flux:badge variant="solid" color="{{ $statusBadge['color'] }}" size="sm">
                                {{ $statusBadge['label'] }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-xs">
                            @if ($resume['days_left'] > 0)
                                {{ $resume['days_left'] }}d left
                            @else
                                <span class="text-gray-400">Ended</span>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>

    <div class="sm:hidden space-y-2">
        @foreach ($resumes as $resume)
            @php
                $barColor = match ($resume['status']) {
                    'on_track' => 'bg-green-500',
                    'warning' => 'bg-yellow-500',
                    default => 'bg-red-500',
                };

                $recordColor = match (true) {
                    $resume['record_frequency'] >= 0.75 => 'green',
                    $resume['record_frequency'] >= 0.25 => 'amber',
                    default => 'red',
                };

                $statusBadge = match ($resume['status']) {
                    'on_track' => ['color' => 'emerald', 'label' => 'On track'],
                    'warning' => ['color' => 'yellow', 'label' => 'At risk'],
                    default => ['color' => 'red', 'label' => 'Off track'],
                };
            @endphp
            <div class="p-3 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-gray-800 shadow-sm space-y-1">
                <div class="text-base font-semibold text-gray-800 dark:text-white">
                    {{ $resume['name'] }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    🎯 Target: <strong>{{ $resume['goal_type'] }} {{ number_format($resume['goal_target'], 1) }} {{ $resume['unit'] }}</strong><br>
                    🕓 Last: {{ number_format($resume['latest_value_in_goal_range'], 1) }} {{ $resume['unit'] }}<br>
                    📈 Avg: {{ number_format($resume['avg_value_in_goal_range'], 1) }} {{ $resume['unit'] }}<br>
                    📊 High / Low: {{ number_format($resume['higher_value_in_goal_range'], 1) }} / {{ number_format($resume['lower_value_in_goal_range'], 1) }}<br>
                    🔁 Records:
                    <flux:badge variant="solid" color="{{ $recordColor }}" size="sm">
                        {{ $resume['values_count'] }}
                    </flux:badge><br>
                    <flux:badge variant="solid" color="{{ $statusBadge['color'] }}" size="sm">
                        {{ $statusBadge['label'] }}
                    </flux:badge>
                    <br>
                    📅 {{ $resume['days_left'] > 0 ? $resume['days_left'].'d left' : 'Ended' }}
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-1">
                        <div class="{{ $barColor }} h-2.5 rounded-full transition-all"
                             style="width: {{ $resume['progress_percent'] }}%;"
                             title="{{ $resume['progress_percent'] }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

