<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Language\LanguageResource;
use App\Models\Language;
use App\Models\User;
use App\Services\FlashMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Mockery\Exception;
use Modules\Hr\App\Http\Requests\Branch\BranchRequest;
use Modules\Hr\App\Models\Branch;
use Modules\Hr\App\Models\Country;
use Modules\Hr\App\resources\Branch\BranchResource;
use Modules\Hr\App\resources\CountryResource;
use Modules\Hr\App\resources\Setting\SimpleUserResource;
use function Laravel\Prompts\error;

class BranchController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$branches = Branch::latest()->paginate(25);
		return Inertia::render('Branches/Index', [
			'branches' => BranchResource::collection($branches)
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//    get all branch form data
		$countries = Country::with('areas.cities')->latest()->get();
		//        mangers TODO how to identify and add branch id
		$mangers = User::latest()->get();
		return Inertia::render('Branches/Create', [
			'countries' => CountryResource::collection($countries),
			'users' => SimpleUserResource::collection($mangers),
			'languages' => LanguageResource::collection(Language::all())
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(BranchRequest $request): RedirectResponse
	{
		$branch = Branch::create($request->validated());
		$branch->branchLanguages()->create([
			'language_id' => $request->language_id,
			'branch_id' => $branch->id,
			'is_default' => true,
			'is_active' => true,
			'short_cut' => Language::find($request->language_id)?->short_cut
		]);
		FlashMessage::make()->success(
			message: __('created successfully')
		)->closeable()->send();
		return redirect()->route('branches.index');
	}


	/**
	 * Show the specified resource.
	 */
	public function show(Branch $branch)
	{
		return Inertia::render('Branches/Show', [
			'languages' => LanguageResource::collection(Language::where('id', '<>', $branch->branchLanguages()->where('is_default', true)->first()?->language_id)->get()),
			'branch' => BranchResource::make($branch),
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Branch $branch)
	{
		$countries = Country::with('areas.cities')->latest()->get();
		//        mangers TODO how to identify and add branch id
		$mangers = User::latest()->get();
		return Inertia::render('Branches/Create', [
			'countries' => CountryResource::collection($countries),
			'users' => SimpleUserResource::collection($mangers),
			'branch' => BranchResource::make($branch),
			'languages' => LanguageResource::collection(Language::all())
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(BranchRequest $request, $id): RedirectResponse
	{
		$branch = Branch::findOrFail($id);
		$branch->update($request->validated());
		FlashMessage::make()->success(
			message: __('updated successfully')
		)->closeable()->send();
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		// validate delete
		$branch = Branch::findOrfail($id);
		if ($branch->users) {
			FlashMessage::make()->error(__('Branch Has Users'))->send();
			return redirect()->back();
		}
		try {
			$branch->delete();
			FlashMessage::make()->success(
				message: __('Deleted successfully')
			)->closeable()->send();
			return redirect()->back();
		} catch (Exception $exception) {
			FlashMessage::make()->error(__('Branch Has Related Data'))->send();
			return redirect()->back();
		}
	}
}
