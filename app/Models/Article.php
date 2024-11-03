<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;

    protected $hidden = ['updated_at'];
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d.m.y') : null;
    }
    public function getImageAttribute($value)
    {
        return $value ? Storage::disk('public')->url('articles/' . $value) : null;
    }
}
