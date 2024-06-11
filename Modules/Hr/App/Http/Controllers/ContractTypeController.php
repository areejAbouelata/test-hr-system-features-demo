<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\ContractTypeRequest;
use Modules\Hr\App\Models\ContractType;
use Modules\Hr\App\resources\ContractTypeResource;

class ContractTypeController extends Controller
{
	public function index(Request $request)
	{
		$countries = ContractType::latest()->paginate();
		return Inertia::render('Hr::ContractType/Index', [
			'contractTypes' => ContractTypeResource::collection($countries),
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(ContractTypeRequest $request): RedirectResponse
	{
		ContractType::create($request->validated());
		return redirect()->back();
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(ContractTypeRequest $request, $id): RedirectResponse
	{
		$country = ContractType::findOrFail($id);
		$country->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$country = ContractType::findOrFail($id);
		$country->delete();
		return redirect()->back();
	}
}
