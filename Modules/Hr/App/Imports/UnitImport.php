<?php

namespace Modules\Hr\App\Imports;

use Modules\Hr\App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\Hr\App\Models\Employee;

class UnitImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Validate the row data
        $validatedData = $this->validateRow($row);

        // Create a new Unit model instance
        return new Unit([
            'name' => $validatedData[0],
            'description' => $validatedData[1],
            'unit_manager_id' => $validatedData[2],
            'created_by' => auth()->user()->id,
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string|max:255', // Name
            '1' => 'nullable|string',  // Description
            '2' => 'required|exists:employees,id', // Unit Manager ID
        ];
    }

    public function customValidationMessages()
    {
        // Define custom validation error messages
        return [
            '2.exists' => 'The unit manager does not exist.',
        ];
    }

    private function validateRow(array $row)
    {
        // Validate the row data here, you can perform additional validation if needed
        return $row;
    }
}
