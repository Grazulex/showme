<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\CalorieEstimationService;
use Illuminate\Console\Command;

final class EstimateCaloriesCommand extends Command
{
    protected $signature = 'calories:estimate {url}';

    protected $description = 'Estimate calories from a food image using GPT-4 Vision';

    public function handle(): int
    {
        $url = $this->argument('url');

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            $this->error('Invalid URL.');

            return self::FAILURE;
        }

        $this->info('Sending image to OpenAI...');

        $result = app(CalorieEstimationService::class)->estimateFromImage($url);

        if (! $result) {
            $this->error('No response or failed to estimate.');

            return self::FAILURE;
        }

        $this->info("\n💡 Estimated calories:\n".$result);

        return self::SUCCESS;
    }
}
