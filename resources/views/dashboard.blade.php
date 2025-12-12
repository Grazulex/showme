<x-layouts.app :title="__('Dashboard')">
    {{-- Header with gradient title --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl" class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                {{ __('Dashboard') }}
            </flux:heading>
            <flux:text class="mt-2 text-gray-600 dark:text-gray-400">Track your progress and stay on top of your goals.</flux:text>
        </div>
    </div>

    {{-- Hero Stats Section --}}
    <livewire:dashboard.hero-stats/>

    {{-- Charts Section with Tabs --}}
    <div class="mt-12" x-data="{ activeTab: '{{ $topics->first()->slug ?? 'body-fat' }}' }">
        <flux:heading size="lg" class="mb-6 text-gray-800 dark:text-gray-100">Your Metrics</flux:heading>

        {{-- Tab Buttons --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach ($topics as $topic)
                <button
                    @click="activeTab = '{{ $topic->slug }}'"
                    :class="activeTab === '{{ $topic->slug }}' ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/25' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="px-4 py-2.5 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 border border-gray-200 dark:border-gray-700"
                >
                    {{ $topic->name }}
                </button>
            @endforeach
        </div>

        {{-- Tab Panels --}}
        @foreach ($topics as $topic)
            <div x-show="activeTab === '{{ $topic->slug }}'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <livewire:dashboard.charts.topics :topic_id="$topic->id" :key="'topic-chart-'.$topic->id"/>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Goals Section --}}
    <div class="mt-12">
        <flux:heading size="lg" class="mb-6 text-gray-800 dark:text-gray-100">Your Goals</flux:heading>
        <livewire:dashboard.goals-grid/>
    </div>
</x-layouts.app>
