<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer_account(){
        return $this->belongsTo(account::class, 'customer');
    }

    public function details(){
        return $this->hasMany(quotationDetails::class, 'quot');
    }
}
