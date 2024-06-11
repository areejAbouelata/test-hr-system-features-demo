<?php

namespace Modules\Hr\App\resources\Permissions;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 */
	public function toArray($request): array
	{
		$userCount = User::whereHas('roles', function ($query) {
			$query->where('id', $this->id);
		})->count();

		return [
			'id' => $this->id,
			'name' => $this->name,
			'guard_name' => $this->guard_name,
			'users_count' => $userCount,
			'permissions_count' => $this->permissions->count(),
			'company_id' => $this->company_id,
			'branch_id' => $this->branch_id,
			'group' => $this->group,
			'module' => $this->module,
			'created_at' => $this->created_at?->format('Y-m-d')
		];
	}
}
