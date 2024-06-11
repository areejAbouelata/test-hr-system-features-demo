<?php

namespace Modules\Hr\App\Livewire;

use Modules\Hr\App\Models\Employee;
use Livewire\Component;
use Modules\Hr\App\Models\Department;

class Departments extends Component
{
    public $search;
    protected $debug = true;
    public $description;
    public $name;
    public $short_name;
    public $headOfDepartment;
    public $status;
   

    public function render()
    {
        return view('hr::livewire.departments', [
            'departments' => Department::search($this->search)->paginate(20),
            'employees' => Employee::search($this->search)->get()
        ]);
    }

    public function submitForm()
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
}
