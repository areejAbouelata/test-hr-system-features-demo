<?php

namespace Modules\Hr\App\Http\Requests\CorporationDocument;

use Illuminate\Foundation\Http\FormRequest;

class CorporationDocumentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'doc_number' => 'required|integer',
            'document_name' => 'required|string',
            'desc' => 'string|nullable',
            'expiration_date' => 'date_format:Y-m-d',
            'show_in_employee' => 'required|boolean',
            'attachment' => 'file|nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
