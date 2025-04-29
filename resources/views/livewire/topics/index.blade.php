<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Topics') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Manage your Topics') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
        <flux:separator />
        <flux:table :paginate="$topics" class="w-full">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Desciption</flux:table.column>
                <flux:table.column>Unit</flux:table.column>
                <flux:table.column>Goals</flux:table.column>
                <flux:table.column>Values</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($topics as $topic)
                    <flux:table.row :key="$topic->id">
                        <flux:table.cell variant="strong">{{ $topic->name }}</flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $topic->description }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge icon="{{$topic->unit->icon()}}" size="sm" :color="$topic->unit->color()" inset="top bottom">{{ $topic->unit->label() }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge icon="academic-cap" size="sm" color="success" inset="top bottom">{{ $topic->goals->count() }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button.group>
                                <flux:button size="xs" icon="pencil" />
                                <flux:button size="xs" variant="danger" icon="trash" />
                            </flux:button.group>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
