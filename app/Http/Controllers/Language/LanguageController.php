<?php

namespace App\Http\Controllers\Language;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\LanguageRequest;
use App\Http\Resources\Language\LanguageResource;
use App\Models\Language;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LanguageController extends Controller
{
	public function index(Request $request)
	{
		$languages = Language::latest()->paginate(25);
		return Inertia::render('Setting/Language/Index', [
			'languages' => LanguageResource::collection($languages)
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(LanguageRequest $request): RedirectResponse
	{
		Language::create($request->validated());
		return redirect()->back();
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(LanguageRequest $request, $id): RedirectResponse
	{
		$Language = Language::findOrFail($id);
		$Language->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$Language = Language::findOrFail($id);
//        todo CHECK IF HAS DATA BEFORE DELETE
		$Language->delete();
		return redirect()->back();
	}

//	Todo get user to toggle for
}
