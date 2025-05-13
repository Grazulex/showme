<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;

final class CalorieEstimationService
{
    public function estimateFromImage(string $imageUrl): ?array
    {
        $apiKey = config('services.openai.key');

        $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => <<<'PROMPT'
You're a food image analyzer. Given the image, respond ONLY with a valid JSON object containing:

{
  "contains_food": boolean,        // true if the image shows food
  "items": string[],               // list of food items in English
  "estimated_calories_kcal": int  // approximate total calories
}

Do not include any commentary or explanation. Only return valid JSON.
PROMPT,
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
            'max_tokens' => 500,
        ]);

        $content = $response->json('choices.0.message.content');

        try {
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            Log::error('Failed to decode calorie estimation JSON', [
                'error' => $e->getMessage(),
                'response' => $content,
            ]);

            return null;
        }
    }
}
