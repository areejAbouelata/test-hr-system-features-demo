<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
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
			'country_id' => $this->country_id,
			'is_active' => (bool)$this->is_active,
			'cities_count' => (int)$this->cities()->count(),
			'cities' => CityResource::collection($this->whenLoaded('cities')),
				'translations' => $this->translations,
		] + $locales;
	}
}
