<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\CityRequest;
use Modules\Hr\App\Models\Area;
use Modules\Hr\App\Models\City;
use Modules\Hr\App\Models\Country;
use Modules\Hr\App\resources\AreaResource;
use Modules\Hr\App\resources\CityResource;
use Modules\Hr\App\resources\CountryResource;

class CityController extends Controller
{
	public function index(Request $request)
	{
		$cities = City::latest()->paginate(25);
		return Inertia::render('City/Index', [
			'cities' => CityResource::collection($cities),
			'countries' => CountryResource::collection(Country::with('areas')->get()),
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(CityRequest $request): RedirectResponse
	{
		City::create($request->validated());
		return redirect()->back();
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(CityRequest $request, $id): RedirectResponse
	{
		$city = City::findOrFail($id);
		$city->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$city = City::findOrFail($id);
		$city->delete();
		return redirect()->back();
	}
}
