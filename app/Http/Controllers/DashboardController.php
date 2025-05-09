<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

final class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $topics = Auth::user()->topics()->orderBy('name', 'asc')->get();

        return view('dashboard', [
            'topics' => $topics,
        ]);
    }
}
