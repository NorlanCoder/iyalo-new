<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'user_id',
        'amount',
        'is_confirm',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
