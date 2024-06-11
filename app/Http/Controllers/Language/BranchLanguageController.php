<?php

namespace App\Http\Controllers\Language;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\BranchLanguageRequest;
use App\Models\Language;
use App\Services\FlashMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Hr\App\Models\Branch;

class BranchLanguageController extends Controller
{
	public function addLanguage(BranchLanguageRequest $request)
	{
		//		assign language to branch
		$branch = Branch::findOrFail($request->branch_id);
		$branch->branchLanguages()->updateOrCreate(
			[
				'language_id' => $request->language_id,
				'branch_id' => $request->branch_id,
			],
			[
				'language_id' => $request->language_id,
				'branch_id' => $request->branch_id,
				'short_cut' => Language::find($request->language_id)->short_cut,
				'is_active' => $request->is_active
			]
		);
		FlashMessage::make()->success(
			message: __('Updated successfully')
		)->closeable()->send();
		return redirect()->back();
	}

	//	from a branch get the language
	public function getLanguages($branch_id)
	{
		$branch = Branch::findOrFail($branch_id);
		$languages = $branch->branchLanguages;
		Inertia::share('branch_languages', function () use ($languages) {
			return $languages;
		});
		return redirect()->back()->with('branch_languages', $languages);
	}

	public function changeUiLanguage(Request $request)
	{
		//		get language short cut in request
		auth()->user()->update([
			'current_locale' => $request->current_locale
		]);

		Inertia::share('user_local', function () {
			return auth()->user()->current_locale;
		});
		return redirect()->back()->with('user_local', auth()->user()->current_locale);
	}
}
