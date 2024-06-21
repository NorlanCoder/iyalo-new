<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'user_id',
        'category_id',
        'price',
        'frequency',
        'city',
        'country',
        'district',
        'cover_url',
        'lat',
        'long',
        'description',
        'room',
        'bathroom',
        'lounge',
        'swingpool',
        'status',
        'visite_price',
        'conditions',
        'device',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'favories', 'property_id', 'user_id');
    }
}
