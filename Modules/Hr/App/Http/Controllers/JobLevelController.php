<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\JobLevelRequest;
use Modules\Hr\App\Models\JobLevel;
use Modules\Hr\App\resources\JobLevelResource;

class JobLevelController extends Controller
{
	public function index(Request $request)
	{
		$job_levels = JobLevel::latest()->paginate();
		return Inertia::render('Hr::JobLevel/Index', [
			'jobLevels' => JobLevelResource::collection($job_levels),
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(JobLevelRequest $request): RedirectResponse
	{
		JobLevel::create($request->validated());
		return redirect()->back();
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(JobLevelRequest $request, $id): RedirectResponse
	{
		$JobLevel = JobLevel::findOrFail($id);
		$JobLevel->update($request->validated());
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$JobLevel = JobLevel::findOrFail($id);
		//        todo CHECK IF HAS DATA BEFORE DELETE
		$JobLevel->delete();
		return redirect()->back();
	}
}
