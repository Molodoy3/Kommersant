<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProperty extends Model
{
    /** @use HasFactory<\Database\Factories\TypePropertyFactory> */
    use HasFactory;

    protected $hidden = ['id', 'updated_at', 'created_at'];
}