<x-layouts.app :title="__('Dashboard')">
    <flux:heading size="lg">
        {{ __('Dashboard') }}
    </flux:heading>
    <flux:text class="mt-2 mb-2">Track your progress and stay on top of your goals.</flux:text>
    <livewire:dashboard.upload />
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($topics as $topic)
                <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 aspect-[4/3] sm:aspect-video">
                    <livewire:dashboard.charts.topics :topic_id="$topic->id"/>
                </div>
            @endforeach
        </div>
        <livewire:dashboard.resume />
    </div>
</x-layouts.app>
