<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'lib',
        'media_url',
        'property_id',
    ];

    public function porperty()
    {
        return $this->belongsTo('App\Models\Porperty');
    }

}
