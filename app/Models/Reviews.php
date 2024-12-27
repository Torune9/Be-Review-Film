<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory,HasUlids;

    protected $fillable = ['user_id', 'movie_id', 'critic', 'rating'];

    public function user(){
        return $this->belongsTo(Users::class);
    }

    public function movies(){
        return $this->belongsTo(Movie::class);
    }
}
