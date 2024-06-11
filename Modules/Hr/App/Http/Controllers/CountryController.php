<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\CountryRequest;
use Modules\Hr\App\Models\Country;
use Modules\Hr\App\resources\CountryResource;

class CountryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$countries = Country::latest()->paginate();
		return Inertia::render('Country/Index', [
			'countries' => CountryResource::collection($countries)
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(CountryRequest $request): RedirectResponse
	{
		Country::create($request->validated());
		return redirect()->route('hr.settings.country.index');
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(CountryRequest $request, $id): RedirectResponse
	{
		$country = Country::findOrFail($id);
		$country->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$country = Country::findOrFail($id);
		$country->delete();
		return redirect()->back();
	}
}
