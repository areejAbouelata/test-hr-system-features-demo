<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobLevelResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 */
	public function toArray($request): array
	{
//		$locales = [];
//		foreach (config('translatable.locales') as $locale) {
//			$locales[$locale]['name'] = $this->translate($locale)->name;
//			$locales[$locale]['desc'] = $this->translate($locale)?->desc;
//		}
		return [
			'id' => $this->id,
			'name' => $this->name,
			'desc' => $this->desc,
			'is_active' => (bool)$this->is_active,
			'created_at' => $this->created_at ? $this->created_at : null,
			'translations' => $this->translations,
		] ;
	}
}
