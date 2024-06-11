<?php

namespace Modules\Hr\App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\TaskRequest;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\SpecialTask;
use Modules\Hr\App\Models\Task;
use Modules\Hr\App\resources\TaskResource;

class TaskController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
       
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

        $tasks =  SpecialTask::search($request->search)->latest()->paginate($page_size);


        return Inertia::render('Hr::Tasks/Index', [
            'tasks' =>  TaskResource::collection($tasks),
            'employees'=>Employee::get(),    
          
        ]);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): RedirectResponse
    {
      
    
        SpecialTask::create($request->validated());

        return redirect()->route('tasks.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, $id): RedirectResponse
    {
        $task = SpecialTask::find($id);
        
        $task->update($request->validated());

        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       $task = SpecialTask::find($id);
       $task->forceDelete();
        return redirect()->route('tasks.index');

    }

    public function RestoreOrDelete($id): RedirectResponse
    {
        $task = SpecialTask::find($id);
        if ($task->deleted_at) {

            $task->restore();
        } else {

            $task->delete();
        }
        return redirect()->back();
    }

}
