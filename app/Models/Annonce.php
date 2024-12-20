<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'agencename',
        'adresse',
        'image',
        'active',
        'description',
        'user_id',
     ];
 
     public function user()
     {
         return $this->belongsTo('App\Models\User');
     }
    
}
