<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($topics as $topic)
                <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 aspect-[4/3] sm:aspect-video">
                    <livewire:dashboard.charts.topics :topic_id="$topic->id"/>
                </div>
            @endforeach
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 aspect-[4/3] sm:aspect-video">
                <livewire:dashboard.resume />
            </div>
        </div>
    </div>
</x-layouts.app>
