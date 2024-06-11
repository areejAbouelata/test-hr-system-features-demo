<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\JobTitleRequest;
use Modules\Hr\App\Models\JobTitle;
use Modules\Hr\App\resources\JobTitleResource;

class JobTitleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$job_titles = JobTitle::latest()->paginate();
		return Inertia::render('Hr::JobTitle/Index', [
			'jobTitles' => JobTitleResource::collection($job_titles)
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(JobTitleRequest $request): RedirectResponse
	{
		JobTitle::create($request->validated());
		return redirect()->back();
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(JobTitleRequest $request, $id): RedirectResponse
	{
		$JobTitle = JobTitle::findOrFail($id);
		$JobTitle->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$JobTitle = JobTitle::findOrFail($id);
		//        todo CHECK IF HAS DATA BEFORE DELETE
		$JobTitle->delete();
		return redirect()->back();
	}
}
