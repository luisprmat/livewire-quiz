<?php

namespace Tests\Feature\Livewire;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class QuizzesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function testAdminCanCreateQuiz()
    {
        $this->actingAs(User::factory()->admin()->create());

        Volt::test('quizzes.form')
            ->set('title', 'quiz title')
            ->call('save')
            ->assertHasNoErrors(['title', 'slug', 'description', 'published', 'public', 'questions'])
            ->assertRedirect(route('quizzes'));

        $this->assertDatabaseHas('quizzes', [
            'title' => 'quiz title',
        ]);
    }

    public function testTitleIsRequired()
    {
        $this->actingAs(User::factory()->admin()->create());

        Volt::test('quizzes.form')
            ->set('title', '')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);
    }

    public function testAdminCanEditQuiz()
    {
        $this->actingAs(User::factory()->admin()->create());

        $quiz = Quiz::factory()
            ->has(Question::factory())
            ->create();

        Volt::test('quizzes.form', [$quiz])
            ->set('title', 'new quiz')
            ->set('published', true)
            ->call('save')
            ->assertSet('published', true)
            ->assertHasNoErrors(['title', 'slug', 'description', 'published', 'public', 'questions']);

        $this->assertDatabaseHas('quizzes', [
            'title' => 'new quiz',
            'published' => true,
        ]);
    }
}
