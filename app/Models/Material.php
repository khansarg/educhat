<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{

    protected $fillable = [
        'clo_id',
        'title',
        'content',
        'source_url',
        // 'embedding', // kalau nanti pakai pgvector (PostgreSQL)
    ];

    public function clo()
    {
        return $this->belongsTo(Clo::class);
    }
}
