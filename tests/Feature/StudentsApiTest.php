<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_endpoint_returns_students_with_their_class(): void
    {
        $student = Students::factory()->create();

        $this->getJson('/api/students')
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('data.0.id', $student->id)
            ->assertJsonPath('data.0.name', $student->name)
            ->assertJsonPath('data.0.school_class.name', $student->schoolClass->name);
    }

    public function test_students_endpoint_paginates_results(): void
    {
        Students::factory()->count(11)->create();

        $this->getJson('/api/students')
            ->assertOk()
            ->assertJsonPath('per_page', 10)
            ->assertJsonPath('total', 11)
            ->assertJsonCount(10, 'data');
    }

    public function test_student_can_be_created_and_includes_its_class(): void
    {
        $schoolClass = SchoolClass::factory()->create();
        $studentData = [
            'name' => 'New Student',
            'email' => 'new-student@example.com',
            'phone' => '123456789',
            'class_id' => $schoolClass->id,
        ];

        $this->postJson('/api/students', $studentData)
            ->assertCreated()
            ->assertJsonPath('name', 'New Student')
            ->assertJsonPath('school_class.id', $schoolClass->id);

        $this->assertDatabaseHas('students', $studentData);
    }

    public function test_student_can_be_shown_updated_and_partially_updated(): void
    {
        $student = Students::factory()->create();

        $this->getJson("/api/students/{$student->id}")
            ->assertOk()
            ->assertJsonPath('id', $student->id)
            ->assertJsonPath('school_class.id', $student->class_id);

        $updatedData = [
            'name' => 'Updated Student',
            'email' => $student->email,
            'phone' => $student->phone,
            'class_id' => $student->class_id,
        ];

        $this->putJson("/api/students/{$student->id}", $updatedData)
            ->assertOk()
            ->assertJsonPath('name', 'Updated Student');

        $this->patchJson("/api/students/{$student->id}", ['name' => 'Patched Student'])
            ->assertOk()
            ->assertJsonPath('name', 'Patched Student');

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Patched Student',
            'email' => $student->email,
        ]);
    }

    public function test_student_can_be_deleted(): void
    {
        $student = Students::factory()->create();

        $this->deleteJson("/api/students/{$student->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
        $this->getJson("/api/students/{$student->id}")->assertNotFound();
    }

    public function test_school_class_endpoint_lists_classes_for_student_forms(): void
    {
        $schoolClass = SchoolClass::factory()->create(['name' => 'Class 10 - A']);

        $this->getJson('/api/school-classes')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $schoolClass->id)
            ->assertJsonPath('0.name', 'Class 10 - A');
    }

    public function test_student_creation_validates_required_fields_and_unique_email(): void
    {
        $student = Students::factory()->create();
        $schoolClass = SchoolClass::factory()->create();

        $this->postJson('/api/students', [
            'name' => '',
            'email' => $student->email,
            'phone' => '123456789',
            'class_id' => $schoolClass->id,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_student_can_be_created_without_a_phone_number(): void
    {
        $schoolClass = SchoolClass::factory()->create();

        $this->postJson('/api/students', [
            'name' => 'Student Without Phone',
            'email' => 'no-phone@example.com',
            'class_id' => $schoolClass->id,
        ])
            ->assertCreated()
            ->assertJsonPath('phone', null);

        $this->assertDatabaseHas('students', [
            'email' => 'no-phone@example.com',
            'phone' => null,
        ]);
    }

    public function test_students_page_renders_the_react_mount_point(): void
    {
        $this->get('/students')
            ->assertOk()
            ->assertSee('id="students-app"', false);
    }
}
