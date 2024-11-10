<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyLabel extends Model
{
    use HasFactory;
    protected $hidden = ['id', 'updated_at', 'created_at'];
    protected $table = 'property_label';
}
