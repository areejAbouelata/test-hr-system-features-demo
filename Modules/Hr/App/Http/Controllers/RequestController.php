<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\Request as RequestModel;
use Modules\Hr\App\resources\Requests\RequestResource;

class RequestController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;
        return Inertia::render('Hr::Requests/Index', [
            'requests' => RequestResource::collection(RequestModel::search($request->search)->paginate($page_size)),
            'users' => User::get(),
            'employees'=>Employee::get()
        ]);
    }
   
    public function edit(RequestModel $request)
    {
        return view('hr::requests.edit', compact('request'));
    }
    public function myRequests()
    {
        return view('hr::requests.my_requests');
    }
    public function createMyRequests()
    {
        return view('hr::requests.create_my_requests');
    }
    public function editMyRequests(RequestModel $request)
    {
        return view('hr::requests.edit_my_requests', compact('request'));
    }
}
