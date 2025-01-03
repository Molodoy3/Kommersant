<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $fillable = ['title', 'description', 'price', 'category_id'];
    protected $hidden = ['updated_at'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function getPriceAttribute($value): string
    {
        if (!$value)
            return 'Договорная';
        return number_format($value, 0, '.', ' ') . '₽';
    }
    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('d.m.y') : null;
    }
}
