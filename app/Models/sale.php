<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function customer_account(){
        return $this->belongsTo(account::class, 'customer');
    }

    public function account(){
        return $this->belongsTo(account::class, 'paidIn', 'id');
    }

    public function details(){
        return $this->hasMany(sale_details::class,'bill_id');
    }

    public function saleReturns(){
        return $this->hasOne(saleReturn::class,'bill_id');
    }

    public function receives(){
        return $this->hasMany(sale_receives::class,'saleID');
    }
}
