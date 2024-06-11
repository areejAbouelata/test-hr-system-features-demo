<?php

namespace  Modules\Hr\App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Hr\App\Models\Unit;

class UnitExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Unit::all();

    }
}
