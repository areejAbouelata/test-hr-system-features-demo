<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\CorporationDocument\CorporationDocumentRequest;
use Modules\Hr\App\Models\CorporationDocument;
use Modules\Hr\App\resources\CorporationDocument\CorporationDocumentResorce;

class CorpirationDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //        return view('hr::setting.corpiration-documents.index');
        $corporation_documents = CorporationDocument::latest()->paginate(25);
        return Inertia::render('Hr::Settings/Account/CorporationDocuments/Index', [
            'corporationDocuments' => \Modules\Hr\App\resources\Setting\CorporationDocumentResorce::collection($corporation_documents)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(CorporationDocumentRequest $request): RedirectResponse
    {
        //        todo UPLOAD FILE
        $data = $request->except('attachment');
        if ($request->expiration_date)
            $data['expiration_date'] = Carbon::parse($request->expiration_date)->format('Y-m-d');
        CorporationDocument::create($data);
        return redirect()->route('hr.settings.corporation-document.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $corporation_documents = CorporationDocument::findOrFail($id);
        return Inertia::render('Hr::Settings/Account/CompanyDocuments/Show', [
            'corporation_documents' => \Modules\Hr\App\resources\Setting\CorporationDocumentResorce::make($corporation_documents)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CorporationDocumentRequest $request, $id): RedirectResponse
    {
        $corporation_documents = CorporationDocument::findOrFail($id);
        $corporation_documents->update($request->except('attachment'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $corporation_documents = CorporationDocument::findOrFail($id);
        $corporation_documents->delete();
        return redirect()->back();
    }
}
