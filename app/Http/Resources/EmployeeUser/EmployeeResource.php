<?php

namespace App\Http\Resources\EmployeeUser;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Modules\Hr\App\Http\Requests\JobTitleRequest;
use Modules\Hr\App\resources\Branch\BranchResource;
use Modules\Hr\App\resources\CountryResource;
use Modules\Hr\App\resources\DepartmentsResource;
use Modules\Hr\App\resources\JobLevelResource;
use Modules\Hr\App\resources\Setting\SimpleUserResource;
use Modules\Hr\App\resources\Shifts\ShiftResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
			'id' => $this->id ,
			'name' => $this->name ,
			'image' => $this->image ,
			'image_url' => $this->image ? asset(Storage::url($this->image)) : null,
			'note' => $this->note ,
			'email' => $this->email ,
			'is_active' =>(boolean) $this->is_active ,
			'is_ban' =>(boolean) $this->is_ban ,
			'default_local' => $this->default_local ,
			'birth_date' => $this->birth_date ,
			'gender' => $this->gender ,
			'country_id' => $this->country_id ,
			'country' =>   CountryResource::make( ($this->country)) ,
			'civilization_state' => $this->civilization_state ,
			'employing_number' => $this->employing_number ,
			'id_number' => $this->id_number ,
			'nationality' => $this->nationality ,
			'iqama_expiration_date' => $this->iqama_expiration_date ,
			'passport' => $this->passport ,
			'address' => $this->address ,
			'job_title_id' => $this->job_title_id ,
			'department_id' => $this->department_id ,
			'job_level_id' => $this->job_level_id ,
			'job_title' => JobTitleRequest::make($this->jobTitle ),
			'department' => DepartmentsResource::make($this->department),
			'job_level' => JobLevelResource::make($this->jobLevel),
			'manager' => SimpleUserResource::make($this->manager),
			'start_date' => $this->start_date ,
			'manager_id' => $this->manager_id ,
			'branch_id' => $this->branch_id ,
			'salary' => $this->salary ,
			'shift_id' => $this->shift_id ,
			'branch' => BranchResource::make($this->branch),
			'shift' => ShiftResource::make($this->shift),
			'attachments' => $this->attachemnts
		];
    }
}
