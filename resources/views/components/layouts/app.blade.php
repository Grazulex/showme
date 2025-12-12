<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main class="page-transition">
        <div class="px-6 py-8 max-w-[1800px] mx-auto">
            {{ $slot }}
        </div>
    </flux:main>
</x-layouts.app.sidebar>
