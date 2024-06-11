<?php

namespace Modules\Hr\App\resources\CorporationDocument;

use Illuminate\Http\Resources\Json\JsonResource;

class CorporationDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'doc_number' => $this->doc_number,
            'document_name' => $this->document_name,
            'desc' =>$this->desc,
            'expiration_date' =>$this->expiration_date,
            'show_in_employee' =>(boolean) $this->show_in_employee,
            'attachment' => $this->attachment,
        ];
    }
}
