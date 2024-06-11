<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Hr\App\Models\JobTitle;
use Tests\TestCase;

class JobTitelTest extends TestCase
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
		$response = $this->get('/job-title');

		$response->assertStatus(200);
	}

	public function test_create_job_title()
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);
		$this->assertAuthenticated();
		$this->post('/job-title', [
			'is_active' => true,
			'branch_id' => 1,
			'en' => [
				'title' => 'run from test case'
			],
			'ar' => [
				'title' => 'هنا اول تست كاس '
			]
		]);
		$response = $this->get('/job-title');
		$response->assertStatus(200);
	}

	public function test_update_job_title()
	{

		$user = User::whereEmail('admin@n.com')->first();
		$this->actingAs($user);
		$job_title = JobTitle::latest()->first();
//		dd($job_title) ;
		$response = $this->withSession(['_token' => csrf_token()])
			->withHeaders([
				'X-Inertia' => true,
				'X-Requested-With' => 'XMLHttpRequest',
			])
			->put("job-title/{$job_title->id}", [
			'is_active' => false,
			'branch_id' => 1,
			'en' => [
				'title' => 'run from test case33333333333'
			],
			'ar' => [
				'title' => 'هنا اول تست كاس 33333333'
			]

		]);
		$response->assertStatus(303);
	}

	public function test_job_delete()
	{

		$user = User::whereEmail('admin@n.com')->first();
		$this->actingAs($user);
		$job_title = JobTitle::latest()->first();
		$response = $this
//			->withSession(['_token' => csrf_token()])
			->withHeaders([
				'X-Inertia' => true,
				'X-Requested-With' => 'XMLHttpRequest',
			])
			->delete("job-title/{$job_title->id}");
		$response->assertStatus(303);
	}

	private function login()
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);
		return $this->assertAuthenticated();
	}
}
