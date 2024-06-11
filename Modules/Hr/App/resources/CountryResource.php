<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CountryResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 */
	public function toArray($request): array
	{
		$locales = [];
		foreach (config('translatable.locales') as $locale) {
			$locales[$locale]['name'] = $this->translate($locale)?->name;
			$locales[$locale]['slug'] = $this->translate($locale)?->slug;
		}
		return [
			'id' => $this->id,
			'translations' => $this->translations,
			'phone_code' => $this->phone_code,
			'name' => $this->name,
			'phone_limit' => $this->phone_limit,
			'is_active' => (bool)$this->is_active,
			'is_default' => (bool)$this->is_default,
			'cities_count' => (int)$this->cities()->count(),
			'areas_count' => (int)$this->areas()->count(),
			'flag' => $this->flag ? asset(Storage::url($this->flag)) : null,
			'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
			'areas' => AreaResource::collection($this->whenLoaded('areas')),
			'cities' => CityResource::collection($this->whenLoaded('cities')),
		] + $locales;
	}
}
