<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Topics') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Manage your Topics') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
        <flux:separator />
        @foreach ($topics as $topic)
            {{ $topic->name }}
        @endforeach

        {{ $topics->links() }}
    </div>
</div>
