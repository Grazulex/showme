<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

final class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $topics = Auth::user()->topics()->orderBy('name', 'asc')->get();
        $TotalCaloriesForToday = (new Meal)->getTotalForToday();
        $TotalCaloriesForMe = Auth::user()->calories_each_day;

        return view('dashboard', [
            'topics' => $topics,
            'TotalCaloriesForToday' => $TotalCaloriesForToday,
            'TotalCaloriesForMe' => $TotalCaloriesForMe,
        ]);
    }
}
