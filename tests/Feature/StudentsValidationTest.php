<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentsValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_validation_returns_the_custom_required_name_message(): void
    {
        $this->app->detectEnvironment(fn () => 'testing');

        $response = $this->from('/student/create')
            ->post('/student/store', []);

        $response->assertRedirect('/student/create');
        $response->assertSessionHas('errors', function ($errors): bool {
            $this->assertSame(
                'ကျောင်းသားအမည်ကိုထည့်ပါ။',
                $errors->getBag('default')->first('name'),
            );

            return true;
        });
    }

    public function test_student_validation_requires_a_valid_class_selection(): void
    {
        $this->app->detectEnvironment(fn () => 'testing');

        $response = $this->from('/student/create')
            ->post('/student/store', [
                'name' => 'Test Student',
                'email' => 'student@example.com',
                'phone' => '1234567',
                'class_id' => '',
            ]);

        $response->assertRedirect('/student/create');
        $response->assertSessionHasErrors([
            'class_id' => 'အတန်းကိုရွေးချယ်ပါ။',
        ]);
    }
}
