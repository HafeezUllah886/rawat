<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class saleReturn extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bill(){
        return $this->belongsTo(sale::class, 'bill_id', 'id');
    }

    public function account(){
        return $this->belongsTo(account::class, 'paidBy', 'id');
    }

    public function details(){
        return $this->hasMany(saleReturnDetails::class, 'return_id');
    }
}
