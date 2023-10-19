<?php

namespace Tests\Feature;

use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VacancyTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->setUpFaker();
        Vacancy::factory(5)->create();
    }

    // Get all vacancies
    public function test_get_all_vacancies(): void
    {
        $response = $this->getJson('/api/v1/vacancies');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'salary',
                'location',
                'company',
                'description',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    // Get a single vacancy
    public function test_get_single_vacancy(): void
    {
        $response = $this->getJson('/api/v1/vacancies/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'title',
            'salary',
            'location',
            'company',
            'description',
            'created_at',
            'updated_at',
        ]);
    }

    // Get a single vacancy that does not exist
    public function test_get_single_vacancy_that_does_not_exist(): void
    {
        $response = $this->getJson('/api/v1/vacancies/10');
        $response->assertStatus(404);
    }

    // Create a new vacancy
    public function test_create_new_vacancy(): void
    {
        $response = $this->postJson('/api/v1/vacancies', [
            'title' => $this->faker->jobTitle(),
            'salary' => $this->faker->numberBetween(),
            'location' => $this->faker->city(),
            'company' => $this->faker->company(),
            'description' => $this->faker->paragraph(rand(1, 20)),
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'title',
            'salary',
            'location',
            'company',
            'description',
            'created_at',
            'updated_at',
        ]);
    }

    // Create a new vacancy with missing data
    public function test_create_new_vacancy_with_missing_data(): void
    {
        $response = $this->postJson('/api/v1/vacancies', []);
        $response->assertStatus(422);
    }

    // Update a vacancy
    public function test_update_vacancy(): void
    {
        $response = $this->putJson('/api/v1/vacancies/1', [
            'title' => 'Test Vacancy',
            'salary' => 100000,
            'location' => 'Test Location',
            'company' => 'Test Company',
            'description' => 'This is a test vacancy',
        ]);
        $response->assertStatus(200);
        $vacancy = Vacancy::find(1);
        $this->assertEquals('Test Vacancy', $vacancy->title);
        $this->assertEquals(100000, $vacancy->salary);
        $this->assertEquals('Test Location', $vacancy->location);
        $this->assertEquals('Test Company', $vacancy->company);
        $this->assertEquals('This is a test vacancy', $vacancy->description);
    }

    // Update a vacancy that does not exist
    public function test_update_vacancy_that_does_not_exist(): void
    {
        $response = $this->putJson('/api/v1/vacancies/10', [
            'title' => 'Test Vacancy'
        ]);
        $response->assertStatus(404);
    }
    // Delete a vacancy
    public function test_delete_vacancy(): void
    {
        $response = $this->deleteJson('/api/v1/vacancies/1');
        $response->assertStatus(200);
        $vacancy = Vacancy::find(1);
        $this->assertNull($vacancy);
    }
    // Delete a vacancy that does not exist
    public function test_delete_vacancy_that_does_not_exist(): void
    {
        $response = $this->deleteJson('/api/v1/vacancies/10');
        $response->assertStatus(404);
    }
}
