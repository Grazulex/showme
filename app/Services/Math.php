<?php

declare(strict_types=1);

namespace App\Services;

final class Math
{
    public function linearTrend(array $data): float
    {
        $n = count($data);
        if ($n < 2) {
            return 0;
        }

        $x = range(1, $n);
        $y = $data;

        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = array_sum(array_map(fn (float|int $xi, float|int $yi): float|int => $xi * $yi, $x, $y));
        $sumX2 = array_sum(array_map(fn (float|int $xi): int => $xi * $xi, $x));

        return ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
    }

    public function averageRelativeChange(array $data): ?float
    {
        $n = count($data);
        if ($n < 2) {
            return null;
        }

        $totalChange = 0;
        $count = 0;

        for ($i = 0; $i < $n - 1; $i++) {
            $current = $data[$i];
            $next = $data[$i + 1];

            if ($current !== 0) {
                $change = (($next - $current) / $current) * 100;
                $totalChange += $change;
                $count++;
            }
        }

        return $count > 0 ? $totalChange / $count : null;
    }
}
