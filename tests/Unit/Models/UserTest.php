<?php

declare(strict_types=1);

use App\Models\Topic;
use App\Models\User;

test('to array', function (): void {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))
        ->toEqual([
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
            'birth_at',
            'height',
            'calories_each_day',
            'activity',
            'gender',
        ]);
});

it('may have topics', function (): void {
    $user = User::factory()->create();
    $user->topics()->saveMany([
        Topic::factory()->make(),
        Topic::factory()->make(),
    ]);

    expect($user->topics)
        ->toHaveCount(2);
});

test('update TDEE without configuration for male', function (): void {
    $user = User::factory()->create([
        'height' => 180,
        'gender' => 'male',
        'birth_at' => now()->subYears(30),
        'activity' => App\Enums\ActivityEnum::SEDENTARY,
    ]);

    $user->updateTDEE();
    expect($user->calories_each_day)
        ->toEqual(2136);
});

test('update TDEE without configuration for female', function (): void {
    $user = User::factory()->create([
        'height' => 180,
        'gender' => 'female',
        'birth_at' => now()->subYears(30),
        'activity' => App\Enums\ActivityEnum::SEDENTARY,
    ]);

    $user->updateTDEE();
    expect($user->calories_each_day)
        ->toEqual(1936);
});

test('update TDEE with configuration', function (): void {
    $user = User::factory()->create([
        'height' => 180,
        'gender' => 'male',
        'birth_at' => now()->subYears(30),
        'activity' => App\Enums\ActivityEnum::SEDENTARY,
    ]);

    $topic = Topic::factory()->create([
        'user_id' => $user->id,
        'unit' => App\Enums\UnitEnum::kilogram,
    ]);

    $configuration = App\Models\Configuration::factory()->create([
        'user_id' => $user->id,
        'topic_weight' => $topic->id,
        'topic_calorie_in' => $topic->id,
        'topic_calorie_out' => $topic->id,
    ]);

    $value = App\Models\Value::factory()->create(
        [
            'user_id' => $user->id,
            'topic_id' => $topic->id,
            'value' => 150,
        ]
    );

    $user->updateTDEE();

    expect($user->calories_each_day)
        ->toEqual(2976);

});
