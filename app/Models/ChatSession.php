<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    protected $fillable = ['user_id', 'clo_id', 'title', 'last_activity_at'];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clo()
    {
        return $this->belongsTo(Clo::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    // helper: ambil pesan terakhir (buat snippet di history)
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }
}
