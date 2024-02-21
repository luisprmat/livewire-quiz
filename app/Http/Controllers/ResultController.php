<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestAnswer;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function show(Test $test): View
    {
        $total_questions = $test->quiz->questions->count();

        $results = TestAnswer::where('test_id', $test->id)
            ->with('question.questionOptions')
            ->get();

        return view('front.results.show', compact('test', 'total_questions', 'results'));
    }
}
