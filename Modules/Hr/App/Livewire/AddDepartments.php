<?php

namespace Modules\Hr\App\Livewire;

use Livewire\Component;
use Modules\Hr\App\Models\Department;

class AddDepartments extends Component
{
    public $name;
    public $headOfDepartment;
    public $short_name;
    public $status;
    public $description;

    public function saveData()
    {
        $this->validate([
            'name' => 'required|min:3',
            'short_name' => 'required|min:5',
            'status' => 'required|enum:active,unactive',
            'description'=>'nullable|min:5',
            'headOfDepartment' => 'nullable|exists:users,id',

        ]);
        
        Department::create([
            'name' => $this->name,
            'short_name' => $this->short_name,
            'head_of_department' => $this->headOfDepartment,
            'status' => $this->status,
            'description' => $this->description
        ]);
 
        return redirect()->to('/departments');
    }

    public function render()
    {
        return view('hr::livewire.add_departments');
    }

   
}