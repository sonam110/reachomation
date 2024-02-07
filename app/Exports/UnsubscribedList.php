<?php

namespace App\Exports;

use App\Models\BlackListEmail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class UnsubscribedList implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array {
        return [
          'Sender Email',
          'Unsubscribe Email',
        ];
     }
    public function collection()
    {
        return BlackListEmail::where('user_id',auth()->user()->id)->select('from','to')->get();
    }
}
