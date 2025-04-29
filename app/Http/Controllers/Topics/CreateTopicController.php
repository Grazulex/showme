<?php

declare(strict_types=1);

namespace App\Http\Controllers\Topics;

use App\Actions\Topics\CreateTopicAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTopicRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class CreateTopicController extends Controller
{
    public function __invoke(StoreTopicRequest $request, CreateTopicAction $action): RedirectResponse
    {
        $action->handle(user: Auth::user(), attributes: $request->validated());

        return redirect()->route('topics.index')->with('success', 'Topic created successfully.');
    }
}
