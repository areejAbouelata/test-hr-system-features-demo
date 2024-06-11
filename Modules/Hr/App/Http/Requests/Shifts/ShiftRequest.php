<?php

namespace Modules\Hr\App\Http\Requests\Shifts;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
{
    public $started_at;
    public $ended_at;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date',
            'next_shift_id' => 'nullable|exists:shifts,id',
            'branch_id'=> 'nullable|exists:branches,id',
            'created_by'=> 'nullable|exists:users,id',
            'updated_by'=> 'nullable|exists:users,id',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale . '.name'] = 'required|string|between:3,250|';
        }
        if ($this->isMethod('post')) {
            $this->merge(['created_by' => auth()->user()->id]);
            $this->merge(['branch_id' => session('current_branch')]);
        }
        if ($this->isMethod('put')) {
            $this->merge(['updated_by' => auth()->user()->id]);
            $this->merge(['branch_id' => session('current_branch')]);
        }
        $this->parseDates();
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    private function parseDates(){
        $this->merge(['started_at' => Carbon::parse($this->started_at)]);
        $this->merge(['ended_at' => Carbon::parse($this->ended_at)]);

    }
}
