<div>
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="text-xs">Topic</flux:table.column>
            <flux:table.column class="text-xs">Target</flux:table.column>
            <flux:table.column class="text-xs">Higher value</flux:table.column>
            <flux:table.column class="text-xs">Lower value</flux:table.column>
            <flux:table.column class="text-xs">AVG</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($resumes as $resume)
                <flux:table.row :key="$resume['id']">
                    <flux:table.cell variant="strong" class="text-xs">{{ $resume['name'] }}</flux:table.cell>
                    <flux:table.cell class="text-xs">{{ $resume['goal_type'] }} {{ number_format($resume['goal_target'],1) }} {{ $resume['unit'] }}</flux:table.cell>
                    <flux:table.cell class="text-xs">{{ number_format($resume['higher_value_in_goal_range'],1) }} {{ $resume['unit'] }}</flux:table.cell>
                    <flux:table.cell class="text-xs">{{ number_format($resume['lower_value_in_goal_range'],1) }} {{ $resume['unit'] }}</flux:table.cell>
                    <flux:table.cell class="text-xs">{{ number_format($resume['avg_value_in_goal_range'],1) }} {{ $resume['unit'] }}</flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
