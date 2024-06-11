<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\AllowanceRequest;
use Modules\Hr\App\Models\Allowance;
use Modules\Hr\App\resources\AllowanceResource;

class AllowanceController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$allowances = Allowance::latest()->paginate();
		return Inertia::render('Hr::Allowance/Index', [
			'allowances' => AllowanceResource::collection($allowances)
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(AllowanceRequest $request): RedirectResponse
	{
		Allowance::create($request->validated());
		return redirect()->back();
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(AllowanceRequest $request, $id): RedirectResponse
	{
		$area = Allowance::findOrFail($id);
		$area->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$area = Allowance::findOrFail($id);
		$area->delete();
		return redirect()->back();
	}
}
