<?php

declare(strict_types=1);

namespace App\Livewire\Charts;

use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class SimpleLineChart extends Component
{
    public int $topicId;

    public array $values = [];

    public float $goal;

    public function mount(int $topicId): void
    {
        $this->topicId = $topicId;

        $this->values = Topic::findOrFail($topicId)
            ->values()
            ->orderBy('created_at') // ou 'created_at'
            ->get()
            ->map(fn ($v): array => [
                'date' => $v->created_at->format('Y-m-d'),
                'value' => $v->value,
            ])
            ->toArray();
        $this->goal = (float) Topic::findOrFail($topicId)->getFirstActiveGoal()->target;
    }

    public function getChartDataProperty(): array
    {
        $numericValues = array_column($this->values, 'value');
        if ($numericValues === []) {
            return [
                'goalY' => 25,
                'min' => 0,
                'max' => 100,
                'color' => 'gray',
            ];
        }
        $min = floor(min($numericValues) / 10) * 10;
        $max = ceil(max($numericValues) / 10) * 10;

        $goalY = 50 - (($this->goal - $min) / ($max - $min) * 50);
        $avg = array_sum($numericValues) / count($numericValues);
        $color = $avg >= $this->goal ? 'green' : 'red';

        return [
            'goalY' => $goalY,
            'min' => $min,
            'max' => $max,
            'color' => $color,
        ];
    }

    public function getChartPointsProperty(): array
    {
        if (count($this->values) < 2) {
            return [];
        }

        $numericValues = array_column($this->values, 'value');
        $min = floor(min($numericValues) / 10) * 10;
        $max = ceil(max($numericValues) / 10) * 10;
        $paddingX = 5; // en pourcent

        return collect($this->values)->map(function (array $entry, $index) use ($min, $max, $paddingX): array {
            $count = count($this->values);
            $x = ($index / ($count - 1)) * (100 - 2 * $paddingX) + $paddingX;
            $y = 50 - (($entry['value'] - $min) / ($max - $min) * 50);

            return [
                'x' => round($x, 2),
                'y' => round($y, 2),
                'value' => $entry['value'],
                'label' => $entry['date'],
            ];
        })->toArray();
    }

    public function getChartPathProperty(): string
    {
        if (count($this->values) < 2) {
            return '';
        }

        $numericValues = array_column($this->values, 'value');
        $min = floor(min($numericValues) / 10) * 10;
        $max = ceil(max($numericValues) / 10) * 10;
        $paddingX = 5;

        $coords = collect($this->values)->map(function (array $entry, $index) use ($min, $max, $paddingX): array {
            $count = count($this->values);
            $x = ($index / ($count - 1)) * (100 - 2 * $paddingX) + $paddingX;
            $y = 50 - (($entry['value'] - $min) / ($max - $min) * 50);

            return ['x' => $x, 'y' => $y];
        })->values();

        if ($coords->count() < 2) {
            return '';
        }

        $d = "M {$coords[0]['x']},{$coords[0]['y']}";
        for ($i = 1; $i < $coords->count(); $i++) {
            $p0 = $coords[$i - 1];
            $p1 = $coords[$i];

            $cpX = ($p0['x'] + $p1['x']) / 2;

            $d .= " C {$cpX},{$p0['y']} {$cpX},{$p1['y']} {$p1['x']},{$p1['y']}";
        }

        return $d;
    }

    public function render(): View
    {
        return view('livewire.charts.simple-line-chart', [
            'chartData' => $this->chartData,
            'chartPoints' => $this->chartPoints,
            'chartPath' => $this->chartPath,
            'goal' => $this->goal,
            'values' => $this->values,
        ]);
    }
}
