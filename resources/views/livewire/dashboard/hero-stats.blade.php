<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mt-6">
    {{-- Card 1: Calories Today --}}
    <div class="relative overflow-hidden rounded-xl p-6 bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg hover:shadow-xl transition-shadow card-hover">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-amber-100 text-sm font-medium">Calories Today</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($caloriesToday) }}</p>
                <p class="text-amber-100 text-xs mt-1">of {{ number_format($caloriesTarget) }} kcal</p>
            </div>
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            @if ($caloriesToday <= $caloriesTarget)
                <flux:badge variant="solid" size="sm" class="bg-emerald-500/90">
                    On track
                </flux:badge>
            @else
                <flux:badge variant="solid" size="sm" class="bg-rose-500/90">
                    Over target
                </flux:badge>
            @endif
        </div>
        <div class="absolute -right-8 -bottom-8 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
            </svg>
        </div>
    </div>

    {{-- Card 2: Current Weight --}}
    <div class="relative overflow-hidden rounded-xl p-6 bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg hover:shadow-xl transition-shadow card-hover">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-emerald-100 text-sm font-medium">Current Weight</p>
                @if($currentWeight)
                    <p class="text-3xl font-bold mt-2">{{ number_format($currentWeight, 1) }}</p>
                    <p class="text-emerald-100 text-xs mt-1">kg</p>
                @else
                    <p class="text-2xl font-bold mt-2">No data</p>
                @endif
            </div>
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            @if($weightChange !== null)
                <div class="flex items-center space-x-2">
                    @if($weightChange < 0)
                        <svg class="w-4 h-4 text-emerald-100" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 3L5 8h10L10 3z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ number_format(abs($weightChange), 1) }} kg this week</span>
                    @elseif($weightChange > 0)
                        <svg class="w-4 h-4 text-emerald-100 rotate-180" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 3L5 8h10L10 3z"/>
                        </svg>
                        <span class="text-sm font-medium">+{{ number_format($weightChange, 1) }} kg this week</span>
                    @else
                        <span class="text-sm font-medium">Stable</span>
                    @endif
                </div>
            @endif
        </div>
        <div class="absolute -right-8 -bottom-8 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
            </svg>
        </div>
    </div>

    {{-- Card 3: Weekly Progress --}}
    <div class="relative overflow-hidden rounded-xl p-6 bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-lg hover:shadow-xl transition-shadow card-hover">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sky-100 text-sm font-medium">Weekly Progress</p>
                @if($weeklyProgress !== null)
                    <p class="text-3xl font-bold mt-2">{{ number_format(abs($weeklyProgress), 1) }}%</p>
                    <p class="text-sky-100 text-xs mt-1">{{ $weeklyProgress < 0 ? 'decrease' : 'increase' }}</p>
                @else
                    <p class="text-2xl font-bold mt-2">No data</p>
                @endif
            </div>
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            @if($weeklyProgress !== null && $weeklyProgress < 0)
                <flux:badge variant="solid" size="sm" class="bg-emerald-500/90">
                    Great progress!
                </flux:badge>
            @elseif($weeklyProgress !== null && $weeklyProgress > 0)
                <flux:badge variant="solid" size="sm" class="bg-amber-500/90">
                    Gaining weight
                </flux:badge>
            @else
                <flux:badge variant="solid" size="sm" class="bg-gray-500/90">
                    No change
                </flux:badge>
            @endif
        </div>
        <div class="absolute -right-8 -bottom-8 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </div>
    </div>

    {{-- Card 4: Quick Action --}}
    <div class="relative overflow-hidden rounded-xl p-6 bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg hover:shadow-xl transition-shadow card-hover">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Quick Action</p>
                <p class="text-lg font-bold mt-2">Log your meal</p>
                <p class="text-purple-100 text-xs mt-1">Upload a photo</p>
            </div>
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <livewire:dashboard.upload/>
        </div>
        <div class="absolute -right-8 -bottom-8 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
            </svg>
        </div>
    </div>
</div>
