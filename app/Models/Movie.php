<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'movies';

    protected $fillable  = ['title','summary','poster','genre_id','year'];

    public function reviews(){
        return $this->hasMany(Reviews::class);
    }

    public function casts()
    {
        return $this->belongsToMany(Cast::class, 'cast_movies')
                    ->withPivot('name','cast_id','movie_id')
                    ->withTimestamps();
    }


    public function genre(){
        return $this->belongsToMany(Genres::class);
    }

}
