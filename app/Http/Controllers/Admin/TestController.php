<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $tests = Test::with(['user', 'quiz'])
            ->withCount('questions')
            ->latest()
            ->paginate();

        return view('admin.tests', compact('tests'));
    }
}
