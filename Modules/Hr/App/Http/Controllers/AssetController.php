<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\Assets\AssetStoreRequest;
use Modules\Hr\App\Models\Asset;
use Modules\Hr\App\Models\AssetType;
use Modules\Hr\App\resources\Assets\AssetResource;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;
        return Inertia::render('Hr::Assets/Index', [
            'assets' => AssetResource::collection(Asset::search($request->search)->paginate($page_size)),
            'users' => User::get()
                /*    'asset_types' => AssetType::get(), */
        ]);
    }

    public function store(AssetStoreRequest $request): RedirectResponse
    {
        Asset::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'status' => $request->status,


        ]);
        return redirect()->route('assets.index');
    }

    public function Update(Asset $asset,AssetStoreRequest $request):RedirectResponse
    {
        $asset->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'status'=>$request->status,
            'user_id'=>$request->user_id
        ]);
        return redirect()->route('assets.index');

    }

    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->delete();
        return redirect()->route('assets.index');
    }
}
