<?php

namespace App\Imports;

use App\Models\account;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class customersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $check = account::where("title", $row['title'])->count();
        if($check > 0) {
        }
        else{
        return new account([
            'title' => $row['title'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'type' => 'Customer'
        ]);
    }
    }
}
