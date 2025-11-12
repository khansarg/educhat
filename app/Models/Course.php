<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable=['code','name','description'];
public function clos(){ return $this->hasMany(Clo::class); }
}
