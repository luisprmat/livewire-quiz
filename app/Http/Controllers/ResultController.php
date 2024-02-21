<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function show(Test $test): View
    {
        $total_questions = $test->quiz->questions->count();

        return view('front.results.show', compact('test', 'total_questions'));
    }
}
