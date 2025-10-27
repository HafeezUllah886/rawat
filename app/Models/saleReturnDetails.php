<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class saleReturnDetails extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function returnBill(){
        return $this->belongsTo(saleReturn::class, 'return_id', 'id');
    }

    public function product(){
        return $this->belongsTo(products::class, 'product_id', 'id');
    }
}
