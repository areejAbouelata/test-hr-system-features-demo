<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\AreaRequest;
use Modules\Hr\App\Models\Area;
use Modules\Hr\App\Models\Country;
use Modules\Hr\App\resources\AreaResource;
use Modules\Hr\App\resources\CountryResource;

class AreaController extends Controller
{
	public function index(Request $request)
	{
		$areas = Area::latest()->paginate();
		return Inertia::render('Area/Index', [
			'areas' => AreaResource::collection($areas),
			'countries' => CountryResource::collection(Country::all())
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(AreaRequest $request): RedirectResponse
	{
		Area::create($request->validated());
		return redirect()->back();
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(AreaRequest $request, $id): RedirectResponse
	{
		$area = Area::findOrFail($id);
		$area->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$area = Area::findOrFail($id);
		$area->delete();
		return redirect()->back();
	}
}
