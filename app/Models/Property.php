<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    protected $hidden = ['updated_at', 'created_at'];

    public function type():BelongsTo
    {
        return $this->BelongsTo(TypeProperty::class, 'type_property_id');
    }
    public function transactionType():BelongsTo
    {
        return $this->BelongsTo(TransactionType::class, 'transaction_type_id');
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
