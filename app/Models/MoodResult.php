<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoodResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'mood_label',
        'confidence',
        'description',
        'motivation',
        'raw_response'
    ];
    
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
