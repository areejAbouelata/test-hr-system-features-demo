<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobTitleResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 */
	public function toArray($request): array
	{
		// $locales = [];
		// foreach (config('translatable.locales') as $locale) {
		// 	$locales[$locale]['title'] = $this->translate($locale)->title;
		// 	$locales[$locale]['description'] = $this->translate($locale)?->description;
		// }
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'branch_id' => $this->branch_id,
			'is_active' => (bool)$this->is_active,
			'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
			'translations' => $this->translations,
		];
	}
}
