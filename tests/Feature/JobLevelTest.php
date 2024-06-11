<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Hr\App\Models\JobLevel;
use Tests\TestCase;

class JobLevelTest extends TestCase
{
    /**
     * A basic feature test example.
     */
	public function test_example(): void
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);

		$this->assertAuthenticated();
		$response = $this->get('/job-level');

		$response->assertStatus(200);
	}

	public function test_create_job_level()
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);
		$this->assertAuthenticated();
		$this->post('/job-level', [
			'is_active' => true,
			'branch_id' => 1,
			'en' => [
				'name' => 'job title test'
			],
			'ar' => [
				'name' => 'هنا اول تست كاس '
			]
		]);
		$response = $this->get('/job-level');
		$response->assertStatus(200);
	}

	public function test_update_job_title()
	{

		$user = User::whereEmail('admin@n.com')->first();
		$this->actingAs($user);
		$job_title = JobLevel::latest()->first();
//		dd($job_title) ;
		$response = $this->withSession(['_token' => csrf_token()])
			->withHeaders([
				'X-Inertia' => true,
				'X-Requested-With' => 'XMLHttpRequest',
			])
			->put("job-level/{$job_title->id}", [
				'is_active' => false,
				'branch_id' => 1,
				'en' => [
					'name' => 'هنا اول تست كاس '
				],
				'ar' => [
					'name' => 'هنا اول تست كاس '
				]

			]);
		$response->assertStatus(303);
	}

	public function test_job_delete()
	{

		$user = User::whereEmail('admin@n.com')->first();
		$this->actingAs($user);
		$job_level = JobLevel::latest()->first();
		$response = $this
//			->withSession(['_token' => csrf_token()])
			->withHeaders([
				'X-Inertia' => true,
				'X-Requested-With' => 'XMLHttpRequest',
			])
			->delete("job-level/{$job_level->id}");
		$response->assertStatus(303);
	}
}
