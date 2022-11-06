<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonament extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','start_date', 'end_date', 'amount'];

}
