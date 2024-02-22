<?php

namespace Tests\Feature\Livewire;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class QuestionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function testAdminCanCreateQuestion()
    {
        $this->actingAs(User::factory()->admin()->create());

        Volt::test('questions.form')
            ->set('form.question_text', 'very secret question')
            ->set('form.questionOptions.0.option', 'first answer')
            ->call('save')
            ->assertHasNoErrors(['question_text', 'code_snippet', 'answer_explanation', 'more_info_link', 'topic_id', 'questionOptions', 'questionOptions.*.option'])
            ->assertRedirect(route('questions'));

        $this->assertDatabaseHas('questions', [
            'question_text' => 'very secret question',
        ]);
    }

    public function testQuestionTextIsRequired()
    {
        $this->actingAs(User::factory()->admin()->create());

        Volt::test('questions.form')
            ->set('form.question_text', '')
            ->call('save')
            ->assertHasErrors(['form.question_text' => 'required']);
    }

    public function testAdminCanEditQuestion()
    {
        $this->actingAs(User::factory()->admin()->create());

        $question = Question::factory()
            ->has(QuestionOption::factory())
            ->create();

        Volt::test('questions.form', [$question])
            ->set('form.question_text', 'very secret question')
            ->call('save')
            ->assertHasNoErrors(['question_text', 'code_snippet', 'answer_explanation', 'more_info_link', 'topic_id', 'questionOptions', 'questionOptions.*.option'])
            ->assertRedirect(route('questions'));

        $this->assertDatabaseHas('questions', [
            'question_text' => 'very secret question',
        ]);
    }
}
