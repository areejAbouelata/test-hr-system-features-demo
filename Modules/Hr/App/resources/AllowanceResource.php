<?php

namespace Modules\Hr\App\resources;

use App\Models\BranchLanguage;
use Illuminate\Http\Resources\Json\JsonResource;

class AllowanceResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 */
	public function toArray($request): array
	{
		$locales = [];
		foreach (BranchLanguage::whereId($this->branch_id)->get() as $locale) {
			$locales[$locale->short_cut]['name'] = $this->translate($locale->short_cut)?->name;
			$locales[$locale->short_cut]['desc'] = $this->translate($locale->short_cut)?->desc;
		}
		return [
			'branch_id' => $this->branch_id,
			'is_active' => (bool)$this->is_active,
			'name' => $this->name,
			'desc' => $this->desc,
			'translations' => $this->translations,
		] + $locales;
	}
}
