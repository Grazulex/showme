<div>
    <div class="w-full h-48 relative p-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md">
        <svg viewBox="0 0 100 60" preserveAspectRatio="none" class="w-full h-full">
            {{-- Grille + labels Y --}}
            @for ($i = 0; $i <= 5; $i++)
                @php
                    $y = $i * 10;
                    $label = $chartData['max'] - ($i * (($chartData['max'] - $chartData['min']) / 5));
                @endphp
                <line x1="0" x2="100" y1="{{ $y }}" y2="{{ $y }}" stroke="#e5e7eb" stroke-width="0.2" />
                <text x="1" y="{{ $y - 0.8 }}" font-size="1.8" fill="#6b7280">{{ round($label) }}</text>
            @endfor

            {{-- Grille + labels X (dates) --}}
            @foreach ($chartPoints as $pt)
                <line y1="0" y2="50" x1="{{ $pt['x'] }}" x2="{{ $pt['x'] }}" stroke="#e5e7eb" stroke-width="0.2" />
                <text x="{{ $pt['x'] }}" y="58" font-size="1.8" text-anchor="middle" fill="#6b7280" transform="rotate(-30, {{ $pt['x'] }}, 58)">
                    {{ \Carbon\Carbon::parse($pt['label'])->format('d M') }}
                </text>
            @endforeach

            {{-- Ligne de goal --}}
            <line x1="0" x2="100" y1="{{ $chartData['goalY'] }}" y2="{{ $chartData['goalY'] }}" stroke="red" stroke-dasharray="2,2" stroke-width="0.4" />
            <text x="95" y="{{ $chartData['goalY'] - 1.5 }}" font-size="2" text-anchor="end" fill="red">
                Goal: {{ $goal }}
            </text>

            {{-- Courbe lissée --}}
            <path d="{{ $chartPath }}" fill="none" stroke="{{ $chartData['color'] }}" stroke-width="0.7" />

            {{-- Points avec effet hover + tooltip natif --}}
            @foreach ($chartPoints as $pt)
                <g>
                    <circle
                        cx="{{ $pt['x'] }}"
                        cy="{{ $pt['y'] }}"
                        r="1"
                        fill="{{ $chartData['color'] }}"
                        class="transition duration-150 hover:opacity-80"
                    />

                    <title>{{ $pt['label'] }}: {{ $pt['value'] }}</title>
                </g>
            @endforeach
        </svg>

    </div>

</div>
