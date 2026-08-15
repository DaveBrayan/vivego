<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapacityType extends Model
{
    use HasFactory;

    protected $table = 'capacity_types';

    protected $fillable = [
        'name',
        'color_hex',
        'status',
    ];
}
