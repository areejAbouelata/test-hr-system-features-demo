<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\AssetTypeRequest;
use Modules\Hr\App\Models\AssetType;
use Modules\Hr\App\resources\AssetTypeResource;

class AssetTypeController extends Controller
{
	public function index(Request $request)
	{
		$AssetType = AssetType::latest()->paginate();
		return Inertia::render('Hr::AssetType/Index', [
			'AssetTypes' => AssetTypeResource::collection($AssetType),
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(AssetTypeRequest $request): RedirectResponse
	{
		AssetType::create($request->validated());
		return redirect()->back();
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(AssetTypeRequest $request, $id): RedirectResponse
	{
		$AssetType = AssetType::findOrFail($id);
		$AssetType->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$AssetType = AssetType::findOrFail($id);
		//        todo CHECK IF HAS DATA BEFORE DELETE
		$AssetType->delete();
		return redirect()->back();
	}
}
