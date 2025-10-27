<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deposit extends Model
{
    use HasFactory;
    protected $fillable = (
        [
            'account_id',
            'date',
            'amount',
            'desc',
            'ref'
        ]
    );

    public function account()
    {
        return $this->belongsTo(account::class, 'account_id', 'id');
    }
}
