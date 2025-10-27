<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class account extends Model
{
    use HasFactory;
    protected $fillable = (
        [
            'title',
            'type',
            'phone',
            'category',
            'address',
        ]
    );

    public function transactions(){
        return $this->hasMany(transactions::class, 'account_id');
    }
}

