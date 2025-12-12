<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach ($goals as $goal)
        @php
            $gradientClass = match ($goal['status']) {
                'on_track' => 'from-emerald-500 to-teal-600',
                'warning' => 'from-amber-500 to-orange-600',
                default => 'from-rose-500 to-pink-600',
            };

            $statusColor = match ($goal['status']) {
                'on_track' => 'emerald',
                'warning' => 'amber',
                default => 'rose',
            };

            $statusLabel = match ($goal['status']) {
                'on_track' => 'On Track',
                'warning' => 'At Risk',
                default => 'Off Track',
            };

            $recordColor = match (true) {
                $goal['record_frequency'] >= 0.75 => 'emerald',
                $goal['record_frequency'] >= 0.25 => 'amber',
                default => 'rose',
            };
        @endphp

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br {{ $gradientClass }} text-white shadow-lg hover:shadow-2xl transition-all duration-300 card-hover">
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-black/10"></div>

            {{-- Background Pattern --}}
            <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 100 100">
                    <circle cx="75" cy="25" r="25"/>
                    <circle cx="25" cy="75" r="25"/>
                </svg>
            </div>

            {{-- Content --}}
            <div class="relative z-10 p-6 space-y-4">
                {{-- Header --}}
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $goal['name'] }}</h3>
                        <p class="text-white/80 text-sm mt-1">
                            {{ ucfirst($goal['goal_type']) }} {{ number_format($goal['goal_target'], 1) }} {{ $goal['unit'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium">Progress</span>
                        <span class="font-bold">{{ $goal['progress_percent'] }}%</span>
                    </div>
                    <div class="w-full bg-white/30 rounded-full h-3 overflow-hidden">
                        <div class="bg-white h-3 rounded-full transition-all duration-500"
                             style="width: {{ $goal['progress_percent'] }}%;">
                        </div>
                    </div>
                </div>

                {{-- Stats Grid --}}
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                        <p class="text-white/70 text-xs">Latest</p>
                        <p class="text-lg font-bold">{{ number_format($goal['latest_value'], 1) }}<span class="text-sm font-normal ml-1">{{ $goal['unit'] }}</span></p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                        <p class="text-white/70 text-xs">Average</p>
                        <p class="text-lg font-bold">{{ number_format($goal['avg_value'], 1) }}<span class="text-sm font-normal ml-1">{{ $goal['unit'] }}</span></p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                        <p class="text-white/70 text-xs">High / Low</p>
                        <p class="text-sm font-bold">{{ number_format($goal['higher_value'], 1) }} / {{ number_format($goal['lower_value'], 1) }}</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                        <p class="text-white/70 text-xs">Records</p>
                        <p class="text-lg font-bold">{{ $goal['values_count'] }}<span class="text-xs font-normal ml-1">/ {{ $goal['days_total'] }}d</span></p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between pt-2 border-t border-white/20">
                    <div class="flex items-center gap-2">
                        <flux:badge variant="solid" size="sm" class="bg-white/20">
                            {{ $statusLabel }}
                        </flux:badge>
                        <flux:badge variant="solid" size="sm" class="bg-white/20">
                            {{ number_format($goal['record_frequency'] * 100, 0) }}% tracked
                        </flux:badge>
                    </div>

                    <div class="text-sm">
                        @if ($goal['days_left'] > 0)
                            <span class="font-medium">{{ $goal['days_left'] }}d</span> left
                        @else
                            <span class="text-white/70">Ended</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
