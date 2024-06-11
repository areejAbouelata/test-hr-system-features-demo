<?php

namespace Modules\Hr\App\resources\Branch;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BranchResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 */
	public function toArray($request): array
	{
		$locales = [];
		foreach (config('translatable.locales') as $locale) {
			$locales[$locale]['name'] = $this->translate($locale)->name;
		}
		return [
				'id' => $this->id,
				'name' => $this->name,
				'is_active' => (bool)$this->is_active,
				'country' => $this->country,
				'country_id' => $this->country_id,
				'city' => $this->city,
				'city_id' => $this->city_id,
				'area' => $this->area,
				'area_id' => $this->area_id,
				'manager' => $this->manager,
				'manager_id' => $this->manager_id,
				'address' => $this->address,
				'employees_count' => $this->employees_count,
				'email' => $this->email,
				'phone' => $this->phone,
				'inbox_number' => $this->inbox_number,
				'fax_number' => $this->fax_number,
				'website' => $this->website,
				'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
				'image' => $this->image ? asset(Storage::url($this->image)) : null,
				'default_language' => $this->branchLanguages()->where('is_default', true)->first(),
				'languages' => $this->branchLanguages()->where(['is_default' => false, 'is_active' => true])->get(),
				'translations' => $this->translations,

			] + $locales;
	}
}
