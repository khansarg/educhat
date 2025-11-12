<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    public $incrementing=false; protected $keyType='string';
protected $fillable=['id','course_id','clo_id','user_ref','meta'];
protected $casts=['meta'=>'array'];
public function course(){ return $this->belongsTo(Course::class); }
public function clo(){ return $this->belongsTo(Clo::class); }
public function messages(){ return $this->hasMany(ChatMessage::class,'session_id'); }
}
