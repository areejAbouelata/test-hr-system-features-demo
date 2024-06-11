<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Hr\App\Models\Branch;
use Tests\TestCase;

class BranchTest extends TestCase
{
	/**
	 * A basic feature test example.
	 */
	public function test_index(): void
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);

		$this->assertAuthenticated();
		$response = $this->get('/branches');
		$response->assertStatus(200);
	}

	public function test_create()
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);

		$this->assertAuthenticated();
		$response = $this->get('/branches/create');
		$response->assertStatus(200);
	}

	public function test_show()
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);
		$branch = Branch::first();
		$this->assertAuthenticated();
		$response = $this->get("/branches/{$branch->id}");
		$response->assertStatus(200);
	}

	public function test_edit()
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);
		$branch = Branch::first();
		$this->assertAuthenticated();
		$response = $this->get("/branches/edit/{$branch->id}");
		$response->assertStatus(200);
	}

	public function test_getLanguages()
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);
		$branch = Branch::first();
		$this->assertAuthenticated();
		$response = $this->get("/branches/languages/{$branch->id}");
		$response->assertStatus(302);
	}
	public function test_store()
	{
		$response = $this->post('/login', [
			'email' => 'admin@n.com',
			'password' => '123456789',
		]);
		$this->assertAuthenticated();
	    $response=	$this->post('/branches', [
			'is_active' => true,
			'country_id' => 1,
			'city_id' => 1,
			'area_id' => 1,
			'manager_id' => 1,
			'address' => 'test add',
			'employees_count' => 1 ,
			'email' => "test@mail.com",
			'phone' => "32323",
			'inbox_number' => "32323",
			'fax_number' => "32323",
			'website' => "https://website.com",
			'language_id' => "1",
			'en' => [
				'name' => 'job title test'
			],
			'ar' => [
				'name' => 'هنا اول تست كاس '
			]
		]);
//		dd($response) ;
//		$response = $this->get('/branches');
		$response->assertStatus(302);
	}

}
