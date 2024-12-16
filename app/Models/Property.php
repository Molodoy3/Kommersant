<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'prise', 'address', 'type_property_id', 'transaction_type_id', 'square', 'latitude', 'longitude', 'link', 'labels'];
    protected $hidden = ['updated_at'];

    public function type():BelongsTo
    {
        return $this->BelongsTo(TypeProperty::class, 'type_property_id');
    }
    public function transactionType():BelongsTo
    {
        return $this->BelongsTo(TransactionType::class, 'transaction_type_id');
    }
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d.m.y') : null;
    }
    public function labels():BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'property_label');
    }
    public function getPriseAttribute($value)
    {
        $editedPrice = number_format($value, 0, '.', ' ');
        return $this->transaction_type_id == 1 ? $editedPrice . 'р/мес.' : $editedPrice . 'р.';
    }
}
