<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_visite',
        'user_id',
        'property_id',

        'amount',
        'free',
        'type',
        'reference',
        'transaction',

        'visited',
        'describ',
        'confirm_client',
        'confirm_owner',
        'is_refund',
    ];

    protected $casts = [
        'transaction' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function property()
    {
        return $this->belongsTo('App\Models\Property');
    }
}
