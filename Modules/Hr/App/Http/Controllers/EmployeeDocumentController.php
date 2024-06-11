<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\EmployeeDocumentRequest;
use Modules\Hr\App\Models\EmployeeDocument;
use Modules\Hr\App\resources\EmployeeDocumentResource;

class EmployeeDocumentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$employee_documents = EmployeeDocument::latest()->paginate();
		return Inertia::render('Hr::EmployeeDocument/Index', [
			'documents' => EmployeeDocumentResource::collection($employee_documents)
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(EmployeeDocumentRequest $request): RedirectResponse
	{
		EmployeeDocument::create($request->validated());
		return redirect()->back();
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(EmployeeDocumentRequest $request, $id): RedirectResponse
	{
		$EmployeeDocument = EmployeeDocument::findOrFail($id);
		$EmployeeDocument->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$EmployeeDocument = EmployeeDocument::findOrFail($id);
		//        todo CHECK IF HAS DATA BEFORE DELETE
		$EmployeeDocument->delete();
		return redirect()->back();
	}
}
