<div>
    <flux:chart wire:model="data" class="w-[5rem] aspect-[3/1]">
        <flux:chart.svg gutter="0">
            <flux:chart.line class="text-{{$color}}-500 dark:text-{{$color}}-400" />
        </flux:chart.svg>
    </flux:chart>
{{$color}}#{{$slope}}
</div>
