<?php

declare(strict_types=1);

use App\Models\Configuration;
use App\Models\Topic;
use App\Models\User;

it('loads configuration when it exists', function () {
    $user = User::factory()->create();
    $configuration = Configuration::factory()->create([
        'user_id' => $user->id,
        'topic_weight' => Topic::factory()->create()->id,
        'topic_calorie_in' => Topic::factory()->create()->id,
        'topic_calorie_out' => Topic::factory()->create()->id,
    ]);

    Auth::shouldReceive('user')->andReturn($user);

    $component = Livewire::test(App\Livewire\Configurations\Index::class);

    expect($component->get('topicWeight'))->toBe($configuration->topic_weight)
        ->and($component->get('topicCalorieIn'))->toBe($configuration->topic_calorie_in)
        ->and($component->get('topicCalorieOut'))->toBe($configuration->topic_calorie_out);
});

it('renders topics for the authenticated user', function () {
    $user = User::factory()->create();
    $topics = Topic::factory()->count(3)->create(['user_id' => $user->id]);

    Auth::shouldReceive('user')->andReturn($user);

    $component = Livewire::test(App\Livewire\Configurations\Index::class);

    $component->assertViewHas('topics', function ($viewTopics) use ($topics) {
        return $viewTopics->pluck('id')->sort()->values()->all() === $topics->pluck('id')->sort()->values()->all();
    });
});

it('saves configuration with valid data', function () {
    $user = User::factory()->create();
    $topics = Topic::factory()->count(3)->create(['user_id' => $user->id]);

    Auth::shouldReceive('user')->andReturn($user);

    $component = Livewire::test(App\Livewire\Configurations\Index::class)
        ->set('topicWeight', $topics[0]->id)
        ->set('topicCalorieIn', $topics[1]->id)
        ->set('topicCalorieOut', $topics[2]->id)
        ->call('submit');

    $this->assertDatabaseHas('configurations', [
        'user_id' => $user->id,
        'topic_weight' => $topics[0]->id,
        'topic_calorie_in' => $topics[1]->id,
        'topic_calorie_out' => $topics[2]->id,
    ]);
});

it('throws validation error when required fields are missing', function () {
    $user = User::factory()->create();

    Auth::shouldReceive('user')->andReturn($user);

    Livewire::test(App\Livewire\Configurations\Index::class)
        ->set('topicWeight', null)
        ->set('topicCalorieIn', null)
        ->set('topicCalorieOut', null)
        ->call('submit')
        ->assertHasErrors(['topicWeight', 'topicCalorieIn', 'topicCalorieOut']);
});
