<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
			'country' => $this->country,
			'area' => $this->area,
			'is_active' => (bool)$this->is_active,
			'translations' => $this->translations,
		];
	}
}
