<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

final class CalorieEstimationService
{
    public function estimateFromImage(string $imageUrl): ?string
    {
        $apiKey = config('services.openai.key');

        $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-vision-preview',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Estimate how many calories are in this food image. I need only one number as a response. (float with 2 decimal places and a dot as a decimal separator)',
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $imageUrl,
                            ],
                        ],
                    ],
                ],
            ],
            'max_tokens' => 300,
        ]);

        return $response->json('choices.0.message.content');
    }
}
