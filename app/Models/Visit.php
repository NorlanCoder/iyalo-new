<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_visite',
        'visited',
        'user_id',
        'property_id',
        'amount',
        'type',
        'reference',
        'transaction',
    ];

    protected $casts = [
        'transaction' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function porperty()
    {
        return $this->belongsTo('App\Models\Porperty');
    }
}
