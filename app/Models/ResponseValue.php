<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_response_id', 'form_field_id', 'value', 'file_paths'
    ];

    protected $casts = [
        'file_paths' => 'array',
    ];

    // Relations
    public function response()
    {
        return $this->belongsTo(FormResponse::class, 'form_response_id');
    }

    public function field()
    {
        return $this->belongsTo(FormField::class, 'form_field_id');
    }
}
