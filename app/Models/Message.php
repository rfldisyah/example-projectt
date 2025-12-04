<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory as FactoriesHasFactory;

class Message extends Model
{
    use FactoriesHasFactory;
    protected $fillable = [
        'user_id',
        'message'
    ];

    public function user(){
        return $this->belongsTo(user::class);
    }
    public function moodresult(){
        return $this->hasOne(Moodresult::class);
    }
}
