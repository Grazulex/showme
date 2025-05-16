<?php

declare(strict_types=1);

test('get color decrease', function (): void {
    $user = App\Models\User::factory()->create();
    $topic = App\Models\Topic::factory()->create();

    $goal = App\Models\Goal::factory()->create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'type' => App\Enums\GoalTypeEnum::decrease,
    ]);

    $diff = -100;
    $color = new App\Services\Color()->getColor($goal, $diff);
    expect($color)
        ->toBe('green');

    $diff = 100;
    $color = new App\Services\Color()->getColor($goal, $diff);
    expect($color)
        ->toBe('red');
});

test('get color increase', function (): void {
    $user = App\Models\User::factory()->create();
    $topic = App\Models\Topic::factory()->create();

    $goal = App\Models\Goal::factory()->create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'type' => App\Enums\GoalTypeEnum::increase,
    ]);

    $diff = -100;
    $color = new App\Services\Color()->getColor($goal, $diff);
    expect($color)
        ->toBe('red');

    $diff = 100;
    $color = new App\Services\Color()->getColor($goal, $diff);
    expect($color)
        ->toBe('green');
});

test('get color maintain', function (): void {
    $user = App\Models\User::factory()->create();
    $topic = App\Models\Topic::factory()->create();

    $goal = App\Models\Goal::factory()->create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'type' => App\Enums\GoalTypeEnum::maintain,
    ]);

    $diff = -100;
    $color = new App\Services\Color()->getColor($goal, $diff);
    expect($color)
        ->toBe('red');

    $diff = 100;
    $color = new App\Services\Color()->getColor($goal, $diff);
    expect($color)
        ->toBe('red');

    $diff = 0;
    $color = new App\Services\Color()->getColor($goal, $diff);
    expect($color)
        ->toBe('green');
});
