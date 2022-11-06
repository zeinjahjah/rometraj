<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    protected $casts = [
        'actors' => 'array'
    ];
    protected $fillable = ['name','description','category','publish_year','photo_url','video_url','director','actors'];
    
}
