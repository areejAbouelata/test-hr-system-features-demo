<?php

namespace Modules\Hr\App\resources\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CorporationDocumentResorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
//        dump($this->attachment ?asset( Storage::url($this->attachment) ): null) ;
        return [
            'id' =>$this->id,
            'doc_number' =>$this->doc_number,
            'document_name' => $this->document_name,
            'desc' =>  $this->desc,
            'expiration_date' =>  $this->expiration_date,
            'show_in_employee' =>(boolean) $this->show_in_employee,
            'attachment' => $this->attachment ? asset(Storage::url($this->attachment)) : null,
        ];
    }
}
