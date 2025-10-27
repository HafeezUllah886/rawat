<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class products extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

  /*   public function company(){
        return $this->belongsTo(company::class, 'coy', 'id');
    }

    public function category(){
        return $this->belongsTo(catergory::class, 'cat', 'id');
    } */

    public function stock(){
        return $this->hasMany(stock::class, 'product_id');
    }

    public function scopeActive($query){
        return $query->where('status', 'Active');
    }
}
