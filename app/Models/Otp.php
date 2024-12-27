<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = ['otp','user_id','validate_until'];


    public function user(){
        return $this->belongsTo(Users::class,'user_id');
    }
}
