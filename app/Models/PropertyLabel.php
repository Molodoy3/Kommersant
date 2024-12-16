<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyLabel extends Model
{
    use HasFactory;
    protected $fillable = ['property_id', 'label_id', 'id'];
    protected $hidden = ['updated_at'];
    protected $table = 'property_label';
}
