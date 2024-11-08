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
        'user_price'
    ];
}
