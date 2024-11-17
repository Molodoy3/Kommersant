<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'name',
        'telephone',
        'comment',
        'service_id',
        'property_id',
        'user_price'
    ];
}
