<?php

namespace Modules\Hr\tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Modules\Hr\App\Http\Controllers\PermissionController;
use Modules\Hr\App\Models\branch;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testIndex()
    {
        $response = $this->get(route('hr.permission.index'));

        $response->assertStatus(Response::HTTP_FOUND);

    }

    /**
     * Test create method of PermissionController.
     *
     * @return void
     */
    public function testCreate()
    {
        $response = $this->get(route('hr.role.create'));

        $response->assertStatus(Response::HTTP_FOUND);
    }
  

    /**
     * Test store method of PermissionController.
     *
     * @return void
     */
    public function testStore()
    {
        $this->withoutExceptionHandling(); // Disable exception handling to see detailed errors
        $user = User::factory()->create();

        $this->actingAs($user);
        $roleData = [
            'name' => 'Test Role',
            'ids' => [1, 2, 3] // Replace with valid permission IDs
        ];

        $response = $this->post(route('hr.role.store'), $roleData);

        $response->assertRedirect(route('hr.permission.index'));
        $this->assertDatabaseHas('roles', ['name' => $roleData['name']]);
    }

    public function testUpdate()
    {
        // Create a role
        $role = Role::factory()->create();

        // Send a PUT request to update the role
        $response = $this->put(route('hr.role.update', $role->id), [
            'name' => 'Updated Role Name',
            // Add other fields as needed
        ]);

        // Assert that the response status is 302 (redirect)
        $response->assertStatus(Response::HTTP_FOUND);

        // Assert that the role was updated in the database
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Role Name',
            // Add other fields as needed
        ]);
    }

    /**
     * Test deleting a role.
     *
     * @return void
     */
    /* public function testDestroy()
    {
        // Create a role
        $role = Role::factory()->create();

        // Send a DELETE request to delete the role
        $response = $this->delete(route('hr.role.destroy', $role->id));

        // Assert that the response status is 302 (redirect)
        $response->assertStatus(Response::HTTP_FOUND);

        // Assert that the role was deleted from the database
        $this->assertDeleted('roles', [
            'id' => $role->id,
        ]);
    } */
}
