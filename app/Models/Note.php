<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'comment',
        'property_id',
        'user_id',
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
