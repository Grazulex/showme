<div>
    @if ($label)
        <div>
            @if ($slope > 0.05)
                <flux:badge color="green" size="xs">{{$label}}%</flux:badge>
            @elseif ($slope < -0.05)
                <flux:badge color="red" size="xs">{{$label}}%</flux:badge>
            @else
                <flux:badge color="blue" size="xs">{{$label}}%</flux:badge>
            @endif
        </div>
    @endif
</div>
