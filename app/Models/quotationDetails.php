<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quotationDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function quotation()
    {
        return $this->belongsTo(quotation::class, 'quot');
    }

    public function product1(){
        return $this->belongsTo(products::class, 'product', 'id');
    }
}
