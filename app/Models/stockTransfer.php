<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stockTransfer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(products::class, 'productID', 'id');
    }

    public function from()
    {
        return $this->belongsTo(warehouses::class, 'fromID', 'id');
    }

    public function to()
    {
        return $this->belongsTo(warehouses::class, 'toID', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }
}
