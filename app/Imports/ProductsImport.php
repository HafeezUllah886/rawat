<?php

namespace App\Imports;

use App\Models\products;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $check = products::where('partno', $row['part_no'])->count();
        if($check > 0){

        }
        else
        {
            return new products([
                'name' => $row['product'],
                'partno' => $row['part_no'],
                'model' => $row['model'],
                'brand' => $row['brand'],
                'madein' => $row['made_in'],
                'size' => $row['size'],
                'uom' => $row['uom'],
                'pprice' => $row['cost'],
                'price' => $row['price'],
            ]);
        }

    }
}
