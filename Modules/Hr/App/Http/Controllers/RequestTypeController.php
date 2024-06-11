<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\RequestTypeRequest;
use Modules\Hr\App\Models\ChoiceOption;
use Modules\Hr\App\Models\RequestField;
use Modules\Hr\App\Models\RequestType;
use Modules\Hr\App\resources\RequestTypeResource;

class RequestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {

        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

        $requests = RequestType::
        with('fields')->with('fields.options')
        ->paginate($page_size);
        return Inertia::render('Hr::RequestTypes/Index', [
            'requests' => RequestTypeResource::collection($requests)
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestTypeRequest $request): RedirectResponse
    {
       
        //create type
        $type = RequestType::create($request->validated());
        foreach ($request->fields as $field) {

            //create field inside type
            $request_field = RequestField::create([
                'label' => $field['label'],
                'field_type' => $field['type'],
                'is_required' => $field['required'],
                'request_type_id' => $type->id
            ]);

            //create select options and date start_date and end_date

            foreach ($field['options'] as $option) {

                $choice = new ChoiceOption();
                if (isset($option['label'])) {
                    $choice->label = $option['label'];
                }
                if (isset($option['start_date'])) {
                    $choice->start_date = Carbon::parse($option['start_date']);
                }
                if (isset($option['end_date'])) {
                    $choice->end_date = Carbon::parse($option['end_date']);
                }
                $choice->request_field_id = $request_field->id;
                $choice->save();
            }
        }



        return redirect()->route('request_types.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('hr::show');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RequestTypeRequest $request, $id): RedirectResponse
    {

        // Fetch the existing RequestType
        $type = RequestType::findOrFail($id);

        // Update RequestType details
        $type->update($request->validated());

        // Update RequestFields
        foreach ($request->fields as $fieldData) {
            $requestField = RequestField::updateOrCreate(
                [
                    'id' => $fieldData['id'] ?? null
                ],
                [
                    'label' => $fieldData['label'],
                    'field_type' => $fieldData['type'],
                    'is_required' => $fieldData['is_required'],
                    'request_type_id' => $type->id,
                    'updated_by' => auth()->user()->id
                ]
            );

            // Handle options for each field
            if (isset($fieldData['options'])) {
                foreach ($fieldData['options'] as $option) {
                    $choiceOption = ChoiceOption::updateOrCreate(
                        [
                            'id' => $option['id'] ?? null
                        ],
                        [
                            'label' => $option['label'],
                            'start_date' => $option['start_date'] ?? null,
                            'end_date' => $option['end_date'] ?? null,
                            'request_field_id' => $requestField->id
                        ]
                    );
                }
            }

            // Optionally, handle deletion of options not included in the request
            if (isset($fieldData['options_to_delete'])) {
                ChoiceOption::destroy($fieldData['options_to_delete']);
            }
        }

        // Optionally, handle deletion of fields not included in the request
        if (isset($request->fields_to_delete)) {
            RequestField::destroy($request->fields_to_delete);
        }

        return redirect()->route('request_types.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $request_type = RequestType::find($id);
        $request_type->delete();
    }
}
