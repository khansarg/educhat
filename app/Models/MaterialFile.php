<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialFile extends Model
{
    protected $fillable = [
        'material_id', 'original_name', 'pdf_path', 'pdf_url',
    ];

    protected $appends = ['download_url'];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function getDownloadUrlAttribute()
    {
        if ($this->pdf_url) return $this->pdf_url;
        return $this->pdf_path ? asset('storage/'.$this->pdf_path) : null;
    }
}
