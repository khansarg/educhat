<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clo extends Model
{
    protected $fillable=['course_id','code','title','outcome_text','tags'];
protected $casts=['tags'=>'array'];
public function course(){ return $this->belongsTo(Course::class); }
public function materials(){ return $this->hasMany(Material::class); }
}
