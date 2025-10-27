<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transfer extends Model
{
    use HasFactory;
    protected $fillable = (
    [
        'from',
        'to',
        'date',
        'amount',
        'desc',
        'ref'
    ]
    );

    public function from_account(){
        return $this->belongsTo(account::class, 'from', 'id');
    }

    public function to_account(){
        return $this->belongsTo(account::class, 'to', 'id');
    }
}
