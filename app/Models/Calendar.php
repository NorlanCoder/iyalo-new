<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'porperty_id',
        'day',
        'hour',
    ];

    protected $casts = [
        'hour' => 'array',
    ];

    public function porperty()
    {
        return $this->belongsTo('App\Models\Porperty');
    }

}
