<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class CalorieEstimationService
{
    /**
     * @throws ConnectionException
     */
    public function estimateFromImage(string $imageUrl): ?float
    {
        $apiKey = config('services.openai.key');

        $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o',
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

        Log::debug($response->json());
        Log::debug($response->json('choices.0.message.content'));

        return (float) $response->json('choices.0.message.content');
    }
}
